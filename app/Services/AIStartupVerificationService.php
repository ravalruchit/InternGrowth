<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIStartupVerificationService
{
    private string $apiKey;
    private string $model;

    private const GEMINI_API_BASE = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
        $this->model  = config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * Verify a startup registration using Google Gemini.
     */
    public function verifyStartup(
        ?string $gstPath,
        string $regPath,
        string $companyName,
        ?string $gstNumber,
        string $registrationNumber
    ): array {
        set_time_limit(120);

        if (empty($this->apiKey)) {
            Log::error('AIStartupVerificationService: GEMINI_API_KEY is not set in .env');
            return $this->errorResult('AI verification key is not set. Queued for manual review.');
        }

        try {
            $hasGst = !empty($gstNumber) && !empty($gstPath);

            // ── Resolve and prepare Registration Document ────────────────────
            $regResult = $this->prepareFile($regPath);
            if (!$regResult) {
                return $this->errorResult('Registration document file not found on server.');
            }

            // ── Resolve and prepare GST Certificate ─────────────────────────
            $gstResult = null;
            if ($hasGst) {
                $gstResult = $this->prepareFile($gstPath);
                if (!$gstResult) {
                    return $this->errorResult('GST Certificate file not found on server.');
                }
            }

            // ── Build prompt & parts ──────────────────────────────────────────
            $prompt = $this->buildPrompt($companyName, $gstNumber ?? '', $registrationNumber, $hasGst);
            $parts = [
                ['text' => $prompt],
                [
                    'inline_data' => [
                        'mime_type' => $regResult['mime'],
                        'data'      => $regResult['base64'],
                    ],
                ],
            ];

            if ($gstResult) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $gstResult['mime'],
                        'data'      => $gstResult['base64'],
                    ],
                ];
            }

            // ── Call Gemini API ──────────────────────────────────────────────
            $modelName = ltrim($this->model, 'models/');
            $url = self::GEMINI_API_BASE . "/{$modelName}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(60)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => $parts,
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'      => 0.1,
                        'maxOutputTokens'  => 2048,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if (!$response->successful()) {
                $body = $response->json();
                $errMsg = $body['error']['message'] ?? 'Unknown error';
                Log::error('AIStartupVerificationService: Gemini API error', [
                    'status'  => $response->status(),
                    'message' => $errMsg,
                ]);
                return $this->errorResult("AI service error: {$errMsg}. Queued for manual review.");
            }

            $content = $response->json('candidates.0.content.parts.0.text', '');
            if (empty($content)) {
                return $this->errorResult('AI returned empty document analysis content. Queued for manual review.');
            }

            $parsed = $this->parseAIResponse($content);

            // ── Step 5: Local Matching Engine (Free) ─────────────────────────
            $extractedName = $parsed['company_name'] ?? '';
            $extractedGst  = $parsed['gst_number'] ?? '';
            $extractedReg  = $parsed['registration_number'] ?? '';
            $confidence    = (int) ($parsed['confidence'] ?? 0);
            $fraudFlags    = $parsed['fraud_flags'] ?? [];

            // 1. Company Name Match (up to 20 points)
            $cleanSubmittedName = $this->cleanString($companyName);
            $cleanExtractedName = $this->cleanString($extractedName);
            
            $nameMatchScore = 0;
            if (!empty($cleanSubmittedName) && !empty($cleanExtractedName)) {
                if ($cleanSubmittedName === $cleanExtractedName) {
                    $nameMatchScore = 20;
                } else {
                    $lev = levenshtein($cleanSubmittedName, $cleanExtractedName);
                    $maxLen = max(strlen($cleanSubmittedName), strlen($cleanExtractedName));
                    $pct = $maxLen > 0 ? (1 - ($lev / $maxLen)) * 100 : 0;
                    
                    if ($pct >= 90) {
                        $nameMatchScore = 20;
                    } elseif ($pct >= 70) {
                        $nameMatchScore = 15;
                    } elseif ($pct >= 50) {
                        $nameMatchScore = 10;
                    } else {
                        $nameMatchScore = 0;
                    }
                }
            }

            // 2. GST Match (up to 30 points)
            $gstMatchScore = 0;
            if ($hasGst) {
                $cleanSubmittedGst = $this->cleanString($gstNumber ?? '');
                $cleanExtractedGst = $this->cleanString($extractedGst);
                if (!empty($cleanSubmittedGst) && $cleanSubmittedGst === $cleanExtractedGst) {
                    $gstMatchScore = 30;
                } else {
                    $gstMatchScore = 0;
                }
            } else {
                $gstMatchScore = 30; // Skip if optional and not provided
            }

            // 3. CIN Match (up to 30 points)
            $cinMatchScore = 0;
            $cleanSubmittedReg = $this->cleanString($registrationNumber);
            $cleanExtractedReg = $this->cleanString($extractedReg);
            if (!empty($cleanSubmittedReg) && $cleanSubmittedReg === $cleanExtractedReg) {
                $cinMatchScore = 30;
            } else {
                $cinMatchScore = 0;
            }

            // 4. Document Quality (up to 20 points)
            $qualityScore = 20;
            if (in_array('blurry_document', $fraudFlags)) $qualityScore -= 10;
            if (in_array('screenshot', $fraudFlags)) $qualityScore -= 5;
            if (in_array('cropped_document', $fraudFlags)) $qualityScore -= 5;
            $qualityScore = max(0, $qualityScore);

            $verificationScore = $nameMatchScore + $gstMatchScore + $cinMatchScore + $qualityScore;

            // Fraud Score Calculation (0-100)
            $fraudScore = 0;
            if ($nameMatchScore < 20) $fraudScore += 25;
            if ($hasGst && $gstMatchScore == 0) $fraudScore += 40;
            if ($cinMatchScore == 0) $fraudScore += 40;
            if (in_array('blurry_document', $fraudFlags)) $fraudScore += 20;
            if (in_array('screenshot', $fraudFlags)) $fraudScore += 15;
            if (in_array('cropped_document', $fraudFlags)) $fraudScore += 15;
            if (in_array('missing_seal', $fraudFlags)) $fraudScore += 20;
            if (in_array('ai_generated', $fraudFlags) || in_array('manipulated_document', $fraudFlags)) $fraudScore += 50;

            $fraudScore = min(100, $fraudScore);

            // ── Step 6: Determine Verification Level & Status ─────────────────
            $recommendation = 'manual_review';
            $level = 'C';

            if ($verificationScore >= 95 && $fraudScore < 20) {
                $recommendation = 'pre_approve';
                $level = 'A';
            } elseif ($verificationScore >= 85) {
                $recommendation = 'manual_review';
                $level = 'B';
            } elseif ($verificationScore >= 70) {
                $recommendation = 'manual_review';
                $level = 'C';
            } else {
                $recommendation = 'reject';
                $level = 'D';
            }

            return [
                'approved'            => ($recommendation === 'pre_approve'),
                'confidence'          => $confidence,
                'verification_score'  => $verificationScore,
                'fraud_score'         => $fraudScore,
                'verification_level'  => $level,
                'recommendation'      => $recommendation,
                'company_name'        => $extractedName ?: $companyName,
                'gst_number'          => $extractedGst ?: $gstNumber,
                'registration_number' => $extractedReg ?: $registrationNumber,
                'document_type'       => $parsed['document_type'] ?? 'Unknown',
                'fraud_risk'          => $fraudScore >= 60 ? 'high' : ($fraudScore >= 20 ? 'medium' : 'low'),
                'reason'              => $parsed['reason'] ?? '',
                'raw'                 => $parsed,
                'match_details'       => [
                    'name_match_score'  => $nameMatchScore,
                    'gst_match_score'   => $gstMatchScore,
                    'cin_match_score'   => $cinMatchScore,
                    'quality_score'     => $qualityScore,
                    'extracted_name'    => $extractedName,
                    'extracted_gst'     => $extractedGst,
                    'extracted_cin'     => $extractedReg
                ]
            ];

        } catch (\Exception $e) {
            Log::error('AIStartupVerificationService exception', ['message' => $e->getMessage()]);
            return $this->errorResult('An unexpected error occurred during AI analysis. Queued for manual review.');
        }
    }

    private function prepareFile(string $path): ?array
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/' . $path);
        }
        if (!file_exists($fullPath)) {
            return null;
        }

        $mime = $this->getMimeType($fullPath);
        $base64 = '';

        if ($mime !== 'application/pdf' && extension_loaded('gd')) {
            $imageBytes = $this->compressImage($fullPath);
            $base64     = base64_encode($imageBytes);
        } else {
            $base64     = base64_encode(file_get_contents($fullPath));
        }

        return [
            'mime'   => $mime,
            'base64' => $base64
        ];
    }

    private function buildPrompt(string $companyName, string $gstNumber, string $regNumber, bool $hasGst): string
    {
        $gstText = $hasGst ? "Registered GSTIN: \"{$gstNumber}\"" : "No GSTIN provided (registration certificate only).";
        
        return <<<PROMPT
You are a professional startup verification officer. Your job is to analyze the uploaded documents and determine whether this appears to be a legitimate business entity.

The startup registered with these details:
- Company Name: "{$companyName}"
- Registration Number/CIN: "{$regNumber}"
- {$gstText}

Inspect the uploaded documents. Extract and evaluate the following:
1. Company Name (printed on the documents)
2. GST Number (if GST certificate is present)
3. Registration Number / CIN (if registration document is present)
4. Business/Document Type (e.g. Private Limited, Partnership, Sole Proprietorship, etc.)
5. Registration Authority
6. Issue Date

Security and Fraud Evaluation:
Check for the following visual/digital fraud indicators and flag them:
- Screenshots or screenshots of digital cards/pdfs (flag: "screenshot")
- Cropped documents where key information is missing (flag: "cropped_document")
- Extremely blurry, low-resolution, or unreadable document (flag: "blurry_document")
- Inconsistent fonts, signs of text editing, or photoshop pixel artifacts (flag: "manipulated_document")
- AI-generated documents or fake templates (flag: "ai_generated")
- Missing official seals, signatures, or registration marks (flag: "missing_seal")

Respond ONLY in a valid JSON object format with no markdown code fences and no extra text:
{
  "company_name": "Extracted Name",
  "gst_number": "Extracted GST or null",
  "registration_number": "Extracted Reg No or null",
  "document_type": "Extracted Type",
  "confidence": 95,
  "fraud_flags": [],
  "recommendation": "approve",
  "reason": "Detail explanation here"
}

Note:
- confidence must be an integer (0-100).
- recommendation must be exactly one of: "approve", "manual_review", "reject".
- fraud_flags must be an array of strings representing detected security/quality flags (e.g. ["screenshot", "blurry_document"]). If none, return [].
PROMPT;
    }

    private function parseAIResponse(string $content): array
    {
        $content = preg_replace('/```(?:json)?\s*/i', '', $content);
        $content = preg_replace('/```\s*$/i', '', $content);
        $content = trim($content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            Log::warning('AIStartupVerificationService: JSON parse failed', ['content' => $content]);
            return [
                'company_name'        => '',
                'gst_number'          => '',
                'registration_number' => '',
                'document_type'       => 'Unknown',
                'confidence'          => 50,
                'fraud_flags'         => ['ai_parse_failed'],
                'recommendation'      => 'manual_review',
                'reason'              => 'AI response format invalid.'
            ];
        }

        return $decoded;
    }

    private function cleanString(string $val): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $val));
    }

    private function getMimeType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf'         => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'webp'        => 'image/webp',
            default       => 'image/jpeg',
        };
    }

    private function compressImage(string $filePath): string
    {
        $mime = $this->getMimeType($filePath);
        $originalBytes = file_get_contents($filePath);

        try {
            $src = null;
            if ($mime === 'image/jpeg') {
                $src = imagecreatefromjpeg($filePath);
            } elseif ($mime === 'image/png') {
                $src = imagecreatefrompng($filePath);
            } elseif ($mime === 'image/webp') {
                $src = imagecreatefromwebp($filePath);
            }

            if (!$src) {
                return $originalBytes;
            }

            $width  = imagesx($src);
            $height = imagesy($src);

            $maxDimension = 1000;
            if ($width > $maxDimension || $height > $maxDimension) {
                if ($width > $height) {
                    $newWidth  = $maxDimension;
                    $newHeight = (int) (($height / $width) * $maxDimension);
                } else {
                    $newHeight = $maxDimension;
                    $newWidth  = (int) (($width / $height) * $maxDimension);
                }

                $dst = imagecreatetruecolor($newWidth, $newHeight);
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($src);
                $src = $dst;
            }

            ob_start();
            if ($mime === 'image/png') {
                imagepng($src, null, 7);
            } elseif ($mime === 'image/webp') {
                imagewebp($src, null, 75);
            } else {
                imagejpeg($src, null, 75);
            }
            $compressedBytes = ob_get_clean();
            imagedestroy($src);

            if (strlen($compressedBytes) < strlen($originalBytes)) {
                return $compressedBytes;
            }
            return $originalBytes;
        } catch (\Exception $e) {
            return $originalBytes;
        }
    }

    private function errorResult(string $reason): array
    {
        return [
            'approved'            => false,
            'confidence'          => 0,
            'verification_score'  => 0,
            'fraud_score'         => 50,
            'verification_level'  => 'C',
            'recommendation'      => 'manual_review',
            'company_name'        => '',
            'gst_number'          => '',
            'registration_number' => '',
            'document_type'       => 'Unknown',
            'fraud_risk'          => 'medium',
            'reason'              => $reason,
            'raw'                 => [],
            'match_details'       => []
        ];
    }
}

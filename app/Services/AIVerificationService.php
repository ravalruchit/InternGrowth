<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIVerificationService
{
    private string $apiKey;
    private string $model;
    private int $confidenceThreshold;
    private int $manualReviewThreshold;

    // Gemini API base URL
    private const GEMINI_API_BASE = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey                = config('services.gemini.key', '');
        $this->model                 = config('services.gemini.model', 'gemini-2.5-flash');
        $this->confidenceThreshold   = (int) config('services.gemini.confidence_threshold', 90);
        $this->manualReviewThreshold = (int) config('services.gemini.manual_review_threshold', 70);
    }

    /**
     * Verify a college ID card image using Google Gemini Vision.
     *
     * @param  string  $imagePath   Storage path (relative to storage/app/public/)
     * @param  string  $studentName Student's registered name on InternGrowth
     * @param  int|null $graduationYear Student's declared graduation year
     * @return array{approved: bool, confidence: int, college_name: string, student_name: string, roll_number: string, reason: string, recommendation: string, authenticity: string, raw: array}
     */
    public function verifyCollegeId(string $imagePath, string $studentName, ?int $graduationYear = null): array
    {
        // Give PHP enough time — AI API call can take 10–30s
        set_time_limit(120);

        if (empty($this->apiKey)) {
            Log::error('AIVerificationService: GEMINI_API_KEY is not set in .env');
            return $this->errorResult('AI service is not configured. Your ID has been queued for manual review.');
        }

        try {
            // ── Resolve file path (private disk first, then legacy public) ───
            $fullPath = storage_path('app/private/' . $imagePath);
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/public/' . $imagePath);
            }
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/' . $imagePath);
            }
            if (!file_exists($fullPath)) {
                return $this->errorResult('Image file not found on server. Please re-upload.');
            }

            // ── Read, compress & encode image ────────────────────────────────
            $imageBytes = $this->compressImage($fullPath);
            $base64     = base64_encode($imageBytes);
            $mimeType   = $this->getMimeType($fullPath);

            // ── Build Gemini request ──────────────────────────────────────────
            $prompt = $this->buildCollegeIdPrompt($studentName, $graduationYear);
            // Model can come as 'gemini-2.5-flash' or 'models/gemini-2.5-flash' — normalise it
            $modelName = ltrim($this->model, 'models/');
            $url = self::GEMINI_API_BASE . "/{$modelName}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(60)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data'      => $base64,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.1,   // Low temp = deterministic, consistent output
                        'maxOutputTokens' => 2048,  // Increased to fit thoughts + JSON response
                        'responseMimeType' => 'application/json', // Force JSON output
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                    ],
                ]);

            // ── Handle response ───────────────────────────────────────────────
            if (!$response->successful()) {
                $body = $response->json();
                $errMsg = $body['error']['message'] ?? 'Unknown error';
                Log::error('Gemini API error', [
                    'status'  => $response->status(),
                    'message' => $errMsg,
                ]);
                return $this->errorResult("AI service error: {$errMsg}. Your ID has been queued for manual review.");
            }

            // Gemini response structure:
            // candidates[0].content.parts[0].text
            $content = $response->json('candidates.0.content.parts.0.text', '');

            if (empty($content)) {
                // Could be blocked by safety filters
                $finishReason = $response->json('candidates.0.finishReason', '');
                Log::warning('Gemini returned empty content', [
                    'finishReason' => $finishReason,
                    'response'     => $response->json(),
                ]);
                return $this->errorResult('AI could not analyse this image. Please upload a clearer photo of your college ID.');
            }

            $parsed = $this->parseAIResponse($content);
            return $this->buildResult($parsed);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('AIVerificationService: Connection error', ['message' => $e->getMessage()]);
            return $this->errorResult('Could not reach AI service. Your ID has been queued for manual review.');
        } catch (\Exception $e) {
            Log::error('AIVerificationService exception', ['message' => $e->getMessage()]);
            return $this->errorResult('An unexpected error occurred. Your ID has been queued for manual review.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function buildCollegeIdPrompt(string $studentName, ?int $graduationYear = null): string
    {
        $yearContext = $graduationYear ? "Declared graduation year: \"{$graduationYear}\"" : "Declared graduation year: Not declared";

        return <<<PROMPT
You are a college ID card verification expert for InternGrowth, a platform that connects college students with startups in India.

Analyze the uploaded image and determine if it is a genuine college or university ID card.

Registered student name on the platform: "{$studentName}"
{$yearContext}

Extract these details from the ID card:
1. Student Name (as printed on card)
2. College / University / Institute Name
3. Roll Number or Enrollment Number (if visible)
4. Validity / Academic Year (if visible. Note: Many genuine Indian college IDs do NOT print a validity date or academic year. If it is not visible or not printed, return "not_printed" and do NOT penalize the card or lower your confidence score for this missing detail.)
5. Does the name on the card match the registered name "{$studentName}"? (allow minor spelling differences)
6. If a validity date or academic year is visible, does it align with or support the student's declared graduation year of "{$graduationYear}"? (e.g. if they declare 2026, an academic year of 2024-2026 or 2022-2026 matches; if it doesn't align at all, list this mismatch in security_flags)

Security checks:
- Is this a screenshot of a digital card? (reduce confidence)
- Are there signs of image editing, inconsistent fonts, or pixel artifacts?
- Is the card partially cropped so key info is missing?
- Is this a non-educational document (Aadhaar, driving license, PAN, etc.)? → reject
- Is the image too blurry or dark to read? → reject

Confidence scoring guide:
- 90-100: Clear, authentic-looking physical college ID, primary details (name, college name) are readable, student name matches registered name. Do NOT lower the confidence score if validity/academic year is missing or returned as "not_printed".
- 70-89: Probably genuine but some primary fields (name, college name) are unclear, minor name spelling mismatch, or digital screenshot.
- 50-69: Suspicious, edited-looking, or very unclear.
- Below 50: Clearly not a college ID, or unreadable.

Respond ONLY in valid JSON with no extra text, no markdown code fences:
{"is_college_id":true,"student_name":"","college_name":"","roll_number":"","validity":"","name_match":true,"confidence":90,"authenticity":"high","recommendation":"approve","security_flags":[],"reason":""}

recommendation must be exactly one of: "approve", "manual_review", "reject"
authenticity must be exactly one of: "high", "medium", "low"
PROMPT;
    }

    private function parseAIResponse(string $content): array
    {
        // Strip any markdown code fences Gemini might add
        $content = preg_replace('/```(?:json)?\s*/i', '', $content);
        $content = preg_replace('/```\s*$/i', '', $content);
        $content = trim($content);

        // Extract JSON object if there's extra text
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            Log::warning('AIVerificationService: JSON parse failed', ['content' => $content]);
            return [
                'is_college_id'  => false,
                'student_name'   => '',
                'college_name'   => '',
                'roll_number'    => '',
                'validity'       => '',
                'name_match'     => false,
                'confidence'     => 60,
                'authenticity'   => 'low',
                'recommendation' => 'manual_review',
                'security_flags' => ['AI response could not be parsed'],
                'reason'         => 'Automated analysis inconclusive. Queued for manual review.',
            ];
        }

        return $decoded;
    }

    private function buildResult(array $parsed): array
    {
        $confidence     = (int) ($parsed['confidence'] ?? 0);
        $isCollegeId    = (bool) ($parsed['is_college_id'] ?? false);

        // Override if not a college ID
        if (!$isCollegeId) {
            $confidence = min($confidence, 40);
        }

        // Platform confidence thresholds determine the final decision
        if ($confidence >= $this->confidenceThreshold) {
            $approved       = true;
            $recommendation = 'approve';
        } elseif ($confidence >= $this->manualReviewThreshold) {
            $approved       = false;
            $recommendation = 'manual_review';
        } else {
            $approved       = false;
            $recommendation = 'reject';
        }

        // Never auto-approve if AI says the name does not match
        $nameMatch = (bool) ($parsed['name_match'] ?? false);
        if ($recommendation === 'approve' && !$nameMatch) {
            $approved       = false;
            $recommendation = 'manual_review';
        }

        return [
            'approved'       => $approved,
            'confidence'     => $confidence,
            'college_name'   => $parsed['college_name'] ?? '',
            'student_name'   => $parsed['student_name'] ?? '',
            'roll_number'    => $parsed['roll_number'] ?? '',
            'validity'       => $parsed['validity'] ?? '',
            'name_match'     => (bool) ($parsed['name_match'] ?? false),
            'authenticity'   => $parsed['authenticity'] ?? 'low',
            'recommendation' => $recommendation,
            'security_flags' => $parsed['security_flags'] ?? [],
            'reason'         => $parsed['reason'] ?? '',
            'raw'            => $parsed,
        ];
    }

    private function errorResult(string $reason): array
    {
        return [
            'approved'       => false,
            'confidence'     => 0,
            'college_name'   => '',
            'student_name'   => '',
            'roll_number'    => '',
            'validity'       => '',
            'name_match'     => false,
            'authenticity'   => 'low',
            'recommendation' => 'manual_review',
            'security_flags' => [],
            'reason'         => $reason,
            'raw'            => [],
        ];
    }

    private function getMimeType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            default       => 'image/jpeg',
        };
    }

    /**
     * Compress and resize an image if it's too large, returning the raw binary bytes of the compressed image.
     */
    private function compressImage(string $filePath): string
    {
        $mime = $this->getMimeType($filePath);
        $originalBytes = file_get_contents($filePath);
        
        if (!extension_loaded('gd')) {
            Log::warning('GD extension not loaded. Sending uncompressed image.');
            return $originalBytes;
        }

        try {
            // Load image depending on mime type
            $src = null;
            if ($mime === 'image/jpeg') {
                $src = imagecreatefromjpeg($filePath);
            } elseif ($mime === 'image/png') {
                $src = imagecreatefrompng($filePath);
            } elseif ($mime === 'image/webp') {
                $src = imagecreatefromwebp($filePath);
            } elseif ($mime === 'image/gif') {
                $src = imagecreatefromgif($filePath);
            }

            if (!$src) {
                return $originalBytes;
            }

            $width  = imagesx($src);
            $height = imagesy($src);

            // Calculate new dimensions (max 1000px on either side)
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
                
                // Preserve transparency for PNG/WebP
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($src);
                $src = $dst;
            }

            // Save to buffer
            ob_start();
            if ($mime === 'image/png') {
                imagepng($src, null, 7);
            } elseif ($mime === 'image/webp') {
                imagewebp($src, null, 75);
            } elseif ($mime === 'image/gif') {
                imagegif($src, null);
            } else {
                imagejpeg($src, null, 75);
            }
            $compressedBytes = ob_get_clean();
            imagedestroy($src);

            // Only use compressed image if it's actually smaller
            if (strlen($compressedBytes) < strlen($originalBytes)) {
                Log::info('Image compressed successfully', [
                    'original_size' => strlen($originalBytes),
                    'compressed_size' => strlen($compressedBytes)
                ]);
                return $compressedBytes;
            }

            return $originalBytes;
        } catch (\Exception $e) {
            Log::error('AIVerificationService: Image compression failed', ['message' => $e->getMessage()]);
            return $originalBytes;
        }
    }
}

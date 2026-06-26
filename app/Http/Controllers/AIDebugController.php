<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIDebugController extends Controller
{
    /**
     * Display the AI Debug Interface.
     */
    public function index()
    {
        // Gather current configurations
        $config = [
            'gemini_model'            => config('services.gemini.model', 'Not Set'),
            'gemini_key_configured'   => !empty(config('services.gemini.key')),
            'gemini_key_preview'      => !empty(config('services.gemini.key')) 
                ? substr(config('services.gemini.key'), 0, 10) . '...' . substr(config('services.gemini.key'), -5)
                : 'Not Set',
            'confidence_threshold'    => config('services.gemini.confidence_threshold', 90),
            'manual_threshold'        => config('services.gemini.manual_review_threshold', 70),
            'gd_loaded'               => extension_loaded('gd'),
        ];

        return view('admin.ai-debug', compact('config'));
    }

    /**
     * Run the real-time AI Debug Test via AJAX.
     */
    public function test(Request $request)
    {
        // 120s limit for debugging large uploads
        set_time_limit(120);

        try {
            $request->validate([
                'test_image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
                'student_name'    => 'required|string|max:255',
                'graduation_year' => 'nullable|integer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Validation failed: ' . $e->getMessage(),
            ], 422);
        }

        $studentName = $request->input('student_name');
        $gradYear    = $request->input('graduation_year');
        $file        = $request->file('test_image');

        $originalPath = $file->getRealPath();
        $originalSize = filesize($originalPath);
        $mimeType     = $file->getMimeType();

        $apiKey = config('services.gemini.key');
        $model  = config('services.gemini.model', 'gemini-2.5-flash');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'error'   => 'GEMINI_API_KEY is empty in configuration. Check your .env file or configuration cache.',
            ], 400);
        }

        // Apply compression and measure size
        $compressedBytes = $this->compressImageDebug($originalPath, $mimeType, $compressedSize, $dimensionsInfo);

        $base64 = base64_encode($compressedBytes);

        $yearContext = $gradYear ? "Declared graduation year: \"{$gradYear}\"" : "Declared graduation year: Not declared";

        // Prompt
        $prompt = <<<PROMPT
You are a college ID card verification expert for InternGrowth.
Analyze the uploaded image and determine if it is a genuine college or university ID card.

Registered student name: "{$studentName}"
{$yearContext}

Extract these details:
1. Student Name (as printed on card)
2. College / University / Institute Name
3. Roll Number or Enrollment Number (if visible)
4. Validity / Academic Year (if visible. Note: Many genuine Indian college IDs do NOT print a validity date or academic year. If it is not visible or not printed, return "not_printed" and do NOT penalize the card or lower your confidence score for this missing detail.)
5. Does the name on the card match "{$studentName}"?
6. If a validity date or academic year is visible, does it align with or support the student's declared graduation year of "{$gradYear}"? (e.g. if they declare 2026, an academic year of 2024-2026 or 2022-2026 matches; if it doesn't align at all, list this mismatch in security_flags)

Security checks:
- Is this a screenshot of a digital card?
- Are there signs of image editing or pixel artifacts?
- Is this a non-educational document (Aadhaar, driving license, PAN, etc.)? → reject
- Is the image too blurry or dark? → reject

Respond ONLY in valid JSON:
{"is_college_id":true,"student_name":"","college_name":"","roll_number":"","validity":"","name_match":true,"confidence":90,"authenticity":"high","recommendation":"approve","security_flags":[],"reason":""}
PROMPT;

        $modelName = ltrim($model, 'models/');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

        $startTime = microtime(true);
        $response = null;

        try {
            $response = Http::timeout(60)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data'      => $base64,
                                ],
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'      => 0.1,
                    'maxOutputTokens'  => 2048,
                    'responseMimeType' => 'application/json',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'HTTP Request failed: ' . $e->getMessage(),
                'elapsed' => round(microtime(true) - $startTime, 2),
            ], 500);
        }

        $elapsedTime = round(microtime(true) - $startTime, 2);

        $statusCode = $response->status();
        $rawBody    = $response->body();
        $responseJson = $response->json();

        // Propagate Gemini API failures directly to the debugger UI
        if (!$response->successful()) {
            $errMsg = $responseJson['error']['message'] ?? 'Unknown Gemini API Error';
            return response()->json([
                'success'          => false,
                'status_code'      => $statusCode,
                'elapsed_seconds'  => $elapsedTime,
                'error'            => "Gemini API Error (HTTP {$statusCode}): {$errMsg}",
                'raw_api_response' => $responseJson ?: ['raw_body' => $rawBody],
            ], 400);
        }

        // Extract content and reasoning
        $content = $response->json('candidates.0.content.parts.0.text', '');
        $finishReason = $response->json('candidates.0.finishReason', '');
        $usage = $responseJson['usageMetadata'] ?? [];

        return response()->json([
            'success'          => $response->successful(),
            'status_code'      => $statusCode,
            'elapsed_seconds'  => $elapsedTime,
            'original_size'    => $originalSize,
            'compressed_size'  => $compressedSize,
            'compression_ratio'=> round((1 - ($compressedSize / $originalSize)) * 100, 1) . '%',
            'dimensions'       => $dimensionsInfo,
            'finish_reason'    => $finishReason,
            'usage_metadata'   => $usage,
            'raw_api_response' => $responseJson,
            'model_response'   => $content,
            'parsed_response'  => $this->parseAIResponseDebug($content),
            'raw_body'         => $rawBody,
        ]);
    }

    /**
     * Compress image for debugging.
     */
    private function compressImageDebug(string $filePath, string $mime, &$compressedSize, &$dimensions)
    {
        $originalBytes = file_get_contents($filePath);
        $compressedSize = strlen($originalBytes);

        if (!extension_loaded('gd')) {
            $dimensions = 'GD NOT LOADED';
            return $originalBytes;
        }

        try {
            $src = null;
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $src = imagecreatefromjpeg($filePath);
            } elseif ($mime === 'image/png') {
                $src = imagecreatefrompng($filePath);
            } elseif ($mime === 'image/webp') {
                $src = imagecreatefromwebp($filePath);
            } elseif ($mime === 'image/gif') {
                $src = imagecreatefromgif($filePath);
            }

            if (!$src) {
                $dimensions = 'Failed to load image resource';
                return $originalBytes;
            }

            $width  = imagesx($src);
            $height = imagesy($src);
            $dimensions = "Original: {$width}x{$height}";

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
                $dimensions .= " | Resized: {$newWidth}x{$newHeight}";
            }

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

            if (strlen($compressedBytes) < strlen($originalBytes)) {
                $compressedSize = strlen($compressedBytes);
                return $compressedBytes;
            }

            return $originalBytes;
        } catch (\Exception $e) {
            $dimensions = 'Compression error: ' . $e->getMessage();
            return $originalBytes;
        }
    }

    /**
     * Parse the raw AI response text.
     */
    private function parseAIResponseDebug(string $content): array
    {
        $content = preg_replace('/```(?:json)?\s*/i', '', $content);
        $content = preg_replace('/```\s*$/i', '', $content);
        $content = trim($content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return [
                'parse_success' => false,
                'json_error'    => json_last_error_msg(),
                'raw_text'      => $content,
            ];
        }

        $decoded['parse_success'] = true;
        return $decoded;
    }
}

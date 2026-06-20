<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

$key   = env('GEMINI_API_KEY', '');
$model = env('GEMINI_MODEL', 'gemini-2.5-flash');

echo "Testing Google Gemini API...\n";
echo "Model: {$model}\n";
echo "Key: " . substr($key, 0, 20) . "...\n\n";

$start = microtime(true);

try {
    // Strip 'models/' prefix if present
    $modelName = ltrim($model, 'models/');
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$key}";

    $response = \Illuminate\Support\Facades\Http::timeout(30)->post($url, [
        'contents' => [[
            'parts' => [['text' => 'Reply with exactly this JSON and nothing else: {"status":"ok","model":"gemini working"}']]
        ]],
        'generationConfig' => [
            'temperature'     => 0.1,
            'maxOutputTokens' => 50,
        ],
    ]);

    $elapsed = round(microtime(true) - $start, 2);

    if ($response->successful()) {
        $content = $response->json('candidates.0.content.parts.0.text', '');
        echo "✅ Gemini {$model} is WORKING! ({$elapsed}s)\n";
        echo "Response: {$content}\n\n";
        echo "🎉 Your AI Verification is ready!\n";
        echo "   Go to /student/verify-id and upload a college ID card.\n";
    } else {
        echo "❌ HTTP " . $response->status() . ": " . substr($response->body(), 0, 400) . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

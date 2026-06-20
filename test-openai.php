<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

$key = env('OPENAI_API_KEY');
echo "API Key starts with: " . substr($key, 0, 20) . "...\n";
echo "Key length: " . strlen($key) . "\n\n";

try {
    $response = \Illuminate\Support\Facades\Http::withToken($key)
        ->timeout(10)
        ->get('https://api.openai.com/v1/models');

    echo "HTTP Status: " . $response->status() . "\n";
    if ($response->status() === 401) {
        echo "❌ API KEY IS INVALID or EXPIRED!\n";
    } elseif ($response->status() === 200) {
        echo "✅ API Key is valid and OpenAI is reachable!\n";
    } else {
        echo "Response: " . substr($response->body(), 0, 200) . "\n";
    }
} catch (\Illuminate\Http\Client\ConnectionException $e) {
    echo "❌ CONNECTION ERROR: " . $e->getMessage() . "\n";
    echo "Cannot reach api.openai.com — possible firewall/proxy issue.\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

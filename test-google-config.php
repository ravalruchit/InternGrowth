<?php

/**
 * Quick test script to verify Google OAuth configuration
 * Run this from the InternGrowth directory: php test-google-config.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Google OAuth Configuration Test ===\n\n";

$clientId = config('services.google.client_id');
$clientSecret = config('services.google.client_secret');
$redirectUri = config('services.google.redirect');

echo "Client ID: " . ($clientId ? substr($clientId, 0, 20) . "..." : "NOT SET") . "\n";
echo "Client Secret: " . ($clientSecret ? substr($clientSecret, 0, 15) . "..." : "NOT SET") . "\n";
echo "Redirect URI: " . ($redirectUri ?: "NOT SET") . "\n\n";

if ($clientId && $clientSecret && $redirectUri) {
    echo "✓ All Google OAuth credentials are configured!\n";
    echo "\nNext steps:\n";
    echo "1. Make sure your Laravel server is running: php artisan serve\n";
    echo "2. Visit: http://localhost:8000/login\n";
    echo "3. Click 'Continue with Google'\n";
    echo "\nIf you still get an error, check:\n";
    echo "- Google Cloud Console redirect URIs match: $redirectUri\n";
    echo "- Your OAuth consent screen is configured\n";
    echo "- The OAuth client is not restricted\n";
} else {
    echo "✗ Missing Google OAuth credentials in .env file!\n";
    echo "\nAdd these to your .env file:\n";
    echo "GOOGLE_CLIENT_ID=your-client-id\n";
    echo "GOOGLE_CLIENT_SECRET=your-client-secret\n";
    echo "GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback\n";
}

echo "\n";

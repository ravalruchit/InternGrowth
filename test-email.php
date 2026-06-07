<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \Mail::raw('This is a test email from InternGrowth', function($message) {
        $message->to('240045002100238@ljku.edu.in')
                ->subject('Test Email - InternGrowth');
    });
    
    echo "✓ Email sent successfully!\n";
    echo "Check your inbox: 240045002100238@ljku.edu.in\n";
    echo "Also check spam folder.\n";
} catch (\Exception $e) {
    echo "✗ Error sending email:\n";
    echo $e->getMessage() . "\n";
}

<?php
header('Content-Type: text/plain');
echo "PHP Version: " . phpversion() . "\n";
echo "APP_KEY from getenv(): " . (getenv('APP_KEY') ?: 'NOT SET') . "\n";
echo "APP_KEY from $_ENV: " . ($_ENV['APP_KEY'] ?? 'NOT SET') . "\n";
echo "APP_KEY from $_SERVER: " . ($_SERVER['APP_KEY'] ?? 'NOT SET') . "\n";
echo "DB_HOST from getenv(): " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_CONNECTION from getenv(): " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";

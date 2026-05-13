<?php
/**
 * Simple Laravel Setup Script
 * Handles initial configuration before full Laravel bootstrap
 */

$envFile = __DIR__ . '/.env';
$envExampleFile = __DIR__ . '/.env.example';

// Copy .env if it doesn't exist
if (!file_exists($envFile) && file_exists($envExampleFile)) {
    copy($envExampleFile, $envFile);
    echo "✓ Created .env file\n";
}

// Generate APP_KEY if not set
$envContent = file_get_contents($envFile);

if (strpos($envContent, 'APP_KEY=base64:') === false || strpos($envContent, 'APP_KEY=') === strpos($envContent, 'APP_KEY=')) {
    // Generate a new key
    $key = 'base64:' . base64_encode(random_bytes(32));
    
    if (strpos($envContent, 'APP_KEY=') !== false) {
        $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
    } else {
        $envContent = str_replace('APP_NAME="Language Learning Platform"', 'APP_NAME="Language Learning Platform"' . "\nAPP_KEY=" . $key, $envContent);
    }
    
    file_put_contents($envFile, $envContent);
    echo "✓ Generated APP_KEY\n";
}

echo "\n✓ .env setup complete!\n";
echo "\nNext steps:\n";
echo "1. Update database credentials in .env file\n";
echo "2. Run: php artisan migrate\n";
echo "3. Run: php artisan db:seed\n";
echo "4. Run: php artisan serve\n";

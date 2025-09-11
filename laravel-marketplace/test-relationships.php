<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== COMPREHENSIVE RELATIONSHIP TEST ===" . PHP_EOL;

try {
    // Test User model methods
    $user = new App\Models\User();
    echo "✅ User model loaded" . PHP_EOL;
    
    // Test if methods exist
    if (method_exists($user, 'hasPhoneVerification')) {
        echo "✅ hasPhoneVerification() method exists" . PHP_EOL;
    } else {
        echo "❌ hasPhoneVerification() method missing" . PHP_EOL;
    }
    
    if (method_exists($user, 'canCreateListing')) {
        echo "✅ canCreateListing() method exists" . PHP_EOL;
    } else {
        echo "❌ canCreateListing() method missing" . PHP_EOL;
    }
    
    if (method_exists($user, 'currentPlan')) {
        echo "✅ currentPlan() method exists" . PHP_EOL;
    } else {
        echo "❌ currentPlan() method missing" . PHP_EOL;
    }
    
    // Test Listing model
    $listing = new App\Models\Listing();
    echo "✅ Listing model loaded" . PHP_EOL;
    
    if (method_exists($listing, 'user')) {
        echo "✅ user() relationship exists" . PHP_EOL;
    } else {
        echo "❌ user() relationship missing" . PHP_EOL;
    }
    
    echo PHP_EOL . "🎉 RELATIONSHIP TEST COMPLETE!" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
}

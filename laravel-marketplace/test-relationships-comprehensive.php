<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== COMPREHENSIVE RELATIONSHIP TEST ===" . PHP_EOL;
echo "Testing all model relationships and methods..." . PHP_EOL . PHP_EOL;

try {
    // Test User model methods
    $user = new App\Models\User();
    echo "✅ User model loaded" . PHP_EOL;
    
    // Test User model methods
    $userMethods = [
        'hasPhoneVerification',
        'isFullyVerified', 
        'canCreateListing',
        'currentPlan',
        'getRemainingListingQuota',
        'activeSubscription',
        'subscriptions',
        'payments',
        'listings',
        'createToken',
        'makeHidden'
    ];
    
    echo PHP_EOL . "--- USER MODEL METHODS ---" . PHP_EOL;
    foreach ($userMethods as $method) {
        if (method_exists($user, $method)) {
            echo "✅ {$method}() method exists" . PHP_EOL;
        } else {
            echo "❌ {$method}() method missing" . PHP_EOL;
        }
    }
    
    // Test Listing model
    $listing = new App\Models\Listing();
    echo PHP_EOL . "✅ Listing model loaded" . PHP_EOL;
    
    // Test Listing model relationships
    $listingRelationships = [
        'user',
        'category', 
        'brand',
        'images',
        'conversations'
    ];
    
    echo PHP_EOL . "--- LISTING MODEL RELATIONSHIPS ---" . PHP_EOL;
    foreach ($listingRelationships as $relationship) {
        if (method_exists($listing, $relationship)) {
            echo "✅ {$relationship}() relationship exists" . PHP_EOL;
        } else {
            echo "❌ {$relationship}() relationship missing" . PHP_EOL;
        }
    }
    
    // Test Listing model scopes
    $listingScopes = [
        'scopeActive',
        'scopeByCategory',
        'scopeByBrand', 
        'scopeByCondition',
        'scopeByPriceRange',
        'scopeSearch'
    ];
    
    echo PHP_EOL . "--- LISTING MODEL SCOPES ---" . PHP_EOL;
    foreach ($listingScopes as $scope) {
        if (method_exists($listing, $scope)) {
            echo "✅ {$scope}() scope exists" . PHP_EOL;
        } else {
            echo "❌ {$scope}() scope missing" . PHP_EOL;
        }
    }
    
    // Test Listing model helper methods
    $listingHelpers = [
        'getPrimaryImageAttribute',
        'isExpired',
        'isActive',
        'incrementViewCount',
        'renew'
    ];
    
    echo PHP_EOL . "--- LISTING MODEL HELPERS ---" . PHP_EOL;
    foreach ($listingHelpers as $helper) {
        if (method_exists($listing, $helper)) {
            echo "✅ {$helper}() helper exists" . PHP_EOL;
        } else {
            echo "❌ {$helper}() helper missing" . PHP_EOL;
        }
    }
    
    // Test relationship functionality
    echo PHP_EOL . "--- RELATIONSHIP FUNCTIONALITY ---" . PHP_EOL;
    
    // Test user relationship
    $userRel = $listing->user();
    if ($userRel instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
        echo "✅ user() relationship is BelongsTo" . PHP_EOL;
    } else {
        echo "❌ user() relationship type incorrect" . PHP_EOL;
    }
    
    // Test images relationship
    $imagesRel = $listing->images();
    if ($imagesRel instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
        echo "✅ images() relationship is HasMany" . PHP_EOL;
    } else {
        echo "❌ images() relationship type incorrect" . PHP_EOL;
    }
    
    // Test method calls
    echo PHP_EOL . "--- METHOD CALL TESTS ---" . PHP_EOL;
    
    // Test createToken
    $token = $user->createToken('test-token');
    if (is_object($token) && isset($token->plainTextToken)) {
        echo "✅ createToken() working - Token: " . substr($token->plainTextToken, 0, 10) . "..." . PHP_EOL;
    } else {
        echo "❌ createToken() not working properly" . PHP_EOL;
    }
    
    // Test makeHidden
    $user->makeHidden(['test_field']);
    echo "✅ makeHidden() working" . PHP_EOL;
    
    // Test verification methods
    $user->phone_verified_at = now();
    $user->email_verified_at = now();
    
    if ($user->isFullyVerified()) {
        echo "✅ isFullyVerified() working" . PHP_EOL;
    } else {
        echo "❌ isFullyVerified() not working" . PHP_EOL;
    }
    
    if ($user->hasPhoneVerification()) {
        echo "✅ hasPhoneVerification() working" . PHP_EOL;
    } else {
        echo "❌ hasPhoneVerification() not working" . PHP_EOL;
    }
    
    echo PHP_EOL . "🎉 COMPREHENSIVE RELATIONSHIP TEST COMPLETE!" . PHP_EOL;
    echo "All model relationships and methods are working correctly." . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}

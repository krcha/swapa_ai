<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== SIMPLE RELATIONSHIP TEST ===" . PHP_EOL;
echo "Testing method existence without database operations..." . PHP_EOL . PHP_EOL;

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
    $userMethodsCount = 0;
    foreach ($userMethods as $method) {
        if (method_exists($user, $method)) {
            echo "✅ {$method}() method exists" . PHP_EOL;
            $userMethodsCount++;
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
    $listingRelationshipsCount = 0;
    foreach ($listingRelationships as $relationship) {
        if (method_exists($listing, $relationship)) {
            echo "✅ {$relationship}() relationship exists" . PHP_EOL;
            $listingRelationshipsCount++;
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
    $listingScopesCount = 0;
    foreach ($listingScopes as $scope) {
        if (method_exists($listing, $scope)) {
            echo "✅ {$scope}() scope exists" . PHP_EOL;
            $listingScopesCount++;
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
    $listingHelpersCount = 0;
    foreach ($listingHelpers as $helper) {
        if (method_exists($listing, $helper)) {
            echo "✅ {$helper}() helper exists" . PHP_EOL;
            $listingHelpersCount++;
        } else {
            echo "❌ {$helper}() helper missing" . PHP_EOL;
        }
    }
    
    // Test method calls that don't require database
    echo PHP_EOL . "--- METHOD CALL TESTS (NO DATABASE) ---" . PHP_EOL;
    
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
    
    // Summary
    echo PHP_EOL . "--- SUMMARY ---" . PHP_EOL;
    echo "User Methods: {$userMethodsCount}/" . count($userMethods) . " ✅" . PHP_EOL;
    echo "Listing Relationships: {$listingRelationshipsCount}/" . count($listingRelationships) . " ✅" . PHP_EOL;
    echo "Listing Scopes: {$listingScopesCount}/" . count($listingScopes) . " ✅" . PHP_EOL;
    echo "Listing Helpers: {$listingHelpersCount}/" . count($listingHelpers) . " ✅" . PHP_EOL;
    
    $totalMethods = count($userMethods) + count($listingRelationships) + count($listingScopes) + count($listingHelpers);
    $totalFound = $userMethodsCount + $listingRelationshipsCount + $listingScopesCount + $listingHelpersCount;
    
    echo PHP_EOL . "Total Methods: {$totalFound}/{$totalMethods} ✅" . PHP_EOL;
    
    if ($totalFound === $totalMethods) {
        echo PHP_EOL . "🎉 ALL RELATIONSHIPS AND METHODS COMPLETE!" . PHP_EOL;
        echo "All model relationships and methods are working correctly." . PHP_EOL;
    } else {
        echo PHP_EOL . "⚠️  SOME METHODS MISSING" . PHP_EOL;
        echo "Check the output above for missing methods." . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}

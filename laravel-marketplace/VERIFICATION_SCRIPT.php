<?php
/**
 * Laravel Marketplace - Verification Script
 * 
 * This script verifies that all critical files are present and properly configured
 * for the Laravel marketplace application.
 */

echo "🔍 LARAVEL MARKETPLACE - VERIFICATION SCRIPT\n";
echo "============================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Check essential Laravel files
$essentialFiles = [
    'artisan' => 'Laravel command-line interface',
    'bootstrap/app.php' => 'Application bootstrap',
    'config/app.php' => 'Application configuration',
    'config/database.php' => 'Database configuration',
    'app/Http/Controllers/Controller.php' => 'Base controller',
    'app/Providers/AppServiceProvider.php' => 'Application service provider',
    'app/Providers/AuthServiceProvider.php' => 'Authentication service provider',
    'app/Providers/EventServiceProvider.php' => 'Event service provider',
    'app/Providers/RouteServiceProvider.php' => 'Route service provider',
    'app/Providers/TokenServiceProvider.php' => 'Token service provider',
    'tests/TestCase.php' => 'Base test case',
    'tests/CreatesApplication.php' => 'Application creation trait',
    'routes/web.php' => 'Web routes',
    'routes/api.php' => 'API routes',
    'routes/console.php' => 'Console routes',
];

echo "📁 CHECKING ESSENTIAL FILES:\n";
foreach ($essentialFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check models
$modelFiles = [
    'app/Models/User.php' => 'User model with JMBG validation',
    'app/Models/Listing.php' => 'Listing model with relationships',
    'app/Models/TokenTransaction.php' => 'Token transaction model',
    'app/Models/Conversation.php' => 'Conversation model',
    'app/Models/Message.php' => 'Message model',
    'app/Models/Category.php' => 'Category model',
    'app/Models/Brand.php' => 'Brand model',
    'app/Models/ListingImage.php' => 'Listing image model',
];

echo "\n📊 CHECKING MODELS:\n";
foreach ($modelFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check controllers
$controllerFiles = [
    'app/Http/Controllers/Auth/AuthController.php' => 'Authentication controller',
    'app/Http/Controllers/Auth/VerificationController.php' => 'Verification controller',
    'app/Http/Controllers/ListingController.php' => 'Listing controller',
    'app/Http/Controllers/TokenController.php' => 'Token controller',
    'app/Http/Controllers/ConversationController.php' => 'Conversation controller',
    'app/Http/Controllers/CategoryController.php' => 'Category controller',
    'app/Http/Controllers/BrandController.php' => 'Brand controller',
    'app/Http/Controllers/AdminController.php' => 'Admin controller',
];

echo "\n🎮 CHECKING CONTROLLERS:\n";
foreach ($controllerFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check migrations
$migrationFiles = [
    'database/migrations/2024_01_15_000001_create_users_table.php' => 'Users table migration',
    'database/migrations/2024_01_15_000002_create_categories_table.php' => 'Categories table migration',
    'database/migrations/2024_01_15_000003_create_brands_table.php' => 'Brands table migration',
    'database/migrations/2024_01_15_000004_create_listings_table.php' => 'Listings table migration',
    'database/migrations/2024_01_15_000005_create_listing_images_table.php' => 'Listing images table migration',
    'database/migrations/2024_01_15_000006_create_token_transactions_table.php' => 'Token transactions table migration',
    'database/migrations/2024_01_15_000007_create_conversations_table.php' => 'Conversations table migration',
    'database/migrations/2024_01_15_000008_create_messages_table.php' => 'Messages table migration',
];

echo "\n🗄️ CHECKING MIGRATIONS:\n";
foreach ($migrationFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check middleware
$middlewareFiles = [
    'app/Http/Middleware/AdminMiddleware.php' => 'Admin middleware',
    'app/Http/Middleware/EnsureUserIsVerified.php' => 'User verification middleware',
    'app/Http/Middleware/EnsureUserHasTokens.php' => 'Token requirement middleware',
    'app/Http/Middleware/TrustProxies.php' => 'Trust proxies middleware',
    'app/Http/Middleware/PreventRequestsDuringMaintenance.php' => 'Maintenance mode middleware',
    'app/Http/Middleware/TrimStrings.php' => 'String trimming middleware',
    'app/Http/Middleware/EncryptCookies.php' => 'Cookie encryption middleware',
    'app/Http/Middleware/VerifyCsrfToken.php' => 'CSRF verification middleware',
    'app/Http/Middleware/Authenticate.php' => 'Authentication middleware',
    'app/Http/Middleware/RedirectIfAuthenticated.php' => 'Redirect if authenticated middleware',
    'app/Http/Middleware/ValidateSignature.php' => 'Signature validation middleware',
];

echo "\n🛡️ CHECKING MIDDLEWARE:\n";
foreach ($middlewareFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check tests
$testFiles = [
    'tests/Feature/AuthTest.php' => 'Authentication tests',
    'tests/Feature/TokenTest.php' => 'Token system tests',
    'database/factories/UserFactory.php' => 'User factory',
    'database/factories/ListingFactory.php' => 'Listing factory',
    'database/seeders/CategorySeeder.php' => 'Category seeder',
    'database/seeders/BrandSeeder.php' => 'Brand seeder',
    'database/seeders/DatabaseSeeder.php' => 'Database seeder',
];

echo "\n🧪 CHECKING TESTS:\n";
foreach ($testFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $errors[] = "❌ $file - $description (MISSING)";
    }
}

// Check configuration files
$configFiles = [
    'composer.json' => 'Composer configuration',
    'env.example' => 'Environment example file',
    'README.md' => 'Project documentation',
    'API_DOCUMENTATION.md' => 'API documentation',
];

echo "\n⚙️ CHECKING CONFIGURATION:\n";
foreach ($configFiles as $file => $description) {
    if (file_exists($file)) {
        $success[] = "✅ $file - $description";
    } else {
        $warnings[] = "⚠️ $file - $description (RECOMMENDED)";
    }
}

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 VERIFICATION SUMMARY\n";
echo str_repeat("=", 50) . "\n";

echo "\n✅ SUCCESS: " . count($success) . " files found\n";
echo "❌ ERRORS: " . count($errors) . " files missing\n";
echo "⚠️ WARNINGS: " . count($warnings) . " recommended files missing\n";

if (count($errors) > 0) {
    echo "\n🚨 CRITICAL ERRORS:\n";
    foreach ($errors as $error) {
        echo "   $error\n";
    }
}

if (count($warnings) > 0) {
    echo "\n⚠️ WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "   $warning\n";
    }
}

if (count($errors) === 0) {
    echo "\n🎉 SUCCESS! All critical files are present.\n";
    echo "📋 NEXT STEPS:\n";
    echo "   1. Install PHP and Composer\n";
    echo "   2. Run: composer install\n";
    echo "   3. Run: php artisan key:generate\n";
    echo "   4. Run: php artisan migrate\n";
    echo "   5. Run: php artisan serve\n";
} else {
    echo "\n❌ FAILURE! Some critical files are missing.\n";
    echo "Please fix the errors above before proceeding.\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Verification completed at: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 50) . "\n";

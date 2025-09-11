<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ListingController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\PhoneVerificationController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ReportController;

// Include admin routes
require __DIR__.'/admin.php';

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');

// Listing routes
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/step-filter', [ListingController::class, 'stepFilter'])->name('listings.step-filter');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
Route::post('/listings', [ListingController::class, 'store'])->name('listings.store')->middleware('auth');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Phone verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/phone/verify', function () {
        return view('auth.phone-verification');
    })->name('phone.verification');
    Route::post('/phone/verify/send', [PhoneVerificationController::class, 'send'])->name('phone.verification.send');
    Route::post('/phone/verify/verify', [PhoneVerificationController::class, 'verify'])->name('phone.verification.verify');
    Route::post('/phone/verify/resend', [PhoneVerificationController::class, 'resend'])->name('phone.verification.resend');
    Route::get('/phone/verify/status', [PhoneVerificationController::class, 'status'])->name('phone.verification.status');
    Route::get('/phone/verify/statistics', [PhoneVerificationController::class, 'statistics'])->name('phone.verification.statistics');
    Route::post('/phone/verify/cleanup', [PhoneVerificationController::class, 'cleanup'])->name('phone.verification.cleanup');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Subscription routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/plans', [SubscriptionController::class, 'index'])->name('subscription.plans');
    Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/current', [SubscriptionController::class, 'current'])->name('subscription.current');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/renew', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::get('/subscription/history', [SubscriptionController::class, 'history'])->name('subscription.history');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    
    // Payment routes
    Route::get('/payments', [SubscriptionController::class, 'payments'])->name('payment.history');
    Route::get('/payments/methods', [SubscriptionController::class, 'paymentMethods'])->name('payment.methods');
    Route::post('/payments/methods', [SubscriptionController::class, 'addPaymentMethod'])->name('payment.methods.add');
    Route::put('/payments/methods/default', [SubscriptionController::class, 'setDefaultPaymentMethod'])->name('payment.methods.default');
    Route::delete('/payments/methods/{id}', [SubscriptionController::class, 'removePaymentMethod'])->name('payment.methods.remove');
    
    // Invoice routes
    Route::get('/invoices', [SubscriptionController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{id}/download', [SubscriptionController::class, 'downloadInvoice'])->name('invoices.download');
    Route::get('/invoices/{id}', function ($id) {
        return view('invoice.show', ['invoice' => (object)['id' => $id]]);
    })->name('invoices.show');
});

// Payment form routes
Route::get('/payment/form/{plan}', function ($plan) {
    return view('payment.form', ['plan' => (object)[
        'id' => $plan, 
        'name' => 'Pro Plan', 
        'price' => 2990, 
        'formatted_price' => 'RSD 2,990.00', 
        'description' => 'Monthly subscription'
    ]]);
})->name('payment.form');

// Payment success/failure routes
Route::get('/payment/success', [WebhookController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [WebhookController::class, 'failure'])->name('payment.failure');
Route::get('/payment/cancel', [WebhookController::class, 'cancel'])->name('payment.cancel');

// Webhook routes (no CSRF protection)
Route::post('/webhooks/stripe', [WebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('/webhooks/nlb', [WebhookController::class, 'nlb'])->name('webhooks.nlb');
Route::post('/webhooks/intesa', [WebhookController::class, 'intesa'])->name('webhooks.intesa');

// Messaging routes
Route::middleware(['auth'])->group(function () {
    Route::get('/messaging', [MessagingController::class, 'index'])->name('messaging.index');
    Route::get('/messaging/search', [MessagingController::class, 'search'])->name('messaging.search');
    Route::get('/messaging/{conversation}', [MessagingController::class, 'show'])->name('messaging.show');
    Route::post('/messaging/{listing}/create', [MessagingController::class, 'create'])->name('messaging.create');
    Route::post('/messaging/{conversation}/send', [MessagingController::class, 'sendMessage'])->name('messaging.send');
    Route::post('/messaging/{conversation}/close', [MessagingController::class, 'close'])->name('messaging.close');
    Route::get('/messaging/{conversation}/messages', [MessagingController::class, 'getMessages'])->name('messaging.messages');
    Route::get('/messaging/unread-count', [MessagingController::class, 'unreadCount'])->name('messaging.unread-count');
});

// Buyer experience routes
Route::middleware(['auth'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/buyer/favorites', [BuyerController::class, 'favorites'])->name('buyer.favorites');
    Route::post('/buyer/favorites/{listing}/toggle', [BuyerController::class, 'toggleFavorite'])->name('buyer.favorites.toggle');
    Route::get('/buyer/price-alerts', [BuyerController::class, 'priceAlerts'])->name('buyer.price-alerts');
    Route::post('/buyer/price-alerts', [BuyerController::class, 'createPriceAlert'])->name('buyer.price-alerts.create');
    Route::delete('/buyer/price-alerts/{priceAlert}', [BuyerController::class, 'deletePriceAlert'])->name('buyer.price-alerts.delete');
    Route::get('/buyer/recently-viewed', [BuyerController::class, 'recentlyViewed'])->name('buyer.recently-viewed');
    Route::post('/buyer/recently-viewed/{listing}', [BuyerController::class, 'addToRecentlyViewed'])->name('buyer.recently-viewed.add');
    Route::get('/buyer/search', [BuyerController::class, 'search'])->name('buyer.search');
    Route::get('/buyer/suggestions', [BuyerController::class, 'getSuggestions'])->name('buyer.suggestions');
    Route::get('/buyer/similar/{listing}', [BuyerController::class, 'getSimilarListings'])->name('buyer.similar');
    Route::get('/buyer/purchase-history', [BuyerController::class, 'purchaseHistory'])->name('buyer.purchase-history');
    Route::get('/buyer/saved-searches', [BuyerController::class, 'savedSearches'])->name('buyer.saved-searches');
});

// Contact click tracking routes
Route::post('/contact-click/{listing}', [App\Http\Controllers\ContactClickController::class, 'trackClick'])->name('contact.click.track');

// Safety routes
Route::middleware(['auth'])->group(function () {
    Route::get('/safety/guidelines', [SafetyController::class, 'guidelines'])->name('safety.guidelines');
    Route::get('/safety/report', [SafetyController::class, 'reportForm'])->name('safety.report');
    Route::post('/safety/report', [SafetyController::class, 'submitReport'])->name('safety.report.submit');
    Route::post('/safety/block/{user}', [SafetyController::class, 'blockUser'])->name('safety.block');
    Route::post('/safety/unblock/{user}', [SafetyController::class, 'unblockUser'])->name('safety.unblock');
    Route::get('/safety/blocked-users', [SafetyController::class, 'blockedUsers'])->name('safety.blocked-users');
    Route::get('/safety/dispute', [SafetyController::class, 'disputeForm'])->name('safety.dispute');
    Route::post('/safety/dispute', [SafetyController::class, 'submitDispute'])->name('safety.dispute.submit');
    Route::get('/safety/my-reports', [SafetyController::class, 'myReports'])->name('safety.my-reports');
    Route::get('/safety/tips', [SafetyController::class, 'safetyTips'])->name('safety.tips');
    Route::get('/safety/stats', [SafetyController::class, 'safetyStats'])->name('safety.stats');
});

// Support routes
Route::get('/help-center', [SupportController::class, 'helpCenter'])->name('support.help-center');
Route::get('/contact-us', [SupportController::class, 'contactUs'])->name('support.contact');
Route::post('/contact-us', [SupportController::class, 'submitContact'])->name('support.contact.submit');
Route::get('/safety-tips', [SupportController::class, 'safetyTips'])->name('support.safety-tips');
Route::get('/how-it-works', [SupportController::class, 'howItWorks'])->name('support.how-it-works');

// Report routes
Route::get('/report-issue', [ReportController::class, 'reportIssue'])->name('support.report-issue');
Route::post('/report-issue', [ReportController::class, 'submitReport'])->name('support.report.submit');

// Legal routes
Route::get('/terms-of-service', [LegalController::class, 'termsOfService'])->name('legal.terms');
Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('legal.privacy');
Route::get('/cookie-policy', [LegalController::class, 'cookiePolicy'])->name('legal.cookies');

require __DIR__.'/auth.php';
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApprovedPhoneModelController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\ContactClickController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    
    // User Actions
    Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
    Route::patch('users/{user}/listing-limit', [UserController::class, 'updateListingLimit'])->name('users.listing-limit');
    Route::patch('users/{user}/user-type', [UserController::class, 'changeUserType'])->name('users.user-type');
    
    // Subscription Management
    Route::post('users/{user}/subscription', [UserController::class, 'updateSubscription'])->name('users.subscription.update');
    Route::post('users/{user}/subscription/cancel', [UserController::class, 'cancelSubscription'])->name('users.subscription.cancel');
    Route::post('users/{user}/subscription/extend', [UserController::class, 'extendSubscription'])->name('users.subscription.extend');
    
    // Approved Phone Models Management
    Route::resource('approved-models', ApprovedPhoneModelController::class);
    Route::post('approved-models/{approvedPhoneModel}/toggle-status', [ApprovedPhoneModelController::class, 'toggleStatus'])->name('approved-models.toggle-status');
    
    // Listing Management
    Route::resource('listings', ListingController::class);
    Route::post('listings/{listing}/toggle-status', [ListingController::class, 'toggleStatus'])->name('listings.toggle-status');
    Route::post('listings/{listing}/toggle-featured', [ListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
    Route::post('listings/bulk-action', [ListingController::class, 'bulkAction'])->name('listings.bulk-action');
    
    // Statistics
    Route::get('statistics', [UserController::class, 'statistics'])->name('statistics');
    
    // Contact Click Analytics
    Route::get('analytics/contact-clicks', [ContactClickController::class, 'getStats'])->name('analytics.contact-clicks');
    Route::get('analytics/contact-clicks/{listing}', [ContactClickController::class, 'getListingStats'])->name('analytics.contact-clicks.listing');
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public listing routes
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);

// Public category and brand routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);

// Public token packages
Route::get('/tokens/packages', [TokenController::class, 'packages']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Verification routes
    Route::post('/verify/email/send', [VerificationController::class, 'sendEmailVerification']);
    Route::post('/verify/email', [VerificationController::class, 'verifyEmail']);
    Route::post('/verify/sms/send', [VerificationController::class, 'sendSMSVerification']);
    Route::post('/verify/sms', [VerificationController::class, 'verifySMS']);
    Route::post('/verify/resend', [VerificationController::class, 'resendVerification']);

    // Token routes
    Route::get('/tokens/balance', [TokenController::class, 'balance']);
    Route::get('/tokens/transactions', [TokenController::class, 'transactions']);
    Route::post('/tokens/purchase', [TokenController::class, 'purchase']);
    Route::post('/tokens/consume', [TokenController::class, 'consumeForListing']);

    // Listing management routes (requires verification)
    Route::middleware('verified')->group(function () {
        Route::post('/listings', [ListingController::class, 'store']);
        Route::put('/listings/{id}', [ListingController::class, 'update']);
        Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
        Route::post('/listings/{id}/renew', [ListingController::class, 'renew']);
    });

    // Conversation routes (requires verification)
    Route::middleware('verified')->group(function () {
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::get('/conversations/{id}', [ConversationController::class, 'show']);
        Route::post('/conversations', [ConversationController::class, 'store']);
        Route::post('/conversations/{id}/messages', [ConversationController::class, 'sendMessage']);
        Route::post('/conversations/{id}/close', [ConversationController::class, 'close']);
    });
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/listings', [AdminController::class, 'listings']);
    Route::post('/admin/listings/{id}/approve', [AdminController::class, 'approveListing']);
    Route::post('/admin/listings/{id}/reject', [AdminController::class, 'rejectListing']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/statistics', [AdminController::class, 'statistics']);
});

// Monthly token distribution (cron job)
Route::post('/tokens/monthly-distribution', [TokenController::class, 'distributeMonthlyTokens']);

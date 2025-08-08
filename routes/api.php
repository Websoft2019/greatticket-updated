<?php

use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckInController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ReligionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Open routes
Route::post('login', [ApiAuthController::class, 'login']);
Route::post('register', [ApiAuthController::class, 'register']);
Route::post('verifyOtp', [ApiAuthController::class, 'verifyOtp']);
Route::post('resendOtp', [ApiAuthController::class, 'resendOtp']);
Route::post('guest', [ApiAuthController::class, 'guestlogin']);
Route::post('reset-password', [ApiAuthController::class, 'resetPassword']);

Route::get('religions', [ReligionController::class, 'index']);


Route::post('coupon',[CouponController::class, 'index']);
Route::post('organizer/coupon', [CouponController::class, 'organizerBooking']);

// pages
Route::get('terms-condition', [PageController::class, 'termCondition']);
Route::get('privacy-policy', [PageController::class, 'privacy']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // coupon mobile
    Route::post('mobile-coupon', [CouponController::class, 'mobile']);
    
    // profile
    Route::get('logout', [ApiAuthController::class, 'logout']);
    Route::get('profile', [ApiAuthController::class, 'profile']);
    Route::put('profile', [ApiAuthController::class, 'updateProfile']);
    Route::post('changePassword',[ApiAuthController::class, 'changePassword']);
    Route::delete('/delete-account',[ApiAuthController::class,'deleteAccount'])->name('delete');

    // Cart Routes
    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });

    // Order Routes
    Route::post('/checkout', [OrderController::class, 'store']);

    // History
    Route::get('/history',[OrderController::class, 'history']);
    Route::get('/history/{id}',[OrderController::class, 'historyDetails']);

    Route::get('/getQR/{id}',[OrderController::class,'qrResponse']);

    Route::get('/user/notifications', [NotificationController::class, 'index']);
});

// Event Routes
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events');
    Route::get('/search', [EventController::class, 'search']);
    Route::get('{id}', [EventController::class, 'show'])->where('id', '[0-9]+');
    Route::get('/category/{id}',[EventController::class, 'categoryFilter']);
    Route::get('/trending',[EventController::class, 'trending']);
});

// Category
Route::get('/category',[EventController::class, 'category']);

// Package Routes
Route::prefix('events/{e_id}/packages')->group(function () {
    Route::get('/', [PackageController::class, 'index']);
    Route::get('{id}', [PackageController::class, 'show']);
});


// CheckIN
Route::post('/checkin',[CheckInController::class, 'check']);

// Seats
Route::get('/packages/{package}/seats', [\App\Http\Controllers\Api\SeatController::class, 'getPackageSeats']);

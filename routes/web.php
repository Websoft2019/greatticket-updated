<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Admin\SanamPayController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('test/report', [PageController::class, 'test']);
Route::get('test/ticket', [PageController::class, 'testTicket']);

include __DIR__ . "/admin.php";
include __DIR__ . "/organizer.php";

Route::get('/', [SiteController::class, 'getHome'])->name('getHome');
// Route::get('/site1', [SiteController::class, 'getHome'])->name('getSite1');
Route::get('/events',[SiteController::class, 'events'])->name('getEvents');
Route::get('/search-events',[SiteController::class, 'searchEvents'])->name('searchEvents');
Route::get('/event-detail/{slug}', [SiteController::class, 'getEventDetail'])->name('getEventDetail');
Route::get('/carts', [SiteController::class, 'getCart'])->name('getCart');
Route::delete('/delete-cart/{id}', [SiteController::class, 'cartDestroy'])->name('deleteCart');
Route::post('/cart/{package}/{guest?}', [SiteController::class, 'postAddtoCart'])->name('postAddtoCart');
Route::get('/checkout', [SiteController::class, 'getCheckout'])->name('getCheckout');
Route::get('/about-us', [SiteController::class, 'getAboutUs'])->name('getAboutUs');
Route::get('/contact-us', [SiteController::class, 'getContactUs'])->name('getContactUs');
//email
Route::post('/send-email', [SiteController::class, 'postSendEmail'])->name('postSendEmail');
Route::post('/checkout', [SiteController::class, 'postCheckout'])->name('postCheckout');
Route::get('/modify/confirm',[SiteController::class, 'viewConfirm'])->name('confirm.modify');
Route::put('/modify/confirm/{id}',[SiteController::class, 'updateConfirm'])->name('confirm.update');
Route::get('/confirm', [SiteController::class, 'getconfirm'])->name('getconfirm');
Route::post('/guest-user', [SiteController::class, 'guest'])->name('guest.user');
Route::get('terms-condition', [PageController::class, 'termsCondition'])->name('termsCondition');
Route::get('privacy-policy',[PageController::class, 'privacy'])->name('privacyPolicy');
Route::get("online/order/result", [SanamPayController::class, 'getSanangPayResult'])->name('payment.success');

// Login Admin
Route::get('/admin/login', [LoginController::class, 'showAdmin'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->middleware('guest')->name('admin.login.perform');

// Login Organizer
Route::get('/organizer/login', [LoginController::class, 'showOrganizer'])->middleware('guest')->name('organizer.login');
Route::post('/organizer/login', [LoginController::class, 'organizerLogin'])->middleware('guest')->name('organizer.login.perform');
// Register Organizer
Route::get('/organizer/register', [OrganizerController::class, 'organizerCreate'])->name('organizer.register');
Route::post('/organizer/register', [OrganizerController::class, 'organizerStore'])->name('organizer.store');
Route::get('/test/{order}', [SiteController::class, 'getTest']);

// Route::get('userdash',function(){
// 	$orders = collect();
// 	return view('site.user-dashboard',compact('orders'));
// })->name('userdashboard');

Route::get('contactus',function(){
	return view('site.contactus');
});

Route::get('userdashboard',[OrderController::class, 'history'])->middleware('auth')->name('history');
Route::post('/update-password', [UserProfileController::class, 'changePassword'])->middleware('auth')->name('updatePassword');
Route::get('history-details/{id}',[OrderController::class, 'details'])->middleware('auth')->name('history.details');
Route::get('/history/tickets/{op_id}/download-pdf', [OrderController::class, 'generatePdf'])->name('tickets.download.pdf');
Route::delete('/history/delete/{o_id}', [OrderController::class, 'destroy'])->middleware('auth')->name('order.delete');


// Route::get('/admin',function (){
//     return "This is testing";
// });


Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::get('/guest',[LoginController::class, 'guest'])->name('guest.login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['role:a'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::delete('/user/{id}',[HomeController::class, 'deleteAccount'])->name('user.delete');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('mobile/{payer_id}',[HomeController::class, 'mobile'])->name('mobile.payment');

//update cart qty
Route::post('/cart-update-quantity/{id}', [SiteController::class, 'updateCart'])->name('updateCart');


// seats

Route::post('package/seats', [SeatController::class, 'store'])->name('seats.store');

Route::get('/payment/reserved/{order:qr_code}', [PaymentController::class, 'showPaymentPage'])->name('payment.reserved');
Route::post('/payment/reserved/{order:qr_code}/confirm', [PaymentController::class, 'saveTicketUsers'])->name('payment.reserved.confirm');
Route::get('/payment/reserved/{order:qr_code}/pay', [PaymentController::class, 'showPaymentButton'])->name('payment.reserved.pay');


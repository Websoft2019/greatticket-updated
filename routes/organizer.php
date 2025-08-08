<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrganizerBookingController;
use App\Http\Controllers\Admin\ReservedBookingController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SanamPayController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeatController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/organizer/not-verified', function () {
    return view('site.not-verified');
})->name('organizer.not.verified');


Route::prefix('/organizer')->middleware(['role:o', 'organizer.verified'])->as('organizer.')->group(function () {
    Route::get('/seats-by-package/{id}', function ($id) {
        return \App\Models\Seat::where('package_id', $id)->get();
    });
   
    Route::get('dashboard', [HomeController::class, 'organizer'])->name('dashboard');
    Route::get('sanampay/{order}', [SanamPayController::class, 'getMannualPay'])->name('getMannualPay');
    Route::post('/check-order', [SanamPayController::class, 'postCheckOrderAndSendEmail'])->name('postCheckOrderAndSendEmail');
    Route::get('ajax/orders/{orderId}/details', [SanamPayController::class, 'getMakeapaid']);
    // Event
    Route::prefix('/event')->as('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
        Route::post('/store', [EventController::class, 'store'])->name('store');
        Route::put('/{id}/update', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [EventController::class, 'delete'])->name('delete');


        // Package
        Route::prefix('/package')->as('package.')->group(function () {
            Route::get('/{e_id}', [PackageController::class, 'index'])->where('e_id', '[0-9]+')->name('index');

            Route::get('/create', [PackageController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [PackageController::class, 'edit'])->name('edit');
            Route::post('/store', [PackageController::class, 'store'])->name('store');
            Route::put('/{id}/update', [PackageController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [PackageController::class, 'destroy'])->name('delete');

            Route::get('{id}/seats/create', [SeatController::class, 'create'])->name('seats.create');
            Route::get('{id}/seats/edit', [SeatController::class, 'edit'])->name('seats.edit');
            Route::put('{id}/seats', [SeatController::class, 'update'])->name('seats.update');
            Route::delete('{id}/seats/{seatId}', [SeatController::class, 'destroy'])->name('seats.destroy');
        });


        // Image
        Route::prefix('/image')->as('image.')->group(function () {
            Route::get('{e_id}/', [ImageController::class, 'index'])->where('e_id', '[0-9]+')->name('index');
            Route::get('/create', [ImageController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [ImageController::class, 'edit'])->name('edit');
            Route::post('/store', [ImageController::class, 'store'])->name('store');
            Route::put('/{id}/update', [ImageController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [ImageController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('/order')->as('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/details/{id}', [OrderController::class, 'details'])->name('details');
        Route::get('/test', [OrderController::class, 'test'])->name('test');
        Route::get('/report-list/{id}', [OrderController::class, 'testList'])->name('testList');
        Route::get('/test-details/{id}', [OrderController::class, 'testDetails'])->name('testDetails');
        Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('destroy');
    });

    // Sales Reports
    Route::get('/sales-report', [SalesController::class, 'salesReport'])->name('salesReport');

    // organiser booking
    Route::get('/bookings/create', [OrganizerBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [OrganizerBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{order}', [OrganizerBookingController::class, 'show'])->name('bookings.show');
    Route::get('/booking/confirmation/{order}', [OrganizerBookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/bookings/{order}/pdf', [OrganizerBookingController::class, 'downloadPdf'])->name('bookings.pdf');


    // Download events report
    Route::post('/event/export/pdf', [ExportController::class, 'exportPdf'])->name('event.export.pdf');
});

// Coupon
Route::prefix('/coupon')->as('coupons.')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('index');
    Route::post('/store', [CouponController::class, 'store'])->name('store');
    Route::put('/{id}/update', [CouponController::class, 'update'])->name('update');
    Route::post('/{id}/delete', [CouponController::class, 'destroy'])->name('delete');
});

// Reserved Bookings
Route::prefix('/bookings/reserved')->middleware(['role:o'])->as('bookings.reserved.')->group(function(){
    Route::get('/', [ReservedBookingController::class, 'index'])->name('index');
    Route::get('/details/{id}', [ReservedBookingController::class, 'show'])->name('show');
    Route::put('/bookings/reserved/update/{id}', [ReservedBookingController::class, 'update'])->name('update');
    Route::delete('/bookings/reserved/delete/{id}', [ReservedBookingController::class, 'destroy'])->name('destroy');

});
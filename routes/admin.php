<?php

use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\TermsConditionController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\TrendingEventController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->as('admin.')->middleware(['role:a'])->group(function(){
     Route::post('/send-daily-report', [SalesController::class, 'sendDailyReport'])->name('senddailyreport');
    Route::prefix('/organizer')->as('organizer.')->group(function(){
        Route::get('/',[OrganizerController::class,'index'])->name('index');
        Route::get('/create',[OrganizerController::class,'create'])->name('create');
        Route::get('/{id}/edit',[OrganizerController::class,'edit'])->name('edit');
        Route::post('/store',[OrganizerController::class,'store'])->name('store');
        Route::get('/{id}/verify',[OrganizerController::class, 'verify'])->name('verify');
        Route::put('/{id}/update',[OrganizerController::class,'update'])->name('update');
        Route::delete('/{id}/delete',[OrganizerController::class,'delete'])->name('delete');
    });

    // Pages
    Route::prefix('/page')->as('page.')->group(function(){
        // Privacy Policy
        Route::get('/privacy',[PrivacyPolicyController::class,'index'])->name('privacy.index');
        Route::put('/privacy/{id}/update',[PrivacyPolicyController::class,'update'])->name('privacy.update');

        // Terms and Condition
        Route::get('/terms-condition',[TermsConditionController::class,'index'])->name('term.index');
        Route::put('/terms-condition/{id}/update',[TermsConditionController::class,'update'])->name('term.update');
    });

    Route::prefix('/event/category')->as('event.category.')->group(function (){
        Route::get('/',[CategoryController::class, 'index'])->name('index');
        Route::post('/store',[CategoryController::class, 'store'])->name('store');
        Route::put('/update/{id}',[CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[CategoryController::class, 'destroy'])->name('delete');
    });

    //contact us
        Route::get('/contact-us',[ContactUsController::class, 'index'])->name('contactUs');
        Route::delete('/delete-contact-us/{id}',[ContactUsController::class, 'destroy'])->name('deleteContactUs');


    // Sales Reports
    Route::get('/sales-report/test', [SalesController::class, 'adminReport'])->name('salesReport.test');
    Route::get('sales-report',[SalesController::class, 'test'])->name('salesReport');
   
    // Carousel
    Route::prefix('/carousel')->as('carousel.')->group(function (){
        Route::get('/',[CarouselController::class, 'index'])->name('index');
        Route::post('/store',[CarouselController::class, 'store'])->name('store');
        Route::put('/{id}/update',[CarouselController::class, 'update'])->name('update');
        Route::delete('/{id}/delete',[CarouselController::class, 'destroy'])->name('destroy');
    });

    // Trending Events
    Route::get('/trending-events',[TrendingEventController::class, 'index'])->name('trending-events.index');
    Route::post('/trending-events',[TrendingEventController::class, 'store'])->name('trending-events.store');
    Route::delete('/trending-events/{id}',[TrendingEventController::class, 'destroy'])->name('trending-events.destroy');

    // CheckIn
    Route::prefix('/checkin')->as('checkin.')->group(function(){
        Route::get('/events', [CheckInController::class, 'events'])->name('events');
        Route::get('/checkin/{id}', [CheckInController::class, 'listCheckedin'])->name('checkin');
        Route::post('/checkin/{id}', [CheckInController::class, 'checkin'])->name('checkin.store');

        Route::get('/package/checkin/{id}',[CheckInController::class, 'packageListCheckedin'])->name('package.checkin');
        Route::post('/package/checkin/{id}', [CheckInController::class, 'packageCheckin'])->name('package.checkin.store');
    });
});



<?php

use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\posts\blackListController;
use App\Http\Controllers\admin\system\seederController;
use App\Http\Controllers\catalog\id\paymentController;
use App\Http\Controllers\catalog\posts\postController;
use App\Http\Controllers\system\artisanController;
use App\Http\Controllers\system\DarkModeController;
use App\Http\Controllers\system\gettersController;
use App\Http\Controllers\system\imageUploadController;
use App\Http\Controllers\system\postServicesController;
use Illuminate\Support\Facades\Route;

//Auth
Route::prefix('services')->group(function () {
    Route::post('/upload-image', [imageUploadController::class, 'uploadImage']); //Upload Single Image
    Route::delete('/clear-cache', [gettersController::class, 'clearCacheType']); //Clear Cashe By Types
    Route::post('/get-realtime-to-ajax', [dashboardController::class, 'getRealtimeToAjax'])->name('services.getRealtimeToAjax'); //Get BlackList Phones
    Route::post('/blacklist_phone_data', [blackListController::class, 'getReviews'])->name('services.blacklistPhoneData'); //Get BlackList Phones
    Route::post('/post/delete/{id}', [postController::class, 'deleteByCode'])->name('services.deletePost'); //Delete Post
    Route::get('/get_city_data', [gettersController::class, 'getZoneMetro']); //Get BlackList Phones
    Route::post('/show_phone', [postServicesController::class, 'showPhone'])->name('service.show.phone');//Show Phone
    Route::post('/show_salon__phone', [postServicesController::class, 'showSalonPhone'])->name('service.salon.show.phone');//Salon show Phone
    Route::post('/transition_social', [postServicesController::class, 'transitionSocial'])->name('service.transition.social');//Show Phone

    //MultiUpload images
    Route::post('/upload-post-images', [imageUploadController::class, 'multiUploadImage'])->name('multiUploadPostImages');
    Route::post('/upload-salon-images', [imageUploadController::class, 'multiUploadImageSalon'])->name('multiUploadSalonImages');
    Route::post('/delete-image-to-path', [imageUploadController::class, 'deleteImageToPath'])->name('multiDeletePostImages');
});

//Seeders
Route::middleware('auth')->prefix('config')->group(function () {
    Route::get('/seeder', [seederController::class, 'seeder'])->name('seeders');
});


//Dark Mode
Route::post('/toggle-dark-mode', [DarkModeController::class, 'toggle'])->name('toggle-dark-mode');

//RuKassa Checker
Route::get('/api/ruKassaChecker', [paymentController::class, 'ruKassaChecker'])->name('service.ruKassa.checker');

//Artisan
Route::post('/run-migrations', [artisanController::class, 'runMigrations'])->name('run.migrations');

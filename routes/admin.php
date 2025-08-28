<?php

use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\errors\errorsController;
use App\Http\Controllers\admin\info\bannerController;
use App\Http\Controllers\admin\info\feedbackController;
use App\Http\Controllers\admin\info\informationController;
use App\Http\Controllers\admin\info\newsController;
use App\Http\Controllers\admin\info\ticketsController;
use App\Http\Controllers\admin\location\cityController;
use App\Http\Controllers\admin\location\metroController;
use App\Http\Controllers\admin\location\zoneController;
use App\Http\Controllers\admin\moderation\postModerController;
use App\Http\Controllers\admin\moderation\reviewModerController;
use App\Http\Controllers\admin\moderation\salonModerController;
use App\Http\Controllers\admin\pay\payAaioController;
use App\Http\Controllers\admin\pay\payCodeController;
use App\Http\Controllers\admin\pay\payRuKassaController;
use App\Http\Controllers\admin\pay\transactionController;
use App\Http\Controllers\admin\post_images\postImageMainController;
use App\Http\Controllers\admin\post_images\postImagePhotoController;
use App\Http\Controllers\admin\post_images\postImageSelfieController;
use App\Http\Controllers\admin\post_images\postImageVerifyController;
use App\Http\Controllers\admin\post_images\postImageVideoController;
use App\Http\Controllers\admin\posts\blackListController;
use App\Http\Controllers\admin\posts\categoryController;
use App\Http\Controllers\admin\posts\postBannerController;
use App\Http\Controllers\admin\posts\postController;
use App\Http\Controllers\admin\posts\reviewsController;
use App\Http\Controllers\admin\posts\tagsController;
use App\Http\Controllers\admin\salon\salonController;
use App\Http\Controllers\admin\system\accessController;
use App\Http\Controllers\admin\system\analyticsController;
use App\Http\Controllers\admin\system\settingsController;
use App\Http\Controllers\admin\system\usersController;
use App\Http\Controllers\admin\system\usersGroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('config')->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('admin.config');
    Route::get('/analytics', [analyticsController::class, 'index'])->name('admin.analytics');
    Route::resource('ticket_system', ticketsController::class);

    //Feedback
    Route::resource('feedback', feedbackController::class);

    Route::prefix('moderation')->group(function () {
        Route::resource('post_moderation', postModerController::class);
        Route::resource('salon_moderation', salonModerController::class);
        Route::resource('review_moderation', reviewModerController::class);
        Route::post('/review_moderation/access', [reviewModerController::class, 'access'])->name('admin.moderation.review.access');
    });

    Route::prefix('posts')->group(function () {
        Route::resource('post', postController::class);
        Route::post('/post/mass-delete', [postController::class, 'massDelete'])->name('admin.post.massDelete');
        Route::resource('banner_post', postBannerController::class);
        Route::post('/banner_post/mass-delete', [postBannerController::class, 'massDelete'])->name('admin.banner_post.massDelete');
        Route::resource('tags', tagsController::class);
        Route::post('/tags/mass-delete', [tagsController::class, 'massDelete'])->name('admin.tags.massDelete');
        Route::resource('category', categoryController::class);
        Route::post('/category/mass-delete', [categoryController::class, 'massDelete'])->name('admin.category.massDelete');
        Route::resource('blacklist', blackListController::class);
        Route::post('/blacklist/mass-delete', [blackListController::class, 'massDelete'])->name('admin.blacklist.massDelete');
        Route::resource('review', reviewsController::class);
        Route::post('/review/mass-delete', [reviewsController::class, 'massDelete'])->name('admin.review.massDelete');
    });

    Route::resource('salon', salonController::class);
    Route::post('/salon/mass-delete', [salonController::class, 'massDelete'])->name('admin.salon.massDelete');

    Route::prefix('post_images')->group(function () {
        Route::resource('content_main', postImageMainController::class);
        Route::resource('content_photo', postImagePhotoController::class);
        Route::resource('content_selfie', postImageSelfieController::class);
        Route::resource('content_verify', postImageVerifyController::class);
        Route::resource('content_video', postImageVideoController::class);
    });

    Route::prefix('location')->group(function () {
        Route::resource('city', cityController::class);
        Route::post('/city/mass-delete', [cityController::class, 'massDelete'])->name('admin.city.massDelete');
        Route::resource('zone', zoneController::class);
        Route::post('/zone/mass-delete', [zoneController::class, 'massDelete'])->name('admin.zone.massDelete');
        Route::resource('metro', metroController::class);
        Route::post('/metro/mass-delete', [metroController::class, 'massDelete'])->name('admin.metro.massDelete');
    });

    Route::prefix('information')->group(function () {
        Route::resource('news', newsController::class);
        Route::post('/news/mass-delete', [newsController::class, 'massDelete'])->name('admin.news.massDelete');
        Route::resource('information', informationController::class);
        Route::post('/information/mass-delete', [informationController::class, 'massDelete'])->name('admin.information.massDelete');
        Route::resource('banner', bannerController::class);
        Route::post('/banner/mass-delete', [bannerController::class, 'massDelete'])->name('admin.banner.massDelete');
    });

    Route::prefix('pay')->group(function () {
        Route::resource('payment_code', payCodeController::class);
        Route::get('/payment_aaio', [payAaioController::class, 'index'])->name('admin.pay.payment_aaio');
        Route::post('/payment_aaio', [payAaioController::class, 'saveSetting']);
        Route::get('/payment_ruKassa', [payRuKassaController::class, 'index'])->name('admin.pay.payment_ruKassa');
        Route::post('/payment_ruKassa', [payRuKassaController::class, 'saveSetting']);
        Route::resource('transaction', transactionController::class);
    });

    Route::prefix('users')->group(function () {
        Route::resource('users', usersController::class);
        Route::resource('user_group', usersGroupController::class);
    });

    Route::prefix('app')->group(function () {
        Route::get('/settings', [settingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [settingsController::class, 'saveSetting']);
    });

    //Errors
    Route::get('/errors/{data_type?}/{data_id?}', [errorsController::class, 'not_found'])->name('admin.errors.not_found');

    Route::get('/no-access', [accessController::class, 'index'])->name('admin.no_access');
});

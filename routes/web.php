<?php

use App\Http\Controllers\catalog\id\ticketsController;
use App\Http\Controllers\catalog\info\contactController;
use App\Http\Controllers\catalog\info\news\newsController;
use App\Http\Controllers\catalog\info\news\newsPageController;
use App\Http\Controllers\catalog\info\information\informationController;
use App\Http\Controllers\catalog\info\information\informationPageController;
use App\Http\Controllers\catalog\auth\forgotController;
use App\Http\Controllers\catalog\auth\loginController;
use App\Http\Controllers\catalog\auth\logoutController;
use App\Http\Controllers\catalog\auth\registerController;
use App\Http\Controllers\catalog\id\accountController;
use App\Http\Controllers\catalog\id\blackListController;
use App\Http\Controllers\catalog\id\paymentController;
use App\Http\Controllers\catalog\id\postAddController;
use App\Http\Controllers\catalog\id\postEditController;
use App\Http\Controllers\catalog\id\salonAddController;
use App\Http\Controllers\catalog\id\salonEditController;
use App\Http\Controllers\catalog\id\salonServicesController;
use App\Http\Controllers\catalog\info\priceServicesController;
use App\Http\Controllers\catalog\location\cityController;
use App\Http\Controllers\catalog\location\zoneController;
use App\Http\Controllers\catalog\mainController;
use App\Http\Controllers\catalog\posts\allController;
use App\Http\Controllers\catalog\posts\mapController;
use App\Http\Controllers\catalog\posts\postController;
use App\Http\Controllers\catalog\posts\searchController;
use App\Http\Controllers\catalog\posts\servicesController;
use App\Http\Controllers\catalog\posts\tagsController;
use App\Http\Controllers\catalog\salon\salonController;
use App\Http\Controllers\catalog\salon\salonListController;
use App\Http\Controllers\catalog\sections\bdsmController;
use App\Http\Controllers\catalog\sections\eliteController;
use App\Http\Controllers\catalog\sections\healthController;
use App\Http\Controllers\catalog\sections\individualController;
use App\Http\Controllers\catalog\sections\latestController;
use App\Http\Controllers\catalog\sections\masseuseController;
use App\Http\Controllers\catalog\sections\popularController;
use App\Http\Controllers\catalog\sections\premiumController;
use App\Http\Controllers\errors\errorsController;
use App\Http\Controllers\system\sitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [mainController::class, 'index'])->name('client.index');

//Auth
Route::middleware('guest')->prefix('auth')->group(function () {
    Route::get('/sign-in', [loginController::class, 'index'])->name('client.auth.sign_in');
    Route::post('/sign-in', [loginController::class, 'post']);
    Route::post('/sign-in-popup', [loginController::class, 'popup'])->name('client.auth.popup');
    Route::get('/sign-up', [registerController::class, 'index'])->name('client.auth.sign_up');
    Route::post('/sign-up', [registerController::class, 'post']);
    Route::get('/sign-up/finish', [registerController::class, 'register_finish'])->name('client.auth.register_finish');
    Route::get('/sign-up/finish/{id}/{token}', [registerController::class, 'activate'])->name('client.auth.register_activate');
    Route::get('/forgot', [forgotController::class, 'index'])->name('client.auth.forgot');
    Route::post('/forgot', [forgotController::class, 'post']);
    Route::get('/forgot/{id}/{token}', [forgotController::class, 'forgotLink'])->name('client.auth.forgot_link');
    Route::post('/forgot/{id}/{token}', [forgotController::class, 'forgotLinkPost']);
});

//Profile
Route::middleware('auth')->prefix('id')->group(function () {
    Route::get('/my', [accountController::class, 'index'])->name('client.auth.account'); //Account

    //Posts
    Route::get('/post', [\App\Http\Controllers\catalog\id\postController::class, 'index'])->name('client.auth.posts');
    Route::get('/post/create', [postAddController::class, 'index'])->name('client.auth.post.create');
    Route::post('/post/create', [postAddController::class, 'createPost']);
    Route::get('/post/update-{id}', [postEditController::class, 'index'])->name('client.auth.post.update');
    Route::post('/post/update-{id}', [postEditController::class, 'updatePost']);
    Route::post('/post-list-del', [\App\Http\Controllers\catalog\id\postController::class, 'delPostById'])->name('client.auth.post.delete');
    Route::post('/post-service', [\App\Http\Controllers\catalog\id\servicesController::class, 'getServices'])->name('client.auth.post.services');
    Route::post('/post-service-activation', [\App\Http\Controllers\catalog\id\servicesController::class, 'daysActivation'])->name('client.auth.post.service.activationDays');
    Route::post('/post-service-up-to-top', [\App\Http\Controllers\catalog\id\servicesController::class, 'upToTop'])->name('client.auth.post.service.upToTop');
    Route::post('/post-service-diamond', [\App\Http\Controllers\catalog\id\servicesController::class, 'diamondActivation'])->name('client.auth.post.service.diamond');
    Route::post('/post-service-vip', [\App\Http\Controllers\catalog\id\servicesController::class, 'vipActivation'])->name('client.auth.post.service.vip');
    Route::post('/post-service-color', [\App\Http\Controllers\catalog\id\servicesController::class, 'colorActivation'])->name('client.auth.post.service.color');

    //Salon
    Route::get('/salon', [\App\Http\Controllers\catalog\id\salonController::class, 'index'])->name('client.auth.salon');
    Route::get('/salon/create', [salonAddController::class, 'index'])->name('client.auth.salon.create');
    Route::post('/salon/create', [salonAddController::class, 'createPost']);
    Route::get('/salon/update-{id}', [salonEditController::class, 'index'])->name('client.auth.salon.update');
    Route::post('/salon/update-{id}', [salonEditController::class, 'updatePost']);
    Route::post('/salon-list-del', [\App\Http\Controllers\catalog\id\salonController::class, 'delSalonById'])->name('client.auth.salon.delete');
    Route::post('/salon-service', [salonServicesController::class, 'getServices'])->name('client.auth.salon.services');
    Route::post('/salon-service-activation', [salonServicesController::class, 'daysActivation'])->name('client.auth.salon.service.activationDays');
    Route::post('/salon-service-up-to-top', [salonServicesController::class, 'upToTop'])->name('client.auth.salon.service.upToTop');

    //Tickets
    Route::get('/tickets', [ticketsController::class, 'index'])->name('client.auth.tickets');
    Route::get('/tickets/create', [ticketsController::class, 'create'])->name('client.auth.tickets.create');
    Route::post('/tickets/create', [ticketsController::class, 'createPost']);
    Route::get('/tickets/id/{id}', [ticketsController::class, 'item'])->name('client.auth.tickets.item');
    Route::post('/tickets-write', [ticketsController::class, 'writeTicket'])->name('client.auth.tickets.ajax');
    Route::post('/ticket-close', [ticketsController::class, 'closeTicket'])->name('client.auth.tickets.close');

    Route::get('/pay', [paymentController::class, 'index'])->name('client.auth.pay');
    Route::get('/pay/aaio/aaioPayment/{amount?}', [paymentController::class, 'aaioPayment'])->name('client.auth.pay.aaio.getData');
    Route::get('/pay/aaio/status', [paymentController::class, 'aaioStatus'])->name('client.auth.pay.aaio.status');
    Route::get('/pay/aaio/success', [paymentController::class, 'aaioSuccess'])->name('client.auth.pay.aaio.success');
    Route::get('/pay/aaio/fail', [paymentController::class, 'aaioFail'])->name('client.auth.pay.aaio.fail');
    Route::get('/pay/ruKassa/ruKassaPayment/{amount?}', [paymentController::class, 'ruKassaPayment'])->name('client.auth.pay.ruKassa.getData');
    Route::get('/pay/ruKassa/success', [paymentController::class, 'ruKassaSuccess'])->name('client.auth.pay.ruKassa.success');
    Route::get('/pay/ruKassa/fail', [paymentController::class, 'ruKassaFail'])->name('client.auth.pay.ruKassa.fail');

    Route::get('/pay/pin-code/{pin_code?}', [paymentController::class, 'pinCodePayment'])->name('client.auth.pay.pin.getData');

    //Black List
    Route::get('/black-list', [blackListController::class, 'index'])->name('client.auth.blackList');
    Route::post('/black-list-get', [blackListController::class, 'getPhoneData'])->name('client.auth.blackList.get');
    Route::post('/black-list-set', [blackListController::class, 'setPhoneData'])->name('client.auth.blackList.create');
    Route::post('/black-list-del', [blackListController::class, 'delPhoneData'])->name('client.auth.blackList.delete');

    //Change password and user data
    Route::post('/change-password-ajax', [accountController::class, 'changePassword'])->name('client.auth.change.password'); //Change password ajax
    Route::post('/change-user-ajax', [accountController::class, 'changeUser'])->name('client.auth.change.user'); //Change password ajax
    Route::post('/change-avatar-ajax', [accountController::class, 'changeAvatar'])->name('client.auth.change.avatar'); //Change password ajax

    Route::get('/logout', [logoutController::class, 'logout'])->name('client.auth.logout'); //Logout
});
Route::prefix('section')->group(function () {
    Route::get('/popular', [popularController::class, 'index'])->name('client.post.popular');
    Route::get('/latest', [latestController::class, 'index'])->name('client.post.latest');
    Route::get('/elite', [eliteController::class, 'index'])->name('client.post.elite');
    Route::get('/individual', [individualController::class, 'index'])->name('client.post.individual');
    Route::get('/premium', [premiumController::class, 'index'])->name('client.post.premium');
    Route::get('/health', [healthController::class, 'index'])->name('client.post.health');
    Route::get('/masseuse', [masseuseController::class, 'index'])->name('client.post.masseuse');
    Route::get('/bdsm', [bdsmController::class, 'index'])->name('client.post.bdsm');
});

//Pages
Route::get('/all', [allController::class, 'index'])->name('client.post.all');
Route::get('/search', [searchController::class, 'index'])->name('client.post.search');
Route::get('/map', [mapController::class, 'index'])->name('client.post.map');

Route::get('/services', [servicesController::class, 'index'])->name('client.services.list');
Route::get('/service/{service_name}', [servicesController::class, 'item'])->name('client.services.item');

Route::get('/tags', [tagsController::class, 'index'])->name('client.tags.list');
Route::get('/tag/{tag_name}', [tagsController::class, 'item'])->name('client.tags.item');

//Post
Route::get('/post/{post_id}-{name}', [postController::class, 'index'])->name('client.post');
Route::post('/post-write-review', [postController::class, 'writeReview'])->name('client.post.review.create');

//Information
Route::get('/information', [informationController::class, 'index'])->name('client.information.all');
Route::get('/information/{info_id}-{title}', [informationPageController::class, 'index'])->name('client.information');

//Info Pages
Route::get('/services-and-pricing', [priceServicesController::class, 'index'])->name('client.priceServices');
Route::get('/contact', [contactController::class, 'index'])->name('client.contact');
Route::post('/contact', [contactController::class, 'post']);

//News
Route::get('/news', [newsController::class, 'index'])->name('client.news.all');
Route::get('/news/{news_id}-{title}', [newsPageController::class, 'index'])->name('client.news');
Route::post('/news/rate', [newsPageController::class, 'rateNews'])->name('client.news.rate');

//City
Route::get('/city', [cityController::class, 'index'])->name('client.city.list');
Route::get('/city/{city_id}-{title}', [cityController::class, 'item'])->name('client.city.item');

//Zone
Route::get('/zone', [zoneController::class, 'index'])->name('client.zone.list');
Route::get('/zone/{zone_id}-{title}', [zoneController::class, 'item'])->name('client.zone.item');

//Salon
Route::get('/salon', [salonListController::class, 'index'])->name('client.salon.index');
Route::get('/salon/{salon_id}-{title}', [salonController::class, 'index'])->name('client.salon');

Route::get('/sitemap.xml', [sitemapController::class, 'index'])->name('client.sitemap.main');
Route::get('/sitemap-pages.xml', [sitemapController::class, 'pages'])->name('client.sitemap.pages');
Route::get('/sitemap-posts-{page_id}.xml', [sitemapController::class, 'posts'])->name('client.sitemap.posts');
Route::get('/sitemap-city.xml', [sitemapController::class, 'city'])->name('client.sitemap.city');
Route::get('/sitemap-zone.xml', [sitemapController::class, 'zone'])->name('client.sitemap.zone');
Route::get('/sitemap-services.xml', [sitemapController::class, 'services'])->name('client.sitemap.services');
Route::get('/sitemap-tags.xml', [sitemapController::class, 'tags'])->name('client.sitemap.tags');

//Errors
Route::get('/error-{code}', [errorsController::class, 'index'])->name('client.errors');

//Ajax
Route::post('/post-service', [postController::class, 'getServices'])->name('client.post.services');
Route::post('/post-service-activation', [\App\Http\Controllers\catalog\id\servicesController::class, 'daysActivation'])->name('client.post.service.activationDays');
Route::post('/post-service-up-to-top', [\App\Http\Controllers\catalog\id\servicesController::class, 'upToTop'])->name('client.post.service.upToTop');
Route::post('/post-service-diamond', [\App\Http\Controllers\catalog\id\servicesController::class, 'diamondActivation'])->name('client.post.service.diamond');
Route::post('/post-service-vip', [\App\Http\Controllers\catalog\id\servicesController::class, 'vipActivation'])->name('client.post.service.vip');
Route::post('/post-service-color', [\App\Http\Controllers\catalog\id\servicesController::class, 'colorActivation'])->name('client.post.service.color');

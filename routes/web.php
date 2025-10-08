<?php

<<<<<<< HEAD
=======

>>>>>>> 23c30d7 (Escort project)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\FaqController;
use App\Http\Controllers\admin\AdsController;
use App\Http\Controllers\admin\PaymentGatewayController;
use App\Http\Controllers\fan\DashboardController as FanDashboardController;
use App\Http\Controllers\fan\LoginController as FanLoginController;
use App\Http\Controllers\escort\DashboardController as EscortDashboardController;
use App\Http\Controllers\escort\LoginController as EscortLoginController;
<<<<<<< HEAD
=======
use App\Http\Controllers\escort\PhotosController;
use App\Http\Controllers\escort\VideosController;
>>>>>>> 23c30d7 (Escort project)
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
<<<<<<< HEAD
=======
use App\Http\Controllers\LocationController;
>>>>>>> 23c30d7 (Escort project)

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/load-more-escorts', [HomeController::class, 'loadMoreEscorts'])->name('load-more-escorts');


<<<<<<< HEAD
=======
// Load escorts by category - AJAX
Route::get('/load-escorts-by-category', [HomeController::class, 'loadEscortsByCategory'])->name('load.escorts.by.category');

// routes for filtering escorts by location
Route::get('/filter-escorts', [HomeController::class, 'filterEscorts'])->name('filter.escorts');

// categories page show 
Route::get('/category/{id}', [HomeController::class, 'showCategory'])->name('category.show');

Route::get('/country/{id}', [HomeController::class, 'showCountry'])->name('country.show');

>>>>>>> 23c30d7 (Escort project)
// Check authentication status
Route::get('/check-auth', function () {
  return response()->json(['isAuthenticated' => Auth::guard('fan')->check()]);
})->name('check-auth');

<<<<<<< HEAD
// Billing process
Route::post('/billing', [HomeController::class, 'processBilling'])->name('billing')->middleware('fan.auth');

// Payment page for BTCPay
Route::get('/payment/{invoice_id}', [HomeController::class, 'showPaymentPage'])->name('payment.page');

// Webhook for BTCPay confirmation
Route::post('/btcpay-webhook', [HomeController::class, 'handleWebhook'])->name('btcpay.webhook');

// payment result
Route::get('/payment-success', fn() => view('thankyou'))->name('payment.success');
Route::get('/payment-failed', function () {
  return view('payment-failed');
})->name('payment.failed');
Route::get('/payment-result', [HomeController::class, 'paymentResult'])->name('payment.result');
=======
Route::post('/create-invoice', [HomeController::class, 'createInvoice'])->name('create.invoice');

// Billing process
Route::post('/billing', [HomeController::class, 'processBilling'])->name('billing')->middleware('fan.auth');

Route::get('/payment-success/{invoice_id?}', [HomeController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-failed/{escort_id?}', [HomeController::class, 'paymentFailed'])->name('payment.failed');

// Choose subscription plan before billing
Route::get('/choose-plan/{escort_id}', [HomeController::class, 'choosePlan'])
  ->where('escort_id', '[0-9]+')
  ->name('choose.plan')
  ->middleware('fan.auth');

// Payment page for BTCPay
Route::get('/payment/{invoice_id}', [HomeController::class, 'showPaymentPage'])->name('payment.page');

// Webhook for BTCPay confirmation (CSRF exempt)
Route::post('/btcpay-webhook', [HomeController::class, 'handleWebhook'])->name('btcpay.webhook')->withoutMiddleware(['web']);

// payment result
Route::get('/payment-success/{invoice_id?}', [HomeController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-failed/{escort_id?}', [HomeController::class, 'paymentFailed'])->name('payment.failed');
Route::get('/payment-result', [HomeController::class, 'paymentResult'])->name('payment.result');
Route::get('/check-payment-status', [HomeController::class, 'checkPaymentStatus'])->name('check.payment.status');
Route::get('/payment-status-page', function() { return view('payment-status-check'); })->name('payment.status.page');
Route::get('/payment-complete', function() { return view('payment-complete'); })->name('payment.complete');
Route::get('/payment-instructions', function() { return view('payment-instructions'); })->name('payment.instructions');
Route::get('/payment-processing', function() { return view('payment-processing'); })->name('payment.processing');
Route::get('/btcpay-redirect', function() { return view('btcpay-redirect'); })->name('btcpay.redirect');

// Manual payment sync route
Route::get('/sync-payments', [HomeController::class, 'syncPayments'])->name('sync.payments');

// Debug billing route
Route::get('/debug-billing', [HomeController::class, 'debugBilling'])->name('debug.billing')->middleware('fan.auth');

// Subscription downgrade route
Route::post('/subscription/downgrade', [HomeController::class, 'downgradeSubscription'])->name('subscription.downgrade')->middleware('fan.auth');

// Escort profile listing
Route::get('listing/escort-{id}', [CommonController::class, 'listingEscortProfile'])
  ->where('id', '[0-9]+')
  ->name('listing.profile');




Route::get('get-countries', [LocationController::class, 'getCountries'])->name('get.countries');
Route::get('get-states/{country_id}', [LocationController::class, 'getStates'])->name('get.states');
Route::get('get-cities/{state_id}', [LocationController::class, 'getCities'])->name('get.cities');

>>>>>>> 23c30d7 (Escort project)

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// START ROUTES FOR THE USER

Route::group(['prefix' => '/'], function () {

  Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');

  Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::post('process-register', [LoginController::class, 'processRegister'])->name('processRegister');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

    Route::get('verify-user/{email}', [LoginController::class, 'VerifyUser']);
    Route::post('/resend-verification/{email}', [LoginController::class, 'resendVerification'])->name('resend.verification');

    // password reset routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Support
    Route::get('support', [SupportController::class, 'index'])->name('support');
    Route::post('support/submit', [SupportController::class, 'submit'])->name('support.submit');
    Route::get('support/ticket/create', [SupportController::class, 'create'])->name('support.ticket.create');
    Route::get('support/report', [SupportController::class, 'report'])->name('support.report');
    Route::get('faqs/search', [SupportController::class, 'searchFaq'])->name('faqs.search');

<<<<<<< HEAD
    // hjsb

=======
>>>>>>> 23c30d7 (Escort project)
    // Terms & privecy Policy
    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/privacy', 'legal.privacy')->name('privacy');

  });

  // Authentiated Middleware
  Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
  });

});

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// START ROUTES FOR THE ESCORT

Route::group(['prefix' => 'escort'], function () {

  Route::group(['middleware' => 'escort.guest'], function () {
    Route::get('register', [EscortLoginController::class, 'escortRegister'])->name('escort.register');
    Route::post('register/step-1', [EscortLoginController::class, 'processRegisterStep1'])->name('escort.register.step1');
    Route::get('register/step-2/{userid}', [EscortLoginController::class, 'showRegisterStep2'])->name('escort.register.step2');
    Route::post('register/step-2/{userid}', [EscortLoginController::class, 'processRegisterStep2'])->name('escort.register.step2.post');

    Route::post('authenticate', [EscortLoginController::class, 'authenticate'])->name('escort.authenticate');

  });

  // Authentiated Middleware
  Route::group(['middleware' => 'escort.auth'], function () {
    Route::get('dashboard', [EscortDashboardController::class, 'index'])->name('escort.dashboard');
    Route::get('profile-settings', [EscortDashboardController::class, 'profileSettings'])->name('escort.profile.settings');
    Route::get('account-settings', [EscortDashboardController::class, 'accountSettings'])->name('escort.account.settings');

    Route::get('get-users', [UserController::class, 'getAllUsers'])->name('get_all_users');
    Route::get('get-user', [UserController::class, 'getUser'])->name('get_user');
    Route::post('add-favourites', [UserController::class, 'addFavourites'])->name('add_favourites');
    Route::post('remove-favourites/{id}', [UserController::class, 'removeFavourites'])->name('remove_favourites');
    Route::post('add-friends', [UserController::class, 'addFriends'])->name('add_friends');
    Route::post('remove-friends/{id}', [UserController::class, 'removeFriends'])->name('remove_friends');
    Route::post('get-messages/{id}', [UserController::class, 'getMessages'])->name('get_message');

    Route::get('get-user-chat', [MessageController::class, 'getUserChat']);

<<<<<<< HEAD
=======
    // Escort manage album photos
    Route::get('photo', [PhotosController::class, 'photosManage'])->name('escort.photos');
    Route::post('photo/', [PhotosController::class, 'photosStore'])->name('escort.photos.store');
    Route::get('photos-list/{id}', [PhotosController::class, 'photosList'])->name('escort.photos.list');
    Route::put('photos/{id}', [PhotosController::class, 'photosUpdate'])->name('escort.photos.update');
    Route::delete('photos/{id}', [PhotosController::class, 'photosDestroy'])->name('escort.photos.delete');

    // Escort manage album Videos
    Route::get('video', [VideosController::class, 'videosManage'])->name('escort.videos');
    Route::post('video/', [VideosController::class, 'videosStore'])->name('escort.videos.store');
    Route::get('videos-list/{id}', [VideosController::class, 'videosList'])->name('escort.videos.list');
    Route::put('videos/{id}', [VideosController::class, 'videosUpdate'])->name('escort.videos.update');
    Route::delete('videos/{id}', [VideosController::class, 'videosDestroy'])->name('escort.videos.delete');

    // Thumbnail upload route
    Route::post('videos/add-thumbnail', [VideosController::class, 'addThumbnail'])->name('escort.videos.thumbnail');
    Route::post('videos/thumbnail/remove', [VideosController::class, 'removeThumbnail'])->name('escort.videos.thumbnail.remove');

>>>>>>> 23c30d7 (Escort project)

    Route::get('messages', [EscortDashboardController::class, 'messages'])->name('escort.messages');
    Route::get('verification', [EscortDashboardController::class, 'verification'])->name('escort.verification');
    Route::get('payouts', [EscortDashboardController::class, 'payouts'])->name('escort.payouts');
    Route::get('earnings', [EscortDashboardController::class, 'earnings'])->name('escort.earnings');
    Route::get('subscribers', [EscortDashboardController::class, 'subscribers'])->name('escort.subscribers');
    Route::get('referrals', [EscortDashboardController::class, 'referrals'])->name('escort.referrals');
    Route::get('logout', [EscortLoginController::class, 'logout'])->name('escort.logout');
  });

});


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// START ROUTES FOR THE FAN

Route::group(['prefix' => 'fan'], function () {

  Route::group(['middleware' => 'fan.guest'], function () {
    Route::get('register', [FanLoginController::class, 'fanRegister'])->name('fan.register');
    Route::post('process-register-fan', [FanLoginController::class, 'processRegisterFan'])->name('fan.processRegister');
    Route::post('authenticate', [FanLoginController::class, 'authenticate'])->name('fan.authenticate');

  });

  // Authentiated Middleware
  Route::group(['middleware' => 'fan.auth'], function () {
    Route::get('dashboard', [FanDashboardController::class, 'index'])->name('fan.dashboard');
    Route::get('profile/settings', [FanDashboardController::class, 'settings'])->name('fan.profile.settings');
    Route::get('payment/history', [FanDashboardController::class, 'history'])->name('fan.payment.history');
    Route::get('subscription', [FanDashboardController::class, 'subscription'])->name('fan.subscriptions');
    Route::get('cards', [FanDashboardController::class, 'cards'])->name('fan.cards');
    Route::get('referrals', [FanDashboardController::class, 'referrals'])->name('fan.referrals');
    Route::get('logout', [FanLoginController::class, 'logout'])->name('fan.logout');
  });

});

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// START ROUTES FOR THE ADMIN

Route::group(['prefix' => 'admin'], function () {

  Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

  });

  // Authentiated Middleware
  Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('main-dashboard', [AdminDashboardController::class, 'mainDash'])->name('main.dashboard');
    Route::get('profile-settings', [AdminDashboardController::class, 'profileSettings'])->name('admin.profile.settings');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::post('settings-store', [SettingController::class, 'store'])->name('settings.store');


    // admin Escort Routes
    Route::get('escort-category', [AdminDashboardController::class, 'escortCategory'])->name('admin.escort.category.create');
    Route::post('add-escort-category', [SettingController::class, 'escortCategoryStore'])->name('admin.escort.category.store');
    Route::get('escort-categories-list', [SettingController::class, 'escortCategoryList'])->name('admin.escort.categories.list');
    Route::get('edit-escort-category/{id}', [SettingController::class, 'escortEdit']);
    Route::put('update-escort-category/{id}', [SettingController::class, 'escortUpdate']);
    Route::delete('delete-escort-category/{id}', [SettingController::class, 'escortDestroy']);

<<<<<<< HEAD
    Route::get('escort', [AdminDashboardController::class, 'escortCreate'])->name('admin.escort.create');
    Route::post('escort/store', [AdminDashboardController::class, 'escortStore'])->name('admin.escort.store');
    Route::get('escort-manage', [AdminDashboardController::class, 'escortManage'])->name('admin.escort.manage');
=======
    Route::get('escort-create', [AdminDashboardController::class, 'escortCreate'])->name('admin.escort.create');
    Route::post('escort/store', [SettingController::class, 'escortStore'])->name('admin.escort.store');
    Route::post('escort/store', [SettingController::class, 'escortStore'])->name('escort.store');

    Route::get('escort-manage', [AdminDashboardController::class, 'escortManage'])->name('admin.escort.manage');
    Route::post('escort-manage', [AdminDashboardController::class, 'escortManage'])->name('admin.escort.manage');


    Route::get('escort-list', [SettingController::class, 'escortList'])->name('admin.escort.list');
    Route::get('escorts/{id}/view', [SettingController::class, 'viewEscort'])->name('admin.escorts.view');
    Route::get('escorts/{id}/edit', [SettingController::class, 'editEscort'])->name('admin.escorts.edit');
    Route::put('escorts/{id}', [SettingController::class, 'updateEscort'])->name('admin.escorts.update');
    Route::delete('escorts/{id}', [SettingController::class, 'deleteEscort'])->name('admin.escorts.destroy');







>>>>>>> 23c30d7 (Escort project)


    // admin Fan Routes
    Route::get('fan-category', [AdminDashboardController::class, 'fanCategory'])->name('admin.fan.category.create');
    Route::post('add-fan-category', [SettingController::class, 'fanCategoryStore'])->name('admin.fan.category.store');
    Route::get('fan-categories-list', [SettingController::class, 'fanCategoryList'])->name('admin.fan.categories.list');
    Route::get('edit-fan-category/{id}', [SettingController::class, 'fanEdit']);
    Route::put('update-fan-category/{id}', [SettingController::class, 'fanUpdate']);
    Route::delete('delete-fan-category/{id}', [SettingController::class, 'fanDestroy']);

<<<<<<< HEAD
=======
    // Country Management Routes

    Route::get('countries/create', [DashboardController::class, 'createCountry'])->name('admin.countries.create');
    Route::post('countries', [SettingController::class, 'storeCountry'])->name('admin.countries.store');
    Route::get('countries/manage', [SettingController::class, 'manageCountries'])->name('admin.countries.manage');
    Route::get('countries/{id}/edit', [SettingController::class, 'editCountry'])->name('admin.countries.edit');
    Route::put('countries/{id}', [SettingController::class, 'updateCountry'])->name('admin.countries.update');
    Route::delete('countries/{id}', [SettingController::class, 'deleteCountry'])->name('admin.countries.destroy');
    Route::get('countries/{id}/view', [SettingController::class, 'viewCountry'])
      ->name('admin.countries.view');

>>>>>>> 23c30d7 (Escort project)
    // admin membership features
    Route::get('membership-features', [AdminDashboardController::class, 'membershipFeatures'])->name('admin.membership.features');
    Route::post('membership-features-add', [SettingController::class, 'mfeaturesStore'])->name('admin.mfeatures.store');
    Route::get('membership-features-list', [SettingController::class, 'mfeaturesList'])->name('admin.mfeatures.list');
    Route::get('membership-features-edit/{id}', [SettingController::class, 'mfeaturesEdit'])->name('admin.mfeatures.edit');
    Route::put('membership-features-update/{id}', [SettingController::class, 'mfeaturesUpdate'])->name('admin.mfeatures.update');
    Route::delete('membership-features-delete/{id}', [SettingController::class, 'mfeaturesDestroy'])->name('admin.mfeatures.delete');


    // admin membership plan
    Route::get('membership', [AdminDashboardController::class, 'membershipsetup'])->name('admin.membership');
    Route::post('membership-add', [SettingController::class, 'membershipPlanStore'])->name('admin.membership.store');
    Route::get('membership-list', [SettingController::class, 'membershipPlanList'])->name('admin.membership.list');
    Route::get('membership-edit/{id}', [SettingController::class, 'membershipPlanEdit'])->name('admin.membership.edit');
    Route::put('membership-update/{id}', [SettingController::class, 'membershipPlanUpdate'])->name('admin.membership.update');
    Route::delete('membership-delete/{id}', [SettingController::class, 'membershipPlanDestroy'])->name('admin.membership.delete');

    // admin Payment Gateway
    Route::get('payment-gateway', [AdminDashboardController::class, 'paymentGateway'])->name('admin.paymentGateway');
    Route::post('payment-gateway-add', [SettingController::class, 'paymentGatewayStore'])->name('admin.paymentGateway.store');
    Route::get('payment-gateway-list', [SettingController::class, 'paymentGatewayList'])->name('admin.paymentGateway.list');
    Route::get('payment-gateway-edit/{id}', [SettingController::class, 'paymentGatewayEdit'])->name('admin.paymentGateway.edit');
    Route::put('payment-gateway-update/{id}', [SettingController::class, 'paymentGatewayUpdate'])->name('admin.paymentGateway.update');
    Route::delete('payment-gateway-delete/{id}', [SettingController::class, 'paymentGatewayDestroy'])->name('admin.paymentGateway.delete');
    Route::post('payment-gateway-toggle', [SettingController::class, 'paymentGatewayToggle'])->name('admin.paymentGateway.toggle');
    Route::post('payment-gateway-mode', [SettingController::class, 'paymentGatewayMode'])->name('admin.paymentGateway.mode');

<<<<<<< HEAD
    // admin  Escort Management Routes
    Route::get('escorts', [SettingController::class, 'escortManage'])->name('admin.escorts.index');
    // Route::post('escorts', [SettingController::class, 'storeEscort'])->name('admin.escorts.store');
    Route::delete('escorts/{id}', [SettingController::class, 'deleteEscort'])->name('admin.escorts.destroy');
    Route::get('escorts/{id}/view', [SettingController::class, 'viewEscort'])->name('admin.escorts.view');
    Route::get('escorts/{id}/edit', [SettingController::class, 'editEscort'])->name('admin.escorts.edit');
    Route::put('escorts/{id}', [SettingController::class, 'updateEscort'])->name('admin.escorts.update');

    // Escort Manage View Route
    Route::get('escort-manage', [SettingController::class, 'escortManageView'])->name('admin.escort.manage');


    // Update to use /admin/escort instead of /add-escort
    Route::get('escort', [SettingController::class, 'showAddEscort'])->name('admin.escort');
    Route::post('escort/store', [SettingController::class, 'storeEscort'])->name('admin.escort.store');
    ;
    Route::get('escort/create', [SettingController::class, 'showAddEscort'])->name('admin.escort.create');
=======
>>>>>>> 23c30d7 (Escort project)

    // Fan Management Routes
    Route::get('fans', [SettingController::class, 'fanManage'])->name('admin.fans.index');
    Route::post('fans', [SettingController::class, 'storeFan'])->name('admin.fans.store');
    Route::delete('fans/{id}', [SettingController::class, 'deleteFan'])->name('admin.fans.destroy');
    Route::get('fans/{id}/view', [SettingController::class, 'viewFan'])->name('admin.fans.view');
    Route::get('fans/{id}/edit', [SettingController::class, 'editFan'])->name('admin.fans.edit');
    Route::put('fans/{id}', [SettingController::class, 'updateFan'])->name('admin.fans.update');

    // Fan Manage View Route
    Route::get('fan-manage', [SettingController::class, 'fanManageView'])->name('admin.fan.manage');

<<<<<<< HEAD
    // ADD ESCORT ADMOIN PANEL 
    Route::post('admin/escort', [SettingController::class, 'storeEscort'])->name('admin.escort.store');
    Route::get('admin/escort-manage', [SettingController::class, 'escortManage'])->name('admin.escort-manage');

=======
>>>>>>> 23c30d7 (Escort project)
    // ADD fAQs ADMIN PANEL 
    Route::get('faqs', [FaqController::class, 'faqsManage'])->name('admin.faqs');
    Route::post('faqs/', [FaqController::class, 'faqsStore'])->name('admin.faqs.store');
    Route::get('faqs-list', [FaqController::class, 'faqsList'])->name('admin.faqs.list');
    Route::get('faqs/{id}/view', [FaqController::class, 'viewFaqs'])->name('admin.faqs.view');
    Route::get('faqs/{id}/edit', [FaqController::class, 'faqsEdit'])->name('admin.faqs.edit');
    Route::put('faqs/{id}', [FaqController::class, 'faqsUpdate'])->name('admin.faqs.update');
    Route::delete('faqs/{id}', [FaqController::class, 'faqsDestroy'])->name('admin.faqs.delete');


    // Admin Support Ticket Routes
    Route::get('tickets', [SupportController::class, 'ticketsManage'])->name('admin.tickets');
    Route::post('tickets/', [SupportController::class, 'ticketsStore'])->name('admin.tickets.store');
    Route::get('tickets-list', [SupportController::class, 'ticketsList'])->name('admin.tickets.list');
    Route::get('tickets/{id}/view', [SupportController::class, 'viewTicket'])->name('admin.tickets.view');
    Route::get('tickets/{id}/edit', [SupportController::class, 'ticketsEdit'])->name('admin.tickets.edit');
    Route::put('tickets/{id}', [SupportController::class, 'ticketsUpdate'])->name('admin.tickets.update');
    Route::delete('tickets/{id}', [SupportController::class, 'ticketsDestroy'])->name('admin.tickets.delete');

    Route::post('/admin/tickets/{ticketId}/reply', [SupportController::class, 'submitReply'])->name('admin.tickets.reply');

    // Admin Ads Routes
    Route::get('ads', [AdsController::class, 'adsManage'])->name('admin.ads');
    Route::post('ads/', [AdsController::class, 'adsStore'])->name('admin.ads.store');
    Route::get('ads-list', [AdsController::class, 'adsList'])->name('admin.ads.list');
    Route::get('ads/{id}/view', [AdsController::class, 'adsView'])->name('admin.ads.view');
    Route::get('ads/{id}/edit', [AdsController::class, 'adsEdit'])->name('admin.ads.edit');
    Route::put('ads/{id}', [AdsController::class, 'adsUpdate'])->name('admin.ads.update');
    Route::delete('ads/{id}', [AdsController::class, 'adsDestroy'])->name('admin.ads.delete');


    // // admin Payment Gateway BtcPay
    // Route::get('payment-gateway-btcpay', [AdminDashboardController::class, 'paymentGatewayBtcpay'])->name('admin.paymentGatewayBtcpay');
    // Route::post('payment-gateway-btcpay-add', [SettingController::class, 'paymentGatewayBtcpayStore'])->name('admin.paymentGatewayBtcpay.store');
    // Route::get('payment-gateway-btcpay-list', [SettingController::class, 'paymentGatewayBtcpayList'])->name('admin.paymentGatewayBtcpay.list');
    // Route::get('payment-gateway-btcpay-edit/{id}', [SettingController::class, 'paymentGatewayBtcpayEdit'])->name('admin.paymentGatewayBtcpay.edit');
    // Route::put('payment-gateway-btcpay-update/{id}', [SettingController::class, 'paymentGatewayBtcpayUpdate'])->name('admin.paymentGatewayBtcpay.update');
    // Route::delete('payment-gateway-btcpay-delete/{id}', [SettingController::class, 'paymentGatewayBtcpayDestroy'])->name('admin.paymentGatewayBtcpay.delete');



    Route::get('fan', [AdminDashboardController::class, 'fanCreate'])->name('admin.fan.create');
    Route::post('fan/store', [AdminDashboardController::class, 'fanStore'])->name('admin.fan.store');
    Route::get('fan-manage', [AdminDashboardController::class, 'fanManage'])->name('admin.fan.manage');


    Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
  });

});

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!





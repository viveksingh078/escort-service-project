<?php

use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\fan\DashboardController as FanDashboardController;
use App\Http\Controllers\fan\LoginController as FanLoginController;
use App\Http\Controllers\escort\DashboardController as EscortDashboardController;
use App\Http\Controllers\escort\LoginController as EscortLoginController;
use App\Http\Controllers\LoginController;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/load-more-escorts', [HomeController::class, 'loadMoreEscorts'])->name('load-more-escorts');


// Check authentication status
Route::get('/check-auth', function () {
  return response()->json(['isAuthenticated' => Auth::guard('fan')->check()]);
})->name('check-auth');

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

    Route::get('cities', [AdminDashboardController::class, 'cities'])->name('admin.cities');
    Route::post('add-cities', [SettingController::class, 'citiesStore'])->name('admin.cities.store');
    Route::get('cities-list', [SettingController::class, 'citiesList'])->name('admin.cities.list');
    Route::get('edit-cities/{id}', [SettingController::class, 'citiesEdit']);
    Route::put('update-cities/{id}', [SettingController::class, 'citiesUpdate']);
    Route::delete('delete-cities/{id}', [SettingController::class, 'citiesDestroy']);


    Route::get('states', [AdminDashboardController::class, 'states'])->name('admin.states');
    Route::post('add-states', [SettingController::class, 'statesStore'])->name('admin.states.store');
    Route::get('states-list', [SettingController::class, 'statesList'])->name('admin.states.list');
    Route::get('edit-states/{id}', [SettingController::class, 'statesEdit']);
    Route::put('update-states/{id}', [SettingController::class, 'statesUpdate']);
    Route::delete('delete-states/{id}', [SettingController::class, 'statesDestroy']);

    Route::get('countries', [AdminDashboardController::class, 'countries'])->name('admin.countries');


    // admin Escort Routes
    Route::get('escort-category', [AdminDashboardController::class, 'escortCategory'])->name('admin.escort.category.create');
    Route::post('add-escort-category', [SettingController::class, 'escortCategoryStore'])->name('admin.escort.category.store');
    Route::get('escort-categories-list', [SettingController::class, 'escortCategoryList'])->name('admin.escort.categories.list');
    Route::get('edit-escort-category/{id}', [SettingController::class, 'escortEdit']);
    Route::put('update-escort-category/{id}', [SettingController::class, 'escortUpdate']);
    Route::delete('delete-escort-category/{id}', [SettingController::class, 'escortDestroy']);

    Route::get('escort', [AdminDashboardController::class, 'escortCreate'])->name('admin.escort.create');
    Route::post('escort/store', [AdminDashboardController::class, 'escortStore'])->name('admin.escort.store');
    Route::get('escort-manage', [AdminDashboardController::class, 'escortManage'])->name('admin.escort.manage');


    // admin Fan Routes
    Route::get('fan-category', [AdminDashboardController::class, 'fanCategory'])->name('admin.fan.category.create');
    Route::post('add-fan-category', [SettingController::class, 'fanCategoryStore'])->name('admin.fan.category.store');
    Route::get('fan-categories-list', [SettingController::class, 'fanCategoryList'])->name('admin.fan.categories.list');
    Route::get('edit-fan-category/{id}', [SettingController::class, 'fanEdit']);
    Route::put('update-fan-category/{id}', [SettingController::class, 'fanUpdate']);
    Route::delete('delete-fan-category/{id}', [SettingController::class, 'fanDestroy']);

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

    // admin  Escort Management Routes
    Route::get('/escorts', [SettingController::class, 'escortManage'])->name('admin.escorts.index');
    // Route::post('/escorts', [SettingController::class, 'storeEscort'])->name('admin.escorts.store');
    Route::delete('/escorts/{id}', [SettingController::class, 'deleteEscort'])->name('admin.escorts.destroy');
    Route::get('/escorts/{id}/view', [SettingController::class, 'viewEscort'])->name('admin.escorts.view');
    Route::get('/escorts/{id}/edit', [SettingController::class, 'editEscort'])->name('admin.escorts.edit');
    Route::put('/escorts/{id}', [SettingController::class, 'updateEscort'])->name('admin.escorts.update');

    // Escort Manage View Route
    Route::get('/escort-manage', [SettingController::class, 'escortManageView'])->name('admin.escort.manage');


    // Update to use /admin/escort instead of /add-escort
    Route::get('/escort', [SettingController::class, 'showAddEscort'])->name('admin.escort');
    Route::post('/escort/store', [SettingController::class, 'storeEscort'])->name('admin.escort.store');
    ;
    Route::get('/escort/create', [SettingController::class, 'showAddEscort'])->name('admin.escort.create');

    // Fan Management Routes
    Route::get('/fans', [SettingController::class, 'fanManage'])->name('admin.fans.index');
    Route::post('/fans', [SettingController::class, 'storeFan'])->name('admin.fans.store');
    Route::delete('/fans/{id}', [SettingController::class, 'deleteFan'])->name('admin.fans.destroy');
    Route::get('/fans/{id}/view', [SettingController::class, 'viewFan'])->name('admin.fans.view');
    Route::get('/fans/{id}/edit', [SettingController::class, 'editFan'])->name('admin.fans.edit');
    Route::put('/fans/{id}', [SettingController::class, 'updateFan'])->name('admin.fans.update');

    // Fan Manage View Route
    Route::get('/fan-manage', [SettingController::class, 'fanManageView'])->name('admin.fan.manage');

    // ADD ESCORT ADMOIN PANEL 
    Route::post('/admin/escort', [SettingController::class, 'storeEscort'])->name('admin.escort.store');
    Route::get('/admin/escort-manage', [SettingController::class, 'escortManage'])->name('admin.escort-manage');



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





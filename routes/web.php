<?php

use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\fan\DashboardController as FanDashboardController;
use App\Http\Controllers\fan\LoginController as FanLoginController;
use App\Http\Controllers\escort\DashboardController as EscortDashboardController;
use App\Http\Controllers\escort\LoginController as EscortLoginController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

// Temporary payment success route
Route::get('/payment-success', function () {
  return 'Payment Successful!'; // Replace with proper view later
})->name('payment.success');

Route::group(['prefix' => '/'], function () {
  Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');

  Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::get('register', [LoginController::class, 'register'])->name('register');
    Route::post('process-register', [LoginController::class, 'processRegister'])->name('processRegister');
    Route::post('authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::get('verify-user/{email}', [LoginController::class, 'VerifyUser']);
    Route::post('/resend-verification/{email}', [LoginController::class, 'resendVerification'])->name('resend.verification');
  });

  Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
  });
});

Route::group(['prefix' => 'escort'], function () {
  Route::group(['middleware' => 'escort.guest'], function () {
    Route::get('register', [EscortLoginController::class, 'escortRegister'])->name('escort.register');
    Route::post('register/step-1', [EscortLoginController::class, 'processRegisterStep1'])->name('escort.register.step1');
    Route::get('register/step-2/{userid}', [EscortLoginController::class, 'showRegisterStep2'])->name('escort.register.step2');
    Route::post('register/step-2/{userid}', [EscortLoginController::class, 'processRegisterStep2'])->name('escort.register.step2.post');
    Route::post('authenticate', [EscortLoginController::class, 'authenticate'])->name('escort.authenticate');
  });

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

Route::group(['prefix' => 'fan'], function () {
  Route::group(['middleware' => 'fan.guest'], function () {
    Route::get('register', [FanLoginController::class, 'fanRegister'])->name('fan.register');
    Route::post('process-register-fan', [FanLoginController::class, 'processRegisterFan'])->name('fan.processRegister');
    Route::post('authenticate', [FanLoginController::class, 'authenticate'])->name('fan.authenticate');
  });

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

Route::group(['prefix' => 'admin'], function () {
  Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
  });

  Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/subscription', [AdminDashboardController::class, 'planIndex'])->name('admin.subscription.index');
    Route::get('/subscription/create', [AdminDashboardController::class, 'addSubscriptionPlan'])->name('admin.subscription.create');
    Route::get('/subscription/list', [AdminDashboardController::class, 'listPlans'])->name('admin.subscription.list');
    Route::post('/subscription/store', [AdminDashboardController::class, 'storePlan'])->name('admin.subscription.store');
    Route::get('/subscription/{id}/edit-plan', [AdminDashboardController::class, 'editPlan'])->name('admin.subscription.edit');
    Route::post('/admin/subscription/check-duplicate', [AdminDashboardController::class, 'checkDuplicatePlan'])->name('admin.subscription.check-duplicate');
    Route::put('/subscription/update/{id}', [AdminDashboardController::class, 'updatePlan'])->name('admin.subscription.update');
    Route::delete('/subscription/delete/{id}', [AdminDashboardController::class, 'deletePlan'])->name('subscription.destroy');

    Route::get('/features', [AdminDashboardController::class, 'features'])->name('admin.features');
    Route::get('/features/list', [AdminDashboardController::class, 'getFeatures'])->name('admin.features.list');
    Route::post('/features', [AdminDashboardController::class, 'storeFeature'])->name('admin.features.store');
    Route::put('/features/{id}', [AdminDashboardController::class, 'updateFeature'])->name('admin.features.update');
    Route::post('/features/{id}/toggle', [AdminDashboardController::class, 'toggleFeature'])->name('admin.features.toggle');
    Route::get('/features/{id}/edit', [AdminDashboardController::class, 'editFeature'])->name('admin.features.edit');
    Route::delete('/features/{id}', [AdminDashboardController::class, 'deleteFeature'])->name('admin.features.delete');
    Route::get('/add-subscription-plan', [AdminDashboardController::class, 'addSubscriptionPlan'])->name('admin.add-plan');

    Route::get('payment-gateway', [AdminDashboardController::class, 'paymentGateway'])->name('admin.paymentgateway');
    Route::get('payment-gateway/list', [SettingController::class, 'paymentGatewaysList'])->name('admin.paymentgateway.list');
    Route::get('admin/pay-btcpay', [PaymentGatewayController::class, 'btcPayPage'])->name('admin.payment.btcpay.page');
    Route::post('admin/pay-btcpay/create', [PaymentGatewayController::class, 'createInvoice'])->name('admin.payment.btcpay.create');
    Route::get('payment/cancel', [PaymentGatewayController::class, 'paymentCancel'])->name('payment.cancel');
    Route::get('payment-gateway/edit/razorpay', [SettingController::class, 'editRazorpay']);
    Route::post('payment-gateway/update/razorpay', [SettingController::class, 'updateRazorpay']);
    Route::delete('payment-gateway/delete/razorpay', [SettingController::class, 'deleteRazorpay']);
    Route::get('payment-gateway/edit/stripe', [SettingController::class, 'editStripe']);
    Route::post('payment-gateway/update/stripe', [SettingController::class, 'updateStripe']);
    Route::delete('payment-gateway/delete/stripe', [SettingController::class, 'deleteStripe']);
    Route::get('payment-gateway/edit/paypal', [SettingController::class, 'editPaypal']);
    Route::post('payment-gateway/update/paypal', [SettingController::class, 'updatePaypal']);
    Route::delete('payment-gateway/delete/paypal', [SettingController::class, 'deletePaypal']);
    Route::get('payment-gateway/edit/btc-pay', [SettingController::class, 'editBtcPay']);
    Route::post('payment-gateway/update/btc-pay', [SettingController::class, 'updateBtcPay']);
    Route::delete('payment-gateway/delete/btc-pay', [SettingController::class, 'deleteBtcPay']);

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
    Route::get('escort-category', [AdminDashboardController::class, 'escortCategory'])->name('admin.escort.category.create');
    Route::post('add-escort-category', [SettingController::class, 'escortCategoryStore'])->name('admin.escort.category.store');
    Route::get('escort-categories-list', [SettingController::class, 'escortCategoryList'])->name('admin.escort.categories.list');
    Route::get('edit-escort-category/{id}', [SettingController::class, 'escortEdit']);
    Route::put('update-escort-category/{id}', [SettingController::class, 'escortUpdate']);
    Route::delete('delete-escort-category/{id}', [SettingController::class, 'escortDestroy']);
    Route::get('escort', [AdminDashboardController::class, 'escortCreate'])->name('admin.escort.create');
    Route::post('escort/store', [AdminDashboardController::class, 'escortStore'])->name('admin.escort.store');
    Route::get('escort-manage', [AdminDashboardController::class, 'escortManage'])->name('admin.escort.manage');
    Route::get('fan-category', [AdminDashboardController::class, 'fanCategory'])->name('admin.fan.category.create');
    Route::post('add-fan-category', [SettingController::class, 'fanCategoryStore'])->name('admin.fan.category.store');
    Route::get('fan-categories-list', [SettingController::class, 'fanCategoryList'])->name('admin.fan.categories.list');
    Route::get('edit-fan-category/{id}', [SettingController::class, 'fanEdit']);
    Route::put('update-fan-category/{id}', [SettingController::class, 'fanUpdate']);
    Route::delete('delete-fan-category/{id}', [SettingController::class, 'fanDestroy']);
    Route::get('fan', [AdminDashboardController::class, 'fanCreate'])->name('admin.fan.create');
    Route::post('fan/store', [AdminDashboardController::class, 'fanStore'])->name('admin.fan.store');
    Route::get('fan-manage', [AdminDashboardController::class, 'fanManage'])->name('admin.fan.manage');
    Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
  });
});
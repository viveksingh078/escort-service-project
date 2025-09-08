<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Features;

class DashboardController extends Controller
{
  public function index()
  {
    return view('admin.layout');
  }

  public function mainDash()
  {
    return view('admin.dashboard');
  }

  public function profileSettings()
  {
    return view('admin.profile-settings');
  }

  public function escortCategory()
  {
    return view('admin.escort-category');
  }

  public function escortCreate()
  {
    return view('admin.escort');
  }

  public function escortManageView()
  {
    \Log::info('Rendering admin.escort-manage view');
    return view('admin.escort-manage');
  }

  public function fanCategory()
  {
    return view('admin.fan-category');
  }

  public function fanCreate()
  {
    return view('admin.fan');
  }

  public function fanManage()
  {
    return view('admin.fan-manage');
  }

  public function cities()
  {
    return view('admin.cities');
  }

  public function states()
  {
    return view('admin.states');
  }

  public function countries()
  {
    return view('admin.countries');
  }

  public function settings()
  {
    return view('admin.settings');
  }

  public function membershipFeatures()
  {
    return view('admin.membership-features');
  }

  public function membershipsetup()
  {
    $features = Features::all();
    return view('admin.membership', compact('features'));
  }

  public function paymentGateway()
  {
    return view('admin.payment-gateway');
  }

  public function paymentGatewayBtcpay()
  {
    return view('admin.payment-gateway-btcpay');
  }


}
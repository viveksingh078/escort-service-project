<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Features;
<<<<<<< HEAD
=======
use App\Models\EscortCategory;
>>>>>>> 23c30d7 (Escort project)

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
<<<<<<< HEAD
    return view('admin.escort');
  }

  public function escortManageView()
  {
    \Log::info('Rendering admin.escort-manage view');
    return view('admin.escort-manage');
=======
    $categories = EscortCategory::all(); // Fetch all categories
    return view('admin.escort', compact('categories')); // Pass to view
  }

  public function escortManage()
  {
    $categories = EscortCategory::all();
    return view('admin.escort-manage', compact('categories'));
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD
=======
  public function createCountry()
  {
    return view('admin.create-country');
  }
>>>>>>> 23c30d7 (Escort project)

}
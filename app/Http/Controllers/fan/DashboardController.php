<?php

namespace App\Http\Controllers\fan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
      return view('fan.dashboard');
    }

    public function settings(){
      return view('fan.profile-settings');
    }

    public function history(){
      return view('fan.payment-history');
    }

    public function subscription(){
      return view('fan.subscription');
    }

    public function cards(){
      return view('fan/cards');
    }

    public function referrals(){
      return view('fan.referrals');
    }
}

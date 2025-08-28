<?php

namespace App\Http\Controllers\escort;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
      return view('escort.dashboard');
    }

    public function profileSettings(){
      return view('escort.profile-settings');
    }

    public function messages(){
      return view('escort.messages');
    }

    public function accountSettings(){
      return view('escort.account-settings');
    }

    public function verification(){
      return view('escort.verification');
    }

    public function payouts(){
      return view('escort/payouts');
    }

    public function earnings(){
      return view('escort/earnings');
    }

    public function subscribers(){
      return view('escort/subscribers');
    }

    public function referrals(){
      return view('escort.referrals');
    }
}

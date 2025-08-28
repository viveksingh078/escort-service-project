<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // this method will show dashnboard page for user  
    public function index(){
      return view('dashboard');
    }
}

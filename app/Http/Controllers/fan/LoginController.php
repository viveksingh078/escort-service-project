<?php

namespace App\Http\Controllers\fan;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function fanRegister()
    {
        return view('fan-register');
    }

    public function processRegisterFan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|string|min:6|max:20|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'terms' => 'accepted',
            'over18' => 'accepted',
        ], [
            'name.required' => 'Display Name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'Username already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'Email already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 6 characters.',
            'terms.accepted' => 'You must accept the Terms and Conditions.',
            'over18.accepted' => 'You must confirm that you are over 18.',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'fan';
        $user->accepted_terms = $request->has('terms');
        $user->is_over_18 = $request->has('over18');
        $user->marketing_opt_in = $request->has('marketing');
        $user->save();

        return redirect()->route('login')->with('success', 'You have successfully registered.');
    }

    public function logout()
    {
        Auth::guard('fan')->logout();
        return redirect()->route('home');
    }
}

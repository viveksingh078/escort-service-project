<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    //
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        //  Check if user exists and is an admin
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->role === 'admin') {
            return back()->withErrors(['email' => 'Email id does not exist .']);
        }

        //  Proceed to send reset link for non-admin users
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

}

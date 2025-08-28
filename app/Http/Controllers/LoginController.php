<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Mail\VerifyUser;
use App\Helpers\EmailHelper;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $loginInput = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if (!$user || $user->active == 0) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Account does not exist.'], 401);
            }
            return redirect()->back()->with('error', 'Account does not exist.');
        }

        if ($user->role === 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Access denied.'], 403);
            }
            return redirect()->back()->with('error', 'Access denied.');
        }

        $allowedRoles = ['user', 'fan', 'escort', 'client', 'moderator', 'support', 'billing'];
        if (!in_array($user->role, $allowedRoles)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized role.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized role.');
        }

        $guard = $user->role;
        $credentials = [
            filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $loginInput,
            'password' => $password,
        ];

        if (Auth::guard($guard)->attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route($guard . '.dashboard');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'error' => 'Either email or password is incorrect.'], 401);
        }
        return redirect()->back()->with('error', 'Either email or password is incorrect.');
    }

    public function register()
    {
        return view('register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';
            $user->save();

            return redirect()->route('login')->with('success', 'You have successfully registered.');
        } else {
            return redirect()->route('register')->withInput()->withErrors($validator);
        }
    }

    public function VerifyUser($email)
    {
        try {
            $userEmail = Crypt::decryptString($email);
            $user = User::where('email', $userEmail)->first();

            if ($user) {
                $user->email_verified_at = Carbon::now();
                $user->email_verified = 1;

                if ($user->save()) {
                    return redirect()->route('login')->with('success', 'Email verified successfully.');
                } else {
                    return redirect()->route('login')->with('error', 'Failed to verify email.');
                }
            } else {
                return redirect()->route('login')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Invalid or corrupted email token.');
        }
    }

    public function resendVerification($email)
    {
        try {
            $userEmail = Crypt::decryptString($email);
            $result = EmailHelper::sendVerificationEmail($userEmail);

            return redirect()->back()->with(
                $result['status'] ? 'success' : 'error',
                $result['message']
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid email address or token.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
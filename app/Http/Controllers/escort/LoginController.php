<?php

namespace App\Http\Controllers\escort;

use App\Models\User;
use App\Models\Usermeta;
use App\Models\EscortCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Helpers\EmailHelper;

class LoginController extends Controller
{

    public function escortRegister()
    {
        return view('escort-register');
    }

    public function processRegisterStep1(Request $request)
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
        $user->role = 'escort';
        $user->accepted_terms = $request->has('terms');
        $user->is_over_18 = $request->has('over18');
        $user->save();

        // Set session flag to allow access to step 2
        session(['escort_registration_user_id' => $user->id]);

        $result = EmailHelper::sendVerificationEmail($request->email);

        if ($result['status']) {
            return redirect()->route('escort.register.step2', ['userid' => $user->id])
                ->with('success', 'Step 1 completed. Continue to Step 2.');
        } else {
            return redirect()->back()->with('error', $result['message']);
        }


    }

    public function showRegisterStep2($userid)
    {
        // Check if session has access flag
        if (session('escort_registration_user_id') != $userid) {
            abort(403, 'Unauthorized access to step 2.');
        }

        $userid = User::findOrFail($userid);
        $categories = EscortCategory::all();

        return view('escort-register-step2', compact('userid', 'categories'));
    }


    public function processRegisterStep2(Request $request, $userid)
    {
        $user = User::findOrFail($userid);
        $username = $user->username;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10',
            'phone_number' => 'required|string|min:6|max:20',
            'dob' => 'required|date|before:today',
            'display_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'public_country_code' => 'required|string|max:10',
            // 'category_id' => 'required|exists:escort_categories,id',
            'introduction' => 'required|string|max:1000',
            'subscription_price' => 'required|numeric|min:0|max:100',

            // ✅ Profile picture new rule
            'profile_picture' => '  required|file|mimes:jpeg,png,jpg,pdf|max:10240',

            // 'photo_id_doc' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
            // 'photo_id' => 'required|file|mimes:jpeg,png,jpg|max:10240',
            'terms' => 'accepted',
            'digital_services_only' => 'accepted',
            'promote_profile' => 'accepted',
        ], [
            // 'photo_id_doc.required' => 'Please upload a photo of your ID document.',
            // 'photo_id.required' => 'Please upload a photo of you holding your ID.',
            'terms.accepted' => 'You must accept the Terms and Conditions.',
            'digital_services_only.accepted' => 'You must agree to the digital services policy.',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        // Upload files to escort/{username}/
        $storagePath = 'escort/' . $username;
        $photoIdDocPath = null;
        $photoIdPath = null;
        $profilePicturePath = null; // ✅ new

        // if ($request->hasFile('photo_id_doc')) {
        //     $photoIdDocPath = $request->file('photo_id_doc')->store($storagePath, 'public');
        // }

        // if ($request->hasFile('photo_id')) {
        //     $photoIdPath = $request->file('photo_id')->store($storagePath, 'public');
        // }

        // ✅ Profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store($storagePath, 'public');
        }

        // Save as user meta
        update_user_meta($userid, 'first_name', $request->first_name);
        update_user_meta($userid, 'last_name', $request->last_name);
        update_user_meta($userid, 'country_code', $request->country_code);
        update_user_meta($userid, 'phone_number', $request->phone_number);
        update_user_meta($userid, 'dob', $request->dob);
        update_user_meta($userid, 'display_name', $request->display_name);
        update_user_meta($userid, 'age', $request->age);
        update_user_meta($userid, 'public_country_code', $request->public_country_code);
        update_user_meta($userid, 'introduction', $request->introduction);
        update_user_meta($userid, 'subscription_price', $request->subscription_price);
        update_user_meta($userid, 'category_id', $request->category_id);
        update_user_meta($userid, 'photo_id_doc', $photoIdDocPath);
        update_user_meta($userid, 'photo_id', $photoIdPath);

        // ✅ Save profile picture in usermeta
        update_user_meta($userid, 'profile_picture', $profilePicturePath);

        update_user_meta($userid, 'terms', 1);
        update_user_meta($userid, 'digital_services_only', 1);
        update_user_meta($userid, 'promote_profile', $request->has('promote_profile') ? 1 : 0);

        // Unset the registration session flag after successful registration
        session()->forget('escort_registration_user_id');

        return redirect()->route('login')->with('success', 'Your information has been successfully submitted.');
    }





    public function logout()
    {
        Auth::guard('escort')->logout();
        return redirect()->route('login');
    }
}

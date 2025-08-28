<?php

namespace App\Http\Controllers\admin;

use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\EscortCategory;
use App\Models\FanCategory;
use App\Models\Cities;
use App\Models\States;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller
{


    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        WEB SETTINGS STORE IN TO THE DATABASE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */
    public function store(Request $request)
    {
        // 1. Validate Inputs
        $validator = Validator::make($request->all(), [
            'website_url' => 'required|url',
            'website_name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'email' => 'required|email',
            'secondary_email' => 'nullable|email',
            'phone_number' => 'required|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'secondary_address' => 'nullable|string|max:500',
            'map_embed' => 'nullable|string',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'white_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $ext = $favicon->getClientOriginalExtension();
            $filename = 'favicon.' . $ext;

            // Delete old favicon
            $oldFavicon = get_option('favicon');
            if ($oldFavicon) {
                $oldFaviconPath = public_path(str_replace('storage/', 'storage/', $oldFavicon)); // full public path
                Storage::disk('public')->delete(str_replace('storage/', '', $oldFavicon));
                if (File::exists($oldFaviconPath)) {
                    File::delete($oldFaviconPath);
                }
            }

            // Store new favicon
            $favicon->storeAs('settings', $filename, 'public');
            update_option('favicon', 'storage/settings/' . $filename);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $ext = $logo->getClientOriginalExtension();
            $filename = 'logo.' . $ext;

            // Delete old logo
            $oldLogo = get_option('logo');
            if ($oldLogo) {
                $oldLogoPath = public_path(str_replace('storage/', 'storage/', $oldLogo));
                Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
                if (File::exists($oldLogoPath)) {
                    File::delete($oldLogoPath);
                }
            }

            // Store new logo
            $logo->storeAs('settings', $filename, 'public');
            update_option('logo', 'storage/settings/' . $filename);
        }

        if ($request->hasFile('white_logo')) {
            $whiteLogo = $request->file('white_logo');
            $ext = $whiteLogo->getClientOriginalExtension();
            $filename = 'white-logo.' . $ext;

            // Delete old white logo
            $oldWhiteLogo = get_option('white_logo');
            if ($oldWhiteLogo) {
                $oldWhiteLogoPath = public_path(str_replace('storage/', 'storage/', $oldWhiteLogo));
                Storage::disk('public')->delete(str_replace('storage/', '', $oldWhiteLogo));
                if (File::exists($oldWhiteLogoPath)) {
                    File::delete($oldWhiteLogoPath);
                }
            }

            // Store new white logo
            $whiteLogo->storeAs('settings', $filename, 'public');
            update_option('white_logo', 'storage/settings/' . $filename);
        }




        // 4. Store all text inputs
        $fields = [
            'website_url',
            'website_name',
            'meta_title',
            'meta_description',
            'email',
            'secondary_email',
            'phone_number',
            'secondary_phone',
            'address',
            'secondary_address',
            'map_embed',
            'facebook',
            'twitter',
            'instagram',
            'linkedin'
        ];

        foreach ($fields as $field) {
            update_option($field, $request->input($field));
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        ESCORT CATEGORIES DATA MANAGE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function escortCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:escort_categories,name',
            'category_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        EscortCategory::create([
            'name' => $request->category_name,
            'slug' => $request->category_slug,
        ]);

        return response()->json(['success' => true, 'message' => 'Escort Category added successfully.']);
    }

    public function escortCategoryList(Request $request)
    {
        if ($request->ajax()) {
            $data = EscortCategory::select(['id', 'name', 'slug', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button> 
                        <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function escortEdit($id)
    {
        $category = EscortCategory::findOrFail($id);
        return response()->json($category);
    }

    public function escortUpdate(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
            'category_slug' => 'required|unique:escort_categories,slug,' . $id
        ]);

        $category = EscortCategory::findOrFail($id);
        $category->update([
            'name' => $request->category_name,
            'slug' => $request->category_slug
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
    }

    public function escortDestroy($id)
    {
        $category = EscortCategory::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    public function homepage()
    {
        $categories = DB::table('escort_categories as ec')
            ->leftJoin('usermeta as um', function ($join) {
                $join->on('um.meta_value', '=', 'ec.id')
                    ->where('um.meta_key', 'category_id');
            })
            ->select(
                'ec.id',
                'ec.name',
                'ec.slug',
                DB::raw('COUNT(um.user_id) as escorts_count')
            )
            ->groupBy('ec.id', 'ec.name', 'ec.slug')
            ->orderBy('ec.name')
            ->get(); // 👈 ye stdClass objects ka collection aayega, jisme escorts_count ALREADY hoga

        return view('homepage', compact('categories'));
    }


    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        FAN CATEGORIES DATA MANAGE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function fanCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:fan_categories,name',
            'category_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        FanCategory::create([
            'name' => $request->category_name,
            'slug' => $request->category_slug,
        ]);

        return response()->json(['success' => true, 'message' => 'Fan Category added successfully.']);
    }

    public function fanCategoryList(Request $request)
    {
        if ($request->ajax()) {
            $data = FanCategory::select(['id', 'name', 'slug', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button> 
                        <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function fanEdit($id)
    {
        $category = FanCategory::findOrFail($id);
        return response()->json($category);
    }

    public function fanUpdate(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
            'category_slug' => 'required|unique:fan_categories,slug,' . $id
        ]);

        $category = FanCategory::findOrFail($id);
        $category->update([
            'name' => $request->category_name,
            'slug' => $request->category_slug
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
    }

    public function fanDestroy($id)
    {
        $category = FanCategory::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        CITIES DATA MANAGE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */


    public function citiesStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cities_name' => 'required|string|max:255|unique:cities,name',
            'state_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Cities::create([
            'name' => $request->cities_name,
            'state_id' => $request->state_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Cities added successfully.']);
    }

    public function citiesList(Request $request)
    {
        if ($request->ajax()) {
            $data = Cities::select(['id', 'name', 'state_id', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button> 
                        <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['state_id', 'action'])
                ->make(true);
        }
    }

    public function citiesEdit($id)
    {
        $city = Cities::findOrFail($id);
        return response()->json($city);
    }

    public function citiesUpdate(Request $request, $id)
    {
        $request->validate([
            'cities_name' => 'required',
            'state_id' => 'required|integer|unique:cities,state_id,' . $id
        ]);

        $cities = Cities::findOrFail($id);
        $cities->update([
            'name' => $request->cities_name,
            'state_id' => $request->state_id
        ]);

        return response()->json(['success' => true, 'message' => 'Cities updated successfully.']);
    }

    public function citiesDestroy($id)
    {
        $cities = Cities::findOrFail($id);
        $cities->delete();
        return response()->json(['success' => true, 'message' => 'Cities deleted successfully.']);
    }


    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        STATE DATA MANAGE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */


    public function statesStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name' => 'required|string|max:255|unique:states,name',
            'country_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        States::create([
            'name' => $request->state_name,
            'country_id' => $request->country_id,
        ]);

        return response()->json(['success' => true, 'message' => 'State added successfully.']);
    }

    public function statesList(Request $request)
    {
        if ($request->ajax()) {
            $data = States::select(['id', 'name', 'country_id', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button> 
                        <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['country_id', 'action'])
                ->make(true);
        }
    }

    public function statesEdit($id)
    {
        $state = States::findOrFail($id);
        return response()->json($state);
    }

    public function statesUpdate(Request $request, $id)
    {
        $request->validate([
            'state_name' => 'required',
            'country_id' => 'required|integer|unique:states,country_id,' . $id
        ]);

        $states = States::findOrFail($id);
        $states->update([
            'name' => $request->state_name,
            'country_id' => $request->country_id
        ]);

        return response()->json(['success' => true, 'message' => 'States updated successfully.']);
    }

    public function statesDestroy($id)
    {
        $states = States::findOrFail($id);
        $states->delete();
        return response()->json(['success' => true, 'message' => 'States deleted successfully.']);
    }

    /*
    ///////////////////////////////////////////////////////////////////////////////////////////////
    -----------------------------------------------------------------------------------------------
        PAYMENT GATEWAY DATA MANAGE
    -----------------------------------------------------------------------------------------------
    ///////////////////////////////////////////////////////////////////////////////////////////////
    */

    // Razorpay
    public function editRazorpay()
    {
        return response()->json([
            'key_id' => get_option('razorpay_key_id'),
            'key_secret' => get_option('razorpay_key_secret'),
            'status' => get_option('razorpay_status', 'inactive')
        ]);
    }

    public function updateRazorpay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key_id' => 'required|string',
            'key_secret' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        update_option('razorpay_key_id', $request->key_id);
        update_option('razorpay_key_secret', $request->key_secret);
        update_option('razorpay_status', $request->status);

        return response()->json(['success' => true, 'message' => 'Razorpay updated successfully.']);
    }

    public function deleteRazorpay()
    {
        \DB::table('settings')->whereIn('key', ['razorpay_key_id', 'razorpay_key_secret', 'razorpay_status'])->delete();
        return response()->json(['success' => true, 'message' => 'Razorpay deleted successfully.']);
    }

    // Stripe
    public function editStripe()
    {
        return response()->json([
            'public_key' => get_option('stripe_public_key'),
            'secret_key' => get_option('stripe_secret_key'),
            'status' => get_option('stripe_status', 'inactive')
        ]);
    }

    public function updateStripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        update_option('stripe_public_key', $request->public_key);
        update_option('stripe_secret_key', $request->secret_key);
        update_option('stripe_status', $request->status);

        return response()->json(['success' => true, 'message' => 'Stripe updated successfully.']);
    }

    public function deleteStripe()
    {
        \DB::table('settings')->whereIn('key', ['stripe_public_key', 'stripe_secret_key', 'stripe_status'])->delete();
        return response()->json(['success' => true, 'message' => 'Stripe deleted successfully.']);
    }

    // PayPal
    public function editPaypal()
    {
        return response()->json([
            'client_id' => get_option('paypal_client_id'),
            'client_secret' => get_option('paypal_client_secret'),
            'status' => get_option('paypal_status', 'inactive')
        ]);
    }

    public function updatePaypal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        update_option('paypal_client_id', $request->client_id);
        update_option('paypal_client_secret', $request->client_secret);
        update_option('paypal_status', $request->status);

        return response()->json(['success' => true, 'message' => 'PayPal updated successfully.']);
    }

    public function deletePaypal()
    {
        \DB::table('settings')->whereIn('key', ['paypal_client_id', 'paypal_client_secret', 'paypal_status'])->delete();
        return response()->json(['success' => true, 'message' => 'PayPal deleted successfully.']);
    }

    // BTC Pay
    public function editBtcPay()
    {
        return response()->json([
            'btc_pay_key' => get_option('btc_pay_key'),
            'btc_pay_secret' => get_option('btc_pay_secret'),
            'status' => get_option('btc_pay_status', 'inactive')
        ]);
    }

    public function updateBtcPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'btc_pay_key' => 'required|string',
            'btc_pay_secret' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        update_option('btc_pay_key', $request->btc_pay_key);
        update_option('btc_pay_secret', $request->btc_pay_secret);
        update_option('btc_pay_status', $request->status);

        return response()->json(['success' => true, 'message' => 'BTC Pay updated successfully.']);
    }

    public function deleteBtcPay()
    {
        \DB::table('settings')->whereIn('key', ['btc_pay_key', 'btc_pay_secret', 'btc_pay_status'])->delete();
        return response()->json(['success' => true, 'message' => 'BTC Pay deleted successfully.']);
    }

    public function paymentGatewaysList()
    {
        $gateways = [
            ['name' => 'Razorpay', 'type' => 'gateway', 'details' => json_encode(['key_id' => get_option('razorpay_key_id'), 'key_secret' => get_option('razorpay_key_secret')]), 'status' => get_option('razorpay_status', 'inactive')],
            ['name' => 'PayPal', 'type' => 'gateway', 'details' => json_encode(['client_id' => get_option('paypal_client_id'), 'client_secret' => get_option('paypal_client_secret')]), 'status' => get_option('paypal_status', 'inactive')],
            ['name' => 'Stripe', 'type' => 'gateway', 'details' => json_encode(['public_key' => get_option('stripe_public_key'), 'secret_key' => get_option('stripe_secret_key')]), 'status' => get_option('stripe_status', 'inactive')],
            ['name' => 'BTC Pay', 'type' => 'gateway', 'details' => json_encode(['btc_pay_key' => get_option('btc_pay_key'), 'btc_pay_secret' => get_option('btc_pay_secret')]), 'status' => get_option('btc_pay_status', 'inactive')],
        ];
        return response()->json(['data' => $gateways]);
    }

}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\EscortCategory;
use App\Models\FanCategory;
use App\Models\User;
use App\Models\Ads;
use App\Models\Features;
use App\Models\SubscriptionPlans;
use Yajra\DataTables\Facades\DataTables;

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
      ->get();
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
   ADDED CODE FRO THE MEMBERSHIP FEATURES
  -----------------------------------------------------------------------------------------------
  ///////////////////////////////////////////////////////////////////////////////////////////////
  */

  public function mfeaturesStore(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255|unique:features,name',
        'description' => 'required|string',
      ]);

      $feature = Features::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => true,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Feature added successfully.',
        'data' => $feature
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('StoreFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add feature. Please try again.'
      ], 500);
    }
  }

  public function mfeaturesList(Request $request)
  {
    if ($request->ajax()) {
      $data = Features::select('id', 'name', 'description', 'is_active');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('created_at', function ($row) {
          return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
        })
        ->addColumn('action', function ($row) {
          $btn = '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>';
          $btn .= ' <button class="btn btn-sm btn-danger delBtn delFeatureBtn" data-id="' . $row->id . '">Delete</button>';
          return $btn;
        })
        ->make(true);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function mfeaturesEdit($id)
  {
    try {
      $feature = Features::findOrFail($id);
      return response()->json($feature);
    } catch (\Exception $e) {
      \Log::error('EditFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load feature data.'
      ], 500);
    }
  }

  public function mfeaturesUpdate(Request $request, $id)
  {

    try {
      $request->validate([
        'name' => 'required|string|max:255|unique:features,name,' . $id,
        'description' => 'required|string',
      ]);

      $feature = Features::findOrFail($id);
      $feature->update([
        'name' => $request->name,
        'description' => $request->description,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Feature updated successfully',
        'data' => $feature
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('UpdateFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update feature. Please try again.'
      ], 500);
    }

  }

  public function mfeaturesDestroy($id)
  {
    try {
      $feature = Features::findOrFail($id);
      $feature->delete();

      return response()->json([
        'success' => true,
        'message' => 'Feature deleted successfully.'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('DeleteFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete feature. Please try again.'
      ], 500);
    }
  }


  /*
  ///////////////////////////////////////////////////////////////////////////////////////////////
  -----------------------------------------------------------------------------------------------
    START CODE FOR THE MEMBERSHIP PLAN
  -----------------------------------------------------------------------------------------------
  ///////////////////////////////////////////////////////////////////////////////////////////////
  */

  public function membershipPlanStore(Request $request)
  {
    try {

      $featureIds = [];

      // Pattern 1: feature_ids[] array (from JavaScript)
      if ($request->has('feature_ids') && is_array($request->feature_ids)) {
        $featureIds = array_filter($request->feature_ids);
        \Log::info('Found feature_ids array:', ['feature_ids' => $featureIds]);
      }
      // Pattern 2: features[] array
      elseif ($request->has('features') && is_array($request->features)) {
        $featureIds = $request->features;
        \Log::info('Found features array:', ['features' => $featureIds]);
      }
      // Pattern 3: Individual checkboxes (1, 2, 3, etc.)
      else {
        foreach ($request->all() as $key => $value) {
          if (is_numeric($key) && $value) {
            $featureIds[] = intval($key);
            \Log::info('Found numeric checkbox:', ['key' => $key, 'value' => $value]);
          }
        }
      }

      $featureIds = array_unique(array_filter($featureIds));


      $validated = $request->validate([
        'name' => 'nullable|string|max:255|unique:subscription_plans,name',
        'price' => 'required|numeric',
        'duration' => 'required|integer',
      ]);

      $planName = $validated['name'] ?? 'Plan - $' . $validated['price'] . ' (' . $validated['duration'] . ' days)';

      $plan = SubscriptionPlans::create([
        'name' => $planName,
        'price' => $validated['price'],
        'duration_days' => $validated['duration'],
        'features' => !empty($featureIds) ? json_encode($featureIds) : json_encode([]),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Membership Plan added successfully.',
        'data' => $plan
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('StoreFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add Membership Plan. Please try again.'
      ], 500);
    }
  }

  public function membershipPlanList(Request $request)
  {

    if ($request->ajax()) {
      $data = SubscriptionPlans::select('id', 'name', 'price', 'duration_days as duration', 'features', 'created_at');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('created_at', function ($row) {
          return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
        })
        ->editColumn('price', function ($row) {
          if (empty($row->price)) {
            return 'Not Found';
          }
          return $row->price;
        })
        ->editColumn('features', function ($row) {
          if (empty($row->features)) {
            return 'No features';
          }

          $featureIds = json_decode($row->features, true);
          if (!is_array($featureIds)) {
            return 'No features';
          }

          $features = Features::whereIn('id', $featureIds)->pluck('name')->toArray();
          return implode(', ', $features);
        })
        ->addColumn('action', function ($row) {
          return '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                              <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>';
        })
        ->make(true);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function membershipPlanEdit($id)
  {
    try {

      $plan = SubscriptionPlans::findOrFail($id);

      // Decode the feature JSON to array for checkbox pre-selection
      $featureIds = [];
      if (!empty($plan->feature)) {
        $decoded = json_decode($plan->feature, true);
        if (is_array($decoded)) {
          $featureIds = $decoded;
        }
      }

      return response()->json([
        'id' => $plan->id,
        'name' => $plan->name,
        'price' => $plan->price,
        'duration' => $plan->duration_days, // Return duration_days as duration for form
        'feature_ids' => $featureIds // Return decoded feature IDs array
      ]);

    } catch (\Exception $e) {
      \Log::error('EditFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load feature data.'
      ], 500);
    }
  }

  public function membershipPlanUpdate(Request $request, $id)
  {

    try {

      $validated = $request->validate([
        'name' => 'required|string|max:255|unique:subscription_plans,name,' . $id,
        'price' => 'required|numeric',
        'duration' => 'required|integer',
      ]);

      $featureIds = [];
      if ($request->has('feature_ids') && is_array($request->feature_ids)) {
        $featureIds = array_filter($request->feature_ids);
      }

      $plan = SubscriptionPlans::findOrFail($id);
      $plan->update([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'duration_days' => $validated['duration'],
        'features' => !empty($featureIds) ? json_encode($featureIds) : json_encode([])
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Feature updated successfully',
        'data' => $plan
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('UpdateFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update feature. Please try again.'
      ], 500);
    }

  }

  public function membershipPlanDestroy($id)
  {
    try {
      $plan = SubscriptionPlans::findOrFail($id);
      $plan->delete();

      return response()->json([
        'success' => true,
        'message' => 'Membership Plan deleted successfully.'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('DeleteFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete Membership Plan. Please try again.'
      ], 500);
    }
  }

  /*
  ///////////////////////////////////////////////////////////////////////////////////////////////
  -----------------------------------------------------------------------------------------------
    START CODE FOR THE PAYMENT GATEWAYS
  -----------------------------------------------------------------------------------------------
  ///////////////////////////////////////////////////////////////////////////////////////////////
  */

  public function paymentGatewayList()
  {
    $gateways = [
      [
        'id' => '1',
        'slug' => 'btc_pay',
        'name' => 'BTC Pay',
        'type' => 'gateway',
        'details' => json_encode([
          'store_id' => get_option('btc_pay_store_id'),
          'api_key' => get_option('btc_pay_api_key'),
          'server_url' => get_option('btc_pay_server_url'),
          'currency' => get_option('btc_pay_currency', 'BTC'),
          'webhook_url' => get_option('btc_pay_webhook_url'),
          'test_mode' => get_option('btc_pay_test_mode', 'yes')
        ]),
        'enabled' => get_option('btc_pay_enabled', 'no'),
        'mode' => get_option('btc_pay_mode', 'sandbox'),
        'status' => get_option('btc_pay_status', 'inactive')
      ],
      [
        'id' => '2',
        'slug' => 'razorpay',
        'name' => 'Razorpay',
        'type' => 'gateway',
        'details' => json_encode([
          'key_id' => get_option('razorpay_key_id'),
          'key_secret' => get_option('razorpay_key_secret'),
          'currency' => get_option('razorpay_currency', 'INR'),
          'webhook_url' => get_option('razorpay_webhook_url'),
          'test_mode' => get_option('razorpay_test_mode', 'yes')
        ]),
        'enabled' => get_option('razorpay_enabled', 'no'),
        'mode' => get_option('razorpay_mode', 'sandbox'),
        'status' => get_option('razorpay_status', 'inactive')
      ],
      [
        'id' => '3',
        'slug' => 'paypal',
        'name' => 'PayPal',
        'type' => 'gateway',
        'details' => json_encode([
          'client_id' => get_option('paypal_client_id'),
          'client_secret' => get_option('paypal_client_secret'),
          'mode' => get_option('paypal_mode', 'sandbox'),
          'currency' => get_option('paypal_currency', 'USD'),
          'webhook_id' => get_option('paypal_webhook_id'),
          'test_mode' => get_option('paypal_test_mode', 'yes')
        ]),
        'enabled' => get_option('paypal_enabled', 'no'),
        'mode' => get_option('paypal_mode', 'sandbox'),
        'status' => get_option('paypal_status', 'inactive')
      ],
      [
        'id' => '4',
        'slug' => 'stripe',
        'name' => 'Stripe',
        'type' => 'gateway',
        'details' => json_encode([
          'public_key' => get_option('stripe_public_key'),
          'secret_key' => get_option('stripe_secret_key'),
          'webhook_key' => get_option('stripe_webhook_key'),
          'currency' => get_option('stripe_currency', 'USD'),
          'test_mode' => get_option('stripe_test_mode', 'yes')
        ]),
        'enabled' => get_option('stripe_enabled', 'no'),
        'mode' => get_option('stripe_mode', 'sandbox'),
        'status' => get_option('stripe_status', 'inactive')
      ],
    ];

    return response()->json(['data' => $gateways]);
  }



  public function paymentGatewayToggle(Request $request)
  {
    $gateway = $request->input('gateway');
    $enabled = $request->input('enabled', 'no');

    $optionKey = $gateway . "_enabled";
    update_option($optionKey, $enabled);

    return response()->json(['success' => true, 'enabled' => $enabled]);
  }

  public function paymentGatewayMode(Request $request)
  {
    $gateway = $request->input('gateway');
    $mode = $request->input('mode');
    update_option("{$gateway}_mode", $mode);
    return response()->json(['success' => true, 'mode' => $mode]);
  }

  /*
     |--------------------------------------------------------------------------
     | ESCORT MANAGEMENT METHODS
     |--------------------------------------------------------------------------
     */

  public function escortManageView()
  {
    return view('admin.escort-manage');
  }

  // Escort Manage DataTable
  public function escortManage(Request $request)
  {
    if ($request->ajax()) {
      $data = User::where('role', 'escort')->with('usermeta')->latest();

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('profile_picture', function ($row) {
          $photo = $row->usermeta->where('meta_key', 'profile_picture')->first();
          return $photo ? $photo->meta_value : 'images/default-profile.jpg';
        })
        ->addColumn('action', function ($row) {
          return '
                        <button class="btn btn-sm btn-info viewEscortBtn" data-id="' . $row->id . '">View</button>
                        <button class="btn btn-sm btn-warning editEscortBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delEscortBtn" data-id="' . $row->id . '">Delete</button>
                    ';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
  }

  // Show Add Escort Page (for /admin/escort)
  public function showAddEscort()
  {
    return view('admin.escort');
  }

  // Store Escort
  public function storeEscort(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255', // Display Name
      'username' => 'nullable|string|max:255|unique:users,username',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'country_code' => 'nullable|string|max:10',
      'phone_number' => 'nullable|string|max:20',
      'dob' => 'nullable|date',
      'profile_picture' => 'nullable|image|max:2048',
      'display_name' => 'nullable|string|max:255',
      'age' => 'nullable|integer',
      'public_country_code' => 'nullable|string|max:10',
      'category_id' => 'nullable|exists:categories,id',
      'introduction' => 'nullable|string',
      'subscription_price' => 'nullable|numeric|min:0|max:100',
      'terms' => 'required|boolean',
      'over18' => 'required|boolean',
      'digital_services_only' => 'nullable|boolean',
      'promote_profile' => 'nullable|boolean',
    ]);

    $user = User::create([
      'name' => $request->name, // Display Name
      'username' => $request->username,
      'email' => $request->email,
      'role' => 'escort',
      'password' => bcrypt($request->password),
    ]);

    // Additional personal details
    $user->usermeta()->createMany([
      ['meta_key' => 'first_name', 'meta_value' => $request->first_name],
      ['meta_key' => 'last_name', 'meta_value' => $request->last_name],
      ['meta_key' => 'country_code', 'meta_value' => $request->country_code],
      ['meta_key' => 'phone_number', 'meta_value' => $request->phone_number],
      ['meta_key' => 'dob', 'meta_value' => $request->dob],
      ['meta_key' => 'display_name', 'meta_value' => $request->display_name],
      ['meta_key' => 'age', 'meta_value' => $request->age],
      ['meta_key' => 'public_country_code', 'meta_value' => $request->public_country_code],
      ['meta_key' => 'category_id', 'meta_value' => $request->category_id],
      ['meta_key' => 'introduction', 'meta_value' => $request->introduction],
      ['meta_key' => 'subscription_price', 'meta_value' => $request->subscription_price],
      ['meta_key' => 'terms', 'meta_value' => $request->terms ? 1 : 0],
      ['meta_key' => 'over18', 'meta_value' => $request->over18 ? 1 : 0],
      ['meta_key' => 'digital_services_only', 'meta_value' => $request->digital_services_only ? 1 : 0],
      ['meta_key' => 'promote_profile', 'meta_value' => $request->promote_profile ? 1 : 0],
    ]);

    if ($request->hasFile('profile_picture')) {
      $folderName = str_replace(' ', '_', $request->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_picture')->extension();
      $request->file('profile_picture')->move(storage_path('app/public/escort/' . $folderName), $fileName);

      $user->usermeta()->create([
        'meta_key' => 'profile_picture',
        'meta_value' => 'escort/' . $folderName . '/' . $fileName
      ]);
    }

    return response()->json(['success' => true, 'message' => 'Escort added successfully!', 'redirect' => route('admin.escort.manage')]);
  }

  // View Escort
  public function viewEscort($id)
  {
    $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);
    $meta = $escort->usermeta->pluck('meta_value', 'meta_key')->toArray();
    $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first();

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $escort->id,
        'name' => $escort->name,
        'email' => $escort->email,
        'first_name' => $meta['first_name'] ?? '',
        'last_name' => $meta['last_name'] ?? '',
        'country_code' => $meta['country_code'] ?? '',
        'phone_number' => $meta['phone_number'] ?? '',
        'dob' => $meta['dob'] ?? '',
        'display_name' => $meta['display_name'] ?? '',
        'age' => $meta['age'] ?? '',
        'public_country_code' => $meta['public_country_code'] ?? '',
        'category_id' => $meta['category_id'] ?? '',
        'introduction' => $meta['introduction'] ?? '',
        'subscription_price' => $meta['subscription_price'] ?? '',
        'terms' => $meta['terms'] ?? 0,
        'over18' => $meta['over18'] ?? 0,
        'digital_services_only' => $meta['digital_services_only'] ?? 0,
        'promote_profile' => $meta['promote_profile'] ?? 0,
        'profile_picture' => $photo ? $photo->meta_value : 'images/default-profile.jpg'
      ]
    ]);
  }

  // Edit Escort
  public function editEscort($id)
  {
    return $this->viewEscort($id);
  }

  // Update Escort
  public function updateEscort(Request $request, $id)
  {
    $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'profile_picture' => 'nullable|image|max:2048',
    ]);

    $escort->update([
      'name' => $request->name,
      'email' => $request->email,
    ]);

    if ($request->hasFile('profile_picture')) {
      $oldMeta = $escort->usermeta->where('meta_key', 'profile_picture')->first();
      if ($oldMeta && $oldMeta->meta_value) {
        File::delete(storage_path('app/public/' . $oldMeta->meta_value));
        $oldMeta->delete();
      }

      $folderName = str_replace(' ', '_', $request->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_picture')->extension();
      $request->file('profile_picture')->move(storage_path('app/public/escort/' . $folderName), $fileName);

      $escort->usermeta()->create([
        'meta_key' => 'profile_picture',
        'meta_value' => 'escort/' . $folderName . '/' . $fileName
      ]);
    }

    return response()->json(['success' => true, 'message' => 'Escort updated successfully!']);
  }

  // Delete Escort
  public function deleteEscort($id)
  {
    $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);

    $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first();
    if ($photo && $photo->meta_value) {
      File::delete(storage_path('app/public/' . $photo->meta_value));
      $photo->delete();
    }

    $escort->usermeta()->delete();
    $escort->delete();

    return response()->json(['success' => true, 'message' => 'Escort deleted successfully!']);
  }

  /*
|--------------------------------------------------------------------------
| FAN MANAGEMENT METHODS
|--------------------------------------------------------------------------
*/

  // Fan Manage (Ajax DataTable)
  public function fanManage(Request $request)
  {
    if ($request->ajax()) {
      try {
        $data = User::where('role', 'fan')->with('usermeta')->latest();

        return DataTables::of($data)
          ->addIndexColumn()
          ->addColumn('profile_picture', function ($row) {
            $photo = $row->usermeta->where('meta_key', 'profile_picture')->first();
            return $photo ? $photo->meta_value : 'images/default-profile.jpg';
          })
          ->addColumn('action', function ($row) {
            return '
                        <button class="btn btn-sm btn-info viewFanBtn" data-id="' . $row->id . '">View</button>
                        <button class="btn btn-sm btn-warning editFanBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delFanBtn" data-id="' . $row->id . '">Delete</button>
                    ';
          })
          ->rawColumns(['action'])
          ->make(true);

      } catch (\Exception $e) {
        \Log::error('fanManage error: ' . $e->getMessage());

        return response()->json([
          'error' => true,
          'message' => $e->getMessage()
        ], 500);
      }
    }

    return view('admin.fan-manage');
  }

  public function fanManageView()
  {
    return view('admin.fan-manage');
  }

  // Add Fan
  public function storeFan(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'profile_picture' => 'nullable|image|max:2048',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = 'fan';
    $user->password = bcrypt('defaultpassword');
    $user->save();

    if ($request->hasFile('profile_picture')) {
      $folderName = str_replace(' ', '_', $request->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_picture')->extension();
      $request->file('profile_picture')->move(storage_path('app/public/fan/' . $folderName), $fileName);

      $user->usermeta()->create([
        'meta_key' => 'profile_picture',
        'meta_value' => 'fan/' . $folderName . '/' . $fileName
      ]);
    }

    return response()->json(['success' => true, 'message' => 'Fan added successfully!']);
  }

  // View Fan
  public function viewFan($id)
  {
    $fan = User::where('role', 'fan')->with('usermeta')->findOrFail($id);
    $profile_picture = $fan->usermeta->where('meta_key', 'profile_picture')->first();
    $profile_picture = $profile_picture ? $profile_picture->meta_value : 'images/default-profile.jpg';

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $fan->id,
        'name' => $fan->name,
        'email' => $fan->email,
        'profile_picture' => $profile_picture
      ]
    ]);
  }

  // Edit Fan
  public function editFan($id)
  {
    return $this->viewFan($id);
  }

  // Update Fan
  public function updateFan(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'profile_picture' => 'nullable|image|max:2048',
    ]);

    $fan = User::where('role', 'fan')->with('usermeta')->findOrFail($id);

    $fan->name = $request->name;
    $fan->email = $request->email;

    if ($request->hasFile('profile_picture')) {
      $oldPhotoMeta = $fan->usermeta->where('meta_key', 'profile_picture')->first();
      if ($oldPhotoMeta && $oldPhotoMeta->meta_value) {
        File::delete(storage_path('app/public/' . $oldPhotoMeta->meta_value));
        $oldPhotoMeta->delete();
      }

      $folderName = str_replace(' ', '_', $request->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_picture')->extension();
      $request->file('profile_picture')->move(storage_path('app/public/fan/' . $folderName), $fileName);

      $fan->usermeta()->create([
        'meta_key' => 'profile_picture',
        'meta_value' => 'fan/' . $folderName . '/' . $fileName
      ]);
    }

    $fan->save();

    return response()->json(['success' => true, 'message' => 'Fan updated successfully!']);
  }

  // Delete Fan
  public function deleteFan($id)
  {
    $fan = User::where('role', 'fan')->with('usermeta')->findOrFail($id);

    $photoMeta = $fan->usermeta->where('meta_key', 'profile_picture')->first();
    if ($photoMeta && $photoMeta->meta_value) {
      File::delete(storage_path('app/public/' . $photoMeta->meta_value));
      $photoMeta->delete();
    }

    $fan->usermeta()->delete();
    $fan->delete();

    return response()->json(['success' => true, 'message' => 'Fan deleted successfully.']);
  }



}

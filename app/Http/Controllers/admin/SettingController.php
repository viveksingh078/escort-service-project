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
<<<<<<< HEAD

class SettingController extends Controller
{


=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\CountryFlag;
use Illuminate\Support\Facades\Log;
use App\Models\Country;



class SettingController extends Controller
{
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD



=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
   ADDED CODE FRO THE MEMBERSHIP FEATURES
=======
      ADDED CODE FOR THE MEMBERSHIP FEATURES
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD

  /*
  ///////////////////////////////////////////////////////////////////////////////////////////////
  -----------------------------------------------------------------------------------------------
    START CODE FOR THE MEMBERSHIP PLAN
=======
  /*
  ///////////////////////////////////////////////////////////////////////////////////////////////
  -----------------------------------------------------------------------------------------------
      START CODE FOR THE MEMBERSHIP PLAN
>>>>>>> 23c30d7 (Escort project)
  -----------------------------------------------------------------------------------------------
  ///////////////////////////////////////////////////////////////////////////////////////////////
  */

  public function membershipPlanStore(Request $request)
  {
    try {
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
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
=======
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

                // Check if features is already an array
                $featureIds = is_array($row->features) ? $row->features : json_decode($row->features, true);
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
>>>>>>> 23c30d7 (Escort project)

  public function membershipPlanEdit($id)
  {
    try {
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD

    try {

=======
    try {
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
    START CODE FOR THE PAYMENT GATEWAYS
=======
      START CODE FOR THE PAYMENT GATEWAYS
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD


=======
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
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
=======
  |--------------------------------------------------------------------------
  | ESCORT MANAGEMENT METHODS
  |--------------------------------------------------------------------------
  */

  // Escort Manage DataTable
  public function escortList(Request $request)
  {
    if ($request->ajax()) {
      $data = User::where('role', 'escort')->with('usermeta')->orderBy('id', 'desc');
>>>>>>> 23c30d7 (Escort project)

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

<<<<<<< HEAD
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
=======
  public function escortStore(Request $request)
  {
    DB::beginTransaction();  // Transaction for safety
    try {
      \Log::info('Raw Request Data: ', $request->all());

      // Validation (age ko server-side calculate karenge)
      $dob = $request->input('dob');
      $birthDate = new \DateTime($dob);
      $today = new \DateTime('2025-09-22');  // Current date as per context, ya use new \DateTime();
      $age = $today->diff($birthDate)->y;

      $validated = $request->validate([
        'display_name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
        'terms' => 'required|accepted',
        'over_18' => 'required|accepted',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dob' => 'required|date|before:2007-09-22',  // Min 18 years (calculated for 2025-09-22)
        'country_code' => 'required|numeric',
        'phone_number' => 'required|string|max:20',
        'whatsapp_number' => 'nullable|string|max:20',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'profile_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'nullable|exists:escort_categories,id',
        'facebook_url' => 'nullable|url',
        'twitter_url' => 'nullable|url',
        'linkedin_url' => 'nullable|url',
        'youtube_url' => 'nullable|url',
        'country' => 'required|exists:countries,id',
        'state' => 'required|exists:states,id',
        'city' => 'required|exists:cities,id',
        'subscription_price' => 'required|numeric|min:0',
        'confirm_income' => 'required|accepted',
        'digital_services_only' => 'required|accepted',
        'promote_profile' => 'nullable|accepted',
        'about_me' => 'nullable|string|max:1000',
        'height' => 'nullable|numeric|min:140|max:210',
        'dress' => 'nullable|numeric|min:1|max:20',
        'weight' => 'nullable|numeric|min:40|max:120',
        'bust' => 'nullable|numeric|min:70|max:120',
        'waist' => 'nullable|numeric|min:40|max:100',
        'eyes' => 'nullable|in:gray,blue,green,hazel,brown,black',
        'hair' => 'nullable|in:blond,black,brown,red,auburn,gray,other',
        'hips' => 'nullable|numeric|min:70|max:120',
        'shoe' => 'nullable|numeric|min:4|max:12',
      ]);

      if ($age < 18) {
        return response()->json([
          'success' => false,
          'errors' => ['dob' => 'You must be at least 18 years old.']
        ], 422);
      }

      $validated['age'] = $age;  // Add calculated age

      \Log::info('Validated Data: ', $validated);

      // Create User
      $user = User::create([
        'name' => $validated['display_name'],
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'escort',
      ]);

      \Log::info('User Created with ID: ' . $user->id);

      // Create Usermeta
      $user->usermeta()->create([
        'category_id' => $validated['category_id'] ?? null,
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'dob' => $validated['dob'],
        'age' => $validated['age'],
        'country_code' => $validated['country_code'],
        'phone_number' => $validated['phone_number'],
        'whatsapp_number' => $validated['whatsapp_number'] ?? null,
        'facebook_url' => $validated['facebook_url'] ?? null,
        'twitter_url' => $validated['twitter_url'] ?? null,
        'linkedin_url' => $validated['linkedin_url'] ?? null,
        'youtube_url' => $validated['youtube_url'] ?? null,
        'country_id' => $validated['country'],
        'state_id' => $validated['state'],
        'city_id' => $validated['city'],
        'subscription_price' => $validated['subscription_price'],
        'confirm_income' => $request->boolean('confirm_income'),
        'digital_services_only' => $request->boolean('digital_services_only'),
        'promote_profile' => $request->boolean('promote_profile') ?? 0,
        'about_me' => $validated['about_me'] ?? null,
        'height' => $validated['height'] ?? null,
        'dress' => $validated['dress'] ?? null,
        'weight' => $validated['weight'] ?? null,
        'bust' => $validated['bust'] ?? null,
        'waist' => $validated['waist'] ?? null,
        'eyes' => $validated['eyes'] ?? null,
        'hair' => $validated['hair'] ?? null,
        'hips' => $validated['hips'] ?? null,
        'shoe' => $validated['shoe'] ?? null,
      ]);

      // File Uploads
      if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('escort/' . str_replace(' ', '_', $user->name . mt_rand(0, 99)), 'public');
        $user->usermeta()->updateOrCreate(['meta_key' => 'profile_picture'], ['meta_value' => $path]);
      }

      if ($request->hasFile('profile_banner')) {
        $path = $request->file('profile_banner')->store('escort/' . str_replace(' ', '_', $user->name . mt_rand(0, 99)), 'public');
        $user->usermeta()->updateOrCreate(['meta_key' => 'profile_banner'], ['meta_value' => $path]);
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Escort added successfully!'
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      DB::rollBack();
      return response()->json([
        'success' => false,
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      DB::rollBack();
      \Log::error('Escort Store Error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors' => ['general' => 'An error occurred: ' . $e->getMessage()]
      ], 500);
    }
>>>>>>> 23c30d7 (Escort project)
  }

  // View Escort
  public function viewEscort($id)
  {
<<<<<<< HEAD
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
=======
    $data = get_userdata($id);
    $photos = get_photos($id);
    $videos = get_videos($id);

    // If AJAX → return JSON
    if (request()->ajax()) {
      return response()->json([
        'success' => true,
        'data' => $data,
        'photos' => $photos,
        'videos' => $videos
      ]);
    }
>>>>>>> 23c30d7 (Escort project)
  }

  // Edit Escort
  public function editEscort($id)
  {
<<<<<<< HEAD
    return $this->viewEscort($id);
  }

  // Update Escort
=======
    $data = get_userdata($id);

    // If AJAX → return JSON
    if (request()->ajax()) {
      return response()->json([
        'success' => true,
        'data' => $data,
      ]);
    }
  }

>>>>>>> 23c30d7 (Escort project)
  public function updateEscort(Request $request, $id)
  {
    $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);

<<<<<<< HEAD
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
=======
    // Validation
    $request->validate([
      'display_name' => 'nullable|string|max:255',
      'username' => 'nullable|string|max:255|unique:users,username,' . $id,
      'user_email' => 'required|email|unique:users,email,' . $id,
      'first_name' => 'nullable|string|max:255',
      'last_name' => 'nullable|string|max:255',
      'dob' => 'nullable|date',
      'country_code' => 'nullable|numeric',
      'phone_number' => 'nullable|string|max:20',
      'whatsapp_number' => 'nullable|string|max:20',
      'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'profile_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'category_id' => 'nullable|exists:escort_categories,id',
      'about_me' => 'nullable|string',
      'height' => 'nullable|numeric|min:140|max:210',
      'dress' => 'nullable|numeric|min:1|max:20',
      'weight' => 'nullable|numeric|min:40|max:120',
      'bust' => 'nullable|numeric|min:70|max:120',
      'waist' => 'nullable|numeric|min:40|max:100',
      'eyes' => 'nullable|string|in:gray,blue,green,hazel,brown,black',
      'hair' => 'nullable|string|in:blond,black,brown,red,auburn,gray,other',
      'hips' => 'nullable|numeric|min:70|max:120',
      'shoe' => 'nullable|numeric|min:4|max:12',
      'facebook_url' => 'nullable|url',
      'twitter_url' => 'nullable|url',
      'linkedin_url' => 'nullable|url',
      'youtube_url' => 'nullable|url',
      'country' => 'nullable|exists:countries,id',
      'state' => 'nullable|exists:states,id',
      'city' => 'nullable|exists:cities,id',
      'subscription_price' => 'nullable|numeric|min:0|max:100',
      'terms' => 'nullable|boolean',
      'digital_services_only' => 'nullable|boolean',
      'promote_profile' => 'nullable|boolean',
    ]);

    // User table update
    $escort->update([
      'name' => $request->display_name ?? $escort->name,
      'username' => $request->username ?? $escort->username,
      'email' => $request->user_email,
    ]);

    // Age calculate
    $age = null;
    if ($request->dob) {
      $dob = new \DateTime($request->dob);
      $today = new \DateTime();
      $age = $today->diff($dob)->y;
    }

    // Usermeta update
    $metas = [
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'dob' => $request->dob,
      'age' => $age,
      'country_code' => $request->country_code,
      'phone_number' => $request->phone_number,
      'whatsapp_number' => $request->whatsapp_number,
      'category_id' => $request->category_id,
      'about_me' => $request->about_me,
      'height' => $request->height,
      'dress' => $request->dress,
      'weight' => $request->weight,
      'bust' => $request->bust,
      'waist' => $request->waist,
      'eyes' => $request->eyes,
      'hair' => $request->hair,
      'hips' => $request->hips,
      'shoe' => $request->shoe,
      'facebook_url' => $request->facebook_url,
      'twitter_url' => $request->twitter_url,
      'linkedin_url' => $request->linkedin_url,
      'youtube_url' => $request->youtube_url,
      'country' => $request->country,
      'state' => $request->state,
      'city' => $request->city,
      'subscription_price' => $request->subscription_price,
      'terms' => $request->terms ? 1 : 0,
      'digital_services_only' => $request->digital_services_only ? 1 : 0,
      'promote_profile' => $request->promote_profile ? 1 : 0,
    ];

    foreach ($metas as $key => $value) {
      if ($value !== null) {
        \Log::info("Updating usermeta: meta_key=$key, meta_value=$value, user_id=$id");
        $escort->usermeta()->updateOrCreate(
          ['meta_key' => $key],
          ['meta_value' => $value, 'user_id' => $id]
        );
      }
    }

    // Profile Picture handle
    if ($request->hasFile('profile_picture')) {
      \Log::info('Profile picture file detected: ' . $request->file('profile_picture')->getClientOriginalName());
      $oldMeta = $escort->usermeta->where('meta_key', 'profile_picture')->first();
      if ($oldMeta && $oldMeta->meta_value) {
        \Log::info('Deleting old profile picture: ' . $oldMeta->meta_value);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldMeta->meta_value);
      }
      $folderName = str_replace(' ', '_', $escort->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_picture')->extension();
      $path = $request->file('profile_picture')->storeAs('escort/' . $folderName, $fileName, 'public');
      \Log::info('Profile picture stored at: ' . $path);
      $escort->usermeta()->updateOrCreate(
        ['meta_key' => 'profile_picture'],
        ['meta_value' => $path, 'user_id' => $id]
      );
    } else {
      \Log::info('No profile picture file in request');
    }

    // Profile Banner handle
    if ($request->hasFile('profile_banner')) {
      \Log::info('Profile banner file detected: ' . $request->file('profile_banner')->getClientOriginalName());
      $oldMeta = $escort->usermeta->where('meta_key', 'profile_banner')->first();
      if ($oldMeta && $oldMeta->meta_value) {
        \Log::info('Deleting old profile banner: ' . $oldMeta->meta_value);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldMeta->meta_value);
      }
      $folderName = str_replace(' ', '_', $escort->name . mt_rand(0, 99));
      $fileName = time() . '.' . $request->file('profile_banner')->extension();
      $path = $request->file('profile_banner')->storeAs('escort/' . $folderName, $fileName, 'public');
      \Log::info('Profile banner stored at: ' . $path);
      $escort->usermeta()->updateOrCreate(
        ['meta_key' => 'profile_banner'],
        ['meta_value' => $path, 'user_id' => $id]
      );
    } else {
      \Log::info('No profile banner file in request');
    }

    // Refresh escort with usermeta
    $escort->refresh()->load('usermeta');

    // Prepare response data
    $responseData = $escort->toArray();
    foreach ($escort->usermeta as $meta) {
      $responseData[$meta->meta_key] = $meta->meta_value;
    }

    return response()->json([
      'success' => true,
      'message' => 'Escort updated Successfully!',
      'data' => $responseData
    ]);
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
|--------------------------------------------------------------------------
| FAN MANAGEMENT METHODS
|--------------------------------------------------------------------------
*/
=======
  |--------------------------------------------------------------------------
  | FAN MANAGEMENT METHODS
  |--------------------------------------------------------------------------
  */
>>>>>>> 23c30d7 (Escort project)

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
<<<<<<< HEAD
                        <button class="btn btn-sm btn-info viewFanBtn" data-id="' . $row->id . '">View</button>
                        <button class="btn btn-sm btn-warning editFanBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delFanBtn" data-id="' . $row->id . '">Delete</button>
                    ';
          })
          ->rawColumns(['action'])
          ->make(true);

      } catch (\Exception $e) {
        \Log::error('fanManage error: ' . $e->getMessage());

=======
                            <button class="btn btn-sm btn-info viewFanBtn" data-id="' . $row->id . '">View</button>
                            <button class="btn btn-sm btn-warning editFanBtn" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delFanBtn" data-id="' . $row->id . '">Delete</button>
                        ';
          })
          ->rawColumns(['action'])
          ->make(true);
      } catch (\Exception $e) {
        \Log::error('fanManage error: ' . $e->getMessage());
>>>>>>> 23c30d7 (Escort project)
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

<<<<<<< HEAD


}
=======
  /*
  |--------------------------------------------------------------------------
  | COUNTRY FLAG MANAGEMENT METHODS
  |--------------------------------------------------------------------------
  */

  // Manage Countries Flags (Ajax DataTable)php<?php



  // Manage Countries (Ajax DataTable)
  public function manageCountries(Request $request)
  {
    if ($request->ajax()) {
      try {
        $data = Country::latest();

        return DataTables::of($data)
          ->addIndexColumn()
          ->addColumn('flag', function ($row) {
            return $row->flag ?: null;
          })
          ->addColumn('escorts_count', function ($row) {
            return $row->escorts_count;
          })
          ->addColumn('action', function ($row) {
            return '
                            <button class="btn btn-sm btn-info viewCountryFlagBtn" data-id="' . $row->id . '">View</button>
                            <button class="btn btn-sm btn-warning editCountryFlagBtn" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delCountryFlagBtn" data-id="' . $row->id . '">Delete</button>
                        ';
          })
          ->rawColumns(['action', 'flag'])
          ->make(true);
      } catch (\Exception $e) {
        Log::error('manageCountries error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
      }
    }

    return view('admin.manage-country-flags');
  }

  public function storeCountry(Request $request)
  {
    Log::info('storeCountry called with data:', $request->all());

    try {
      DB::beginTransaction();

      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'iso2' => 'nullable|string|size:2',
        'iso3' => 'nullable|string|size:3',
        'phonecode' => 'nullable|string|max:10',
        'flag' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
      ]);

      if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        return response()->json([
          'success' => false,
          'message' => 'Validation failed',
          'errors' => $validator->errors()
        ], 422);
      }

      $country = new Country();
      $country->name = $request->name;
      $country->iso2 = $request->iso2;
      $country->iso3 = $request->iso3;
      $country->phonecode = $request->phonecode;

      if ($request->hasFile('flag')) {
        $file = $request->file('flag');
        $filename = $request->iso2 ? strtolower($request->iso2) . '.' . $file->getClientOriginalExtension() : 'flag_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('flags', $filename, 'public');
        $country->flag = 'storage/' . $path;
      }

      $country->save();
      Log::info('Country saved to database: ' . $country->id);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Country added successfully!',
        'data' => [
          'id' => $country->id,
          'name' => $country->name,
          'flag' => $country->flag,
          'iso2' => $country->iso2,
          'iso3' => $country->iso3,
          'phonecode' => $country->phonecode,
          'escorts_count' => $country->escorts_count,
        ]
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error in storeCountry: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add country: ' . $e->getMessage()
      ], 500);
    }
  }

  public function viewCountry($id)
  {
    try {
      $country = Country::findOrFail($id);
      return response()->json([
        'success' => true,
        'data' => [
          'id' => $country->id,
          'name' => $country->name,
          'flag' => $country->flag,
          'iso2' => $country->iso2,
          'iso3' => $country->iso3,
          'phonecode' => $country->phonecode,
          'escorts_count' => $country->escorts_count,
        ]
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error in viewCountry: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch country: ' . $e->getMessage()
      ], 500);
    }
  }

  public function editCountry($id)
  {
    try {
      $country = Country::findOrFail($id);
      return response()->json([
        'success' => true,
        'data' => [
          'id' => $country->id,
          'name' => $country->name,
          'flag' => $country->flag,
          'iso2' => $country->iso2,
          'iso3' => $country->iso3,
          'phonecode' => $country->phonecode,
          'escorts_count' => $country->escorts_count,
        ]
      ], 200);
    } catch (\Exception $e) {
      Log::error('Error in editCountry: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch country for edit: ' . $e->getMessage()
      ], 500);
    }
  }

  public function updateCountry(Request $request, $id)
  {
    Log::info('updateCountry called for ID: ' . $id, $request->all());

    try {
      DB::beginTransaction();

      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'iso2' => 'nullable|string|size:2',
        'iso3' => 'nullable|string|size:3',
        'phonecode' => 'nullable|string|max:10',
        'flag' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
      ]);

      if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        return response()->json([
          'success' => false,
          'message' => 'Validation failed',
          'errors' => $validator->errors()
        ], 422);
      }

      $country = Country::findOrFail($id);
      $country->name = $request->name;
      $country->iso2 = $request->iso2;
      $country->iso3 = $request->iso3;
      $country->phonecode = $request->phonecode;

      if ($request->hasFile('flag')) {
        if ($country->flag) {
          Storage::disk('public')->delete(str_replace('storage/', '', $country->flag));
        }
        $file = $request->file('flag');
        $filename = $request->iso2 ? strtolower($request->iso2) . '.' . $file->getClientOriginalExtension() : 'flag_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('flags', $filename, 'public');
        $country->flag = 'storage/' . $path;
      }

      $country->save();
      Log::info('Country updated in database: ' . $country->id);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Country updated successfully!',
        'data' => [
          'id' => $country->id,
          'name' => $country->name,
          'flag' => $country->flag,
          'iso2' => $country->iso2,
          'iso3' => $country->iso3,
          'phonecode' => $country->phonecode,
          'escorts_count' => $country->escorts_count,
        ]
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error in updateCountry: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update country: ' . $e->getMessage()
      ], 500);
    }
  }

  public function deleteCountry($id)
  {
    Log::info('deleteCountry called for ID: ' . $id);

    try {
      DB::beginTransaction();

      $country = Country::findOrFail($id);

      $userCount = DB::table('usermeta')
        ->where('meta_key', 'country')
        ->where('meta_value', $id)
        ->count();

      if ($userCount > 0) {
        return response()->json([
          'success' => false,
          'message' => 'Cannot delete country because it is associated with ' . $userCount . ' user(s).'
        ], 400);
      }

      if ($country->flag) {
        Storage::disk('public')->delete(str_replace('storage/', '', $country->flag));
      }

      $country->delete();
      Log::info('Country deleted from database: ' . $id);

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Country deleted successfully.'
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error in deleteCountry: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete country: ' . $e->getMessage()
      ], 500);
    }
  }


}
>>>>>>> 23c30d7 (Escort project)

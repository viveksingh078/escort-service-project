<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\Feature;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
  public function index()
  {
    return view('admin.layout');
  }

  public function mainDash()
  {
    return view('admin.dashboard');
  }

  public function profileSettings()
  {
    return view('admin.profile-settings');
  }

  public function settings()
  {
    return view('admin.settings');
  }

  public function escortCategory()
  {
    return view('admin.escort-category');
  }

  public function escortCreate()
  {
    return view('admin.escort');
  }

  public function fanCategory()
  {
    return view('admin.fan-category');
  }

  public function fanCreate()
  {
    return view('admin.fan');
  }

  public function cities()
  {
    return view('admin.cities');
  }

  public function states()
  {
    return view('admin.states');
  }

  public function countries()
  {
    return view('admin.countries');
  }

  // Subscription Plan Views and Methods
  public function addSubscriptionPlan()
  {
    $features = Feature::all();
    return view('admin.add-new-plan', compact('features'));
  }

  public function allSubscriptionPlans()
  {
    $features = Feature::all();
    return view('admin.add-new-plan', compact('features'));
  }

  public function listPlans(Request $request)
  {
    $plans = SubscriptionPlan::select('id', 'name', 'price', 'duration_days as duration', 'feature', 'created_at');
    return DataTables::of($plans)
      ->addIndexColumn()
      ->addColumn('action', function ($row) {
        return '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delBtn" data-id="' . $row->id . '">Delete</button>';
      })
      ->editColumn('feature', function ($row) {
        if (empty($row->feature)) {
          return 'No features';
        }

        $featureIds = json_decode($row->feature, true);
        if (!is_array($featureIds)) {
          return 'No features';
        }

        $features = Feature::whereIn('id', $featureIds)->pluck('name')->toArray();
        return implode(', ', $features);
      })
      ->editColumn('created_at', function ($row) {
        return $row->created_at->format('Y-m-d H:i:s');
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function planIndex()
  {
    $plans = SubscriptionPlan::latest()->paginate(10);
    return view('admin.membership-feature', compact('plans'));
  }

  public function storePlan(Request $request)
  {
    try {
      \Log::info('=== SIMPLE FORM DEBUG ===');
      \Log::info('Raw form data:', $request->all());

      $featureIds = [];

      if ($request->has('feature_ids') && is_array($request->feature_ids)) {
        $featureIds = array_filter($request->feature_ids);
        \Log::info('Found feature_ids array:', ['feature_ids' => $featureIds]);
      } elseif ($request->has('features') && is_array($request->features)) {
        $featureIds = $request->features;
        \Log::info('Found features array:', ['features' => $featureIds]);
      } else {
        foreach ($request->all() as $key => $value) {
          if (is_numeric($key) && $value) {
            $featureIds[] = intval($key);
            \Log::info('Found numeric checkbox:', ['key' => $key, 'value' => $value]);
          }
        }
      }

      $featureIds = array_unique(array_filter($featureIds));
      \Log::info('Final selected features:', ['feature_ids' => $featureIds]);

      $validated = $request->validate([
        'name' => 'nullable|string|max:255|unique:subscription_plans,name',
        'price' => 'required|numeric',
        'duration' => 'required|integer',
      ]);

      $planName = $validated['name'] ?? 'Plan - $' . $validated['price'] . ' (' . $validated['duration'] . ' days)';

      $plan = SubscriptionPlan::create([
        'name' => $planName,
        'price' => $validated['price'],
        'duration_days' => $validated['duration'],
        'feature' => !empty($featureIds) ? json_encode($featureIds) : json_encode([]),
      ]);

      \Log::info('Plan created successfully:', ['plan' => $plan->toArray()]);

      return response()->json([
        'success' => true,
        'message' => 'Plan added successfully.',
        'data' => $plan
      ], 200);

    } catch (\Exception $e) {
      \Log::error('StorePlan error: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString(),
        'request_data' => $request->all()
      ]);
      return response()->json([
        'success' => false,
        'message' => 'Failed to add plan. Please try again.'
      ], 500);
    }
  }

  public function editPlan($id)
  {
    $plan = SubscriptionPlan::findOrFail($id);

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
      'duration' => $plan->duration_days,
      'feature_ids' => $featureIds
    ]);
  }

  public function updatePlan(Request $request, $id)
  {
    try {
      \Log::info('=== UPDATE PLAN DEBUG ===');
      \Log::info('Update request data:', $request->all());

      $validated = $request->validate([
        'name' => 'required|string|max:255|unique:subscription_plans,name,' . $id,
        'price' => 'required|numeric',
        'duration' => 'required|integer',
      ]);

      $featureIds = [];
      if ($request->has('feature_ids') && is_array($request->feature_ids)) {
        $featureIds = array_filter($request->feature_ids);
      }

      \Log::info('Processed feature IDs:', ['feature_ids' => $featureIds]);

      $plan = SubscriptionPlan::findOrFail($id);
      $plan->update([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'duration_days' => $validated['duration'],
        'feature' => !empty($featureIds) ? json_encode($featureIds) : json_encode([])
      ]);

      \Log::info('Plan updated successfully:', ['plan' => $plan->toArray()]);

      return response()->json([
        'success' => true,
        'message' => 'Plan updated successfully',
        'data' => $plan
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('UpdatePlan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update plan. Please try again.'
      ], 500);
    }
  }

  public function deletePlan($id)
  {
    try {
      $plan = SubscriptionPlan::findOrFail($id);
      $plan->delete();
      return response()->json([
        'success' => true,
        'message' => 'Plan deleted successfully'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('DeletePlan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete plan. Please try again.'
      ], 500);
    }
  }

  public function checkDuplicatePlan(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255'
      ]);

      $exists = SubscriptionPlan::where('name', $request->name)->exists();
      return response()->json([
        'success' => true,
        'exists' => $exists
      ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('CheckDuplicatePlan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error checking plan name.'
      ], 500);
    }
  }

  // Feature Methods
  public function features()
  {
    return view('admin.features');
  }

  public function getFeatures(Request $request)
  {
    if ($request->ajax()) {
      $data = Feature::select('id', 'name', 'description', 'status');
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $btn = '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>';
          $btn .= ' <button class="btn btn-sm btn-danger delFeatureBtn" data-id="' . $row->id . '">Delete</button>';
          return $btn;
        })
        ->editColumn('status', function ($row) {
          return $row->status ? 'Active' : 'Inactive';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function storeFeature(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255|unique:features,name',
        'description' => 'required|string',
      ]);

      $feature = Feature::create([
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

  public function updateFeature(Request $request, $id)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255|unique:features,name,' . $id,
        'description' => 'required|string',
      ]);

      $feature = Feature::findOrFail($id);
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

  public function toggleFeature(Request $request, $id)
  {
    try {
      $feature = Feature::findOrFail($id);
      $feature->update(['status' => !$feature->status]);

      return response()->json([
        'success' => true,
        'message' => 'Status updated successfully',
        'data' => $feature
      ], 200);
    } catch (\Exception $e) {
      \Log::error('ToggleFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to toggle status. Please try again.'
      ], 500);
    }
  }

  public function editFeature($id)
  {
    try {
      $feature = Feature::findOrFail($id);
      return response()->json($feature);
    } catch (\Exception $e) {
      \Log::error('EditFeature error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load feature data.'
      ], 500);
    }
  }

  public function deleteFeature($id)
  {
    try {
      $feature = Feature::findOrFail($id);
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

  public function paymentGateway()
  {
    return view('admin.payment-gateway');
  }

  // Escort Management Methods
  public function escortManage(Request $request)
  {
    if ($request->ajax()) {
      $data = User::where('role', 'escort')->with('usermeta');

      if ($request->has('search') && $request->search['value']) {
        $search = $request->search['value'];
        $data->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        });
      }

      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('profile_picture', function ($row) {
          $photo = $row->usermeta->where('meta_key', 'profile_picture')->first();
          return $photo ? $photo->meta_value : 'images/default-profile.jpg';
        })
        ->addColumn('action', function ($row) {
          return '<button class="btn btn-sm btn-info viewEscortBtn" data-id="' . $row->id . '">View</button>
                            <button class="btn btn-sm btn-warning editEscortBtn" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delEscortBtn" data-id="' . $row->id . '">Delete</button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    return view('admin.escort-manage');
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'profile_picture' => 'nullable|image|max:2048',
      ]);

      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->role = 'escort';
      $user->password = bcrypt('defaultpassword');

      $user->save();

      if ($request->hasFile('profile_picture')) {
        $folderName = str_replace(' ', '%20', $request->name . mt_rand(0, 99));
        $fileName = time() . '.' . $request->profile_picture->extension();
        $path = $request->profile_picture->storeAs('public/escort/' . $folderName, $fileName, 'public');
        \Log::info('Image saved at: ' . $path); // Debug line
        $user->usermeta()->create(['meta_key' => 'profile_picture', 'meta_value' => 'escort/' . $folderName . '/' . $fileName]);
      }

      return response()->json(['success' => true, 'message' => 'Escort added successfully!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('StoreEscort error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add escort. Please try again.'
      ], 500);
    }
  }

  public function deleteEscort($id)
  {
    try {
      $escort = User::where('role', 'escort')->findOrFail($id);

      if (method_exists($escort, 'usermeta')) {
        $photoMeta = $escort->usermeta->where('meta_key', 'profile_picture')->first();
        if ($photoMeta && $photoMeta->meta_value) {
          if (Storage::disk('public')->exists($photoMeta->meta_value)) {
            Storage::disk('public')->delete($photoMeta->meta_value);
          }
          $photoMeta->delete();
        }
        $escort->usermeta()->delete();
      }

      $escort->delete();

      return response()->json([
        'success' => true,
        'message' => 'Escort deleted successfully.'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('DeleteEscort error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete escort. Please try again.'
      ], 500);
    }
  }

  public function viewEscort($id)
  {
    try {
      $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);
      $profile_picture = $escort->usermeta->where('meta_key', 'profile_picture')->first()
        ? $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value
        : null;

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $escort->id,
          'name' => $escort->name,
          'email' => $escort->email,
          'profile_picture' => $profile_picture ? $profile_picture : 'images/default-profile.jpg'
        ]
      ], 200);
    } catch (\Exception $e) {
      \Log::error('ViewEscort error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load escort details.'
      ], 500);
    }
  }

  public function editEscort($id)
  {
    try {
      $escort = User::where('role', 'escort')->with('usermeta')->findOrFail($id);
      $profile_picture = $escort->usermeta->where('meta_key', 'profile_picture')->first()
        ? $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value
        : null;

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $escort->id,
          'name' => $escort->name,
          'email' => $escort->email,
          'profile_picture' => $profile_picture ? $profile_picture : 'images/default-profile.jpg'
        ]
      ], 200);
    } catch (\Exception $e) {
      \Log::error('EditEscort error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load escort for editing.'
      ], 500);
    }
  }

  public function updateEscort(Request $request, $id)
  {
    try {
      \Log::info('Update Request Received for ID: ' . $id . ', Raw Data: ', $request->all());

      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'profile_picture' => 'nullable|image|max:2048',
      ]);

      $escort = User::where('role', 'escort')->findOrFail($id);
      \Log::info('Existing Escort Data for ID: ' . $id . ', Data: ', $escort->toArray());

      $escort->name = $request->name;
      $escort->email = $request->email;

      if ($request->hasFile('profile_picture')) {
        $oldPhotoMeta = $escort->usermeta->where('meta_key', 'profile_picture')->first();
        if ($oldPhotoMeta && $oldPhotoMeta->meta_value) {
          if (Storage::disk('public')->exists($oldPhotoMeta->meta_value)) {
            Storage::disk('public')->delete($oldPhotoMeta->meta_value);
          }
          $oldPhotoMeta->delete();
        }

        $folderName = str_replace(' ', '%20', $request->name . mt_rand(0, 99));
        $fileName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->storeAs('public/escort/' . $folderName, $fileName, 'public');
        $escort->usermeta()->create(['meta_key' => 'profile_picture', 'meta_value' => 'escort/' . $folderName . '/' . $fileName]);
      }

      $escort->save();

      \Log::info('Update Successful for ID: ' . $id . ', Updated Data: ', $escort->toArray());

      return response()->json(['success' => true, 'message' => 'Escort updated successfully!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
      \Log::error('Validation Error for ID: ' . $id . ', Errors: ', $e->errors());
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('UpdateEscort Error for ID: ' . $id . ': ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to update escort. Please try again.'
      ], 500);
    }
  }

  // Fan Management Methods
  public function fanManage(Request $request)
  {
    if ($request->ajax()) {
      $data = User::where('role', 'fan')->with('usermeta');

      if ($request->has('search') && $request->search['value']) {
        $search = $request->search['value'];
        $data->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        });
      }

      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('profile_picture', function ($row) {
          $photo = $row->usermeta->where('meta_key', 'profile_picture')->first();
          return $photo ? $photo->meta_value : 'images/default-profile.jpg';
        })
        ->addColumn('action', function ($row) {
          return '<button class="btn btn-sm btn-info viewFanBtn" data-id="' . $row->id . '">View</button>
                            <button class="btn btn-sm btn-warning editFanBtn" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delFanBtn" data-id="' . $row->id . '">Delete</button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    $title = 'Manage Fan'; // Updated to "Manage Fan"
    $role = 'fan'; // Default role
    return view('admin.fan-manage', compact('title', 'role'));
  }


  public function storeFan(Request $request)
  {
    try {
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

      if ($request->hasFile('profile_picture')) {
        $fileName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->storeAs('public/profiles', $fileName);
        $user->usermeta()->create(['meta_key' => 'profile_picture', 'meta_value' => 'profiles/' . $fileName]);
      }

      $user->save();

      return response()->json(['success' => true, 'message' => 'Fan add ho gaya successfully!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('StoreFan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Fan add karne mein error. Phir try karo.'
      ], 500);
    }
  }

  public function deleteFan($id)
  {
    try {
      $fan = User::where('role', 'fan')->findOrFail($id);

      if (method_exists($fan, 'usermeta')) {
        $photoMeta = $fan->usermeta->where('meta_key', 'profile_picture')->first();
        if ($photoMeta && $photoMeta->meta_value) {
          $value = $photoMeta->meta_value;
          if (Str::startsWith($value, 'storage/')) {
            $value = substr($value, 8);
          }
          if (Storage::disk('public')->exists($value)) {
            Storage::disk('public')->delete($value);
          }
        }
        $fan->usermeta()->delete();
      }

      $fan->delete();

      return response()->json([
        'success' => true,
        'message' => 'Fan delete ho gaya successfully.'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('DeleteFan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Fan delete karne mein error. Phir try karo.'
      ], 500);
    }
  }

  public function viewFan($id)
  {
    try {
      $fan = User::where('role', 'fan')->with('usermeta')->findOrFail($id);
      $profile_picture = $fan->usermeta->where('meta_key', 'profile_picture')->first()
        ? $fan->usermeta->where('meta_key', 'profile_picture')->first()->meta_value
        : null;

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $fan->id,
          'name' => $fan->name,
          'email' => $fan->email,
          'profile_picture' => $profile_picture ? $profile_picture : 'images/default-profile.jpg'
        ]
      ], 200);
    } catch (\Exception $e) {
      \Log::error('ViewFan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Fan details load karne mein error.'
      ], 500);
    }
  }

  public function editFan($id)
  {
    try {
      $fan = User::where('role', 'fan')->with('usermeta')->findOrFail($id);
      $profile_picture = $fan->usermeta->where('meta_key', 'profile_picture')->first()
        ? $fan->usermeta->where('meta_key', 'profile_picture')->first()->meta_value
        : null;

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $fan->id,
          'name' => $fan->name,
          'email' => $fan->email,
          'profile_picture' => $profile_picture ? $profile_picture : 'images/default-profile.jpg'
        ]
      ], 200);
    } catch (\Exception $e) {
      \Log::error('EditFan error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Fan edit karne mein error.'
      ], 500);
    }
  }

  public function updateFan(Request $request, $id)
  {
    try {
      \Log::info('Update Request Received for ID: ' . $id . ', Raw Data: ', $request->all());

      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'profile_picture' => 'nullable|image|max:2048',
      ]);

      $fan = User::where('role', 'fan')->findOrFail($id);
      \Log::info('Existing Fan Data for ID: ' . $id . ', Data: ', $fan->toArray());

      $fan->name = $request->name;
      $fan->email = $request->email;

      if ($request->hasFile('profile_picture')) {
        $oldPhotoMeta = $fan->usermeta->where('meta_key', 'profile_picture')->first();
        if ($oldPhotoMeta && $oldPhotoMeta->meta_value) {
          $value = $oldPhotoMeta->meta_value;
          if (Str::startsWith($value, 'fan/')) {
            $value = substr($value, 4);
          }
          if (Storage::disk('public')->exists($value)) {
            Storage::disk('public')->delete($value);
          }
          $oldPhotoMeta->delete();
        }

        $folderName = str_replace(' ', '%20', $request->name . mt_rand(0, 99));
        $fileName = time() . '.' . $request->profile_picture->extension();
        $request->profile_picture->storeAs('public/fan/' . $folderName, $fileName);
        $fan->usermeta()->create(['meta_key' => 'profile_picture', 'meta_value' => 'fan/' . $folderName . '/' . $fileName]);
      }

      $fan->save();

      \Log::info('Update Successful for ID: ' . $id . ', Updated Data: ', $fan->toArray());

      return response()->json(['success' => true, 'message' => 'Fan update ho gaya successfully!']);
    } catch (\Illuminate\Validation\ValidationException $e) {
      \Log::error('Validation Error for ID: ' . $id . ', Errors: ', $e->errors());
      return response()->json([
        'success' => false,
        'message' => 'Validation failed.',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('UpdateFan Error for ID: ' . $id . ': ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Fan update karne mein error. Phir try karo.'
      ], 500);
    }
  }
}
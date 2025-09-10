<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ads;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{

  public function adsManage()
  {
      return view('admin.ads-space');
  }


  public function adsStore(Request $request)
  {
      try {
          $request->validate([
              'ads_title'       => 'required|string|max:255|unique:ads,title',
              'ads_image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
              'ads_link'        => 'nullable|url',
              'ads_position'    => 'nullable|string|max:100',
              'ads_description' => 'nullable|string',
          ]);

          $ads = new Ads();
          $ads->title       = $request->ads_title;
          $ads->link        = $request->ads_link;
          $ads->position    = $request->ads_position;
          $ads->description = $request->ads_description;

          // Handle Image Upload
          if ($request->hasFile('ads_image')) {
              $folderName  = Str::slug($request->ads_title); // safe folder name
              $basePath    = public_path('ads');             // main ads folder
              $uploadPath  = $basePath . '/' . $folderName;  // subfolder

              // Ensure main ads folder exists
              if (!file_exists($basePath)) {
                  mkdir($basePath, 0777, true);
              }

              // Ensure subfolder for this ad exists
              if (!file_exists($uploadPath)) {
                  mkdir($uploadPath, 0777, true);
              }

              // File name = title itself
              $extension = $request->ads_image->extension();
              $fileName  = $folderName . '.' . $extension;

              // Move image
              $request->ads_image->move($uploadPath, $fileName);

              // Save relative path (ads/{folder}/{file})
              $ads->image = 'ads/' . $folderName . '/' . $fileName;
          }

          $ads->save();

          return response()->json([
              'success' => true,
              'message' => 'Ads created successfully.',
              'data'    => $ads
          ], 200);

      } catch (\Illuminate\Validation\ValidationException $e) {
          return response()->json([
              'success' => false,
              'message' => 'Validation failed.',
              'errors'  => $e->errors()
          ], 422);
      } catch (\Exception $e) {
          \Log::error('Store Ads error: ' . $e->getMessage());
          return response()->json([
              'success' => false,
              'message' => $e->getMessage() // show actual error during debugging
          ], 500);
      }
  }


  public function adsList(Request $request)
  {
      if ($request->ajax()) {
          $data = Ads::select('id', 'title', 'description', 'image', 'position', 'link', 'created_at')
                     ->orderBy('created_at', 'desc'); 

          return DataTables::of($data)
              ->addIndexColumn()
              ->editColumn('created_at', function ($row) {
                  return \Carbon\Carbon::parse($row->created_at)->format('d M Y');
              })
              ->editColumn('image', function ($row) {
                  if ($row->image && file_exists(public_path($row->image))) {
                      return '<img src="' . asset($row->image) . '" alt="' . e($row->title) . '" height="220" width="220" class="shadow-sm"/>';
                  }
                  return '<span class="text-muted">No Image</span>';
              })
              ->addColumn('action', function ($row) {
                  $btn = '<button class="btn btn-sm btn-warning viewBtn mx-1" data-id="' . $row->id . '">View</button>';
                  $btn .= '<button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>';
                  $btn .= ' <button class="btn btn-sm btn-danger delBtn deladsBtn" data-id="' . $row->id . '">Delete</button>';
                  return $btn;
              })
              ->rawColumns(['image', 'action']) // allow HTML for image + buttons
              ->make(true);
      }

      return response()->json(['error' => 'Unauthorized'], 401);
  }

  public function adsEdit($id)
  {
    try {
      $faqs = Ads::findOrFail($id);
      return response()->json($faqs);
    } catch (\Exception $e) {
      \Log::error('Editfaqs error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to load faqs data.'
      ], 500);
    }
  }


  public function adsUpdate(Request $request, $id)
  {
      try {
          $request->validate([
              'ads_title'       => 'required|string|max:255|unique:ads,title,' . $id,
              'ads_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
              'ads_link'        => 'nullable|url',
              'ads_position'    => 'nullable|string|max:100',
              'ads_description' => 'nullable|string',
          ]);

          $ads = Ads::findOrFail($id);

          // Save old paths before updating
          $oldImagePath = $ads->image ? public_path($ads->image) : null;
          $oldFolder    = $ads->image ? dirname(public_path($ads->image)) : null;
          $oldTitle     = $ads->title;

          // Update fields
          $ads->title       = $request->ads_title;
          $ads->link        = $request->ads_link;
          $ads->position    = $request->ads_position;
          $ads->description = $request->ads_description;

          $folderName  = Str::slug($request->ads_title);
          $basePath    = public_path('ads');
          $uploadPath  = $basePath . '/' . $folderName;

          // Ensure ads base folder exists
          if (!file_exists($basePath)) {
              mkdir($basePath, 0777, true);
          }
          if (!file_exists($uploadPath)) {
              mkdir($uploadPath, 0777, true);
          }

          // Case 1: New image uploaded → replace old one
          if ($request->hasFile('ads_image')) {
              $extension = $request->ads_image->extension();
              $fileName  = $folderName . '.' . $extension;

              // Save new file
              $request->ads_image->move($uploadPath, $fileName);

              // Update DB path
              $ads->image = 'ads/' . $folderName . '/' . $fileName;

              // Remove old folder (and image) if different
              if ($oldFolder && File::exists($oldFolder) && $oldFolder !== $uploadPath) {
                  File::deleteDirectory($oldFolder);
              }
          }
          // Case 2: Title changed but no new image → move folder
          elseif ($request->ads_title !== $oldTitle && $oldFolder && File::exists($oldFolder)) {
              $newFolderPath = $uploadPath;

              if (!File::exists($newFolderPath)) {
                  File::makeDirectory($newFolderPath, 0777, true);
                  File::moveDirectory($oldFolder, $newFolderPath);
              }

              // Update DB path with new folder
              if ($ads->image) {
                  $ads->image = 'ads/' . $folderName . '/' . basename($ads->image);
              }
          }

          $ads->save();

          return response()->json([
              'success' => true,
              'message' => 'Ad updated successfully.',
              'data'    => $ads
          ], 200);

      } catch (\Illuminate\Validation\ValidationException $e) {
          return response()->json([
              'success' => false,
              'message' => 'Validation failed.',
              'errors'  => $e->errors()
          ], 422);
      } catch (\Exception $e) {
          \Log::error('Update Ads error: ' . $e->getMessage());
          return response()->json([
              'success' => false,
              'message' => $e->getMessage()
          ], 500);
      }
  }


  public function adsDestroy($id)
  {
      try {
          $ads = Ads::findOrFail($id);

          // Delete image + folder if exists
          if ($ads->image) {
              $imagePath = public_path($ads->image);

              // Remove file
              if (file_exists($imagePath)) {
                  @unlink($imagePath);
              }

              // Remove parent folder if empty
              $folderPath = dirname($imagePath);
              if (is_dir($folderPath) && count(glob($folderPath . '/*')) === 0) {
                  @rmdir($folderPath);
              }
          }

          // Delete record
          $ads->delete();

          return response()->json([
              'success' => true,
              'message' => 'Ad deleted successfully.'
          ], 200);

      } catch (\Exception $e) {
          \Log::error('Delete Ads error: ' . $e->getMessage());
          return response()->json([
              'success' => false,
              'message' => 'Failed to delete Ad. Please try again.'
          ], 500);
      }
  }



  public function adsView($id)
  {
      $ads = Ads::findOrFail($id);

      return response()->json([
          'id'            => $ads->id,
          'title'     => $ads->title,
          'link'      => $ads->link,
          'position'  => $ads->position,
          'description'=> $ads->description,
          'image'     => $ads->image, // relative path
          'created_at'    => $ads->created_at->format('d M Y'),
      ]);
  }


  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
}

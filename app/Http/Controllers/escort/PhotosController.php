<?php

namespace App\Http\Controllers\Escort;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscortPhotos;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class PhotosController extends Controller
{
    // Show the Photos Management Page
    public function photosManage()
    {
        return view('escort.photos');
    }

    public function photosStore(Request $request)
    {
        try {
            $request->validate([
                'title'       => 'required|string|max:255',
                'file_path.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'description' => 'nullable|string',
                'is_public'   => 'required|boolean',
            ]);

            $savedPhotos = [];

            if ($request->hasFile('file_path')) {
                $folderName = Str::slug($request->title);
                $uploadPath = public_path('escort/photos/' . $folderName);

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true);
                }

                $escortId = Auth::guard('escort')->user()->id;

                // 1️⃣ Get max number from DB
                $maxDbNumber = EscortPhotos::where('escort_id', $escortId)
                    ->where('title', 'like', $request->title . ' Image%')
                    ->get()
                    ->map(function ($photo) {
                        if (preg_match('/Image (\d+)$/', $photo->title, $matches)) {
                            return (int) $matches[1];
                        }
                        return 0;
                    })
                    ->max();

                // 2️⃣ Get max number from existing files in folder
                $maxFileNumber = 0;
                if (File::exists($uploadPath)) {
                    foreach (File::files($uploadPath) as $file) {
                        if (preg_match('/-image-(\d+)\./', $file->getFilename(), $matches)) {
                            $maxFileNumber = max($maxFileNumber, (int) $matches[1]);
                        }
                    }
                }

                // 3️⃣ Start from the highest number found
                $startNumber = max($maxDbNumber, $maxFileNumber);

                foreach ($request->file('file_path') as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $imageNumber = $startNumber + $index + 1;

                    $fileName  = $folderName . '-image-' . str_pad($imageNumber, 2, '0', STR_PAD_LEFT) . '.' . $extension;
                    $file->move($uploadPath, $fileName);

                    $photo = new EscortPhotos();
                    $photo->escort_id   = $escortId;
                    $photo->title       = $request->title . ' Image ' . $imageNumber;
                    $photo->description = $request->description;
                    $photo->is_public   = $request->is_public;
                    $photo->is_approved = 1;
                    $photo->media_type  = 'photo';
                    $photo->file_path   = 'escort/photos/' . $folderName . '/' . $fileName;
                    $photo->save();

                    $savedPhotos[] = $photo;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Photo(s) uploaded successfully.',
                'data'    => $savedPhotos
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store Photo error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function photosList($escortId, Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $offset == 0 ? 8 : 8;

        $photos = EscortPhotos::where('escort_id', $escortId)
        ->where('media_type', 'photo')
        ->orderBy('id', 'desc')
        ->skip($offset)
        ->take($limit)
        ->get();



        $html = '';
        if($photos->isEmpty()){
            $html = '<div class="text-center w-100 p-3">No photos found.</div>';
        } else {
            foreach($photos as $photo){
                $html .= '<div class="album-photo-item position-relative mb-4 col-sm-6 col-lg-3 col-md-3" >
                            <a href="'.asset($photo->file_path).'" data-fancybox="gallery" data-caption="'.htmlspecialchars($photo->title).'">
                                <img src="'.asset($photo->file_path).'" class="shadow-sm rounded" style="width:100%;height:168px;">
                            </a>
                            <button class="btn btn-danger btn-sm remove-photo rounded-pill shadow-sm" 
                                    data-id="'.$photo->id.'" 
                                    style="position:absolute;bottom:7px;right:17px;z-index:10;">
                                <i class="fas fa-trash"></i>
                            </button>
                          </div>';
            }
        }

        $totalPhotos = EscortPhotos::where('escort_id', $escortId)->count();
        $hasMore = ($offset + $limit) < $totalPhotos;

        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore
        ]);
    }

    public function photosDestroy($id)
    {
        $photo = EscortPhotos::find($id);

        if (!$photo) {
            return response()->json(['success' => false, 'message' => 'Photo not found.']);
        }

        // Get folder path
        $folderPath = public_path(dirname($photo->file_path));

        // Delete the physical file
        if (file_exists(public_path($photo->file_path))) {
            unlink(public_path($photo->file_path));
        }

        // Delete the record
        $photo->delete();

        // Check if folder is empty and delete it
        if (File::exists($folderPath) && count(File::files($folderPath)) === 0) {
            File::deleteDirectory($folderPath);
        }

        return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
    }



}

<?php

namespace App\Http\Controllers\Escort;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscortVideos;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class VideosController extends Controller
{
    // Show the Videos Management Page
    public function videosManage()
    {
        return view('escort.videos');
    }

    // Store multiple videos
    public function videosStore(Request $request)
    {
        try {
            $request->validate([
                'title'       => 'required|string|max:255',
                'file_path.*' => 'required|mimes:mp4,mov,avi,webm|max:204800',
                'description' => 'nullable|string',
                'is_public'   => 'required|boolean',
            ]);

            $savedVideos = [];
            $escortId   = Auth::guard('escort')->user()->id;
            $folderName = Str::slug($request->title);
            $uploadPath = public_path('escort/videos/' . $folderName);

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }

            // Max number from DB
            $maxDbNumber = EscortVideos::where('escort_id', $escortId)
                ->where('title', 'like', $request->title . ' Video%')
                ->get()
                ->map(function ($video) {
                    if (preg_match('/Video (\d+)$/', $video->title, $matches)) {
                        return (int) $matches[1];
                    }
                    return 0;
                })
                ->max();

            // Max number from files
            $maxFileNumber = 0;
            if (File::exists($uploadPath)) {
                foreach (File::files($uploadPath) as $file) {
                    if (preg_match('/-video-(\d+)\./', $file->getFilename(), $matches)) {
                        $maxFileNumber = max($maxFileNumber, (int)$matches[1]);
                    }
                }
            }

            $startNumber = max($maxDbNumber, $maxFileNumber);

            foreach ($request->file('file_path') as $index => $videoFile) {
                $videoNumber = $startNumber + $index + 1;
                $videoExt    = $videoFile->getClientOriginalExtension();
                $videoName   = $folderName . '-video-' . str_pad($videoNumber, 2, '0', STR_PAD_LEFT) . '.' . $videoExt;
                $videoFile->move($uploadPath, $videoName);

                $video = new EscortVideos();
                $video->escort_id   = $escortId;
                $video->title       = $request->title . ' Video ' . $videoNumber;
                $video->description = $request->description;
                $video->is_public   = $request->is_public;
                $video->is_approved = 1;
                $video->media_type  = 'video';
                $video->file_path   = 'escort/videos/' . $folderName . '/' . $videoName;
                $video->save();

                $savedVideos[] = $video;
            }

            return response()->json([
                'success' => true,
                'message' => 'Video(s) uploaded successfully.',
                'data'    => $savedVideos
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store Video error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // List videos with offset for Load More
  public function videosList($escortId, Request $request)
  {
      $offset = $request->input('offset', 0);
      $limit  = 8;

      $videos = EscortVideos::where('escort_id', $escortId)
          ->where('media_type', 'video')
          ->orderBy('id', 'desc')
          ->skip($offset)
          ->take($limit)
          ->get();

      $html = '';
      if ($videos->isEmpty()) {
          $html = '<div class="text-center w-100 p-3">No videos found.</div>';
      } else {
          foreach ($videos as $video) {
              if ($video->thumbnail_path) {
                  // Show thumbnail + Change + Remove buttons
                  $thumb = asset($video->thumbnail_path);
                  $html .= '<div class="album-video-item position-relative mb-4 col-sm-6 col-lg-3 col-md-3">
                              <a href="'.asset($video->file_path).'" data-fancybox="gallery" data-caption="'.htmlspecialchars($video->title).'">
                                  <img src="'.$thumb.'" class="shadow-sm rounded" style="width:100%;height:168px;object-fit:cover;">
                              </a>
                              <button class="btn btn-danger btn-sm remove-thumbnail rounded-pill shadow-sm" 
                                      data-id="'.$video->id.'" 
                                      style="position:absolute;bottom:7px;left:15px;z-index:10;">
                                 <i class="fas fa-trash"></i> Thumbnail
                              </button>
                              <button class="btn btn-danger btn-sm remove-video rounded-pill shadow-sm" 
                                      data-id="'.$video->id.'" 
                                      style="position:absolute;bottom:7px;right:15px;z-index:10;">
                                  <i class="fas fa-trash"></i>
                              </button>
                            </div>';
              } else {
                  // Show video icon + Add Thumbnail + Remove video buttons
                  $html .= '<div class="album-video-item position-relative mb-4 col-sm-6 col-lg-3 col-md-3 d-flex align-items-center justify-content-center bg-light rounded shadow-sm" 
                               style="height:168px; border:1px solid #ddd;">
                              <i class="fas fa-video fa-3x text-secondary"></i>
                              
                              <button class="btn btn-primary btn-sm add-thumbnail rounded-pill shadow-sm" 
                                      data-id="'.$video->id.'" 
                                      style="position:absolute;bottom:7px;left:7px;z-index:10;">
                                  <i class="fas fa-plus"></i> Thumbnail
                              </button>

                              <button class="btn btn-danger btn-sm remove-video rounded-pill shadow-sm" 
                                      data-id="'.$video->id.'" 
                                      style="position:absolute;bottom:7px;right:7px;z-index:10;">
                                  <i class="fas fa-trash"></i>
                              </button>
                            </div>';
              }
          }
      }

      $totalVideos = EscortVideos::where('escort_id', $escortId)->where('media_type','video')->count();
      $hasMore = ($offset + $limit) < $totalVideos;

      return response()->json([
          'html'    => $html,
          'hasMore' => $hasMore
      ]);
  }



    // Delete video
    public function videosDestroy($id)
    {
        $video = EscortVideos::find($id);

        if (!$video) {
            return response()->json(['success' => false, 'message' => 'Video not found.']);
        }

        $folderPath = public_path(dirname($video->file_path));

        // Delete video file
        if (file_exists(public_path($video->file_path))) {
            unlink(public_path($video->file_path));
        }

        // Delete DB record
        $video->delete();

        // Delete folder if empty
        if (File::exists($folderPath) && count(File::files($folderPath)) === 0) {
            File::deleteDirectory($folderPath);
        }

        return response()->json(['success' => true, 'message' => 'Video deleted successfully.']);
    }


    public function addThumbnail(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer|exists:escort_media,id',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $video = EscortVideos::find($request->video_id);

        $folderPath = public_path('escort/videos/' . Str::slug($video->title));
        if(!File::exists($folderPath)){
            File::makeDirectory($folderPath, 0777, true);
        }

        $thumbFile = $request->file('thumbnail');
        $thumbExt = $thumbFile->getClientOriginalExtension();
        $thumbName = Str::slug($video->title) . '-thumb.' . $thumbExt;

        $thumbFile->move($folderPath, $thumbName);

        $video->thumbnail_path = 'escort/videos/' . Str::slug($video->title) . '/' . $thumbName;
        $video->save();

        return response()->json([
            'success' => true,
            'message' => 'Thumbnail uploaded successfully.',
            'thumbnail_path' => asset($video->thumbnail_path)
        ]);
    }


    public function removeThumbnail(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer|exists:escort_media,id'
        ]);

        $video = EscortVideos::find($request->video_id);

        if ($video->thumbnail_path && file_exists(public_path($video->thumbnail_path))) {
            unlink(public_path($video->thumbnail_path));
            $video->thumbnail_path = null;
            $video->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Thumbnail removed successfully.'
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
<<<<<<< HEAD
    //
=======
  //profile listing of the escort
 public function listingEscortProfile($id)
  {
      // Get escort profile by id
      $data = get_userdata($id);

      // Get the latest 20 photos
      $photos = get_photos($id);
      $videos = get_videos($id);

      // Pass both to view
      return view('listing-profile', compact('data', 'photos', 'videos'));
  }

>>>>>>> 23c30d7 (Escort project)
}

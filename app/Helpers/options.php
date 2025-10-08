<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('add_option')) {
    function add_option(string $key, $value): bool
    {
        if (DB::table('settings')->where('key', $key)->exists()) {
            return false;
        }

        return DB::table('settings')->insert([
            'key' => $key,
            'value' => is_array($value) ? json_encode($value) : $value,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

if (!function_exists('update_option')) {
    function update_option(string $key, $value): bool
    {
        return DB::table('settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'updated_at' => now()
            ]
        );
    }
}

if (!function_exists('get_option')) {
    function get_option(string $key, $default = null)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        return $setting ? json_decode($setting->value, true) ?? $setting->value : $default;
    }
}

if (!function_exists('delete_option')) {
    function delete_option(string $key): bool
    {
        return DB::table('settings')->where('key', $key)->delete() > 0;
    }
}


if (!function_exists('get_userdata')) {
    /**
     * Get merged user data with usermeta in a single flat array.
     *
     * @param  int  $user_id
     * @return array|null
     */
    function get_userdata($user_id)
    {
        // Fetch user from `users` table
        $user = DB::table('users')->where('id', $user_id)->first();

        if (!$user) {
            return null;
        }

        // Convert user object to array
        $userArray = (array) $user;

        // Fetch user meta from `usermeta` table
        $usermeta = DB::table('usermeta')
            ->where('user_id', $user_id)
            ->pluck('meta_value', 'meta_key')
            ->toArray();

        // Merge both arrays
        return array_merge($userArray, $usermeta);
    }
}
<<<<<<< HEAD
=======


if (!function_exists('get_photos')) {
    /**
     * Get all public escort photos by escort ID as plain arrays.
     *
     * @param  int  $escortId
     * @return array
     */
    function get_photos($escortId)
    {
        return DB::table('escort_media')
            ->where('escort_id', $escortId)
            ->where('media_type', 'photo')
            ->where('is_public', 1)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                return (array) $item; // cast each stdClass object to array
            })
            ->toArray(); // convert collection to plain array
    }
}


if (!function_exists('get_videos')) {
    /**
     * Get all public escort videos by escort ID as plain arrays.
     *
     * @param  int  $escortId
     * @return array
     */
    function get_videos($escortId)
    {
        return DB::table('escort_media')
            ->where('escort_id', $escortId)
            ->where('media_type', 'video')  // filter for videos
            ->where('is_public', 1)         // only public videos
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                return (array) $item;       // cast each stdClass object to array
            })
            ->toArray();                    // convert collection to plain array
    }
}



if (!function_exists('allUsers')) {
    function allUsers($countryId = null)
    {
        try {
            // Base query for countries with escort counts
            $query = DB::table('users')
                ->join('usermeta', function ($join) {
                    $join->on('users.id', '=', 'usermeta.user_id')
                        ->where('usermeta.meta_key', 'country_id');
                })
                ->join('country_flags', 'usermeta.meta_value', '=', 'country_flags.id')
                ->where('users.role', 'escort')
                ->select(
                    'country_flags.id as country_id',
                    'country_flags.name as country_name',
                    'country_flags.flag_path',
                    DB::raw('COUNT(users.id) as escorts_count')
                )
                ->groupBy('country_flags.id', 'country_flags.name', 'country_flags.flag_path')
                ->orderBy('escorts_count', 'desc')
                ->limit(15);

            // Fetch popular countries
            $popularCountries = $query->get();

            // Fetch escorts list (filtered by country if provided)
            $escortsQuery = User::where('role', 'escort')
                ->with('usermeta')
                ->when($countryId, function ($query) use ($countryId) {
                    return $query->whereHas('usermeta', function ($q) use ($countryId) {
                        $q->where('meta_key', 'country_id')->where('meta_value', $countryId);
                    });
                })
                ->latest();

            $escorts = $escortsQuery->paginate(9);

            return [
                'popularCountries' => $popularCountries,
                'escorts' => $escorts
            ];
        } catch (\Exception $e) {
            \Log::error('allUsers helper error: ' . $e->getMessage());
            return [
                'popularCountries' => collect([]),
                'escorts' => collect([])
            ];
        }
    }

}


>>>>>>> 23c30d7 (Escort project)

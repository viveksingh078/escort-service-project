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

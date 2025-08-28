<?php

use App\Models\Usermeta;

/**
 * Get user meta data
 *
 * @param  int         $user_id
 * @param  string|null $key
 * @param  bool        $single
 * @return mixed
 */
    if (!function_exists('get_user_meta')) {
        function get_user_meta(int $user_id, string $key = null, bool $single = true)
        {
            if ($key !== null) {
                $query = Usermeta::where('user_id', $user_id)->where('meta_key', $key);

                if ($single) {
                    $meta = $query->first();

                    if (!$meta) {
                        return null;
                    }

                    // Decode if JSON
                    $decoded = json_decode($meta->meta_value, true);
                    return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $meta->meta_value;
                }

                // Return multiple values (array of strings or decoded JSONs)
                return $query->get()->map(function ($item) {
                    $decoded = json_decode($item->meta_value, true);
                    return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item->meta_value;
                })->toArray();
            }

            // If no key provided, return all key => value pairs
            return Usermeta::where('user_id', $user_id)
                ->get()
                ->mapWithKeys(function ($item) {
                    $decoded = json_decode($item->meta_value, true);
                    $value = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item->meta_value;
                    return [$item->meta_key => $value];
                })
                ->toArray();
        }
    }


/**
 * Update or create user meta data
 *
 * @param  int    $user_id
 * @param  string $key
 * @param  mixed  $value
 * @return bool
 */
if (!function_exists('update_user_meta')) {
    function update_user_meta(int $user_id, string $key, $value): bool
    {
        // Convert arrays or objects to JSON strings
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $meta = Usermeta::where('user_id', $user_id)->where('meta_key', $key)->first();

        if ($meta) {
            $meta->meta_value = $value;
            return (bool)$meta->save();
        }

        return (bool)Usermeta::create([
            'user_id' => $user_id,
            'meta_key' => $key,
            'meta_value' => $value,
        ]);
    }
}


/**
 * Delete a user meta item
 *
 * @param  int    $user_id
 * @param  string $key
 * @return int  Number of deleted rows
 */
if (!function_exists('delete_user_meta')) {
    function delete_user_meta(int $user_id, string $key): int
    {
        return Usermeta::where('user_id', $user_id)
            ->where('meta_key', $key)
            ->delete();
    }
}




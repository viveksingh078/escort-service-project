<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;


class UserController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $current_user_id = Auth::guard('escort')->id();
        $roles = $request->input('roles', ['escort', 'fan']); 
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all');

        $query = User::whereIn('role', $roles)
                     ->where('id', '!=', $current_user_id); 

        if (!empty($search)) {
            $query->where('username', 'LIKE', '%' . $search . '%');
        }

        // Apply filter logic
        if ($filter === 'unread') {
            $query->whereHas('messagesReceived', function ($q) {
                $q->where('is_read', false);
            });
        } elseif ($filter === 'favourites') {
            $favourite_ids = get_user_meta($current_user_id, 'is_favorites');
            if (!is_array($favourite_ids)) {
                $favourite_ids = [];
            }
            $query->whereIn('id', $favourite_ids);
        } elseif ($filter === 'friends') {
            $is_friends = get_user_meta($current_user_id, 'is_friends');
            if (!is_array($is_friends)) {
                $is_friends = [];
            }
            $query->whereIn('id', $is_friends);
        }

        $users = $query->get();

        $user_data = [];

        foreach ($users as $user) {
            $user_data[] = get_userdata($user->id);
        }

        return response()->json($user_data);
    }



    public function getUser(Request $request)
    {
        $current_user_id = Auth::guard('escort')->id();
        $user_id = $request->input('user_id');

        $is_friends = (array) get_user_meta($current_user_id, 'is_friends');
        $is_favorites = (array) get_user_meta($current_user_id, 'is_favorites');

         // Optional: get full data with helper
        $user_data = get_userdata($user_id);

        // Append status info to response
        $user_data['is_friend'] = in_array($user_id, $is_friends);
        $user_data['is_favorite'] = in_array($user_id, $is_favorites);


        return response()->json($user_data);
    }

    public function addFavourites(Request $request)
    {
        $current_user_id = Auth::guard('escort')->user()->id;
        $userId = $request->input('user_id');

        // Get current favorites
        $is_favorites = get_user_meta($current_user_id, 'is_favorites');

        // Ensure it's an array
        if (!is_array($is_favorites)) {
            $is_favorites = [];
        }

        // If already favorite, return a warning
        if (in_array($userId, $is_favorites)) {
            return response()->json([
                'status' => false,
                'message' => 'User already in favourites.'
            ]);
        }

        // Add user ID to favorites
        $is_favorites[] = $userId;

        // Save it
        update_user_meta($current_user_id, 'is_favorites', $is_favorites);

        return response()->json([
            'status' => true,
            'message' => 'User added to favourites.',
            'favourites' => $is_favorites
        ]);
    }


    public function addFriends(Request $request){

         $current_user_id = Auth::guard('escort')->user()->id;
        $userId = $request->input('user_id');

        // Get current favorites
        $is_friends = get_user_meta($current_user_id, 'is_friends');

        // Ensure it's an array
        if (!is_array($is_friends)) {
            $is_friends = [];
        }

        // If already favorite, return a warning
        if (in_array($userId, $is_friends)) {
            return response()->json([
                'status' => false,
                'message' => 'User already in friends.'
            ]);
        }

        // Add user ID to favorites
        $is_friends[] = $userId;

        // Save it
        update_user_meta($current_user_id, 'is_friends', $is_friends);

        return response()->json([
            'status' => true,
            'message' => 'User added to friends.',
            'friends' => $is_friends
        ]);

    }


    public function removeFriends(Request $request)
    {
        $current_user_id = Auth::guard('escort')->user()->id;
        $userId = $request->input('user_id');

        // Get current friends
        $is_friends = get_user_meta($current_user_id, 'is_friends');

        // Ensure it's an array
        if (!is_array($is_friends)) {
            $is_friends = [];
        }

        // If user is not in friends list, return a warning
        if (!in_array($userId, $is_friends)) {
            return response()->json([
                'status' => false,
                'message' => 'User is not in friends list.'
            ]);
        }

        // Remove user from friends list
        $is_friends = array_filter($is_friends, function ($id) use ($userId) {
            return $id != $userId;
        });

        // Re-index the array to prevent index gaps
        $is_friends = array_values($is_friends);

        // Save updated list
        update_user_meta($current_user_id, 'is_friends', $is_friends);

        return response()->json([
            'status' => true,
            'message' => 'User removed from friends.',
            'friends' => $is_friends
        ]);
    }


    public function removeFavourites(Request $request)
    {
        $current_user_id = Auth::guard('escort')->user()->id;
        $userId = $request->input('user_id');

        // Get current friends
        $is_favorites = get_user_meta($current_user_id, 'is_favorites');

        // Ensure it's an array
        if (!is_array($is_favorites)) {
            $is_favorites = [];
        }

        // If user is not in friends list, return a warning
        if (!in_array($userId, $is_favorites)) {
            return response()->json([
                'status' => false,
                'message' => 'User is not in favorites list.'
            ]);
        }

        // Remove user from friends list
        $is_favorites = array_filter($is_favorites, function ($id) use ($userId) {
            return $id != $userId;
        });

        // Re-index the array to prevent index gaps
        $is_favorites = array_values($is_favorites);

        // Save updated list
        update_user_meta($current_user_id, 'is_favorites', $is_favorites);

        return response()->json([
            'status' => true,
            'message' => 'User removed from favorites.',
            'friends' => $is_favorites
        ]);
    }


    public function getMessages(Request $request, $userId)
    {
        try {
            // Get the authenticated user
            $currentUser = auth()->guard('escort')->user();
            
            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Validate the other user exists
            $otherUser = User::find($userId);
            if (!$otherUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Get messages between these two users
            $messages = Message::where(function($query) use ($currentUser, $otherUser) {
                    $query->where('sender_id', $currentUser->id)
                          ->where('receiver_id', $otherUser->id);
                })
                ->orWhere(function($query) use ($currentUser, $otherUser) {
                    $query->where('sender_id', $otherUser->id)
                          ->where('receiver_id', $currentUser->id);
                })
                ->with(['sender', 'receiver']) // Eager load relationships
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark any unread messages as read
            Message::where('receiver_id', $currentUser->id)
                   ->where('sender_id', $otherUser->id)
                   ->whereNull('read_at')
                   ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'messages' => $messages->map(function($message) use ($currentUser) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id,
                        'message' => $message->message,
                        'is_me' => $message->sender_id === $currentUser->id,
                        'created_at' => $message->created_at->toDateTimeString(),
                        'read_at' => $message->read_at?->toDateTimeString(),
                        'sender' => [
                            'id' => $message->sender->id,
                            'name' => $message->sender->name,
                            'photo' => $message->sender->photo_url
                        ],
                        'receiver' => [
                            'id' => $message->receiver->id,
                            'name' => $message->receiver->name,
                            'photo' => $message->receiver->photo_url
                        ]
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

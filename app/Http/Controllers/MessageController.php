<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'message' => 'nullable|string',
            'attachment' => 'nullable|string',
            'attachment_type' => 'nullable|string',
        ]);

        Message::create([
            'sender_id' => intval($validated['sender_id']),
            'receiver_id' => intval($validated['receiver_id']),
            'message' => $validated['message'],
            'attachment' => $validated['attachment'],
            'attachment_type' => $validated['attachment_type'],
            'is_read' => false,
        ]);

        return response()->json(['status' => 'success']);
    }


    public function getUserChat(Request $request)
    {
        $currentEscortId = Auth::guard('escort')->id();
        $user_id = $request->input('user_id');

        try {
            $messages = Message::where(function($query) use ($currentEscortId, $user_id) {
                    $query->where('sender_id', $currentEscortId)
                          ->where('receiver_id', $user_id);
                })
                ->orWhere(function($query) use ($currentEscortId, $user_id) {
                    $query->where('sender_id', $user_id)
                          ->where('receiver_id', $currentEscortId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Decryption failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve messages'], 500);
        }
    }


}


<?php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\CallHistory;
use App\Models\Notification;

class ChatController extends Controller
{
    // Get messages between users
    public function getMessages($userId)
    {
        $currentUser = auth()->user();
        $otherUser = User::findOrFail($userId);
        
        $messages = Message::where(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id);
            })
            ->orWhere(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json($messages);
    }
    
    // Send a new message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);
        
        // Dispatch event for real-time update
        event(new NewMessage($message));
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    // Initiate a call
    public function initiateCall(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'call_type' => 'required|in:audio,video'
        ]);
        
        $call = CallHistory::create([
            'caller_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'call_type' => $request->call_type,
            'status' => 'initiated',
            'start_time' => now()
        ]);
        
        // Dispatch event to notify receiver
        event(new IncomingCall($call));
        
        return response()->json([
            'success' => true,
            'call' => $call
        ]);
    }
    
    // Generate PeerJS ID
    public function generatePeerId()
    {
        return response()->json([
            'peer_id' => 'escort_' . auth()->id(),
            'timestamp' => now()
        ]);
    }
    
    // Other methods...
}
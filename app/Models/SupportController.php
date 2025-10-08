<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SupportTicket;
use App\Models\Faq;

class SupportController extends Controller
{
    /**
     * Show support page with FAQs
     */

    public function index()
    {
        // Load normal FAQs (latest first)
        $faqs = Faq::orderBy('created_at', 'desc')->get();

        // Always return support.support if it exists, otherwise fallback to support
        if (view()->exists('support.support')) {
            return view('support.support', compact('faqs'));
        }

        return view('support', compact('faqs'));
    }

    public function submit(Request $request)
    {
        if (auth()->guard('fan')->check()) {
            \Log::info('Fan authenticated, ID: ' . auth()->guard('fan')->id());
        } elseif (auth()->guard('escort')->check()) {
            \Log::info('Escort authenticated, ID: ' . auth()->guard('escort')->id());
        } else {
            \Log::info('Not authenticated (neither fan nor escort)');
        }

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'category' => 'required|string|in:account,listing,billing,safety,technical,other',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:8192|mimes:png,jpg,jpeg,pdf,txt'
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('support-attachments', 'public');
        }

        // Check both guards for user_id
        $userId = null;
        if (auth()->guard('fan')->check()) {
            $userId = auth()->guard('fan')->id();
        } elseif (auth()->guard('escort')->check()) {
            $userId = auth()->guard('escort')->id();
        }
        $data['user_id'] = $userId;
        \Log::info('User ID to save: ' . $userId);

        $ticket = SupportTicket::create($data);

        // Admin email `users` table se fetch karo (role = 'admin' wala)
        $admin = \App\Models\User::where('role', 'admin')->first(); // Pehla admin user fetch
        if ($admin && $admin->email) {
            $adminEmail = $admin->email;
            \Log::info('Admin email fetched from users table: ' . $adminEmail);
            // Email to Admin
            \Notification::route('mail', $adminEmail)->notify(new \App\Notifications\TicketSubmitted($ticket));
        } else {
            \Log::info('No admin found in users table with role "admin"');
        }

        // Email to User
        \Notification::route('mail', $ticket->email)->notify(new \App\Notifications\TicketSubmitted($ticket));

        return redirect()->route('support')
            ->with('status', 'Your ticket has been submitted successfully and notifications have been sent.');
    }
    // Admin view (optional)
    public function adminIndex()
    {
        $tickets = SupportTicket::latest()->paginate(20);
        return view('support.admin.index', compact('tickets'));
    }

    public function searchFaq(Request $request)
    {
        $q = $request->get('q', '');
        $faqs = Faq::where('question', 'like', "%{$q}%")
            ->orWhere('answer', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        return response()->json($faqs);
    }

    //////////////////////////////////////////////////////////////////////////////////////
    ///////////ticket management for admin panel
    //////////////////////////////////////////////////////////////////////////////////////

    // Admin view for tickets
    public function ticketsManage()
    {
        return view('admin.tickets');
    }

    // List tickets for DataTable
    public function ticketsList(Request $request)
    {
        $tickets = SupportTicket::query();

        return datatables()->of($tickets)
            ->addColumn('DT_RowIndex', function ($ticket) {
                return $ticket->id; // Ya row number ke liye meta.row + 1 (see blade change)
            })
            ->addColumn('id', function ($ticket) {
                return $ticket->id; // Ticket ID
            })
            ->addColumn('email', function ($ticket) {
                return $ticket->email; // Email ID
            })
            ->addColumn('category', function ($ticket) {
                return $ticket->category;
            })
            ->addColumn('message', function ($ticket) {
                return $ticket->message;
            })
            ->addColumn('created_at', function ($ticket) {
                return $ticket->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($ticket) {
                return '<button class="btn btn-sm btn-warning viwBtn" data-id="' . $ticket->id . '">View</button>
            <button class="btn btn-sm btn-primary editBtn" data-id="' . $ticket->id . '">Edit</button>
            <button class="btn btn-sm btn-danger delfaqsBtn" data-id="' . $ticket->id . '">Delete</button>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    // Store new ticket (admin side)
    public function ticketsStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'category' => 'required|string|in:account,listing,billing,safety,technical,other',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:8192|mimes:png,jpg,jpeg,pdf,txt',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('support-attachments', 'public');
        }

        $ticket = SupportTicket::create($data);

        // Admin email fetch aur notification
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin && $admin->email) {
            $adminEmail = $admin->email;
            \Log::info('Admin email fetched from users table (from ticketsStore): ' . $adminEmail);

            try {
                \Notification::route('mail', $adminEmail)->notify(new \App\Notifications\TicketSubmitted($ticket));
                \Log::info('Admin notification sent successfully (from ticketsStore)');
            } catch (\Exception $e) {
                \Log::error('Admin notification failed (from ticketsStore): ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => 'Ticket created successfully']);
    }


    // View ticket
    public function viewTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        return response()->json($ticket);
    }

    // Edit ticket
    public function ticketsEdit($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        return response()->json($ticket);
    }

    // Update ticket
    public function ticketsUpdate(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'category' => 'required|string|in:account,listing,billing,safety,technical,other',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:8192|mimes:png,jpg,jpeg,pdf,txt',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('attachment')) {
            if ($ticket->attachment) {
                Storage::disk('public')->delete(str_replace('storage/', '', $ticket->attachment));
            }
            $data['attachment'] = $request->file('attachment')->store('support-attachments', 'public');
        }

        $ticket->update($data);
        return response()->json(['success' => true, 'message' => 'Ticket updated successfully']);
    }

    // Delete ticket
    public function ticketsDestroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        if ($ticket->attachment) {
            Storage::disk('public')->delete(str_replace('storage/', '', $ticket->attachment));
        }
        $ticket->delete();
        return response()->json(['success' => true, 'message' => 'Ticket deleted successfully']);
    }

}

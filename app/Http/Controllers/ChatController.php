<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Chat;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageEvent;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = (string) Auth::id();
        
        // Simple where works for arrays in MongoDB
        $myChats = ChatRoom::with(['chats', 'appointment']) 
                        ->where('participants', $userId) 
                        ->orderByDesc('updated_at')
                        ->get();

        $myChats->transform(function ($room) use ($userId) {
            // Find the "other" person
            $otherId = collect($room->participants)->first(fn($id) => (string)$id !== $userId);
            
            // Ensure we find the user
            $room->other_user = \App\Models\User::find($otherId);

            // UI Helpers
            $room->last_message = $room->last_msg ?? 'No messages yet';
            $room->updated_at_human = $room->updated_at ? $room->updated_at->diffForHumans() : '';
            
            if ($room->chats) {
                $room->chats->each(function($msg) {
                    $msg->created_at_human = $msg->created_at->diffForHumans();
                });
            }
            
            return $room;
        });

        return Inertia::render('Chat', [
            'chats' => $myChats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate - check if validation actually passes
        $validated = $request->validate([
            'room_id' => 'required|string', 
            'message' => 'required|string|min:1'
        ]);

        try {
            // 2. Create the message using the validated data
            $message = new Chat();
            $message->room_id   = (string) $request->room_id;
            $message->sender_id = (string) Auth::id();
            $message->message   = $request->message;
            $message->save(); // Use save() to trigger errors if it fails

            // 3. Update the room (using find to ensure we hit the right document)
            $room = ChatRoom::find($request->room_id);
            if ($room) {
                $room->update([
                    'last_msg'   => $request->message,
                    'updated_at' => now()
                ]);
            }

            // 4. Prepare for Broadcast
            $message->load('user'); 
            $message->created_at_human = $message->created_at->diffForHumans();
            
            event(new ChatMessageEvent($message));

            return redirect()->back();
            
        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['message' => 'Database error.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}

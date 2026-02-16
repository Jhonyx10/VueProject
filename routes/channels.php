<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', function ($user, $id) {
    $currentUserId = (string) ($user->id ?? $user->_id ?? '');
    $authorized = $currentUserId === (string) $id;
    \Illuminate\Support\Facades\Log::info('Channel Auth: user.{id}', [
        'user_id' => $currentUserId,
        'requested_id' => $id,
        'authorized' => $authorized
    ]);
    return $authorized;
});

Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
    $room = \App\Models\ChatRoom::find($roomId);
    if (!$room) return false;

    $userId = (string) ($user->_id ?? $user->id);
    
    // Convert all participants to strings before checking
    $participantIds = array_map(fn($id) => (string)$id, $room->participants);
    
    return in_array($userId, $participantIds);
});

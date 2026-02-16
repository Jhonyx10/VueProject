<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;
use MongoDB\Laravel\Relations\BelongsTo;

class Chat extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'chat_messages';

    protected $fillable = [
        'room_id', 
        'sender_id',    
        'message',  
    ];

     protected $casts = [
        'room_id' => 'string',
        'sender_id' => 'string',
     ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', '_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }
}

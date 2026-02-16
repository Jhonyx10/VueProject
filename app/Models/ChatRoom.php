<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\HasMany;
use MongoDB\Laravel\Relations\BelongsTo;

class ChatRoom extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'chat_rooms';

    protected $fillable = [
        'appointment_id', 
        'participants', 
    ];

    protected $casts = [
        // 'participants' => 'array',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

     public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'room_id', '_id');
    }
}

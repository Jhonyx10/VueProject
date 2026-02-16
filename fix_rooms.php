<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rooms = ChatRoom::all();
foreach ($rooms as $room) {
    if (is_string($room->participants)) {
        echo "Fixing room: " . $room->id . "\n";
        $decoded = json_decode($room->participants, true);
        if (is_array($decoded)) {
            $room->participants = $decoded;
            $room->save();
            echo "Success!\n";
        } else {
            echo "Failed to decode participants for room " . $room->id . "\n";
        }
    } else {
        echo "Room " . $room->id . " already has array participants.\n";
    }
}

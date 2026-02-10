<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;
    
    protected $connection = 'mongodb';

    protected $collection = 'doctors';

    protected $fillable = [
        'user_id',
        'firstName',
        'lastName',
        'age',
        'gender',
        'address',
        'expertise'
    ];

    public function user()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }
}

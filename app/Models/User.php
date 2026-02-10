<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\hasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    // Helper to check if user is a Patient
    public function isPatient(): bool
    {
        return $this->role === 'user';
    }

    public function doctorProfile()
    {
        // This links to the 'doctors' collection we discussed earlier
        return $this->hasOne(Doctor::class, 'user_id');
    }
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

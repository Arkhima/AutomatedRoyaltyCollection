<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'bio',
        'avatar_url',
        'instagram',
        'twitter',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the artist profile associated with this user.
     * This uses the user_id foreign key in the artists table.
     */
    public function artist()
    {
        return $this->hasOne(Artist::class, 'user_id');
    }

    /**
     * Check if the user is an admin.
     * You can modify this logic based on how you identify admins.
     */
    public function getIsAdminAttribute()
    {
        // Option 1: If you have an is_admin column in users table
        // return $this->is_admin ?? false;
        
        // Option 2: If you have a role column
        // return $this->role === 'admin';
        
        // Option 3: Check if user email is in admin list
        $adminEmails = ['admin@example.com']; // Add your admin emails here
        return in_array($this->email, $adminEmails);
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->avatar_url 
            ? asset($this->avatar_url) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1c2541&color=fff';
    }
}
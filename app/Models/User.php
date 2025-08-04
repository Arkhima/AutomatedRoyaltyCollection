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
        'artist_id',
        'phone_number',
        'bio',
        'avatar_url',
        'instagram',
        'twitter',
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

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function getProfilePhotoUrlAttribute()
{
    return $this->avatar_url 
        ? asset($this->avatar_url) 
        : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1c2541&color=fff';
}

}

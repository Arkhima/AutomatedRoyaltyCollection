<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'stage_name',
        'email',
        'phone',
        'bank_details',
        'mpesa_paybill',
        'national_id',
        'profile_image',
        'bio',
        'instagram',
        'twitter',
    ];

    /**
     * Get the user that owns this artist profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tracks for this artist.
     */
    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    /**
     * Get the streams for this artist.
     */
    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    /**
     * Get the payments for this artist.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the royalties for this artist.
     */
    public function royalties()
    {
        return $this->hasMany(Royalty::class);
    }

    /**
     * Get the artist's profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image 
            ? asset($this->profile_image) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? $this->stage_name ?? 'Artist');
    }
}
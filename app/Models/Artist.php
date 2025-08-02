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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }
}

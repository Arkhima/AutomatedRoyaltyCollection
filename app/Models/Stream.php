<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'artist_id',
        'stream_count',
        'as_of_date',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}

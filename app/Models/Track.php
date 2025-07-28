<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isrc_code',
        'artist_id',
        'rate_per_stream',
        'file_path',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
    
    public function streams()
    {
    return $this->hasMany(Stream::class);
    }

}

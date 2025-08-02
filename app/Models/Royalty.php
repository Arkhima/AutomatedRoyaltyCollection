<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Royalty extends Model
{
    // If your table is named differently than the plural 'royalties', specify here:
    // protected $table = 'royalties';

    // Which fields can be mass assigned
    protected $fillable = [
        'track_id',
        'artist_id',
        'total_streams',
        'rate_per_stream',
        'total_royalty',
        'calculation_date', // date when the royalty was calculated or earned
    ];

    // Disable timestamps if your table does not have created_at or updated_at
    public $timestamps = false;

    // Cast calculation_date to a datetime (Carbon instance)
    protected $casts = [
        'calculation_date' => 'datetime',
    ];

    // Relationships

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}

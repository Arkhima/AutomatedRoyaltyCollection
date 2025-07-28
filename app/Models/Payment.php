<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'artist_id',
        'amount',
        'method',
        'status',
        'requested_at',
        'processed_at',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}

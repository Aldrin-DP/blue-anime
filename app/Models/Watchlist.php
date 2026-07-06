<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    protected $fillable = ['user_id', 'anime_id'];

    public function anime(): BelongsTo 
    {
        return $this->belongsTo(Watchlist::class);
    }
}

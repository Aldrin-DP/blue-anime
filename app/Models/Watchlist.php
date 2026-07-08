<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'status', 'progress', 'notes', 'is_favorite'];

    public function anime(): BelongsTo 
    {
        return $this->belongsTo(Watchlist::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

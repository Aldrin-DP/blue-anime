<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'status', 'progress', 'notes', 'is_favorite', 'completed_at'];

    public function anime(): BelongsTo 
    {
        return $this->belongsTo(AnimeCache::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    protected $casts = [
        'completed_at' => 'datetime',
        'is_favorite' => 'boolean'
    ];
}

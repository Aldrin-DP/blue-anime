<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchHistory extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'episode', 'current_time', 'duration', 'is_completed'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);        
    }

    public function anime(): BelongsTo 
    {
        return $this->belongsTo(AnimeCache::class);
    }
}

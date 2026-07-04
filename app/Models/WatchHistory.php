<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'episode', 'current_time', 'completed_at'];

    public function user() {
        return $this->belongsTo(User::class);        
    }

    public function anime() {
        return $this->belongsTo(AnimeCache::class);
    }
}

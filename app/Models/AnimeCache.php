<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimeCache extends Model
{
    protected $fillable = ['api_id', 'title', 'format', 'cover_image', 'banner_image'];

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }
    public function watch_histories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimeCache extends Model
{
    protected $fillable = [
        'api_id', 
        'title', 
        'romaji_title',
        'description',
        'format', 
        'cover_image', 
        'banner_image', 
        'score', 
        'season', 
        'season_year',
        'genres', 
        'episodes',
        'status',
        'popularity',
        'country_of_origin'
    ];

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }
    public function watch_histories(): HasMany
    {
        return $this->hasMany(WatchHistory::class, 'anime_id');
    }

    
    protected $casts = [
        'genres' => 'array'
    ];
}

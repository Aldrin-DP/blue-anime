<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeCache extends Model
{
    protected $fillable = ['api_id', 'title', 'format', 'cover_image'];

    public function watchlists(){
        return $this->hasMany(Watchlist::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $fillable = ['user_id', 'anime_id'];

    public function anime() {
        return $this->belongsTo(Watchlist::class);
    }
}

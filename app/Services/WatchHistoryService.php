<?php

namespace App\Services;

use App\Models\WatchHistory;

class WatchHistoryService
{
    public function getWatchHistoryIds() 
    {
        $user = auth()->user();

        return WatchHistory::where('user_id', $user->id)
            ->distinct()
            ->pluck('anime_id')
            ->toArray();   
    }
}

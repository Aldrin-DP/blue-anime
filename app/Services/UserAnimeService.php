<?php

namespace App\Services;

use App\Models\User;
use App\Models\WatchHistory;
use App\Models\Watchlist;

class UserAnimeService
{
    public function isFavorited(int $animeId, ?User $user): bool 
    {
        if (!$user) {
            return false;
        }

        return Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->where('is_favorite', true)
            ->exists();
    }

    public function isInWatchlist(int $animeId, ?User $user): bool 
    {
        if (!$user) {
            return false;
        }
  
        return Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->exists();
    }

    public function getUserAnimeStatus(int $animeId, ?User $user): ?string 
    {
        if (!$user) {
            return null;
        }
            
        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->first();

        if (!$watchlist) {
            return null;
        }
        
        return $watchlist->status;
    }

    public function getEpisodeProgress(int $animeId, ?User $user): array 
    {
        if (!$user) {
            return [];
        }

        $watchedHistories = WatchHistory::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->get();
            
        
        return array_map(fn($episode) => [
                'episode' => $episode['episode'],
                'currentTime' => $episode['current_time'],
                'progress' => $episode['duration']
                    ?($episode['current_time'] / $episode['duration']) * 100
                    : 0, 
                'isCompleted' => $episode['is_completed']
            ], $watchedHistories->toArray()
        );

    }

}

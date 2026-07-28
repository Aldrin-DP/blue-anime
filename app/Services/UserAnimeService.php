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

    public function getContinueWatchingList(int $userId): array
    {   
        $continueWatchingList = [];

        $watchHistory = WatchHistory::where('user_id', $userId)
            ->where('is_completed', false)
            ->where('hidden_from_continue_watching', false)
            ->with('anime')
            ->latest('updated_at')
            ->get()
            ->unique('anime_id')
            ->values();

            
            foreach ($watchHistory as $index => $watchItem) {
                $episode = $watchItem['episode'];
                $progress = $watchItem->duration
                    ? ($watchItem->current_time / $watchItem->duration) * 100
                    : 0;

                $continueWatchingList[$index]['id'] = $watchItem->id;
                $continueWatchingList[$index]['title'] = $watchItem->anime->title;
                $continueWatchingList[$index]['episode'] = $episode;
                $continueWatchingList[$index]['progress'] = $progress;
                $continueWatchingList[$index]['bannerImage'] = $watchItem->anime->banner_image;
                $continueWatchingList[$index]['api_id'] = $watchItem->anime->api_id;
                $continueWatchingList[$index]['coverImage'] = $watchItem->anime->cover_image;
            }

        return $continueWatchingList;
    }

}

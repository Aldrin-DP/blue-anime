<?php

namespace App\Services;

use App\Models\User;
use App\Models\WatchHistory;

class WatchHistoryService
{
    public function getWatchHistoryIds(User $user) 
    {
        return WatchHistory::where('user_id', $user->id)
            ->distinct()
            ->pluck('anime_id')
            ->toArray();   
    }

    public function saveWatchHistory(
        User $user, 
        int $animeId, 
        int $episode, 
        float $currentTime, 
        int $duration, 
        bool $isCompleted
    ){
        WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id, 
                'anime_id' => $animeId, 
                'episode' => $episode
            ],
            [
                'current_time' => $currentTime, 
                'duration' => $duration, 
                'is_completed' => $isCompleted
            ]
        );
    }

    public function addNextEpisodeToContinueWatching(
        User $user, 
        int $animeId,
        int $episode,
        int $totalEpisode,
        float $watchedPercentage,
        ?int $existingNextEpisode

    ){  
        if ($watchedPercentage >= 90 && $episode < $totalEpisode) {
            if ((($episode+1) === $existingNextEpisode) && $existingNextEpisode !== null){ 
                return;
            }
            $nextEpisode = $episode + 1;

            $history = WatchHistory::firstOrCreate(
                ['user_id' => $user->id, 'anime_id' => $animeId, 'episode' => $nextEpisode],
                ['current_time' => 0, 'duration' => 0, 'is_completed' => false, 'hidden_from_continue_watching' => false]
            );

            $history->update([
                'hidden_from_continue_watching' => false
            ]);
        }
    }
}

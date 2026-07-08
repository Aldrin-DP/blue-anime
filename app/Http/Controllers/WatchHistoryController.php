<?php

namespace App\Http\Controllers;

use App\Models\WatchHistory;
use App\Services\AnimeService;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function save(Request $request, int $anilistId, int $episode, AnimeService $animeService) 
    {
        $cachedAnime = $animeService->getOrCacheAnime($anilistId);
  
        $user = $request->user();

        $currentTime = $request->input('currentTime');
        $duration = $request->input('duration');
        $isCompleted = $request->input('isCompleted');

        WatchHistory::updateOrCreate(
            ['user_id' => $user->id, 'anime_id' => $cachedAnime->id, 'episode' => $episode],
            ['current_time' => $currentTime, 'duration' => $duration, 'is_completed' => $isCompleted]
        );
    }

    public function update(Request $request, int $anilistId, int $episode, AnimeService $animeService) {

        $user = $request->user();

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);

        WatchHistory::updateOrCreate([
                'user_id' => $user->id, 
                'anime_id' => $cachedAnime->id, 
                'episode' => $episode 
            ],
            [
                'current_time' => $request->input('currentTime'), 
                'duration' => $request->input('duration'), 
                'is_completed' => $request->input('isCompleted')
            ]
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Services\AnilistService;
use App\Services\AnimeService;
use App\Services\StreamingService;
use App\Services\UserAnimeService;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function show(AnilistService $anilistService, StreamingService $streamingService, AnimeService $animeService, UserAnimeService $userAnimeService, int $anilistId, int $episode, Request $request) 
    {
        $user = auth()->user();
        
        $isWatched = $request->input('isWatched');

        $anime = $anilistService->getAnime($anilistId);

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);
        $episodeData = $streamingService->getEpisode($anilistId, $episode);
              
        $episodeProgress = $userAnimeService->getEpisodeProgress($cachedAnime->id, $user); 
        
        $resumeTime = collect($episodeProgress)->firstWhere('episode', $episode);
        if (!$resumeTime){
            $resumeTime = 0;
        } else {
            $resumeTime = $resumeTime['currentTime'];
        }

        return inertia('Episode/Show', [
            'anime' => $anime['data']['Media'],
            'episodeData' => $episodeData,
            'currentEpisode' => $episode,
            'resumeTime' => $resumeTime,
            'episodesProgress' => $episodeProgress
        ]);
    }
}

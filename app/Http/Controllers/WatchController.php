<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Services\AnilistService;
use App\Services\StreamingService;


class WatchController extends Controller
{
    public function show(StreamingService $streamingService, AnilistService $anilistService, int $anilistId, int $episode) {

        $episodeData = $streamingService->getEpisode($anilistId, $episode);

        $anime = $anilistService->getAnime($anilistId);

        $userId = auth()->id();

        $watched = null;

        $cachedAnime = AnimeCache::firstOrCreate(
            [ 'api_id' => $anilistId, ],
            [
                'title' => $anime['data']['Media']['title']['english'],
                'format' => $anime['data']['Media']['format'],
                'cover_image' => $anime['data']['Media']['coverImage']['extraLarge']
            ]
        );

        if ($userId) {
            $watched = WatchHistory::where('user_id', $userId)->where('anime_id', $cachedAnime->id)->where('episode', $episode)->first();        
        }   

        return inertia('Episode/Show', [
            'anime' => $anime['data']['Media'],
            'episodeData' => $episodeData,
            'currentEpisode' => $episode,
            'resumeTime' => $watched ? $watched->current_time : 'null'
        ]);
    }
}

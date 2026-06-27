<?php

namespace App\Http\Controllers;

use App\Services\AnilistService;
use App\Services\StreamingService;


class WatchController extends Controller
{
    public function show(StreamingService $streamingService, AnilistService $anilistService, int $animeId, int $episode) {

        $episodeData = $streamingService->getEpisode($animeId, $episode);

        $anime = $anilistService->getAnime($animeId);

        return inertia('Episode/Show', [
            'anime' => $anime['data']['Media'],
            'episodeData' => $episodeData
        ]);
    }
}

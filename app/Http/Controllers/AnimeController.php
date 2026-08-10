<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AnilistService;
use App\Services\AnimeService;
use App\Services\UserAnimeService;

class AnimeController extends Controller
{
    public function show(
        int $anilistId, 
        AnilistService $anilistService, 
        AnimeService $animeService, 
        UserAnimeService $userAnimeService
    ){
        $user = auth()->user();

        $anime = $anilistService->getAnime($anilistId);

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);
        $inWatchlist = $userAnimeService->isInWatchlist($cachedAnime->id, $user);
        $episodeProgress = $userAnimeService->getEpisodeProgress($cachedAnime->id, $user);
        $status = $userAnimeService->getUserAnimeStatus($cachedAnime->id, $user);
        
        $isFavorited = $userAnimeService->isFavorited($cachedAnime->id, $user);

        $continueWatchingList = [];

        if ($user) {
            $continueWatchingList = $userAnimeService->getContinueWatchingList($user);
        }
        $continueLastWatched = array_find($continueWatchingList, fn($anime) => $anime['api_id'] === $anilistId);
        
        $continueLastWatchedEpisode = $continueLastWatched['episode'] ?? null;
        
        return inertia('Anime/Show', [
            'anime' => $anime['data']['Media'],
            'inWatchlist' => $inWatchlist,
            'episodesProgress' => $episodeProgress,
            'status' => $status,
            'isFavorited' => $isFavorited,
            'continueLastWatchedEpisode' => $continueLastWatchedEpisode
        ]);
    }

}

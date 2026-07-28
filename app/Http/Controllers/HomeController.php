<?php

namespace App\Http\Controllers;

use App\Services\AnilistService;
use App\Services\AnimeService;
use App\Services\UserAnimeService;
use App\Services\WatchHistoryService;

class HomeController extends Controller
{
    public function home(
        AnilistService $anilistService, 
        AnimeService $animeService, 
        UserAnimeService $userAnimeService,
        WatchHistoryService $watchHistoryService)
    {
        $user = auth()->user();

        $newEpisodes = $anilistService->getNewEpisodes();
        $popularAnime = $animeService->getPopularAnime();
        $trendingAnime = $animeService->getTrendingAnime();
        $topRatedAnime = $animeService->getTopRatedAnime();
        $featuredAnime = $animeService->getProcessedFeaturedAnime();
    
        $continueWatchingList = [];
        $watchHistoryIds = [];

        if ($user) {
            $watchHistoryIds = $watchHistoryService->getWatchHistoryIds();    
            $continueWatchingList = $userAnimeService->getContinueWatchingList($user->id);
        }

        return inertia('Home', [
            'newEpisodes' => $newEpisodes,
            'trendingAnime' => $trendingAnime,
            'popularAnime' => $popularAnime,
            'topRatedAnime' => $topRatedAnime,
            'continueAnime' => $continueWatchingList,
            'featuredAnime' => $featuredAnime,
            'watchHistoryIds' => $watchHistoryIds
        ]);
    }
    
}

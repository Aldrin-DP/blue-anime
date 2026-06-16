<?php

namespace App\Http\Controllers;

use App\Services\AnilistService;

class HomeController extends Controller
{
    public function home(AnilistService $anilistService)
    {
        return inertia('Home', [
            'trendingAnime' => $anilistService->getTrending(),
            'newEpisodes' => $anilistService->getNewEpisodes(),
            'popularAnime' => $anilistService->getPopular(),
            'topRatedAnime' => $anilistService->getTopRated()
        ]);
    }
}

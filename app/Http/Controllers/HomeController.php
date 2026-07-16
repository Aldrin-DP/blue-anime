<?php

namespace App\Http\Controllers;

use App\Models\WatchHistory;
use App\Services\AnilistService;

class HomeController extends Controller
{
    public function home(AnilistService $anilistService)
    {
        $user = auth()->user();

        $continueWatchingList = [];

        if ($user) {
            $watchHistory = WatchHistory::where('user_id', $user->id)
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
            }
        }

        return inertia('Home', [
            'trendingAnime' => $anilistService->getTrending(),
            'newEpisodes' => $anilistService->getNewEpisodes(),
            'popularAnime' => $anilistService->getPopular(),
            'topRatedAnime' => $anilistService->getTopRated(),
            'continueAnime' => $continueWatchingList
        ]);
    }
}

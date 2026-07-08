<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Services\AnilistService;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function home(AnilistService $anilistService)
    {
        $user = auth()->user();

        $continueWatchingList = [];

        if ($user) {
           $watchHistory = WatchHistory::where('user_id', $user->id)
                ->where('is_completed', false)
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

                // if ($watchItem->is_completed){
                //     $nextEpisode = $watchItem->episode + 1;
                //     $anilistId = $watchItem->anime->api_id;
                //     $response =  Http::get("https://anivexa-api-navy.vercel.app/watch/anikoto/$anilistId/sub/anikoto-$nextEpisode");
                //     // dd([
                //     //     'status' => $response->status(),
                //     //     'successful' => $response->successful(),
                //     //     'body' => $response->body(),
                //     // ]);
                //     if ($response->successful()){
                //         $episode = $episode + 1;
                //         $progress = 0;
                //     } else {
                //         continue;
                //     }
                // }
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

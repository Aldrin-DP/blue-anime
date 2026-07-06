<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Models\Watchlist;
use App\Services\AnilistService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function show(Request $request, int $animeId, AnilistService $anilistService)
    {
        $anime = $anilistService->getAnime($animeId);

        $user = $request->user();

        $inWatchlist = false;

        $episodesProgress = [];

        $cachedAnime = AnimeCache::firstOrCreate(
            [ 'api_id' => $animeId, ],
            [
                'title' => $anime['data']['Media']['title']['english'],
                'format' => $anime['data']['Media']['format'],
                'cover_image' => $anime['data']['Media']['coverImage']['extraLarge'],
                'banner_image' => $anime['data']['Media']['bannerImage']
            ]
        );

        if ($user){
            $watchlistItem = AnimeCache::where('api_id', $animeId)->first();

            if ($watchlistItem) {
                $inWatchlist = Watchlist::where('user_id', $user->id)
                    ->where('anime_id', $watchlistItem->id)
                    ->exists();
            }

            $watchedHistories = WatchHistory::where('user_id', $user->id)->where('anime_id', $cachedAnime->id)->get();
            
            $episodesProgress = 
                array_map(fn($episode) => 
                [
                    'episode' => $episode['episode'],
                    'progress' => ($episode['current_time'] / $episode['duration']) * 100
                ], 
                $watchedHistories->toArray()
            );
        }

        return inertia('Anime/Show', [
            'anime' => $anime['data']['Media'],
            'inWatchlist' => $inWatchlist,
            'episodesProgress' => $episodesProgress
        ]);
    }
}

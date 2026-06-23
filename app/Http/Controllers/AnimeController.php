<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
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

        if ($user){
            $cachedAnime = AnimeCache::where('api_id', $animeId)->first();

            if ($cachedAnime) {
                $inWatchlist = Watchlist::where('user_id', $user->id)
                    ->where('anime_id', $cachedAnime->id)
                    ->exists();
            }
        }

        return inertia('Anime/Show', [
            'anime' => $anime,
            'inWatchlist' => $inWatchlist
        ]);
    }
}

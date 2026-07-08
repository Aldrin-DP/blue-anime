<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\Watchlist;
use App\Services\AnimeService;
use App\Services\UserAnimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function store(Request $request, AnimeService $animeService): RedirectResponse
    {
        $user = $request->user();

        $cachedAnime = $animeService->getOrCacheAnime($request->anilistId);

        Watchlist::create([
            'user_id' => $user->id,
            'anime_id' => $cachedAnime->id
        ]);

        return back()->with('success', 'Added to Watchlists');
    }

    public function patch(Request $request, int $anilistId, AnimeService $animeService, UserAnimeService $userAnimeService)
    {
        $user = auth()->user();

        $request->validate([
            'status' => ['required', 'in:watching,plan_to_watch,completed,dropped'],
        ]);

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);

        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('anime_id', $cachedAnime->id)
            ->first();

        switch($request->input('status')){
            case 'watching':
                $progress = count($userAnimeService->getEpisodeProgress($cachedAnime->id, $user));
                $watchlist->update([
                    'status' => $request->input('status'),
                    'progress' => $progress
                ]);
                break;
            case 'plan_to_watch':
                $watchlist->update([
                    'status' => $request->input('status')
                ]);
                break;
            case 'completed':
                break;
            case 'dropped':
                break;
        }

        return back()->with('status-updated', 'Updated status');
    }
}

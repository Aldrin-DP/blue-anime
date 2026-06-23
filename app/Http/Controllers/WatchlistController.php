<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $cachedAnime = AnimeCache::firstOrCreate(
            [ 'api_id' => $request->api_id ],
            [
                'title' => $request->title,
                'format' => $request->format,
                'cover_image' => $request->cover_image
            ]
        );

        $watchlistEntry = Watchlist::where('user_id', $user->id)
            ->where('anime_id', $cachedAnime->id)
            ->first();

        if ($watchlistEntry){
            $watchlistEntry->delete();
            return back()->with('success', 'Removed');
        }

        Watchlist::create([
            'user_id' => $user->id,
            'anime_id' => $cachedAnime->id
        ]);

        return back()->with('success', 'Added');
    }
}

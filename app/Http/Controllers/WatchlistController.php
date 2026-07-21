<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Models\Watchlist;
use App\Services\AnimeService;
use App\Services\UserAnimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class WatchlistController extends Controller
{   
    public function index() {

        $user = auth()->user();

        $watchlists = Watchlist::with([
            'anime', 
            'anime.watch_histories' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('current_time', '>', 0);
            }])
        ->where('user_id', $user->id)
        ->latest('updated_at')
        ->get();

        $userWatchlists = $watchlists->map(fn($watchlist) => [
            'id' => $watchlist->id,
            'anilistId' => $watchlist->anime->api_id,
            'status' => $watchlist->status,
            'progress' => $watchlist->progress,
            'isFavorite' => $watchlist->is_favorite,
            'title' => $watchlist->anime->title,
            'format' => $watchlist->anime->format,
            'coverImage' => $watchlist->anime->cover_image,
            'score' => $watchlist->anime->score,
            'genres' => $watchlist->anime->genres,
            'episodes' => $watchlist->anime->episodes,
            'lastWatchedEpisode' => $watchlist->anime->watch_histories->last()?->episode,
            'lastWatched' => $watchlist->anime->watch_histories->last()?->duration ? 
                    ($watchlist->anime->watch_histories->last()?->current_time / $watchlist->anime->watch_histories->last()?->duration * 100) : null,
            'completed_at' => $watchlist->completed_at?->format('M d, Y')
            ]);

        return inertia('Watchlist/Index', [
            'watchlists' => $userWatchlists
        ]);
    }

    public function store(Request $request, AnimeService $animeService): RedirectResponse
    {
        $user = $request->user();

        $cachedAnime = $animeService->getOrCacheAnime($request->anilistId);

        Watchlist::create([
            'user_id' => $user->id,
            'anime_id' => $cachedAnime->id,
            'status' => 'plan_to_watch'
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
                $watchlist->update([
                    'status' => $request->input('status'),
                    'completed_at' => now()
                ]);
                break;
            case 'dropped':
                $watchlist->update([
                    'status' => $request->input('status'),
                ]);
                break;
        }

        return back()->with('status-updated', 'Updated status');
    }

    public function toggleFavorite(Request $request, int $anilistId, AnimeService $animeService) 
    {
        $user = $request->user();

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);

        $watchlist = Watchlist::firstOrNew([
            'user_id' => $user->id,
            'anime_id' => $cachedAnime->id
        ]);

        if (!$watchlist->exists) {
            $watchlist->status = 'plan_to_watch';
        }

        $watchlist->is_favorite = !$watchlist->is_favorite;
        $watchlist->save();

        return back();
    }   

    public function destroy(int $id)
    {
        $user = auth()->user();
        
        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        
        $watchlist->delete();

        return back()->with('success', 'Watchlist removed');
    }
}

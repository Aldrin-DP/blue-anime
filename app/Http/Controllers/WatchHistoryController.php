<?php

namespace App\Http\Controllers;

use App\Models\WatchHistory;
use App\Models\Watchlist;
use App\Services\AnimeService;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function save(Request $request, int $anilistId, int $episode, AnimeService $animeService) 
    {
        $cachedAnime = $animeService->getOrCacheAnime($anilistId);
  
        $user = $request->user();

        $currentTime = $request->input('currentTime');
        $duration = $request->input('duration');
        $isCompleted = $request->input('isCompleted');

        $watchedPercentage = ($currentTime / $duration) * 100;
        
        if ($watchedPercentage >= 60) {

            $inWatchlists = Watchlist::where('user_id', $user->id)
                ->where('anime_id', $cachedAnime->id)
                ->first();

            if (!$inWatchlists){
                Watchlist::create([
                    'user_id' => $user->id,
                    'anime_id' => $cachedAnime->id,
                    'status' => 'watching',
                    'progress' => $watchedPercentage,
                ]);
            } elseif ($inWatchlists->status === 'plan_to_watch'){
                $inWatchlists->status = 'watching';
                $inWatchlists->progress = $watchedPercentage;
                $inWatchlists->save();
            } elseif ($inWatchlists->status === 'watching') {
                $inWatchlists->progress = $watchedPercentage;
                $inWatchlists->save();
            } else {
                // status completed or dropped
            }
        }

        WatchHistory::updateOrCreate(
            ['user_id' => $user->id, 'anime_id' => $cachedAnime->id, 'episode' => $episode],
            ['current_time' => $currentTime, 'duration' => $duration, 'is_completed' => $isCompleted]
        );

        if ($watchedPercentage >= 90 && $episode < $cachedAnime->episodes) {
            $nextEpisode = $episode + 1;

            WatchHistory::firstOrCreate(
                ['user_id' => $user->id, 'anime_id' => $cachedAnime->id, 'episode' => $nextEpisode],
                ['current_time' => 0, 'duration' => 0, 'is_completed' => false]);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, int $anilistId, int $episode, AnimeService $animeService) 
    {
        $user = $request->user();

        $cachedAnime = $animeService->getOrCacheAnime($anilistId);

        WatchHistory::updateOrCreate([
                'user_id' => $user->id, 
                'anime_id' => $cachedAnime->id, 
                'episode' => $episode 
            ],
            [
                'current_time' => $request->input('currentTime'), 
                'duration' => $request->input('duration'), 
                'is_completed' => $request->input('isCompleted')
            ]
        );

        return response()->json(['success' => true]);
    }

    public function hide(int $id) 
    {
        $user = auth()->user();

        $watchItem = WatchHistory::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        $watchItem->hidden_from_continue_watching = true;
        $watchItem->save();

        return back();
    }
}


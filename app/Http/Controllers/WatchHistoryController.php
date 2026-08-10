<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveWatchHistoryRequest;
use App\Models\WatchHistory;
use App\Models\Watchlist;
use App\Services\AnimeService;
use App\Services\UserAnimeService;
use App\Services\WatchHistoryService;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{   
    public function __construct(
        private AnimeService $animeService, 
        private UserAnimeService $userAnimeService,
        private WatchHistoryService $watchHistoryService
    ){}

    public function save(SaveWatchHistoryRequest $request, int $anilistId, int $episode) 
    {
        $user = $request->user();

        $validated = $request->validated();

        $duration = $validated['duration'];
        $currentTime = $validated['currentTime'];
        $isCompleted = $validated['isCompleted'];

        $watchedPercentage = $duration > 0
            ? ($currentTime / $duration) * 100
            : 0;

        $cachedAnime = $this->animeService->getOrCacheAnime($anilistId);  

        $this->userAnimeService->updateWatchlistProgress($user, $cachedAnime->id, $watchedPercentage);

        $this->watchHistoryService->saveWatchHistory(
            $user,
            $cachedAnime->id,
            $episode,
            $currentTime,
            $duration,
            $isCompleted
        );   
        
        $this->watchHistoryService->addNextEpisodeToContinueWatching(
            $user,
            $cachedAnime->id,
            $episode,
            $cachedAnime->episodes,
            $watchedPercentage,
            $cachedAnime->next_episode
        );

        $this->userAnimeService->markAnimeAsCompleted(
            $user,
            $cachedAnime->id,
            $episode,
            $cachedAnime->episodes,
            $watchedPercentage
        );

        return response()->json([
            'message' => 'Watch history updated'
        ]);
    }

    public function update(SaveWatchHistoryRequest $request, int $anilistId, int $episode)
    {
        $user = $request->user();
        
        $validated = $request->validated();

        $duration = $validated['duration'];
        $currentTime = $validated['currentTime'];
        $isCompleted = $validated['isCompleted'];

        $cachedAnime = $this->animeService->getOrCacheAnime($anilistId);

        $this->userAnimeService->toggleWatchedStatus(
            $user,
            $cachedAnime->id,
            $episode,
            $currentTime,
            $duration,
            $isCompleted
        );
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

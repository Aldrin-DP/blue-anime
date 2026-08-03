<?php

namespace App\Services;

use App\Models\User;
use App\Models\WatchHistory;
use App\Models\Watchlist;

class UserAnimeService
{
    public function isFavorited(int $animeId, ?User $user): bool 
    {
        if (!$user) {
            return false;
        }

        return Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->where('is_favorite', true)
            ->exists();
    }

    public function isInWatchlist(int $animeId, ?User $user): bool 
    {
        if (!$user) {
            return false;
        }
  
        return Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->exists();
    }

    public function getUserAnimeStatus(int $animeId, ?User $user): ?string 
    {
        if (!$user) {
            return null;
        }
            
        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->first();

        if (!$watchlist) {
            return null;
        }
        
        return $watchlist->status;
    }

    public function getEpisodeProgress(int $animeId, ?User $user): array 
    {
        if (!$user) {
            return [];
        }

        $watchedHistories = WatchHistory::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->get();
            
        
        return array_map(fn($episode) => [
                'episode' => $episode['episode'],
                'currentTime' => $episode['current_time'],
                'progress' => $episode['duration']
                    ?($episode['current_time'] / $episode['duration']) * 100
                    : 0, 
                'isCompleted' => $episode['is_completed']
            ], $watchedHistories->toArray()
        );

    }

    public function getContinueWatchingList(User $user): array
    {   
        $continueWatchingList = [];

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
                $continueWatchingList[$index]['coverImage'] = $watchItem->anime->cover_image;
            }

        return $continueWatchingList;
    }

    public function getUserWatchlists(int $userId): array 
    {
        $userWatchlists = [];

        $watchlists = Watchlist::with([
            'anime', 
            'anime.watch_histories' => function($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('current_time', '>', 0);
            }])
        ->where('user_id', $userId)
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
        ])->toArray();

        return $userWatchlists;
    }

    public function updateWatchlistProgress(User $user, int $animeId, float $watchedPercentage): void
    {
        if ($watchedPercentage < 60) {
            return;
        }
        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('anime_id', $animeId)
            ->first();

        if (!$watchlist) {
            Watchlist::create([
                'user_id' => $user->id,
                'anime_id' => $animeId,
                'status' => 'watching',
                'progress' => $watchedPercentage,
            ]);
        } elseif ($watchlist->status === 'plan_to_watch') {
            $watchlist->status = 'watching';
            $watchlist->progress = $watchedPercentage;
            $watchlist->save();
        } elseif ($watchlist->status === 'watching') {
            $watchlist->progress = $watchedPercentage;
            $watchlist->save();
        } else {
            // status completed or dropped
        }
        
    }

    public function markAnimeAsCompleted(
        User $user,
        int $animeId,
        int $episode,
        int $totalEpisode,
        float $watchedPercentage
    ): void 
    {
        if ($watchedPercentage >= 90 && $episode === $totalEpisode) {
            $inWatchlists = Watchlist::where('user_id', $user->id)
                ->where('anime_id', $animeId)
                ->first();

            if ($inWatchlists) {
                $inWatchlists->status = 'completed';
                $inWatchlists->completed_at = now();
                $inWatchlists->save();
            }
        }
    }

}

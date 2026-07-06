<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use App\Services\AnilistService;
use App\Services\StreamingService;


class WatchController extends Controller
{
    public function show(StreamingService $streamingService, AnilistService $anilistService, int $anilistId, int $episode) {

        $episodeData = $streamingService->getEpisode($anilistId, $episode);
        
        $anime = $anilistService->getAnime($anilistId);
      
        $userId = auth()->id();

        $watched = null;
        $episodesProgress = null;

        $cachedAnime = AnimeCache::firstOrCreate(
            [ 'api_id' => $anilistId, ],
            [
                'title' => $anime['data']['Media']['title']['english'],
                'format' => $anime['data']['Media']['format'],
                'cover_image' => $anime['data']['Media']['coverImage']['extraLarge'],
                'banner_image' => $anime['data']['Media']['bannerImage']
            ]
        );

        if ($userId) {
            $watched = WatchHistory::where('user_id', $userId)->where('anime_id', $cachedAnime->id)->where('episode', $episode)->first();        
            $watchedHistories = WatchHistory::where('user_id', $userId)->where('anime_id', $cachedAnime->id)->get();
            
            $episodesProgress = 
                array_map(fn($episode) => 
                [
                    'episode' => $episode['episode'],
                    'progress' => ($episode['current_time'] / $episode['duration']) * 100
                ], 
                $watchedHistories->toArray()
            );
        }   
        
        return inertia('Episode/Show', [
            'anime' => $anime['data']['Media'],
            'episodeData' => $episodeData,
            'currentEpisode' => $episode,
            'resumeTime' => $watched ? $watched->current_time : 'null',
            'episodesProgress' => $episodesProgress ? $episodesProgress : 'null'
        ]);
    }
}

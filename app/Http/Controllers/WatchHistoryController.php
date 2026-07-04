<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function save(Request $request, int $animeId, int $episode) {

        $user = $request->user();

        $title = $request->input('title');
        $format = $request->input('format');
        $coverImage = $request->input('coverImage');
        $currentTime = $request->input('currentTime');
        $isCompleted = $request->input('isCompleted');

        $cachedAnime = AnimeCache::firstOrCreate(
            [ 'api_id' => $animeId ],
            [
                'title' => $title, 
                'format' => $format, 
                'cover_image' => $coverImage
            ]
        );

        WatchHistory::updateOrCreate(
            ['user_id' => $user->id, 'anime_id' => $cachedAnime->id, 'episode' => $episode],
            ['current_time' => $currentTime, 'is_completed' => $isCompleted]
        );
    }
}

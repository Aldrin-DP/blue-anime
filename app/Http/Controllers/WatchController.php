<?php

namespace App\Http\Controllers;

use App\Services\StreamingService;


class WatchController extends Controller
{
    public function show(StreamingService $streamingService, int $id, int $episode) {


        $episodeData = $streamingService->getEpisode($id, $episode);

        // dd($episodeData);

        // https://anivexa-api-navy.vercel.app/proxy?url=&ref=https%3A%2F%2Fmegaplay.buzz%2F;

        return inertia('Episode/Show', [
            'episodeData' => $episodeData
        ]);
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class StreamingService
{
    public function getEpisode(int $id, int $episode) {
        return Cache::remember("$id-episode-$episode", now()->addHours(6), function () use ($id, $episode) {
            $response =  Http::get("https://anivexa-api-navy.vercel.app/watch/anikoto/$id/sub/anikoto-$episode");

            if ($response->failed()) {
                throw new \Exception("Failed to fetch episode $episode");
            }

            $response->json();

            $episodeLinks = collect($response['ssub']['streams'])
                ->first(fn($link) => $link['default'] === true);

            return ['episodeUrl' => $episodeLinks['url'], 'refererUrl' => $episodeLinks['referer']];
        });
    }
}

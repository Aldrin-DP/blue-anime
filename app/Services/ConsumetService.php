<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ConsumetService
{
    public function getEpisodes(int $animeId) {
        $response = Http::get("https://api.consumet.org/meta/anilist/info/{$animeId}");


        return $response->json()['episodes'] ?? [];
    }   
}

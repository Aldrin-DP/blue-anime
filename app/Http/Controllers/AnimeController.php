<?php

namespace App\Http\Controllers;

use App\Services\AnilistService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function show(Request $request, int $animeId, AnilistService $anilistService)
    {

        $anime = $anilistService->getAnime($animeId);

        return inertia('Anime/Show', [
            'data' => $anime,
        ]);
    }
}

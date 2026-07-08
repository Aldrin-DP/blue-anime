<?php

namespace App\Services;

use App\Models\AnimeCache;

class AnimeService
{

    protected AnilistService $anilistService;

    public function __construct(AnilistService $anilistService)
    {
        $this->anilistService = $anilistService;
    }

    public function getOrCacheAnime(int $anilistId) {

        $anime = $this->anilistService->getAnime($anilistId);

        return AnimeCache::firstOrCreate(
            [ 'api_id' => $anilistId, ],
            [
                'title' => $anime['data']['Media']['title']['english'],
                'format' => $anime['data']['Media']['format'],
                'cover_image' => $anime['data']['Media']['coverImage']['extraLarge'],
                'banner_image' => $anime['data']['Media']['bannerImage']
            ]
        );
    }
}

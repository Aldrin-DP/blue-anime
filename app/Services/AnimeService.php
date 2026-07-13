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

        $cached = AnimeCache::where('api_id', $anilistId)->first();

        if ($cached && $cached->updated_at->gt(now()->subDay())) {
            return $cached;
        }

        $anime = $this->anilistService->getAnime($anilistId);  
        $media = $anime['data']['Media'] ?? [];

        return AnimeCache::updateOrCreate(
            ['api_id' => $anilistId],
            [
                'title' => $media['title']['english'] ?? $media['title']['romaji'],
                'format' => $media['format'],
                'cover_image' => $media['coverImage']['extraLarge'],
                'banner_image' => $media['bannerImage'],
                'score' => $media['averageScore'],
                'episodes' => $media['episodes']
                    ?? (isset($media['nextAiringEpisode']['episode'])
                        ? $media['nextAiringEpisode']['episode'] - 1
                        : null),
                'season' => $media['season'] ?? null,
                'genres' => $media['genres'],
            ]
        );
    }
}

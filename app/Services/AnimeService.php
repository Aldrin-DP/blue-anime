<?php

namespace App\Services;

use App\Models\AnimeCache;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Cache;

class AnimeService
{

    protected AnilistService $anilistService;

    public function __construct(AnilistService $anilistService)
    {
        $this->anilistService = $anilistService;
    }

    public function getOrCacheAnime(int $anilistId) 
    {
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
                'totalEpisodes' => $media['episodes'] ?? null,
                'episodes' => $media['episodes']
                    ?? (isset($media['nextAiringEpisode']['episode'])
                        ? $media['nextAiringEpisode']['episode'] - 1
                        : null),
                'season' => $media['season'] ?? null,
                'genres' => $media['genres'],
            ]
        );
    }

    public function getTrendingAnime() 
    {
        return Cache::remember('anime.trending', now()->addHours(4), function () {
            return AnimeCache::where('season_year', 2026)
                ->orderBy('popularity', 'desc')
                ->limit(18)
                ->get()
                ->toArray();

        });
    }

    public function getTopRatedAnime() 
    {
        return Cache::remember('anime.top.rated', now()->addDays(2), function () {
            return AnimeCache::where('season_year', 2026)
                ->orderBy('score', 'desc')
                ->limit(18)
                ->get()
                ->toArray();
        });
    }

    public function getPopularAnime() 
    {
        return Cache::remember('anime.popular', now()->addDays(2), function () {
            return AnimeCache::orderBy('score', 'desc')
                ->limit(18)
                ->get()
                ->toArray();
        });
    }

    public function getFeaturedAnime() 
    {
        $trendingAnime = $this->getPopularAnime();
        $topRatedAnime = $this->getTopRatedAnime();

        $featuredAnime = [];

        foreach (array_slice($topRatedAnime, 0, 3) as $anime) {
            $featuredAnime[] = $anime;
        }
        foreach (array_slice($trendingAnime, 0, 4) as $anime) {
            $featuredAnime[] = $anime;
        }

        return $featuredAnime;
    }

    public function removeDuplicate(array $featuredAnime) 
    {
        $uniqueFeaturedAnime = [];
        $seen = [];
        
        foreach ($featuredAnime as $anime) {
            if (!isset($seen[$anime['api_id']])){
                $seen[$anime['api_id']] = true;
                $uniqueFeaturedAnime[] = $anime;
            }
        }

        return $uniqueFeaturedAnime;
    }

    public function addWatchProgress(array $featuredAnime)
    {   
        $user = auth()->user();

        if (!$user) {
            return;
        }

        $uniqueFeaturedAnime = [];

        foreach ($featuredAnime as $anime) {
            $progress = WatchHistory::where('user_id', $user->id)
                ->where('anime_id', $anime['id'])
                ->latest()
                ->first();

            if (!$progress) {
                $progress = null;
            }

            $uniqueFeaturedAnime[] = [
                'id' => $anime['id'],
                'api_id' => $anime['api_id'],
                'format' => $anime['format'],
                'title' => $anime['title'],
                'banner_image' => $anime['banner_image'],
                'cover_image' => $anime['cover_image'],
                'score' => $anime['score'],
                'genres' => $anime['genres'],
                'romaji_title' => $anime['romaji_title'],
                'season_year' => $anime['season_year'],
                'episode' => $progress->episode ?? 1
            ];
        }

        return $uniqueFeaturedAnime;
    }

    public function getProcessedFeaturedAnime() 
    {   
        $user = auth()->user();

        $featuredAnime = $this->getFeaturedAnime();
        $featuredAnime = $this->removeDuplicate($featuredAnime);

        $featuredAnime = $user 
            ? $this->addWatchProgress($featuredAnime)
            : $featuredAnime;
    
        return $featuredAnime;
    }
}

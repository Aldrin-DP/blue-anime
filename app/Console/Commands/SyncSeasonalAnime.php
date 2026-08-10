<?php

namespace App\Console\Commands;

use App\Models\AnimeCache;
use App\Services\AnilistService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-seasonal-anime')]
#[Description('Command description')]
class SyncSeasonalAnime extends Command
{
    public function handle(AnilistService $anilistService)
    {
        $page = 1;
        $perPage = 50;
        $hasNextPage = true;

        $month = now()->month;
        
        $season = match(true) {
            $month >= 1 && $month <= 3 => 'WINTER',
            $month >= 4 && $month <= 6 => 'SPRING',
            $month >= 7 && $month <= 9 => 'SUMMER',
            default => 'FALL',
        };

        $year = now()->year;

        while($hasNextPage) {
            $response = $anilistService->getAnimeForSeason($season, $year, $page, $perPage);

            foreach ($response['data']['Page']['media'] as $anime) {
                AnimeCache::updateOrCreate(
                    ['api_id' => $anime['id']],
                    [
                        'title' => $anime['title']['english'] ?? $anime['title']['romaji'],
                        'romaji_title' => $anime['title']['romaji'],
                        'description' => $anime['description'],
                        'score' => $anime['averageScore'],
                        'banner_image' => $anime['bannerImage'],
                        'cover_image' => $anime['coverImage']['extraLarge'],
                        'episodes' => $anime['episodes']
                            ?? (isset($anime['nextAiringEpisode']['episode'])
                                ? $anime['nextAiringEpisode']['episode'] - 1
                                : null),
                        'format' => $anime['format'],
                        'country_of_origin' => $anime['countryOfOrigin'],
                        'status' => $anime['status'],
                        'season' => $anime['season'],
                        'season_year' => $anime['seasonYear'],
                        'popularity' => $anime['popularity'],
                        'studio' => $anime['studios']['nodes'][0]['name'] ?? null,
                        'genres' => $anime['genres'],
                        'total_episode' => $anime['episodes'] ?? null,
                        'next_episode' => $anime['nextAiringEpisode']['episode'] ?? null,
                        'next_episode_airing_at' => $anime['nextAiringEpisode']['airingAt'] ?? null,
                    ]
                );
            }

            $page++;
            $hasNextPage = $response['data']['Page']['pageInfo']['hasNextPage'];

            sleep(2);
        }
    }
}

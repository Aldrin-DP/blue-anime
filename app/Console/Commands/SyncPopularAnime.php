<?php

namespace App\Console\Commands;

use App\Models\AnimeCache;
use App\Services\AnilistService;
use App\Services\AnimeService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-popular-anime')]
#[Description('Command description')]
class SyncPopularAnime extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(AnilistService $anilistService, AnimeService $animeService)
    {
        $page = 1;
        $perPage = 50;
        $hasNextPage = true;

        while($hasNextPage) {
            $response = $anilistService->getPopularAnimePage($page, $perPage);

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
                        'genres' => $anime['genres']
                    ]
                );
            }

            $page++;
            $hasNextPage = $response['data']['Page']['pageInfo']['hasNextPage'];

            sleep(2);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AnilistService
{
    public function getAnime(int $animeId)
    {
        return Cache::remember("anime.detail.{$animeId}", now()->addHours(6), function () use ($animeId) {
            return Http::post('https://graphql.anilist.co', [
                'query' => '
                    query Media($mediaId: Int) {
                        Media(id: $mediaId) {
                            episodes
                            id
                            season
                            type
                            description
                            title {
                                english
                            }
                            coverImage {
                                extraLarge
                                large
                            }
                            averageScore
                            genres
                            startDate {
                                day
                                month
                                year
                            }
                            studios {
                                nodes {
                                    name
                                }
                            }
                            status
                            bannerImage
                            format
                            duration
                            streamingEpisodes {
                                title
                                thumbnail
                            },
                            nextAiringEpisode {
                                episode
                                airingAt
                            }
                            recommendations {
                                nodes {
                                    mediaRecommendation {
                                        episodes
                                        format
                                        title {
                                            english
                                            romaji
                                        }
                                        id
                                        coverImage {
                                            extraLarge
                                            large
                                        }
                                    }
                                }
                            }
                        }
                    }
                ',
                'variables' => [
                    'mediaId' => $animeId,
                ],
            ])->json();
        });
    }

    public function getTrending()
    {

    }


}

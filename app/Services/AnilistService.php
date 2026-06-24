<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AnilistService
{
    public $ANILIST_API = "https://graphql.anilist.co";

    public function getAnime(int $animeId)
    {
        return Cache::remember("anime.detail.{$animeId}", now()->addHours(6), function () use ($animeId) {
            return Http::post($this->ANILIST_API, [
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
        return Cache::remember('anime.trending', now()->addHours(5), function () {
            return Http::post($this->ANILIST_API, [
                'query' => '
                    query Page($page: Int, $perPage: Int, $type: MediaType, $status: MediaStatus, $sort: [MediaSort]) {
                        Page(page: $page, perPage: $perPage) {
                            media(type: $type, status: $status, sort: $sort) {
                                id
                                title {
                                    english
                                    romaji
                                }
                                coverImage {
                                    extraLarge
                                }
                                format
                            }
                        }
                    }',
                'variables' => [
                    'page' => 1,
                    'perPage' => 18,
                    'type' => 'ANIME',
                    'status' => 'RELEASING',
                    'sort' => 'TRENDING_DESC',
                ]
            ])->json()['data']['Page']['media'];
        });
    }

    public function getNewEpisodes()
    {
        return Cache::remember('anime.new.episodes', now()->addHours(5), function (){
            $trendingAnime = Http::post($this->ANILIST_API, [
                'query' => '
                    query($page: Int, $perPage: Int) {
                        Page (page: $page, perPage: $perPage) {
                            media (
                                type: ANIME
                                status: RELEASING
                                sort: TRENDING_DESC
                            ) {
                                id
                                title {
                                    romaji
                                    english
                                }
                            }
                        }
                    }',
                'variables' => [
                    'page' => 1,
                    'perPage' => 18
                ]
            ])->json()['data']['Page']['media'];

            $trendingIds = array_map(fn($anime) => $anime['id'], $trendingAnime);

            $response = Http::post($this->ANILIST_API, [
                'query' => '
                    query($trendingIds: [Int], $page: Int, $perPage: Int) {
                        Page (page: $page, perPage: $perPage) {
                            airingSchedules (
                                notYetAired: false
                                mediaId_in: $trendingIds
                                sort: TIME_DESC
                            ) {
                                episode
                                airingAt
                                media {
                                    id
                                    popularity
                                    trending
                                    title {
                                        romaji
                                        english
                                    },
                                    coverImage {
                                        extraLarge
                                        large
                                    }
                                }
                            }
                        }
                    }',
                'variables' => [
                    'trendingIds' => $trendingIds,
                    'page' => 1,
                    'perPage' => 18
                ]
            ])->json()['data']['Page']['airingSchedules'];

            $newEpisodes =
                array_map(fn($anime) => [
                    'episode' => $anime['episode'],
                    'id' => $anime['media']['id'],
                    'title' => $anime['media']['title'],
                    'coverImage' => $anime['media']['coverImage']
                ], $response);

            return $newEpisodes;
        });
    }

    public function getPopular()
    {
        return Cache::remember('anime.popular', now()->addHours(5), function (){
            return Http::post($this->ANILIST_API, [
                'query' => '
                    query Query($page: Int, $perPage: Int, $sort: [MediaSort], $type: MediaType) {
                        Page(page: $page, perPage: $perPage) {
                            media(sort: $sort, type: $type) {
                                popularity
                                title {
                                    english
                                    romaji
                                }
                                id
                                format
                                coverImage {
                                    extraLarge
                                    large
                                }
                            }
                        }
                    }',
                'variables' => [
                    'page' => 1,
                    'perPage' => 18,
                    'sort' => 'POPULARITY_DESC',
                    'type' => 'ANIME'
                ]
            ])->json()['data']['Page']['media'];
        });
    }

    public function getTopRated()
    {
        return Cache::remember('anime.top.rated', now()->addHours(5), function (){
            return Http::post($this->ANILIST_API, [
                'query' => '
                    query Query($page: Int, $perPage: Int, $sort: [MediaSort], $type: MediaType) {
                        Page(page: $page, perPage: $perPage) {
                            media(sort: $sort, type: $type) {
                                popularity
                                title {
                                    english
                                    romaji
                                }
                                id
                                format
                                coverImage {
                                    extraLarge
                                    large
                                }
                            }
                        }
                    }',
                'variables' => [
                    'page' => 1,
                    'perPage' => 18,
                    'sort' => 'SCORE_DESC',
                    'type' => 'ANIME'
                ]
            ])->json()['data']['Page']['media'];
        });
    }
}

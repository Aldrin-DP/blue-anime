<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnilistService
{
    public $ANILIST_API = "https://graphql.anilist.co";

    public function getAnime(int $animeId)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getTrending()
    {
        try {
            return Cache::remember('anime.trending', now()->addHours(5), function () {
                return Http::timeout(10)->post($this->ANILIST_API, [
                    'query' => '
                    query Page($page: Int, $perPage: Int, $type: MediaType, $status: MediaStatus, $sort: [MediaSort]) {
                        Page(page: $page, perPage: $perPage) {
                            media(type: $type, status: $status, sort: $sort) {
                                id
                                averageScore
                                genres
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
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getNewEpisodes()
    {
        try {
            return Cache::remember('anime.new.episodes', now()->addHours(5), function () {
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
                                averageScore
                                genres
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
                                    genres
                                    format
                                    averageScore
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
                        'format' => $anime['media']['format'],
                        'averageScore' => $anime['media']['averageScore'],
                        'genres' => $anime['media']['genres'],
                        'coverImage' => $anime['media']['coverImage']
                    ], $response);

                return $newEpisodes;
            });
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getPopular()
    {
        try {
            return Cache::remember('anime.popular', now()->addHours(5), function () {
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
                                averageScore
                                genres
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
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getTopRated()
    {
        try {
            return Cache::remember('anime.top.rated', now()->addHours(5), function () {
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
                                averageScore
                                genres
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
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getPopularAnimePage(int $page, int $perPage)
    {
        try {
            return Http::post($this->ANILIST_API, [
                'query' => '
                query ExampleQuery($perPage: Int, $page: Int, $sort: [MediaSort]) {
                    Page(perPage: $perPage, page: $page) {
                        media(sort: $sort) {
                        title {
                            english
                            romaji
                        }
                        averageScore
                        bannerImage
                        countryOfOrigin
                        coverImage {
                            extraLarge
                        }
                        description
                        episodes
                        format
                        genres
                        status
                        id
                        nextAiringEpisode {
                            episode
                            airingAt
                        }
                        seasonYear
                        season
                        popularity
                        studios {
                            nodes {
                                name
                                }
                            }
                        }
                        pageInfo {
                            hasNextPage
                            total
                            perPage
                            currentPage
                            lastPage
                        }
                    }
                }',
                'variables' => [
                    'page' => $page,
                    'perPage' => $perPage,
                    'sort' => 'POPULARITY_DESC'
                ]
            ])->json();
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }

    public function getPopularChineAnimePage(int $page, int $perPage)
    {
        try {
            return Http::post($this->ANILIST_API, [
                'query' => '
                query ExampleQuery($perPage: Int, $page: Int, $sort: [MediaSort], $countryOfOrigin: CountryCode) {
                    Page(perPage: $perPage, page: $page) {
                        media(sort: $sort, countryOfOrigin: $countryOfOrigin) {
                        title {
                            english
                            romaji
                        }
                        averageScore
                        bannerImage
                        countryOfOrigin
                        coverImage {
                            extraLarge
                        }
                        description
                        episodes
                        format
                        genres
                        status
                        id
                        nextAiringEpisode {
                            episode
                            airingAt
                        }
                        seasonYear
                        season
                        popularity
                        studios {
                            nodes {
                                name
                                }
                            }
                        }
                        pageInfo {
                            hasNextPage
                            total
                            perPage
                            currentPage
                            lastPage
                        }
                    }
                }',
                'variables' => [
                    'page' => $page,
                    'perPage' => $perPage,
                    'sort' => 'POPULARITY_DESC',
                    'countryOfOrigin' => 'CN',
                ]
            ])->json();
        } catch (Exception $e) {
            Log::error('Anilist fetch failed:' . $e->getMessage());
            return [];
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SearchService
{
    /**
     * Create a new class instance.
     */
    public function getFilteredAnime(array $filters)
    {

        $variables = array_filter([
            'page' => $filters['page'] ?? 1,
            'perPage' => 18,
            'search' => $filters['search'] ?? null,
            'status' => $filters['status'] ?? null,
            'format' => $filters['format'] ?? null,
            'season' => $filters['season'] ?? null,
        ]);

        $response = Http::post('https://graphql.anilist.co', [
            'query' => '
                query($page: Int, $perPage: Int, $search: String, $format: MediaFormat, $status: MediaStatus, $season: MediaSeason) {
                    Page(page: $page, perPage: $perPage) {
                        media(search: $search, format: $format, status: $status, season: $season) {
                            id
                            title {
                                english
                                romaji
                            }
                            format
                            coverImage {
                                extraLarge
                                large
                            }
                        }
                        pageInfo {
                            total
                            perPage
                            lastPage
                            hasNextPage
                            currentPage
                        }
                    }
                }
            ',
            'variables' => $variables,
        ]);

        return $response->json();
    }
}

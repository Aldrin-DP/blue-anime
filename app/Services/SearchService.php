<?php

namespace App\Services;

use App\Models\AnimeCache;
use Illuminate\Support\Facades\Http;

class SearchService
{
    /**
     * Create a new class instance.
     */
    public function getFilteredAnime(array $filters)
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $format = $filters['format'] ?? null;
        $season = $filters['season'] ?? null;
        $year = $filters['year'] ?? null;
        $country = $filters['country'] ?? null;
        $sort = $filters['sort'] ?? 'popularity';
        $genres = $filters['genres'] ?? null;

        $filteredAnime = AnimeCache::when($search, function ($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('romaji_title', 'LIKE', "%{$search}%");
        })->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })->when($format, function ($query) use ($format) {
            $query->where('format', $format);
        })->when($season, function ($query) use ($season) {
            $query->where('season', $season);
        })->when($year, function ($query) use ($year) {
            $query->where('season_year', $year);
        })->when($country, function ($query) use ($country) {
            $query->where('country_of_origin', $country);
        })->when($genres, function ($query) use ($genres) {
            foreach ($genres as $genre) {
                $query->whereJsonContains('genres', $genre);
            }
        })
        ->orderByDesc($sort)
        ->paginate(24);

        return $filteredAnime;
    }
}

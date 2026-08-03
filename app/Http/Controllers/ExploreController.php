<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request, SearchService $searchService)
    {
        $filters = $request->only(['search', 'status', 'format', 'season', 'year', 'country', 'sort', 'genres', 'page']);

        $selectedFilters = [
            'search' => $filters['search'] ?? '',
            'status' => $filters['status'] ?? '',
            'format' => $filters['format'] ?? '',
            'season' => $filters['season'] ?? '',
            'year' => $filters['year'] ?? '',
            'country' => $filters['country'] ?? '',
            'sort' => $filters['sort'] ?? '',
            'genres' => $filters['genres'] ?? [],
            'page' => $filters['page'] ?? 1
        ];
        $filteredAnime = $searchService->getFilteredAnime($filters);   
        
        return inertia('Explore/Index', [
            'data' => $filteredAnime,
            'selectedFilters' => $selectedFilters
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request, SearchService $searchService)
    {

        $filters = $request->only(['search', 'status', 'format', 'season', 'page']);

        $anime = $searchService->getFilteredAnime($filters);

        return inertia('Explore/Index', [
            'data' => $anime,
        ]);
    }
}

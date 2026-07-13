<?php

namespace App\Http\Controllers;

use App\Models\AnimeCache;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {
        $search = $request->input('search');

        $searchList = AnimeCache::select('id', 'api_id', 'title', 'romaji_title', 'cover_image')
            ->where('title', 'LIKE', "{$search}%")
            ->orWhere('romaji_title', 'LIKE', "{$search}%")
            ->limit(7)
            ->get();


        return response()->json([
            'searchList' => $searchList,
        ]);
    }
}

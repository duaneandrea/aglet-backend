<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    public function index(): View
    {
        return view('movies.index');
    }

    public function grid(): View
    {
        return view('movies.grid');
    }

    public function show(Movie $movie): View
    {
        $movieDetails = $this->tmdbService->getMovieDetails($movie->tmdb_id);
        
        return view('movies.show', compact('movie', 'movieDetails'));
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255'
        ]);

        $results = $this->tmdbService->searchMovies($request->query);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255'
        ]);

        $suggestions = $this->tmdbService->getMovieSuggestions($request->query);

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }
} 
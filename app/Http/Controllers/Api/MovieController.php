<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchMoviesRequest;
use App\Http\Requests\ToggleFavoriteRequest;
use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function __construct(
        private TmdbService $tmdbService
    ) {}

    public function index(): JsonResponse
    {
        $movies = Movie::popular()
            ->with(['users' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->paginate(9);

        return response()->json([
            'success' => true,
            'data' => $movies,
            'message' => 'Movies retrieved successfully'
        ]);
    }

    public function search(SearchMoviesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $results = $this->tmdbService->searchMovies(
            $validated['query'],
            $validated['page'] ?? 1
        );

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => 'Search completed successfully'
        ]);
    }

    public function show(Movie $movie): JsonResponse
    {
        $details = $this->tmdbService->getMovieDetails($movie->tmdb_id);

        return response()->json([
            'success' => true,
            'data' => [
                'movie' => $movie,
                'details' => $details,
                'is_favorited' => auth()->check() ? auth()->user()->hasFavorited($movie) : false
            ],
            'message' => 'Movie details retrieved successfully'
        ]);
    }

    public function toggleFavorite(ToggleFavoriteRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $movie = Movie::findOrFail($validated['movie_id']);
        
        $isFavorited = auth()->user()->toggleFavorite($movie);
        
        return response()->json([
            'success' => true,
            'data' => [
                'is_favorited' => $isFavorited,
                'movie' => $movie
            ],
            'message' => $isFavorited 
                ? 'Movie added to favorites' 
                : 'Movie removed from favorites'
        ]);
    }

    public function favorites(): JsonResponse
    {
        $favorites = auth()->user()
            ->favoriteMovies()
            ->orderBy('user_favorites.created_at', 'desc')
            ->paginate(9);

        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'Favorite movies retrieved successfully'
        ]);
    }

    public function suggestions(SearchMoviesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $suggestions = $this->tmdbService->getMovieSuggestions($validated['query']);

        return response()->json([
            'success' => true,
            'data' => $suggestions,
            'message' => 'Suggestions retrieved successfully'
        ]);
    }
} 
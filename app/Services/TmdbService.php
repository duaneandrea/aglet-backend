<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    private string $baseUrl;
    private string $apiKey;
    private ?string $accessToken;
    private string $imageBaseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
        $this->apiKey = config('services.tmdb.api_key');
        $this->accessToken = config('services.tmdb.access_token');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
    }

    public function validateApiKey(): bool
    {
        try {
            $response = $this->makeRequest('authentication');
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['success'] ?? false;
            }

            Log::error('TMDB API Key Validation Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('TMDB API Key Validation Exception', [
                'message' => $e->getMessage()
            ]);

            return false;
        }
    }

    public function getPopularMovies(int $page = 1): array
    {
        $cacheKey = "popular_movies_page_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($page) {
            try {
                $response = $this->makeRequest('movie/popular', [
                    'page' => $page,
                    'language' => 'en-US'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $this->storeMoviesInDatabase($data['results'] ?? []);
                    return $data;
                }

                Log::error('TMDB API Error - Popular Movies', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'page' => $page
                ]);

                return ['results' => [], 'total_pages' => 0, 'total_results' => 0];
            } catch (\Exception $e) {
                Log::error('TMDB Service Exception - Popular Movies', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'page' => $page
                ]);

                return ['results' => [], 'total_pages' => 0, 'total_results' => 0];
            }
        });
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        $cacheKey = "search_movies_" . md5($query) . "_page_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($query, $page) {
            try {
                $response = $this->makeRequest('search/movie', [
                    'query' => $query,
                    'page' => $page,
                    'language' => 'en-US',
                    'include_adult' => false
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $this->storeMoviesInDatabase($data['results'] ?? []);
                    return $data;
                }

                Log::error('TMDB API Error - Search Movies', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'query' => $query,
                    'page' => $page
                ]);

                return ['results' => [], 'total_pages' => 0, 'total_results' => 0];
            } catch (\Exception $e) {
                Log::error('TMDB Search Exception', [
                    'query' => $query,
                    'message' => $e->getMessage(),
                    'page' => $page
                ]);

                return ['results' => [], 'total_pages' => 0, 'total_results' => 0];
            }
        });
    }

    public function getMovieDetails(int $tmdbId): ?array
    {
        $cacheKey = "movie_details_{$tmdbId}";
        
        return Cache::remember($cacheKey, now()->addDays(1), function () use ($tmdbId) {
            try {
                $response = $this->makeRequest("movie/{$tmdbId}", [
                    'language' => 'en-US',
                    'append_to_response' => 'credits,videos,similar'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDB API Error - Movie Details', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'tmdb_id' => $tmdbId
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('TMDB Movie Details Exception', [
                    'tmdb_id' => $tmdbId,
                    'message' => $e->getMessage()
                ]);

                return null;
            }
        });
    }

    public function getMovieSuggestions(string $query): Collection
    {
        if (strlen($query) < 2) {
            return collect();
        }

        $cacheKey = "movie_suggestions_" . md5($query);
        
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($query) {
            try {
                $response = $this->makeRequest('search/movie', [
                    'query' => $query,
                    'page' => 1,
                    'language' => 'en-US',
                    'include_adult' => false
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return collect($data['results'] ?? [])
                        ->take(10)
                        ->map(fn($movie) => [
                            'id' => $movie['id'],
                            'title' => $movie['title'],
                            'release_date' => $movie['release_date'] ?? null,
                            'poster_path' => $movie['poster_path']
                        ]);
                }

                return collect();
            } catch (\Exception $e) {
                Log::error('TMDB Suggestions Exception', [
                    'query' => $query,
                    'message' => $e->getMessage()
                ]);

                return collect();
            }
        });
    }

    private function makeRequest(string $endpoint, array $params = []): Response
    {
        $httpClient = Http::timeout(30)->retry(3, 1000);

        if ($this->accessToken) {
            $httpClient = $httpClient->withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json'
            ]);
        } else {
            $params['api_key'] = $this->apiKey;
        }
        
        return $httpClient->get("{$this->baseUrl}/{$endpoint}", $params);
    }

    private function storeMoviesInDatabase(array $movies): void
    {
        if (empty($movies)) {
            return;
        }

        foreach ($movies as $movieData) {
            try {
                Movie::updateOrCreate(
                    ['tmdb_id' => $movieData['id']],
                    [
                        'title' => $movieData['title'] ?? 'Unknown Title',
                        'overview' => $movieData['overview'] ?? '',
                        'poster_path' => $movieData['poster_path'],
                        'backdrop_path' => $movieData['backdrop_path'],
                        'release_date' => !empty($movieData['release_date']) ? $movieData['release_date'] : null,
                        'vote_average' => $movieData['vote_average'] ?? 0,
                        'vote_count' => $movieData['vote_count'] ?? 0,
                        'popularity' => $movieData['popularity'] ?? 0,
                        'genre_ids' => $movieData['genre_ids'] ?? [],
                        'original_language' => $movieData['original_language'] ?? 'en',
                        'original_title' => $movieData['original_title'] ?? $movieData['title'] ?? 'Unknown Title',
                        'adult' => $movieData['adult'] ?? false,
                        'video' => $movieData['video'] ?? false
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Error storing movie in database', [
                    'movie_data' => $movieData,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
} 
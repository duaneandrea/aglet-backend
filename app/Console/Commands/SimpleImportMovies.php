<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SimpleImportMovies extends Command
{
    protected $signature = 'movies:simple-import {--pages=5 : Number of pages to import}';
    protected $description = 'Simple import of popular movies without complex validation';

    public function handle(): int
    {
        $pages = (int) $this->option('pages');
        
        // Check configuration
        $apiKey = config('services.tmdb.api_key');
        $accessToken = config('services.tmdb.access_token');
        $baseUrl = config('services.tmdb.base_url');
        
        if (!$apiKey && !$accessToken) {
            $this->error('No TMDB credentials found! Set TMDB_API_KEY or TMDB_ACCESS_TOKEN in .env');
            return self::FAILURE;
        }
        
        $this->info("Importing popular movies from {$pages} pages...");
        
        $progressBar = $this->output->createProgressBar($pages);
        $progressBar->start();
        
        $totalMovies = 0;
        
        for ($page = 1; $page <= $pages; $page++) {
            try {
                $client = Http::timeout(30);
                
                if ($accessToken) {
                    $response = $client->withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/json'
                    ])->get($baseUrl . '/movie/popular', [
                        'language' => 'en-US',
                        'page' => $page
                    ]);
                } else {
                    $response = $client->get($baseUrl . '/movie/popular', [
                        'api_key' => $apiKey,
                        'language' => 'en-US',
                        'page' => $page
                    ]);
                }
                
                if ($response->successful()) {
                    $data = $response->json();
                    $movies = $data['results'] ?? [];
                    
                    foreach ($movies as $movieData) {
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
                    }
                    
                    $totalMovies += count($movies);
                } else {
                    $this->newLine();
                    $this->error("Failed to fetch page {$page}: " . $response->status());
                }
                
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error on page {$page}: " . $e->getMessage());
            }
        }
        
        $progressBar->finish();
        $this->newLine();
        
        if ($totalMovies > 0) {
            $this->info("✓ Successfully imported {$totalMovies} movies!");
        } else {
            $this->error('No movies were imported.');
            return self::FAILURE;
        }
        
        return self::SUCCESS;
    }
} 
<?php

namespace App\Console\Commands;

use App\Services\TmdbService;
use Illuminate\Console\Command;

class TestTmdbApi extends Command
{
    protected $signature = 'tmdb:test';
    protected $description = 'Test TMDB API connection and endpoints';

    public function handle(TmdbService $tmdbService): int
    {
        $this->info('Testing TMDB API connection...');

        $this->info('1. Validating API credentials...');
        if ($tmdbService->validateApiKey()) {
            $this->info('✓ API credentials are valid');
        } else {
            $this->error('✗ API credentials validation failed');
            return self::FAILURE;
        }

        $this->info('2. Testing popular movies endpoint...');
        $popularMovies = $tmdbService->getPopularMovies(1);
        
        if (!empty($popularMovies)) {
            $this->info('✓ Popular movies endpoint working');
            $this->info('Sample movie: ' . ($popularMovies[0]['title'] ?? 'Unknown'));
        } else {
            $this->error('✗ Popular movies endpoint failed');
            return self::FAILURE;
        }

        $this->info('3. Testing search endpoint...');
        $searchResults = $tmdbService->searchMovies('Inception');
        
        if (!empty($searchResults)) {
            $this->info('✓ Search endpoint working');
            $this->info('Search results: ' . count($searchResults) . ' movies found');
        } else {
            $this->error('✗ Search endpoint failed');
            return self::FAILURE;
        }

        $this->info('🎉 All TMDB API tests passed!');
        
        return self::SUCCESS;
    }
} 
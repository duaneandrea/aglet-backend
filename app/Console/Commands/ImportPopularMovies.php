<?php

namespace App\Console\Commands;

use App\Services\TmdbService;
use App\Models\Movie;
use Illuminate\Console\Command;

class ImportPopularMovies extends Command
{
    protected $signature = 'tmdb:import-popular {--pages=5 : Number of pages to import}';
    protected $description = 'Import popular movies from TMDB API';

    public function handle(TmdbService $tmdbService): int
    {
        $pages = (int) $this->option('pages');
        
        $this->info("Importing popular movies from TMDB (pages: {$pages})...");
        
        $totalImported = 0;
        
        for ($page = 1; $page <= $pages; $page++) {
            $this->info("Fetching page {$page}...");
            
            $movies = $tmdbService->getPopularMovies($page);
            
            if (empty($movies)) {
                $this->warn("No movies found on page {$page}");
                continue;
            }
            
            foreach ($movies as $movieData) {
                try {
                    Movie::updateOrCreate(
                        ['tmdb_id' => $movieData['id']],
                        [
                            'title' => $movieData['title'],
                            'overview' => $movieData['overview'],
                            'poster_path' => $movieData['poster_path'],
                            'backdrop_path' => $movieData['backdrop_path'],
                            'release_date' => $movieData['release_date'] ? date('Y-m-d', strtotime($movieData['release_date'])) : null,
                            'vote_average' => $movieData['vote_average'],
                            'vote_count' => $movieData['vote_count'],
                            'popularity' => $movieData['popularity'],
                            'adult' => $movieData['adult'],
                            'genre_ids' => json_encode($movieData['genre_ids']),
                            'original_language' => $movieData['original_language'],
                            'original_title' => $movieData['original_title'],
                        ]
                    );
                    
                    $totalImported++;
                } catch (\Exception $e) {
                    $this->error("Failed to import movie: {$movieData['title']} - {$e->getMessage()}");
                }
            }
            
            sleep(1);
        }
        
        $this->info("✅ Successfully imported {$totalImported} movies!");
        
        return self::SUCCESS;
    }
} 
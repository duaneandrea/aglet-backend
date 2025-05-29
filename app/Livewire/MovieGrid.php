<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class MovieGrid extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 9;
    public bool $showModal = false;
    public ?Movie $selectedMovie = null;
    public array $movieDetails = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function mount(): void
    {
        $this->loadInitialMovies();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function loadInitialMovies(): void
    {
        $tmdbService = app(TmdbService::class);
        
        for ($page = 1; $page <= 5; $page++) {
            $tmdbService->getPopularMovies($page);
        }
    }

    public function toggleFavorite(int $movieId): void
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to add favorites.');
            return;
        }

        $movie = Movie::findOrFail($movieId);
        $isFavorited = auth()->user()->toggleFavorite($movie);
        
        $message = $isFavorited 
            ? "'{$movie->title}' added to favorites!"
            : "'{$movie->title}' removed from favorites!";
            
        session()->flash('success', $message);
    }

    public function showMovieDetails(int $movieId): void
    {
        $this->selectedMovie = Movie::findOrFail($movieId);
        
        $tmdbService = app(TmdbService::class);
        $this->movieDetails = $tmdbService->getMovieDetails($this->selectedMovie->tmdb_id) ?? [];
        
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedMovie = null;
        $this->movieDetails = [];
    }

    public function getSuggestions(): array
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        $tmdbService = app(TmdbService::class);
        return $tmdbService->getMovieSuggestions($this->search)->toArray();
    }

    public function render(): View
    {
        $movies = $this->getMovies();
        $suggestions = $this->getSuggestions();

        return view('livewire.movie-grid', compact('movies', 'suggestions'));
    }

    private function getMovies(): LengthAwarePaginator
    {
        $query = Movie::query();

        if ($this->search) {
            $query->byTitle($this->search);
        } else {
            $query->popular();
        }

        return $query->paginate($this->perPage);
    }
} 
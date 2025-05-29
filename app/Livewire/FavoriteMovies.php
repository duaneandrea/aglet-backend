<?php

namespace App\Livewire;

use App\Models\Movie;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class FavoriteMovies extends Component
{
    use WithPagination;

    public int $perPage = 9;
    public bool $showModal = false;
    public ?Movie $selectedMovie = null;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function removeFavorite(int $movieId): void
    {
        $movie = Movie::findOrFail($movieId);
        auth()->user()->favoriteMovies()->detach($movie);
        
        session()->flash('success', "'{$movie->title}' removed from favorites!");
    }

    public function showMovieDetails(int $movieId): void
    {
        $this->selectedMovie = Movie::findOrFail($movieId);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedMovie = null;
    }

    public function render(): View
    {
        $favoriteMovies = $this->getFavoriteMovies();

        return view('livewire.favorite-movies', compact('favoriteMovies'));
    }

    private function getFavoriteMovies(): LengthAwarePaginator
    {
        return auth()->user()
            ->favoriteMovies()
            ->orderBy('user_favorites.created_at', 'desc')
            ->paginate($this->perPage);
    }
} 
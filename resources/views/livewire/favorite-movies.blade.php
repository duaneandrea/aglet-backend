<div>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold text-light mb-2">My Favorite Movies</h1>
                <p class="text-muted">Your personal collection of favorite movies</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($favoriteMovies as $movie)
            <div class="col-lg-4 col-md-6">
                <div class="movie-card card h-100 border-0 rounded-3 overflow-hidden">
                    <div class="position-relative">
                        <img 
                            src="{{ $movie->poster_url }}" 
                            alt="{{ $movie->title }}"
                            class="card-img-top"
                            style="height: 400px; object-fit: cover;"
                            loading="lazy"
                        >
                        <div class="position-absolute top-0 end-0 p-2">
                            <button 
                                class="btn btn-sm btn-danger"
                                wire:click="removeFavorite({{ $movie->id }})"
                                title="Remove from favorites"
                                wire:confirm="Are you sure you want to remove this movie from your favorites?"
                            >
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        @if($movie->vote_average > 0)
                        <div class="position-absolute bottom-0 start-0 p-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>{{ $movie->vote_average }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-light">{{ $movie->title }}</h5>
                        <p class="card-text text-muted small mb-2">{{ $movie->formatted_release_date }}</p>
                        <p class="card-text text-light flex-grow-1">
                            {{ Str::limit($movie->overview, 120) }}
                        </p>
                        <button 
                            class="btn btn-primary btn-sm mt-auto"
                            wire:click="showMovieDetails({{ $movie->id }})"
                        >
                            <i class="fas fa-info-circle me-1"></i>View Details
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">No favorite movies yet</h3>
                    <p class="text-muted">Start building your collection by adding movies to your favorites</p>
                    <a href="{{ route('movies.index') }}" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Browse Movies
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($favoriteMovies->hasPages())
        <div class="row mt-5">
            <div class="col">
                {{ $favoriteMovies->links() }}
            </div>
        </div>
        @endif
    </div>

    @if($showModal && $selectedMovie)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: rgba(0,0,0,0.9); border: 1px solid rgba(255,255,255,0.2);">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title text-light">{{ $selectedMovie->title }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img 
                                src="{{ $selectedMovie->poster_url }}" 
                                alt="{{ $selectedMovie->title }}"
                                class="img-fluid rounded"
                            >
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-light">Overview</h6>
                            <p class="text-muted">{{ $selectedMovie->overview ?: 'No overview available.' }}</p>
                            
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <strong class="text-light">Release Date:</strong><br>
                                    <span class="text-muted">{{ $selectedMovie->formatted_release_date }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-light">Rating:</strong><br>
                                    <span class="text-muted">
                                        @if($selectedMovie->vote_average > 0)
                                        <i class="fas fa-star text-warning me-1"></i>{{ $selectedMovie->vote_average }}/10
                                        @else
                                        Not rated
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-light">Language:</strong><br>
                                    <span class="text-muted">{{ strtoupper($selectedMovie->original_language) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-light">Popularity:</strong><br>
                                    <span class="text-muted">{{ number_format($selectedMovie->popularity, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button 
                        class="btn btn-danger"
                        wire:click="removeFavorite({{ $selectedMovie->id }})"
                        wire:confirm="Are you sure you want to remove this movie from your favorites?"
                    >
                        <i class="fas fa-heart-broken me-1"></i>Remove from Favorites
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 
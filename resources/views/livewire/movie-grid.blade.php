<div>
    <div class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-4">Discover Amazing Movies</h1>
                    <p class="lead mb-5">Explore thousands of movies and build your personal favorites collection</p>
                    
                    <div class="position-relative">
                        <div class="input-group input-group-lg">
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Search for movies..."
                                wire:model.live.debounce.300ms="search"
                                autocomplete="off"
                            >
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        @if(count($suggestions) > 0)
                        <div class="position-absolute w-100 mt-1" style="z-index: 1000;">
                            <div class="list-group">
                                @foreach($suggestions as $suggestion)
                                <button 
                                    type="button" 
                                    class="list-group-item list-group-item-action d-flex align-items-center"
                                    style="background: rgba(0,0,0,0.9); border-color: rgba(255,255,255,0.2); color: #fff;"
                                    wire:click="$set('search', '{{ $suggestion['title'] }}')"
                                >
                                    @if($suggestion['poster_path'])
                                    <img 
                                        src="https://image.tmdb.org/t/p/w92{{ $suggestion['poster_path'] }}" 
                                        alt="{{ $suggestion['title'] }}"
                                        class="me-3"
                                        style="width: 40px; height: 60px; object-fit: cover;"
                                    >
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $suggestion['title'] }}</div>
                                        @if($suggestion['release_date'])
                                        <small class="text-muted">{{ date('Y', strtotime($suggestion['release_date'])) }}</small>
                                        @endif
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if($search)
        <div class="row mb-4">
            <div class="col">
                <h2 class="h4 mb-0">Search Results for "{{ $search }}"</h2>
                <button class="btn btn-link text-light p-0" wire:click="$set('search', '')">
                    <i class="fas fa-times me-1"></i>Clear Search
                </button>
            </div>
        </div>
        @else
        <div class="row mb-4">
            <div class="col">
                <h2 class="h4 mb-0">Popular Movies</h2>
            </div>
        </div>
        @endif

        <div class="row g-4">
            @forelse($movies as $movie)
            <div class="col-lg-4 col-md-6">
                <div class="movie-card card h-100 border-0 rounded-3 overflow-hidden">
                    <div class="position-relative">
                        <img 
                            src="{{ $movie->poster_url }}" 
                            alt="{{ $movie->title }}"
                            class="card-img-top"
                            style="height: 400px; object-fit: cover;"
                            loading="lazy"
                            onerror="this.src='https://via.placeholder.com/500x750/333/fff?text=No+Image'"
                        >
                        <div class="position-absolute top-0 end-0 p-2">
                            @auth
                            <button 
                                class="btn btn-sm {{ auth()->user()->hasFavorited($movie) ? 'btn-danger' : 'btn-outline-light' }}"
                                wire:click="toggleFavorite({{ $movie->id }})"
                                title="{{ auth()->user()->hasFavorited($movie) ? 'Remove from favorites' : 'Add to favorites' }}"
                            >
                                <i class="fas fa-heart"></i>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light" title="Login to add favorites">
                                <i class="far fa-heart"></i>
                            </a>
                            @endauth
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
                            {{ Str::limit($movie->overview ?: 'No overview available.', 120) }}
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
                    <i class="fas fa-film fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">No movies found</h3>
                    @if($search)
                    <p class="text-muted">Try adjusting your search terms</p>
                    @else
                    <p class="text-muted">No movies have been imported yet. Please run the import command:</p>
                    <code class="text-light bg-dark p-2 rounded">php artisan movies:import-popular</code>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        @if($movies->hasPages())
        <div class="row mt-5">
            <div class="col">
                {{ $movies->links() }}
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
                                onerror="this.src='https://via.placeholder.com/500x750/333/fff?text=No+Image'"
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

                            @if(isset($movieDetails['genres']) && count($movieDetails['genres']) > 0)
                            <div class="mt-3">
                                <strong class="text-light">Genres:</strong><br>
                                @foreach($movieDetails['genres'] as $genre)
                                <span class="badge bg-secondary me-1">{{ $genre['name'] }}</span>
                                @endforeach
                            </div>
                            @endif

                            @if(isset($movieDetails['runtime']) && $movieDetails['runtime'] > 0)
                            <div class="mt-3">
                                <strong class="text-light">Runtime:</strong><br>
                                <span class="text-muted">{{ $movieDetails['runtime'] }} minutes</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    @auth
                    <button 
                        class="btn {{ auth()->user()->hasFavorited($selectedMovie) ? 'btn-danger' : 'btn-primary' }}"
                        wire:click="toggleFavorite({{ $selectedMovie->id }})"
                    >
                        <i class="fas fa-heart me-1"></i>
                        {{ auth()->user()->hasFavorited($selectedMovie) ? 'Remove from Favorites' : 'Add to Favorites' }}
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-1"></i>Login to Add Favorites
                    </a>
                    @endauth
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 
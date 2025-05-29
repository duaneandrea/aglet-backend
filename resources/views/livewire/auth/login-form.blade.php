<div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-film fa-3x text-primary mb-3"></i>
                            <h2 class="text-light">Welcome Back</h2>
                            <p class="text-muted">Sign in to access your favorite movies</p>
                        </div>

                        <form wire:submit="login">
                            <div class="mb-3">
                                <label for="email" class="form-label text-light">Email Address</label>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email"
                                    wire:model="email"
                                    placeholder="Enter your email"
                                    required
                                >
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label text-light">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password"
                                    wire:model="password"
                                    placeholder="Enter your password"
                                    required
                                >
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="remember"
                                        wire:model="remember"
                                    >
                                    <label class="form-check-label text-light" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-2"></i>Signing In...
                                </span>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <div class="alert alert-info" role="alert">
                                <strong>Test Account:</strong><br>
                                Email: jointheteam@aglet.co.za<br>
                                Password: @TeamAglet
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('movies.index') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Back to Movies
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
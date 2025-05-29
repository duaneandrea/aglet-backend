<div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-light mb-3">Get In Touch</h1>
                    <p class="lead text-muted">Have questions or feedback? I'd love to hear from you!</p>
                </div>

                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <h3 class="text-light mb-4">Contact Information</h3>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-user me-2"></i>Full Name
                                    </h5>
                                    <p class="text-light mb-0">Duane Smith</p>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </h5>
                                    <p class="text-light mb-0">
                                        <a href="mailto:duane@example.com" class="text-light text-decoration-none">
                                            duane@example.com
                                        </a>
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-phone me-2"></i>Phone
                                    </h5>
                                    <p class="text-light mb-0">
                                        <a href="tel:+27123456789" class="text-light text-decoration-none">
                                            +27 12 345 6789
                                        </a>
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-share-alt me-2"></i>Social Media
                                    </h5>
                                    <div class="d-flex gap-3">
                                        <a href="https://linkedin.com/in/duanesmith" target="_blank" class="text-light">
                                            <i class="fab fa-linkedin fa-2x"></i>
                                        </a>
                                        <a href="https://github.com/duanesmith" target="_blank" class="text-light">
                                            <i class="fab fa-github fa-2x"></i>
                                        </a>
                                        <a href="https://twitter.com/duanesmith" target="_blank" class="text-light">
                                            <i class="fab fa-twitter fa-2x"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-code me-2"></i>Skills
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-secondary">Laravel</span>
                                        <span class="badge bg-secondary">PHP</span>
                                        <span class="badge bg-secondary">Livewire</span>
                                        <span class="badge bg-secondary">MySQL</span>
                                        <span class="badge bg-secondary">JavaScript</span>
                                        <span class="badge bg-secondary">Vue.js</span>
                                        <span class="badge bg-secondary">Bootstrap</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 h-100" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <h3 class="text-light mb-4">Send a Message</h3>

                                <form wire:submit="submit">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light">Name *</label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            id="name"
                                            wire:model="name"
                                            placeholder="Your full name"
                                            required
                                        >
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label text-light">Email *</label>
                                        <input 
                                            type="email" 
                                            class="form-control @error('email') is-invalid @enderror" 
                                            id="email"
                                            wire:model="email"
                                            placeholder="your.email@example.com"
                                            required
                                        >
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="subject" class="form-label text-light">Subject *</label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('subject') is-invalid @enderror" 
                                            id="subject"
                                            wire:model="subject"
                                            placeholder="What is this about?"
                                            required
                                        >
                                        @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="message" class="form-label text-light">Message *</label>
                                        <textarea 
                                            class="form-control @error('message') is-invalid @enderror" 
                                            id="message"
                                            rows="5"
                                            wire:model="message"
                                            placeholder="Your message here..."
                                            required
                                        ></textarea>
                                        @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin me-2"></i>Sending...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
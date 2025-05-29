<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_movies_page(): void
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSeeLivewire('movie-grid');
    }

    public function test_can_search_movies(): void
    {
        Movie::factory()->create(['title' => 'The Matrix']);
        
        $response = $this->get('/?search=Matrix');
        
        $response->assertStatus(200);
        $response->assertSee('The Matrix');
    }

    public function test_authenticated_user_can_toggle_favorite(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        
        $this->actingAs($user);
        
        $this->assertFalse($user->hasFavorited($movie));
        
        $user->toggleFavorite($movie);
        $this->assertTrue($user->hasFavorited($movie));
        
        $user->toggleFavorite($movie);
        $this->assertFalse($user->hasFavorited($movie));
    }

    public function test_can_view_favorites_page_when_authenticated(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/favorites');
        
        $response->assertStatus(200);
        $response->assertSeeLivewire('favorite-movies');
    }

    public function test_cannot_view_favorites_page_when_not_authenticated(): void
    {
        $response = $this->get('/favorites');
        
        $response->assertRedirect('/login');
    }
} 
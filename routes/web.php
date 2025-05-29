<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies', [MovieController::class, 'grid'])->name('movies.grid');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/api/movies/search', [MovieController::class, 'search'])->name('api.movies.search');
Route::get('/api/movies/suggestions', [MovieController::class, 'suggestions'])->name('api.movies.suggestions');

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('movies.favorites');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

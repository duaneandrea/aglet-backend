<?php

use App\Http\Controllers\Api\MovieController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/movies/search', [MovieController::class, 'search']);
    Route::get('/movies/suggestions', [MovieController::class, 'suggestions']);
    Route::get('/movies/{movie}', [MovieController::class, 'show']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/movies/toggle-favorite', [MovieController::class, 'toggleFavorite']);
        Route::get('/movies/favorites', [MovieController::class, 'favorites']);
    });
}); 
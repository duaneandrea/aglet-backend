<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'release_date',
        'vote_average',
        'vote_count',
        'popularity',
        'genre_ids',
        'original_language',
        'original_title',
        'adult',
        'video'
    ];

    protected $casts = [
        'genre_ids' => 'array',
        'release_date' => 'date',
        'vote_average' => 'decimal:1',
        'popularity' => 'decimal:3',
        'adult' => 'boolean',
        'video' => 'boolean'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites')
            ->withTimestamps();
    }

    public function getPosterUrlAttribute(): string
    {
        return $this->poster_path 
            ? 'https://image.tmdb.org/t/p/w500' . $this->poster_path
            : asset('images/no-poster.jpg');
    }

    public function getBackdropUrlAttribute(): string
    {
        return $this->backdrop_path 
            ? 'https://image.tmdb.org/t/p/w1280' . $this->backdrop_path
            : asset('images/no-backdrop.jpg');
    }

    public function getFormattedReleaseDateAttribute(): string
    {
        return $this->release_date?->format('M d, Y') ?? 'Unknown';
    }

    public function scopePopular($query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    public function scopeByTitle($query, string $title)
    {
        return $query->where('title', 'like', "%{$title}%")
            ->orWhere('original_title', 'like', "%{$title}%");
    }
} 
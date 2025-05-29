<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer', 'exists:movies,id']
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'Movie ID is required.',
            'movie_id.integer' => 'Movie ID must be a valid number.',
            'movie_id.exists' => 'The selected movie does not exist.'
        ];
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(): View
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to view your favorites.');
        }

        return view('favorites.index');
    }
} 
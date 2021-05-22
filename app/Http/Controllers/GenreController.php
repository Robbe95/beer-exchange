<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Transformers\GenreTransformer;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index() {
        return fractal()->collection(Genre::all(), new GenreTransformer());
    }

    public function addGenreToUser(Genre $genre) {
        auth()->user()->genres()->syncWithoutDetaching($genre);
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }
}

<?php

namespace App\Http\Controllers;

use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GenreTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthorizedController extends BaseController
{
    public function me() {
        return fractal()->item(auth()->user(), new UserTransformer())
            ->parseIncludes(['groups', 'applications', 'genres', 'hosted_groups']);
    }

    public function games(Request $request) {
        $search = $request->q;
        $genres = $request->genres;
        $mechanics = $request->mechanics;
        $players_amount = $request->players_amount;
        $age = $request->age;
        $year_published = $request->year_published;
        $play_time = $request->play_time;

        $game = auth()->user()->games()
            ->with('genres')
            ->with('mechanics')
            ->applyFilters(
                search: $search,
                genres: $genres,
                mechanics: $mechanics,
                players_amount: $players_amount,
                age: $age,
                year_published: $year_published,
                play_time: $play_time
            );
        return fractal()->paginate($game, new GameTransformer());
    }

    public function favoriteGames(Request $request) {
        $search = $request->q;
        $genres = $request->genres;
        $mechanics = $request->mechanics;
        $players_amount = $request->players_amount;
        $age = $request->age;
        $year_published = $request->year_published;
        $play_time = $request->play_time;

        $game = auth()->user()->favoriteGames()
            ->with('genres')
            ->with('mechanics')
            ->applyFilters(
                search: $search,
                genres: $genres,
                mechanics: $mechanics,
                players_amount: $players_amount,
                age: $age,
                year_published: $year_published,
                play_time: $play_time
            );
        return fractal()->paginate($game, new GameTransformer());
    }

    public function groups() {
        return fractal()->collection(auth()->user()->hosted_groups, new GroupTransformer());
    }

    public function applications() {
        return fractal()->collection(auth()->user()->applications, new GroupTransformer());
    }

    public function rejected() {
        return fractal()->collection(auth()->user()->rejected, new GroupTransformer());
    }

    public function genres() {
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }

    public function hosted_groups() {
        return fractal()->collection(auth()->user()->hosted_groups, new GroupTransformer());
    }

    public function addGenre() {
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }

    public function addGame() {
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }

    public function addMechanic() {
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }

}

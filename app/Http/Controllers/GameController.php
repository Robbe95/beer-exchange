<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Requests\Games\CreateCustomGameRequest;
use App\Transformers\ApplicationTransformer;
use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GenreTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class GameController extends BaseController
{

    public function index(Request $request) {

        $search = $request->search;
        $genres = $request->genres;
        $mechanics = $request->mechanics;
        $players_amount = $request->players_amount;
        $age = $request->age;
        $year_published = $request->year_published;
        $play_time = $request->play_time;

        $game = Game::public()
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
        if($request->order_by) {
            $game->whereNotNull($request->order_by);
            $request->order_descending ? $game->orderBy($request->order_by,'desc') : $game->orderBy($request->order_by, 'asc');
        }

        return fractal()->paginate($game, new GameTransformer());
    }

    public function games(Request $request) {

        $search = $request->search;
        $genres = $request->genres;
        $mechanics = $request->mechanics;
        $players_amount = $request->players_amount;
        $age = $request->age;
        $year_published = $request->year_published;
        $play_time = $request->play_time;

        $game = auth()->user()->games
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
        if($request->order_by) $request->order_ascending ? $game->orderByAsc($request->order_by) : $game->orderBy($request->order_by);
        return fractal()->paginate($game, new GameTransformer());
    }

    public function groups() {
        return fractal()->collection(auth()->user()->groups, new GroupTransformer());
    }

    public function applications() {
        return fractal()->collection(auth()->user()->applications, new ApplicationTransformer());
    }

    public function genres() {
        return fractal()->collection(auth()->user()->genres, new GenreTransformer());
    }

    public function addCustomGame(CreateCustomGameRequest $request) {
        $validated = $request->validated();
        $validated['public'] = false;
        if($validated['image']) {
            $validated['image'] = 'storage/' . Storage::disk('public')->put('games/images', $validated['image']);
        }
        if($validated['thumbnail']) {
            $validated['thumbnail'] = 'storage/' . Storage::disk('public')->put('games/thumbnails', $validated['thumbnail']);
        }

        $game = Game::create($validated);
        auth()->user()->games()->syncWithoutDetaching([$game->id, ['type' => 'owned']]);
        return fractal()->item($game, new GameTransformer());
    }

    public function favoriteGame(Game $game) {
        auth()->user()->games()->syncWithoutDetaching([$game->id, ['type' => 'favorite']]);
        return fractal()->item($game, new GameTransformer());
    }

    public function ownGame(Game $game) {
        auth()->user()->games()->attach($game->id, ['type' => 'owned']);
        dd('test');
        return fractal()->item($game, new GameTransformer());
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Transformers\ApplicationTransformer;
use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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

        $game = Game::public()->with('genres')->with('mechanics');

        if($search)
        {
            $game = $game->where('title', 'LIKE', '%' . $search . '%');
        }
        if($genres)
        {
            $game = $game->whereHas('genres', function($q) use ($genres) {
                foreach ($genres as $key=>$genre){
                    if($key == 0) {
                        $q->where('genres.title', 'like',  '%' . $genre .'%');
                    } else {
                        $q->orWhere('genres.title', 'like',  '%' . $genre .'%');
                    }
                }
            });
        }

        if($mechanics) {
            $game = $game->whereHas('mechanics', function($q) use ($mechanics) {
                foreach ($mechanics as $key=>$mechanic){
                    if($key == 0) {
                        $q->where('mechanics.title', 'like',  '%' . $mechanic .'%');
                    } else {
                        $q->orWhere('mechanics.title', 'like',  '%' . $mechanic .'%');
                    }
                }
            });
        }

        if($players_amount) {
            $game = $game->where('min_players', '<=', $players_amount)->where('max_players', '>=', $players_amount);
        }

        if($age) {
            $game = $game->where('min_age', '<=', $age);
        }

        if($year_published) {
            $game = $game->where('year_published', $year_published);
        }

        if($play_time) {
            $game = $game->where('min_play_time', '<=', $play_time)->where('max_play_time', '>=', $play_time);
        }

        return fractal()->paginate($game, new GameTransformer());
    }

    public function games() {
        return fractal()->collection(auth()->user()->games, new ApplicationTransformer());
    }

    public function groups() {
        return fractal()->collection(auth()->user()->groups, new GroupTransformer());
    }

    public function applications() {
        return fractal()->collection(auth()->user()->applications, new ApplicationTransformer());
    }

    public function genres() {
        return fractal()->collection(auth()->user()->genres, new ApplicationTransformer());
    }
}

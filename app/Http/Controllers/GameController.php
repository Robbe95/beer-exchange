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

    public function index() {
        return fractal()->paginate(Game::public(), new GameTransformer());
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

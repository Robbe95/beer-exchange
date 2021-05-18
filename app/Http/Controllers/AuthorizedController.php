<?php

namespace App\Http\Controllers;

use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GenreTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Routing\Controller as BaseController;

class AuthorizedController extends BaseController
{
    public function me() {
        return fractal()->item(auth()->user(), new UserTransformer())
            ->parseIncludes(['games', 'groups', 'applications', 'genres', 'hosted_groups']);
    }

    public function games() {
        return fractal()->collection(auth()->user()->games, new GameTransformer());
    }

    public function groups() {
        return fractal()->collection(auth()->user()->groups, new GroupTransformer());
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
}

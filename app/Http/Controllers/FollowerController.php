<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Group;
use App\Models\Location;
use App\Models\User;
use App\Transformers\ApplicationTransformer;
use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\LocationTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FollowerController extends BaseController
{
    public function followers() {
        return fractal()->collection(auth()->user()->followers, new UserTransformer());
    }

    public function followed() {
        return fractal()->collection(auth()->user()->followed, new UserTransformer());
    }

    public function followersOfUser(User $user) {
        return fractal()->collection($user->followers, new UserTransformer());
    }

    public function followedOfUser(User $user) {
        return fractal()->collection($user->followed, new UserTransformer());
    }

    public function followedGroups() {
        return fractal()->collection(auth()->user()->groupsOfFollowed, new GroupTransformer())->parseIncludes('host');
    }

    public function followUser(User $user) {
        auth()->user()->followed()->syncWithoutDetaching($user);
        return fractal()->collection(auth()->user()->followed, new UserTransformer());
    }

    public function unfollowUser(User $user) {
        auth()->user()->followed()->detach($user);
        return fractal()->collection(auth()->user()->followed, new UserTransformer());
    }
}

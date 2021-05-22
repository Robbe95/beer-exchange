<?php

namespace App\Http\Controllers;

use App\Helpers\GameFilterHelper;
use App\Models\Game;
use App\Models\Group;
use App\Models\Location;
use App\Models\User;
use App\Requests\Groups\CreateGroupRequest;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GroupController extends BaseController
{
    public function index() {
        return fractal()->collection(Group::notDone()->get(), new GroupTransformer());
    }

    public function show(Group $group) {
        $includes = ['host'];
        $user = auth()->guard('api')->user();
        if($user && $user->isPlayer($group)) {
            $includes = ['host', 'applicants', 'rejected', 'players', 'games', 'location'];
        }
        return fractal()->item($group, new GroupTransformer())->parseIncludes($includes);
    }

    public function games(Group $group) {
        return fractal()->collection($group->games, new GameTransformer());
    }

    public function gamesWithVotes(Group $group) {
        return fractal()->collection($group->gamesWithVotes, new GameTransformer());
    }

    public function players(Group $group) {
        return fractal()->collection($group->players, new UserTransformer());
    }

    public function applicants(Group $group) {
        return fractal()->collection($group->applications, new UserTransformer());
    }

    public function rejected(Group $group) {
        return fractal()->collection($group->rejected, new UserTransformer());
    }

    public function host(Group $group) {
        return fractal()->item($group->host, new UserTransformer());
    }

    public function location(Group $group) {
        return fractal()->item($group->location, new LocationTransformer());
    }

    public function store(CreateGroupRequest $request) {
        $validated = $request->validated();
        if(isset($validated['image'])) {
            $validated['image'] = 'storage/' . Storage::disk('public')->put('groups', $validated['image']);
        }
        $validated['host_id'] = auth()->user()->id;
        $group = Group::create($validated);
        return fractal()->item($group, new GroupTransformer())->parseIncludes(['host', 'applicants', 'rejected', 'players', 'games']);
    }

    public function apply(Group $group, Request $request) {
        $group->applications()->syncWithoutDetaching([
                auth()->user()->id =>
                    [
                        'description' => $request->description,
                        'status' => 'requested'
                    ]
            ]
        );
        return fractal()->item($group, new GroupTransformer())->parseIncludes(['host']);
    }

    public function reject(Group $group, User $user, Request $request) {
        $group->applications()->syncWithoutDetaching([
            $user->id =>
                [
                    'description' => $request->description,
                    'status' => 'rejected'
                ]
            ]
        );
        return fractal()->item($group, new GroupTransformer())->parseIncludes(['host', 'applicants', 'rejected', 'players', 'games']);
    }

    public function accept(Group $group, User $user, Request $request) {
        $group->applications()->syncWithoutDetaching([
                $user->id =>
                    [
                        'description' => $request->description,
                        'status' => 'accepted'
                    ]
            ]
        );
        return fractal()->item($group, new GroupTransformer())->parseIncludes(['host', 'applicants', 'rejected', 'players', 'games']);
    }

    public function getPossibleGames(Group $group, Request $request) {
        $search = $request->q;
        $genres = $request->genres;
        $mechanics = $request->mechanics;
        $players_amount = $request->players_amount;
        $age = $request->age;
        $year_published = $request->year_published;
        $play_time = $request->play_time;



        $gameCollection = $group->host->games()->with('genres')->with('mechanics')->get();
        foreach($group->players as $player) {
            $gameCollection = $gameCollection->merge($player->games);
        }
        $gameCollection = $gameCollection->unique();
        $favoriteGames = $group->host->favoriteGames()->with('genres')->with('mechanics')->get();;
        foreach($group->players as $player) {
            $favoriteGames = $favoriteGames->merge($player->favoriteGames);
        }
        $favoriteGames = $favoriteGames->unique();

        $totalGames = $gameCollection->merge($favoriteGames);
        $preferredGames = collect();
        foreach($totalGames as $game) {
            $game->favorite = $favoriteGames->find($game->id) ? 1 : 0;
            $game->owned = $gameCollection->find($game->id) ? 1 : 0;
            $preferredGames->push($game);
        }
        $preferredGames = GameFilterHelper::filter(
                $preferredGames,
                search: $search,
                genres: $genres,
                mechanics: $mechanics,
                players_amount: $players_amount,
                age: $age,
                year_published: $year_published,
                play_time: $play_time
            );
        if($request->order_by) $request->order_ascending ? $preferredGames->orderByAsc($request->order_by) : $preferredGames->orderBy($request->order_by);

        return response()->json(['preferred_games' => $preferredGames]);
    }
}

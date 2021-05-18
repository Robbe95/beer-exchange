<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameVote;
use App\Models\Genre;
use App\Models\GenreVote;
use App\Models\Group;
use App\Models\Mechanic;
use App\Models\MechanicVote;
use App\Models\User;
use App\Transformers\ApplicationTransformer;
use App\Transformers\BaseTransformer;
use App\Transformers\GameTransformer;
use App\Transformers\GameVoteTransformer;
use App\Transformers\GenreVoteTransformer;
use App\Transformers\GroupTransformer;
use App\Transformers\MechanicVoteTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class VoteController extends BaseController
{

    public function voteGame(Group $group, Game $game) {
        $vote = GameVote::firstOrCreate(
            [
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'game_id' => $game->id,
            ]
        );
        return $vote;
    }

    public function voteGenre(Group $group, Genre $genre) {
        $vote = GenreVote::firstOrCreate(
            [
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'genre_id' => $genre->id,
            ]
        );
        return $vote;
    }

    public function voteMechanic(Group $group, Mechanic $mechanic) {
        $vote = MechanicVote::firstOrCreate(
            [
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'mechanic_id' => $mechanic->id,
            ]
        );
        return $vote;
    }

    public function undoVoteGame() {
        return fractal()->collection(auth()->user()->groups, new GroupTransformer());
    }

    public function undoVoteGenre() {
        return fractal()->collection(auth()->user()->applications, new ApplicationTransformer());
    }

    public function getVoteGames(Group $group) {
        return fractal()->collection($group->gameVotes, new GameVoteTransformer());
    }

    public function getVoteGenres(Group $group) {
        return fractal()->collection($group->genreVotes, new GenreVoteTransformer());
    }

    public function getVoteMechanics(Group $group) {
        return fractal()->collection($group->mechanicVotes, new MechanicVoteTransformer());
    }
}

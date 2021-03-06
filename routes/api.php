<?php

use App\Http\Controllers\AuthorizedController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\VoteController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['middleware' => ['is.host']], function () {
        Route::post('/groups/{group}/users/{user}/accept', [GroupController::class, 'accept']);
        Route::post('/groups/{group}/users/{user}/reject', [GroupController::class, 'reject']);
        Route::get('/groups/{group}/rejected', [GroupController::class, 'rejected']);
        Route::get('/groups/{group}/applicants', [GroupController::class, 'applicants']);

    });

    Route::group(['middleware' => ['is.player']], function () {
        Route::get('/groups/{group}/games', [GroupController::class, 'games']);
        Route::get('/groups/{group}/games-with-votes', [GroupController::class, 'gamesWithVotes']);

        Route::get('/groups/{group}/players', [GroupController::class, 'players']);
        Route::get('/groups/{group}/host', [GroupController::class, 'host']);
        Route::get('/groups/{group}/location', [GroupController::class, 'location']);

        Route::post('/groups/{group}/games/{game}/vote', [VoteController::class, 'voteGame']);
        Route::post('/groups/{group}/genres/{genre}/vote', [VoteController::class, 'voteGenre']);
        Route::post('/groups/{group}/mechanics/{mechanic}/vote', [VoteController::class, 'voteMechanic']);

        Route::post('/groups/{group}/message', [MessageController::class, 'broadcast']);

    });

    Route::get('/me', [AuthorizedController::class, 'me']);
    Route::get('/me/games', [AuthorizedController::class, 'games']);
    Route::get('/me/favorite-games', [AuthorizedController::class, 'favoriteGames']);

    Route::get('/me/groups', [AuthorizedController::class, 'groups']);
    Route::get('/me/applications', [AuthorizedController::class, 'applications']);
    Route::get('/me/genres', [AuthorizedController::class, 'genres']);
    Route::get('/me/rejected', [AuthorizedController::class, 'rejected']);
    Route::get('/me/hosted-groups', [AuthorizedController::class, 'hosted_groups']);
    Route::post('/me/games', [GameController::class, 'addCustomGame']);

    Route::get('/followers', [FollowerController::class, 'followers']);
    Route::get('/followed', [FollowerController::class, 'followed']);
    Route::get('/followed/groups', [FollowerController::class, 'followedGroups']);
    Route::post('/users/{user}/follow', [FollowerController::class, 'followUser']);
    Route::post('/users/{user}/unfollow', [FollowerController::class, 'unfollowUser']);

    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{group}/possible-games', [GroupController::class, 'getPossibleGames']);

    Route::post('/groups/{group}/apply', [GroupController::class, 'apply']);
    Route::get('/groups/{group}/game-votes', [VoteController::class, 'getVoteGames']);
    Route::get('/groups/{group}/genre-votes', [VoteController::class, 'getVoteGenres']);
    Route::get('/groups/{group}/mechanic-votes', [VoteController::class, 'getVoteMechanics']);

    Route::post('/user/mechanics/{mechanic}', [MechanicController::class, 'addMechanicToUser']);
    Route::post('/user/genres/{genre}', [GenreController::class, 'addGenreToUser']);
    Route::post('/me/games/{game}/own', [GameController::class, 'ownGame']);
    Route::post('/me/games/{game}/favorite', [GameController::class, 'favoriteGame']);
});

Route::get('/users/{user}/followers', [FollowerController::class, 'followersOfUser']);
Route::get('/users/{user}/followed', [FollowerController::class, 'followedOfUser']);

Route::resource('/users', UserController::class);
Route::post('/register', [UserController::class, 'store']);

Route::resource('/games', GameController::class);
Route::get('/groups', [GroupController::class, 'index']);
Route::get('/groups/{group}', [GroupController::class, 'show']);
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/mechanics', [MechanicController::class, 'index']);


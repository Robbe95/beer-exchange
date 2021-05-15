<?php

use App\Http\Controllers\AuthorizedController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
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
        Route::get('/groups/{group}/players', [GroupController::class, 'players']);
        Route::get('/groups/{group}/host', [GroupController::class, 'host']);
        Route::get('/groups/{group}/location', [GroupController::class, 'location']);
    });

    Route::get('/me', [AuthorizedController::class, 'me']);
    Route::get('/me/games', [AuthorizedController::class, 'games']);
    Route::get('/me/groups', [AuthorizedController::class, 'groups']);
    Route::get('/me/applications', [AuthorizedController::class, 'applications']);
    Route::get('/me/genres', [AuthorizedController::class, 'genres']);
    Route::get('/me/rejected', [AuthorizedController::class, 'rejected']);

    Route::post('/groups/{group}/apply', [GroupController::class, 'apply']);


});

Route::resource('/users', UserController::class);
Route::resource('/games', GameController::class);
Route::resource('/groups', GroupController::class);


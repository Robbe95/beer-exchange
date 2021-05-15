<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'image',
        'public',
        'host_id',
        'information',
        'private_information',
        'start_time'

    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    //RELATIONSHIPS
    public function host() {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function players() {
        return $this->belongsToMany(User::class, 'applications', 'group_id', 'user_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'accepted');
    }

    public function applications() {
        return $this->belongsToMany(User::class, 'applications', 'group_id', 'user_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'requested');
    }

    public function rejected() {
        return $this->belongsToMany(User::class, 'applications', 'group_id', 'user_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'rejected');
    }

    public function gameVotes() {
        return $this->hasMany(GameVote::class);
    }

    public function genreVotes() {
        return $this->hasMany(GenreVote::class);
    }


    //ATTRIBUTES
    public function getGamesAttribute() {
        $gameCollection = $this->host->games;
        foreach($this->players as $player) {
            $gameCollection = $gameCollection->merge($player->games);
        }
        return $gameCollection->unique();
    }

    public function getGamesWithVotesAttribute() {
        $gameVotes = $this->gameVotes;
        $genreVotes = $this->genreVotes;

        $gameCollection = $this->host->games;
        foreach($this->players as $player) {
            $gameCollection = $gameCollection->merge($player->games);
        }
        foreach($gameCollection as &$game) {
            $game->votes = 0;
            $game->votes += $gameVotes->filter(function ($e) use ($game) { return $e->game_id === $game->id;})->count();
            $game->votes += $genreVotes->filter(function ($e) use ($game) { return $game->genres()->pluck('genres.id')->contains($e->genre_id); })->count();
        }
        return $gameCollection->unique()->sortByDesc('votes');
    }


    //SCOPES
    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    public function scopeNotDone($query)
    {
        return $query->where('start_time', '>', Carbon::now());
    }


}

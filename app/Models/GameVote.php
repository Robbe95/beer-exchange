<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'game_id',
        'user_id',
        'group_id',
        'chosen'
    ];

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

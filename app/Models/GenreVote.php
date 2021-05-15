<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenreVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'genre_id',
        'user_id',
        'group_id',

        'chosen'
    ];

    public function genre(){
        return $this->belongsTo(Genre::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

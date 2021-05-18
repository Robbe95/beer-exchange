<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'description',
        'title',
        'image',
        'thumbnail',
        'year_published',
        'min_players',
        'max_players',
        'min_play_time',
        'max_play_time',
        'min_age',
        'public'
];

    public function setPublic() {
        $this->public = true;
        $this->save();
    }

    public function setPrivate() {
        $this->public = false;
        $this->save();
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'game_genre', 'game_id', 'genre_id');
    }

    public function mechanics() {
        return $this->belongsToMany(Mechanic::class, 'game_mechanic', 'game_id', 'mechanic_id');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }
}

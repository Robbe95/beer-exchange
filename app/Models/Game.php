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
        'public',
        'rating',
        'users_rated',
        'rank',
        'average',
        'bayes_average'
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

    public function scopeApplyFilters(
        $query,
        string $search = null,
        array $genres = null,
        array $mechanics= null,
        int $players_amount = null,
        int $age = null,
        int $year_published = null,
        int $play_time = null
    )
    {
        if($search)
        {
            $query = $query->where('title', 'LIKE', '%' . $search . '%');
        }
        if($genres)
        {
            $query = $query->whereHas('genres', function($q) use ($genres) {
                foreach ($genres as $key=>$genre){
                    if($key == 0) {
                        $q->where('genres.title', 'like',  '%' . $genre .'%');
                    } else {
                        $q->orWhere('genres.title', 'like',  '%' . $genre .'%');
                    }
                }
            });
        }

        if($mechanics) {
            $query = $query->whereHas('mechanics', function($q) use ($mechanics) {
                foreach ($mechanics as $key=>$mechanic){
                    if($key == 0) {
                        $q->where('mechanics.title', 'like',  '%' . $mechanic .'%');
                    } else {
                        $q->orWhere('mechanics.title', 'like',  '%' . $mechanic .'%');
                    }
                }
            });
        }

        if($players_amount) {
            $query = $query->where('min_players', '<=', $players_amount)->where('max_players', '>=', $players_amount);
        }

        if($age) {
            $query = $query->where('min_age', '<=', $age);
        }

        if($year_published) {
            $query = $query->where('year_published', $year_published);
        }

        if($play_time) {
            $query = $query->where('min_play_time', '<=', $play_time)->where('max_play_time', '>=', $play_time);
        }
        return $query;
    }
}

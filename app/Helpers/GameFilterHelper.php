<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameFilterHelper {
    static function filter(
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
            $query = $query->filter(function ($value) use ($search) {
                return str_contains(strtolower($value->title), strtolower($search));
            });
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
    }}

<?php

namespace App\Transformers;

use App\Models\Game;
use App\Models\User;

class GameTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['genres', 'mechanics'];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Game $resource) {
        if($resource->image && substr($resource->image, 0, 7) === 'storage') {
            $resource->image = env('APP_URL') .$resource->image;
        }
        if($resource->thumbnail && substr($resource->thumbnail, 0, 7) === 'storage') {
            $resource->thumbnail = env('APP_URL') .$resource->thumbnail;
        }

        $return = [
            'id' => $resource->id,
            'title' => $resource->title,
            'description' => $resource->description,
            'image' => $resource->image ?? null,
            'thumbnail' => $resource->thumbnail ?? null,

            'year_published' => $resource->year_published ?? null,
            'min_players' => $resource->min_players ?? null,
            'max_players' => $resource->max_players ?? null,
            'min_play_time' => $resource->min_play_time ?? null,
            'max_play_time' => $resource->max_play_time ?? null,
            'min_age' => $resource->min_age ?? null,
            'rank' => $resource->rank ?? null,
            'average' => $resource->average ?? null,
            'bayes_average' => $resource->bayes_average ?? null,
            'users_rated' => $resource->users_rated ?? null



        ];
        if($resource->votes || $resource->votes === 0) {
            $return['votes'] = $resource->votes;
        }
        return $return;
    }

    public function includeGenres(Game $resource) {
        return $this->collection($resource->genres, new GenreTransformer());
    }

    public function includeMechanics(Game $resource) {
        return $this->collection($resource->mechanics, new MechanicTransformer());
    }
}

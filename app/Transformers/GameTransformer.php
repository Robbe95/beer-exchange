<?php

namespace App\Transformers;

use App\Models\Game;
use App\Models\User;

class GameTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['genres'];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Game $resource) {

        return [
            'id' => $resource->id,
            'title' => $resource->title,
            'description' => $resource->title,
            'image' => $resource->image
        ];
    }

    public function includeGenres(Game $resource) {
        return $this->collection($resource->genres, new GenreTransformer());
    }
}

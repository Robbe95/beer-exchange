<?php

namespace App\Transformers;

use App\Models\Genre;
use App\Models\Profile;
use App\Models\User;

class GenreTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Genre $resource) {
        return [
            'id' => $resource->id,
            'title' => $resource->title
        ];
    }
}

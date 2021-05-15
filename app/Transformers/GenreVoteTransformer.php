<?php

namespace App\Transformers;

use App\Models\Game;
use App\Models\GameVote;
use App\Models\GenreVote;
use App\Models\User;

class GenreVoteTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['user', 'genre'];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(GenreVote $resource) {

        return [];
    }

    public function includeGenre(GenreVote $resource) {
        return $this->item($resource->genre, new GenreTransformer());
    }

    public function includeUser(GenreVote $resource) {
        return $this->item($resource->user, new UserTransformer());
    }
}

<?php

namespace App\Transformers;

use App\Models\Game;
use App\Models\GameVote;
use App\Models\User;

class GameVoteTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['user', 'game'];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(GameVote $resource) {

        return [];
    }

    public function includeGame(GameVote $resource) {
        return $this->item($resource->game, new GameTransformer());
    }

    public function includeUser(GameVote $resource) {
        return $this->item($resource->user, new UserTransformer());
    }
}

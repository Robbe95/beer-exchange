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

        return [
            'id' => $resource->id,
            'created_at' => $resource->created_at->timestamp,
            'updated_at' => $resource->updated_at->timestamp,

        ];
    }

    public function includeGame(GameVote $resource) {
        return $this->item($resource->game, new GameTransformer());
    }

    public function includeUser(GameVote $resource) {
        return $this->item($resource->user, new UserTransformer());
    }
}

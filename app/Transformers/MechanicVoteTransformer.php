<?php

namespace App\Transformers;

use App\Models\Game;
use App\Models\GameVote;
use App\Models\GenreVote;
use App\Models\MechanicVote;
use App\Models\User;

class MechanicVoteTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['user', 'mechanic'];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(MechanicVote $resource) {

        return [];
    }

    public function includeMechanic(MechanicVote $resource) {
        return $this->item($resource->mechanic, new MechanicTransformer());
    }

    public function includeUser(MechanicVote $resource) {
        return $this->item($resource->user, new UserTransformer());
    }
}

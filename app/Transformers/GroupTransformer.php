<?php

namespace App\Transformers;

use App\Models\Group;
use App\Models\Profile;
use App\Models\User;

class GroupTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = ['host', 'players', 'applicants', 'rejected', 'games', 'location'];

    public function __construct() {

    }

    public function transform(Group $resource) {
        return [
            'title' => $resource->title,
            'players_amount' => $resource->players()->count() + 1

        ];
    }

    public function includeHost(Group $resource) {
        return $this->item($resource->host, new UserTransformer());
    }

    public function includePlayers(Group $resource) {
        return $this->collection($resource->players, new UserTransformer());
    }

    public function includeApplicants(Group $resource) {
        return $this->collection($resource->applications, new UserTransformer());
    }

    public function includeRejected(Group $resource) {
        return $this->collection($resource->rejected, new UserTransformer());
    }

    public function includeGames(Group $resource) {
        return $this->collection($resource->games, new GameTransformer());
    }

    public function includeLocation(Group $resource) {
        return $this->nullable_item($resource->location, new LocationTransformer());
    }
}

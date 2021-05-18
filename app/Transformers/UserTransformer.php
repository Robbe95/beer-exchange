<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{

    protected $defaultIncludes = ['profile'];
    protected $availableIncludes = ['games', 'genres', 'applications', 'groups', 'hosted_groups', 'mechanics'];

    public function __construct() {

    }

    public function transform(User $resource) {

        return [
            'id' => $resource->id,
            'email' => $resource->email,
            'name' => $resource->name,
            'followers' => $resource->followers()->count(),
            'followed' => $resource->followed()->count(),
        ];
    }

    public function includeProfile(User $resource) {
        return $this->nullable_item($resource->profile, new ProfileTransformer());
    }

    public function includeGames(User $resource) {
        return $this->collection($resource->games, new GameTransformer());
    }

    public function includeGenres(User $resource) {
        return $this->collection($resource->genres, new GenreTransformer());
    }

    public function includeMechanics(User $resource) {
        return $this->collection($resource->mechanics(), new MechanicTransformer());
    }

    public function includeGroups(User $resource) {
        return $this->collection($resource->groups, new GroupTransformer());
    }

    public function includeHostedGroups(User $resource) {
        return $this->collection($resource->hosted_groups, new GroupTransformer());
    }

    public function includeApplications(User $resource) {
        return $this->collection($resource->applications, new GroupTransformer());
    }
}

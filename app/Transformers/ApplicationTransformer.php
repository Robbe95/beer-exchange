<?php

namespace App\Transformers;

use App\Models\Application;
use App\Models\Profile;
use App\Models\User;

class ApplicationTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = ['user', 'group'];

    public function __construct() {
    }

    public function transform(Application $resource) {

        return [
            'status' => $resource->status,
            'description' => $resource->description,
        ];
    }

    public function includeGroup(Application $resource) {
        return $this->item($resource->group, new GroupTransformer());
    }

    public function includeUser(User $resource) {
        return $this->item($resource->user, new UserTransformer());
    }
}

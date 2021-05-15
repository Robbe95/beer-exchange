<?php

namespace App\Transformers;

use App\Models\Profile;
use App\Models\User;

class ProfileTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Profile $resource) {

        return [
            'id' => $resource->id,
            'bio' => $resource->bio,
            'picture' => $resource->picture,
        ];
    }
}

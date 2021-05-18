<?php

namespace App\Transformers;

use App\Models\Genre;
use App\Models\Mechanic;
use App\Models\Profile;
use App\Models\User;

class MechanicTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Mechanic $resource) {
        return [
            'id' => $resource->id,
            'title' => $resource->title
        ];
    }
}

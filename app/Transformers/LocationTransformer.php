<?php

namespace App\Transformers;

use App\Models\Application;
use App\Models\Location;
use App\Models\Profile;
use App\Models\User;

class LocationTransformer extends BaseTransformer
{

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

    public function __construct() {

    }

    public function transform(Location $resource) {

        return [
            'id' => $resource->id,
            'title' => $resource->title,
            'country' => $resource->country,
            'street' => $resource->street_nr,
            'city' => $resource->city,
            'postcode' => $resource->postcode,
            'region' => $resource->region,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
        ];
    }
}

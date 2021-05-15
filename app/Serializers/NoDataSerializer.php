<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

//Deletes all 'data' fields from collections
class NoDataSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return $data;
    }

    public function null()
    {
        return null;
    }
}

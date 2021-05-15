<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected function nullable_item($resource, $transformer) {

        if($resource === null) {
            return $this->null();
        }

        return $this->item($resource, $transformer);
    }

    protected function nullable_primitive($resource, $transformer) {
        if($resource === null) {
            return $this->null();
        }

        return $this->primitive($resource, $transformer);
    }

    protected function nullable_primitive_item($item) {
        if($item === null) {
            return $this->null();
        }

        return $this->primitive($item, function($item) {
            return $item;
        });
    }


    protected function nullable_float($value) {

        if($value === null) {
            return $value;
        }

        return floatVal($value);
    }


    protected function nullable_int($value) {

        if($value === null) {
            return $value;
        }

        return intVal($value);
    }
}

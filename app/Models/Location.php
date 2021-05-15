<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'country',
        'street',
        'street_nr',
        'city',
        'postcode',
        'region',
        'latitude',
        'longitude',
    ];

    public function setPublic() {
        $this->public = true;
        $this->save();
    }

    public function setPrivate() {
        $this->public = false;
        $this->save();
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use App\Transformers\MechanicTransformer;

class MechanicController extends Controller
{
    public function index() {
        return fractal()->collection(Mechanic::all(), new MechanicTransformer());
    }

    public function addMechanicToUser(Mechanic $mechanic) {
        auth()->user()->mechanics()->syncWithoutDetaching($mechanic);
        return fractal()->collection(auth()->user()->mechanics, new MechanicTransformer());
    }
}

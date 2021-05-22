<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller {
    public function register(RegisterRequest $request) {
        $user = new User([
            'email' => strtolower($request->email),
            'password' => $request->password
        ]);
        $user->save();
        return $this->callSuccess();
    }
}

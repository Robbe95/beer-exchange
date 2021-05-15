<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\BaseTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function index() {
        return fractal()->collection(User::all(), new UserTransformer());
    }

    public function show(User $user) {
        return fractal()->item($user, new UserTransformer())->parseIncludes(['games', 'hosted_groups', 'genres']);
    }

    protected function destroy(User $resource)
    {
        // TODO: Implement destroy() method.
    }

    public function me() {
        return fractal()->item(auth()->user(), new UserTransformer())
            ->parseIncludes(['games', 'groups', 'applications', 'genres', 'hosted_groups']);
    }

    protected function store(Request $request)
    {
        $resource = User::create([
            'email' => strtolower($request->email),
            'name' => $request->name,
            'password' => $request->password,
        ]);
        return fractal()->item($resource, new UserTransformer());
    }

    protected function update()
    {
        // TODO: Implement update() method.
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsPlayer
{
    protected $except = [];

    public function handle($request, Closure $next)
    {
        $group = $request->route('group');
        $user = auth()->user();
        if(is_object($group) && !$user->isPlayer($group)) {
            return response()->json(['error' => 'Not a player'])->setStatusCode(403);
        }
        return $next($request);
    }
}

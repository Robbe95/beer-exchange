<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class ResourceController extends BaseController
{
    abstract protected function index();
    abstract protected function show($resource);
    abstract protected function destroy($resource);
    abstract protected function store(Request $request);
    abstract protected function update(Request $request, $resource);


}

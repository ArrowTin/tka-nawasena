<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ServiceJwt
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Service-Token');

        if (!$token || $token !== env('SERVICE_A_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization'); // Bearer <token>

        // 1. Cek header Authorization
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            // 2. Decode JWT
            $decoded = (array) JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $request->merge([
            'user_id'    => $decoded['sub'],
        ]);

        if ($this->isSupervisor($decoded['roles'] ?? [])) {
            return $next($request);
        }

        return ApiResponse::error('Kamu Bukan Super visor',301);

    }

    /**
     * Cek apakah role termasuk 'student'
     */
    private function isSupervisor(array $roleNames): bool
    {
        return in_array('supervisor', $roleNames);
    }
}

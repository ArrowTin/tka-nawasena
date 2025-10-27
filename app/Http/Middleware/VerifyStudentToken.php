<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class VerifyStudentToken
{
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

        // 3. Optimasi: cek dulu apakah student sudah ada di request
        if ($request->attributes->has('student')) {
            $student = $request->attributes->get('student');
        } else {
            // 4. Ambil student dari DB berdasarkan user_id
            $student = Student::where('user_id', $decoded['sub'])->first();

            // 5. Buat student baru jika belum ada dan role valid
            if (!$student && $this->isStudent($decoded['roles'] ?? [])) {
                $student = Student::create([
                    'user_id'   => $decoded['sub'],
                    'full_name' => $decoded['name'] ?? 'No Name',
                ]);
            }

            // 6. Simpan student ke request supaya controller mudah akses
            if ($student) {
                $request->attributes->set('student', $student);
            }
        }

        // 7. Tambahkan info user_id dan student_id di request secara umum
        $request->merge([
            'user_id'    => $decoded['sub'],
            'student_id' => $student?->id,
            'full_name'  => $decoded['name'] ?? null,
        ]);

        return $next($request);
    }

    /**
     * Cek apakah role termasuk 'student'
     */
    private function isStudent(array $roleNames): bool
    {
        return in_array('student', $roleNames);
    }
}

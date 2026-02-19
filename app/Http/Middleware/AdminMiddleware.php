<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'ไม่มีสิทธิ์เข้าถึง (ต้องเป็นแอดมิน)'], 403);
        }

        return $next($request);
    }
}

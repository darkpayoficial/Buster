<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'ADMIN') {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Acesso não autorizado.'], 403);
            }
            
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

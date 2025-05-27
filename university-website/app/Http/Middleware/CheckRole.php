<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        
        Log::info("CheckRole middleware called", [
            'user_id' => $user ? $user->id : 'guest',
            'user_role' => $user ? $user->role : 'guest',
            'required_roles' => $roles,
            'path' => $request->path()
        ]);
        
        if (!$user) {
            Log::warning("User not authenticated for protected route", ['path' => $request->path()]);
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        if (!in_array($user->role, $roles)) {
            Log::warning("User role not authorized", [
                'user_role' => $user->role,
                'required_roles' => $roles,
                'path' => $request->path()
            ]);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        Log::info("User authorized to access route", [
            'user_role' => $user->role,
            'path' => $request->path()
        ]);

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

     public function handle(Request $request, Closure $next)
    {
        // Contoh sederhana autentikasi API (ganti dengan logika sesuai kebutuhan)
        // Misalnya menggunakan API key yang disimpan di .env
        $apiKey = env('API_KEY');
        
        // Cek header Authorization
        if ($request->header('Authorization') !== 'Bearer ' . $apiKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
    // public function handle($request, Closure $next, $guard = null)
    // {
    //     if ($this->auth->guard($guard)->guest()) {
    //         return response('Unauthorized.', 401);
    //     }

    //     return $next($request);
    // }

    //  public function handle(Request $request, Closure $next)
    // {
    //     $apiKey = $request->header('X-API-KEY');
        
    //     // Validasi API key sederhana (dalam implementasi nyata gunakan metode yang lebih aman)
    //     if (!$apiKey || !$this->isValidApiKey($apiKey)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
        
    //     return $next($request);
    // }
    
    // private function isValidApiKey($key)
    // {
    //     // Daftar API key yang diizinkan (dalam implementasi nyata, simpan di database atau .env)
    //     $validKeys = [
    //         'faculty-api-key-123',
    //         'university-api-key-456'
    //     ];
        
    //     return in_array($key, $validKeys);
    // }
}
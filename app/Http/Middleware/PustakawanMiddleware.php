<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PustakawanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('logged_in')) {
            return redirect('/login')->with('error', 'Anda harus login sebagai pustakawan.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PustakawanAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('pustakawan_logged_in')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        return $next($request);
    }
}

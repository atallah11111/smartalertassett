<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_verified) {
            auth()->logout();
            return redirect('/otp')->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return $next($request);
    }
}

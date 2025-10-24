<?php

namespace Renderbit\Backpack2fa\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = backpack_auth()->user();

        if (!$user) {
            return redirect(backpack_url('login'));
        }

        if ($user->two_factor_secret && !session()->get('auth.two_factor_verified')) {
            return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}

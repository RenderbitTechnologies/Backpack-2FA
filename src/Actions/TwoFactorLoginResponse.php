<?php

namespace Renderbit\Backpack2fa\Actions;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        // Mark as verified
        session(['auth.two_factor_verified' => true]);
        return redirect()->intended(backpack_url('dashboard'));
    }
}

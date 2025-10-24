<?php

namespace Renderbit\Backpack2fa\Http\Controllers;

use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;

class BackpackTwoFactorController extends TwoFactorAuthenticatedSessionController
{
    public function __construct()
    {
        parent::__construct(backpack_auth());
    }
}

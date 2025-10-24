<?php

namespace Renderbit\Backpack2fa\Responses;

use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse as TwoFactorChallengeViewResponseContract;

class BackpackTwoFactorChallengeViewResponse implements TwoFactorChallengeViewResponseContract
{
    public function toResponse($request)
    {
        return response()->view('backpack-2fa::auth.two-factor-challenge', [
            'qrCode' => session('qr_code') ?? null,
            'user_id' => session('user_id') ?? null,
        ]);
    }
}

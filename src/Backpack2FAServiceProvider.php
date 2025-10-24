<?php

namespace Renderbit\Backpack2fa;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Renderbit\Backpack2fa\Actions\TwoFactorLoginResponse;
use Illuminate\Support\Facades\App;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse as TwoFactorChallengeViewResponseContract;
use Renderbit\Backpack2fa\Http\Middleware\EnsureTwoFactorVerified;
use Renderbit\Backpack2fa\Responses\BackpackTwoFactorChallengeViewResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;


class Backpack2FAServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'backpack-2fa');

        $this->publishes([
            __DIR__.'/../config/backpack-2fa.php' => config_path('backpack-2fa.php'),
            __DIR__.'/../config/fortify.php' => config_path('fortify.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/backpack-2fa'),
        ], 'views');

//        Fortify::twoFactorLoginResponseUsing(TwoFactorLoginResponse::class);
        // 2FA Login Response
        App::singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
        App::singleton(TwoFactorChallengeViewResponseContract::class, BackpackTwoFactorChallengeViewResponse::class);

        Fortify::authenticateUsing(function ($request) {
            $credentials = $request->only(backpack_authentication_column(), 'password');
            if (auth('backpack')->attempt($credentials)) {
                return auth('backpack')->user();
            }
        });

        $router = $this->app['router'];
        $router->aliasMiddleware('twofactor', EnsureTwoFactorVerified::class);
        Log::info('Fortify guard: ' . config('fortify.guard'));
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/backpack-2fa.php', 'backpack-2fa');
        $this->mergeConfigFrom(__DIR__.'/../config/fortify.php', 'fortify');

        config(['fortify.guard' => 'backpack']);
    }
}

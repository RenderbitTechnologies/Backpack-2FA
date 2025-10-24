<?php

use Illuminate\Support\Facades\Route;
use Renderbit\Backpack2fa\Http\Controllers\BackpackTwoFactorController;
use Renderbit\Backpack2fa\Http\Controllers\TwoFactorProfileController;

$prefix = config('backpack.base.route_prefix', 'admin');
$middleware = array_merge(
    (array) config('backpack.base.web_middleware', 'web'),
    (array) config('backpack.base.middleware_key', 'admin'),
    ['twofactor'] // your 2FA middleware
);

// Routes that require a logged-in Backpack admin
Route::group([
    'prefix' => $prefix,
    'middleware' => $middleware,
], function () {
    Route::get('two-factor', [TwoFactorProfileController::class, 'index'])->name('admin.twofactor.index');
    Route::post('two-factor/enable', [TwoFactorProfileController::class, 'enable'])->name('admin.twofactor.enable');
    Route::post('two-factor/disable', [TwoFactorProfileController::class, 'disable'])->name('admin.twofactor.disable');
    Route::post('two-factor/recovery-codes', [TwoFactorProfileController::class, 'regenerateRecoveryCodes'])->name('admin.twofactor.recovery');
    Route::post('two-factor/recovery-codes-download', [TwoFactorProfileController::class, 'downloadRecoveryCodes'])->name('admin.twofactor.download');
    Route::get('setup-two-factor', [TwoFactorProfileController::class, 'setupTwoFactorAuthentication'])->name('two-factor.setup');
    Route::get('two-factor/cancel', [TwoFactorProfileController::class, 'cancel2faFlow'])->name('twofactor.cancel');
});

// Fortify’s login challenge routes also need the Backpack guard and web middleware
Route::group([
    'prefix' => $prefix,
    'middleware' => config('backpack.base.web_middleware', 'web'),
], function () {
    Route::get('two-factor-challenge', [BackpackTwoFactorController::class, 'create'])->name('two-factor.login');
    Route::post('two-factor-challenge', [BackpackTwoFactorController::class, 'store'])->name('two-factor.store');
});

# Backpack 2FA

**2FA integration for Laravel Backpack using Fortify**  
Provides Google 2-Factor Authentication for Backpack admin users, including QR codes, recovery codes, and a custom challenge view.

## Package Overview

This package enables secure two-factor authentication for Laravel Backpack admin users. It integrates:

- Laravel Fortify for 2FA handling.
- PragmaRX Google2FA for QR code generation.
- Seamless Backpack admin middleware and guards.
- Recovery codes and 2FA setup flow.

## Features

- Enable/disable 2FA for Backpack admin users
- Generate QR codes for Google Authenticator
- Regenerate/download recovery codes
- Customizable views for 2FA challenge
- Works with Backpack v6+ and Laravel 10+
- Full package-based implementation (no app-level Fortify modifications required)
- Optional middleware `twofactor` to protect routes

## Installation

1. Require the package via Composer:

```bash
composer require renderbit/backpack-2fa
```
2. Publish configuration and views (optional):

```bash
php artisan vendor:publish --provider="Renderbit\Backpack2fa\Backpack2FAServiceProvider" --tag=config
php artisan vendor:publish --provider="Renderbit\Backpack2fa\Backpack2FAServiceProvider" --tag=views
```

3. Verify your backpack guard is set in `config/auth.php`:

```php
'guards' => [
    'backpack' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

4. Clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

## Routes

| Route | HTTP Method | Description |
|-------|------------|-------------|
| `/admin/two-factor` | GET | 2FA dashboard: view status, enable/disable 2FA |
| `/admin/setup-two-factor` | GET | Setup 2FA QR code for the user |
| `/admin/two-factor/enable` | POST | Enable 2FA for the logged-in user |
| `/admin/two-factor/disable` | POST | Disable 2FA for the logged-in user |
| `/admin/two-factor/recovery-codes` | POST | Regenerate recovery codes |
| `/admin/two-factor/recovery-codes-download` | POST | Download recovery codes as a `.txt` file |
| `/admin/two-factor/cancel` | GET | Cancel the 2FA setup flow |
| `/admin/two-factor-challenge` | GET | Display Fortify 2FA login challenge form |
| `/admin/two-factor-challenge` | POST | Submit Fortify 2FA login challenge form |

All `/admin` routes use the backpack guard and Backpack admin middleware automatically.

## Middleware

- `twofactor` → Ensures that users have verified 2FA before accessing protected routes.
- Example usage:
```php
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin', 'twofactor'],
], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
});
```

## Summary - Package Installation in New Projects

- `composer require renderbit/backpack-2fa`
- Publish configs & views
- Clear caches
- Done — routes, controllers, and middleware are ready out-of-the-box.

## License

MIT License © 2025 Renderbit Technologies

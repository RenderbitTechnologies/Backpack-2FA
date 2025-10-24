<?php

namespace Renderbit\Backpack2fa\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Illuminate\Routing\Controller;
use PragmaRX\Google2FA\Google2FA;
use App\Models\User; // Allow this to be overridden via config

class TwoFactorProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = backpack_user();

        if (!$user) {
            return redirect()->route('backpack.auth.login');
        }

        return view('backpack-2fa::auth.twofactor', [
            'enabled' => !is_null($user->two_factor_secret),
        ]);
    }

    public function enable(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $user = backpack_user();

        if ($user->two_factor_secret) {
            return redirect()->back()->with('status', '2FA is already enabled.');
        }

        $user->forceFill([
            'two_factor_secret' => encrypt($provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(fn() => str()->random(10))->all())),
        ])->save();

        return view('backpack-2fa::auth.twofactor-qr', [
            'qrCode' => $this->getQrCode($user),
        ]);
    }

    public function disable(Request $request)
    {
        $user = backpack_user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        return redirect()->back()->with('status', '2FA disabled successfully.');
    }

    public function cancel2faFlow(Request $request)
    {
        $user = config('backpack-2fa.user_model')::find($request->get('param'));
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ]);

        return redirect()->route('admin.twofactor.index')->with('status', '2FA setup cancelled.');
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $user = backpack_user();

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(fn() => str()->random(10))->all())),
        ])->save();

        return redirect()->back()->with('status', 'Recovery codes regenerated.');
    }

    protected function getQrCode($user)
    {
        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);
        return $writer->writeString($qrCodeUrl);
    }

    public function downloadRecoveryCodes()
    {
        $user = backpack_user();

        if (!$user->two_factor_secret) {
            return redirect()->back()->with('error', '2FA is not enabled.');
        }

        $codes = json_decode(decrypt($user->two_factor_recovery_codes ?? ''), true) ?? [];

        return response()->streamDownload(function () use ($codes) {
            foreach ($codes as $code) {
                echo $code . PHP_EOL;
            }
        }, 'recovery-codes.txt');
    }

    public function setupTwoFactorAuthentication(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $user = backpack_user();
        $userID = $user->id;

        if ($user->two_factor_secret) {
            return redirect()->back()->with('status', '2FA is already enabled.');
        }

        $user->forceFill([
            'two_factor_secret' => encrypt($provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(fn() => str()->random(10))->all())),
        ])->save();

        $twoFactorSecret = $this->getQrCode($user);

        session()->forget('auth.two_factor_verified');
        $request->session()->put('login.id', $user->getAuthIdentifier());
        $request->session()->put('login.remember', $request->filled('remember'));

        backpack_auth()->logout();

        return redirect()->route('two-factor.login')
            ->with(['qr_code' => $twoFactorSecret, 'user_id' => $userID]);
    }
}

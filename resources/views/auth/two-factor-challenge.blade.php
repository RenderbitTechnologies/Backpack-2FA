{{----}}

@extends(backpack_view('layouts.auth'))

@section('content')
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg d-none d-lg-block">
                    @if($qrCode)
                        <div class="card card-md">
                            <div class="pt-0">
                                <div class="login-box">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body login-card-body">
                                            <h2 class="h2 text-center my-4">{{ __('Two-Factor Setup') }}</h2>
                                            <p class="text-muted text-center mb-4">Please scan this QR code
                                                to continue.</p>
                                            <div class="mb-4 text-center">{!! $qrCode !!}</div>
                                            <p>
                                                You can use any popular TOTP (Time-based One-Time Password) app to scan
                                                the QR code above. Once your account is added to the app, it will
                                                generate a verification code that you must enter to proceed.
                                                Some recommended TOTP apps include:
                                            </p>
                                            <ul>
                                                <li>
                                                    <strong>Authy</strong>:
                                                    <a href="https://apps.apple.com/app/authy/id494168017"
                                                       target="_blank">iOS</a> |
                                                    <a href="https://play.google.com/store/apps/details?id=com.authy.authy"
                                                       target="_blank">Android</a>
                                                </li>
                                                <li>
                                                    <strong>Google Authenticator</strong>:
                                                    <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                                                       target="_blank">iOS</a> |
                                                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                                       target="_blank">Android</a>
                                                </li>
                                                <li>
                                                    <strong>Microsoft Authenticator</strong>:
                                                    <a href="https://apps.apple.com/app/microsoft-authenticator/id983156458"
                                                       target="_blank">iOS</a> |
                                                    <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator"
                                                       target="_blank">Android</a>
                                                </li>
                                            </ul>
                                            <p>
                                                Simply download any of the above apps, scan the QR code, and use the
                                                generated code to complete your two-factor authentication setup.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @include('svg.login-illustration')
                    @endif
                </div>

                <div class="col-lg">
                    <div class="container-tight">
                        <div class="text-center mb-4 display-6 auth-logo-container">
                            {!! backpack_theme_config('project_logo') !!}
                        </div>
                        <div class="card card-md">
                            <div class="pt-0">
                                <div class="login-box">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body login-card-body">

                                            <h2 class="h2 text-center my-4">{{ __('Two-Factor Authentication') }}</h2>
                                            <p class="text-muted text-center mb-4">Please enter your authentication code
                                                to continue.</p>

                                            <form method="POST" action="{{ url('two-factor-challenge') }}"
                                                  autocomplete="off" novalidate>
                                                @csrf

                                                {{-- Authenticator Code --}}
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                           for="code">{{ __('Authenticator Code') }}</label>
                                                    <div class="input-group input-group-flat">
                            <span class="input-group-text p-0 px-2">
                                <i class="la la-shield-alt"></i>
                            </span>
                                                        <input type="text"
                                                               name="code"
                                                               id="code"
                                                               value="{{ old('code') }}"
                                                               class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                                                               placeholder="Enter 6-digit code"
                                                               autofocus
                                                               autocomplete="one-time-code">
                                                    </div>
                                                    @if ($errors->has('code'))
                                                        <div class="invalid-feedback">{{ $errors->first('code') }}</div>
                                                    @endif
                                                </div>
                                                @if($qrCode)
                                                    <p>
                                                        You can download the recovery codes from the 2FA settings page.
                                                    </p>
                                                @else
                                                    <div class="text-center text-muted my-3">
                                                        <span>— OR —</span>
                                                    </div>
                                                    {{-- Recovery Code --}}
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                               for="recovery_code">{{ __('Recovery Code') }}</label>
                                                        <div class="input-group input-group-flat">
                                                        <span class="input-group-text p-0 px-2">
                                                            <i class="la la-key"></i>
                                                        </span>
                                                            <input type="text"
                                                                   name="recovery_code"
                                                                   id="recovery_code"
                                                                   value="{{ old('recovery_code') }}"
                                                                   class="form-control {{ $errors->has('recovery_code') ? 'is-invalid' : '' }}"
                                                                   placeholder="Enter recovery code"
                                                                   autocomplete="off">
                                                        </div>
                                                        @if ($errors->has('recovery_code'))
                                                            <div
                                                                class="invalid-feedback">{{ $errors->first('recovery_code') }}</div>
                                                        @endif
                                                    </div>

                                                    {{-- Error Message --}}
                                                    @if ($errors->any() && !$errors->has('code') && !$errors->has('recovery_code'))
                                                        <div
                                                            class="alert alert-danger d-flex align-items-center mt-3 mb-3"
                                                            role="alert">
                                                            <i class="la la-exclamation-circle me-2"></i>
                                                            <div>{{ $errors->first() }}</div>
                                                        </div>
                                                    @endif
                                                @endif

                                                {{-- Verify Button --}}
                                                <div class="form-footer">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="la la-check-circle me-1"></i> {{ __('Verify') }}
                                                    </button>
                                                </div>
                                            </form>
                                            <div class="text-center mt-3">
                                                <a href="{{ $qrCode ? route('twofactor.cancel', ['param' => $user_id]) : route('backpack.auth.logout') }}"
                                                   class="text-muted small">
                                                    <i class="la la-sign-out-alt me-1"></i> {{ __('Back to Login') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

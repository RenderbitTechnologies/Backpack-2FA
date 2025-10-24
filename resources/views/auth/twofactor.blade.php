@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <h3 class="mb-4">Two-Factor Authentication</h3>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="row mb-3">
            <!-- Popular TOTP Apps Card -->
            <div class="col-md-12 mb-3">
                <div class="card p-3 h-100">
                    <h5>Popular TOTP Apps</h5>
                    <p>Install one of these apps on your device to use TOTP Login:</p>
                    <ul>
                        <li>
                            <strong>Google Authenticator</strong> -
                            <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank">iOS</a>
                            |
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                               target="_blank">Android</a>
                        </li>
                        <li>
                            <strong>Authy</strong> -
                            <a href="https://apps.apple.com/app/authy/id494168017" target="_blank">iOS</a> |
                            <a href="https://play.google.com/store/apps/details?id=com.authy.authy" target="_blank">Android</a>
                        </li>
                        <li>
                            <strong>Microsoft Authenticator</strong> -
                            <a href="https://apps.apple.com/app/microsoft-authenticator/id983156458"
                               target="_blank">iOS</a> |
                            <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator"
                               target="_blank">Android</a>
                        </li>
                        <li>
                            <strong>LastPass Authenticator</strong> -
                            <a href="https://apps.apple.com/app/lastpass-authenticator/id1079110004"
                               target="_blank">iOS</a> |
                            <a href="https://play.google.com/store/apps/details?id=com.lastpass.authenticator"
                               target="_blank">Android</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (!$enabled)
            <form action="{{ route('admin.twofactor.enable') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Enable 2FA</button>
            </form>
        @else
            <!-- Recovery Codes Card -->
            <div class="card p-3 mb-3">
                <h5>Recovery Codes:</h5>
                <form method="POST" action="{{ route('admin.twofactor.download') }}">
                    @csrf
                    <button class="btn btn-success">Download Codes</button>
                </form>
                <form method="POST" action="{{ route('admin.twofactor.recovery') }}">
                    @csrf
                    <button class="btn btn-outline-secondary mt-2">Regenerate Codes</button>
                </form>
            </div>

            <!-- Disable 2FA -->
            <form method="POST" action="{{ route('admin.twofactor.disable') }}">
                @csrf
                <button class="btn btn-danger">Disable 2FA</button>
            </form>
        @endif
    </div>
@endsection

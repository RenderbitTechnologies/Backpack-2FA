@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <h3 class="mb-4">Two-Factor Authentication QR Code</h3>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="row mb-3">
            <!-- QR Code Card -->
            <div class="col-md-6 mb-3">
                <div class="card p-3 h-100">
                    <h5>Scan this QR code with your Authenticator app:</h5>
                    <div>{!! $qrCode !!}</div>
                </div>
            </div>

            <!-- Popular TOTP Apps Card -->
            <div class="col-md-6 mb-3">
                <div class="card p-3 h-100">
                    <h5>Popular TOTP Apps</h5>
                    <p>Install one of these apps on your device to scan the QR code:</p>
                    <ul>
                        <li>
                            <strong>Google Authenticator</strong> -
                            <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                               target="_blank">iOS</a> |
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
        <div class="d-flex align-items-center gap-2">
            <!-- Cancel / Disable 2FA -->
            <form method="GET" action="{{ route('twofactor.cancel', ['param' => backpack_user()->id]) }}">
                @csrf
                <button type="submit" class="btn btn-danger">Cancel</button>
            </form>

            <a href="{{ route('admin.twofactor.index') }}" class="btn btn-primary">Proceed</a>
        </div>

    </div>
@endsection

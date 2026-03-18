<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Polls</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>window.Laravel = { csrfToken: "{{ csrf_token() }}", user: @json(auth()->user()) };</script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Polls</a>
    <div>
        @auth
            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.polls.index') }}">Dashboard</a>
            <form style="display:inline" method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-link">Logout</button></form>
        @else
            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.login') }}">Admin Login</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.register') }}">Register Admin</a>
        @endauth
    </div>
  </div>
</nav>
<div class="container my-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Please fix the following:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Pusher + Echo -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
<script>
    window._axios = axios;
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Echo setup (Pusher)
    (function initLaravelEchoOnce() {
        if (window.__echoInitialized) return;
        window.__echoInitialized = true;

        // Optional: helps debug WS connection in browser console
        if (window.Pusher) {
            window.Pusher.logToConsole = true;
        }

        var EchoConstructor = (window.Echo && window.Echo.default) ? window.Echo.default : window.Echo;
        if (typeof EchoConstructor !== 'function') {
            console.error('Laravel Echo constructor not found on window.Echo', window.Echo);
            return;
        }

        window.Echo = new EchoConstructor({
            broadcaster: 'reverb',
            key: "{{ config('broadcasting.connections.reverb.key') }}",
            wsHost: "{{ config('broadcasting.connections.reverb.options.host') }}",
            wsPort: {{ (int) config('broadcasting.connections.reverb.options.port') }},
            wssPort: {{ (int) config('broadcasting.connections.reverb.options.port') }},
            forceTLS: {{ config('broadcasting.connections.reverb.options.scheme') === 'https' ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss']
        });

        try {
            window.Echo.connector?.pusher?.connection?.bind('connected', function() {
                console.log('[Echo] connected');
            });
            window.Echo.connector?.pusher?.connection?.bind('error', function(err) {
                console.error('[Echo] connection error', err);
            });
        } catch (e) {
            console.warn('[Echo] could not bind connection events', e);
        }
    })();
</script>

@stack('scripts')
</body>
</html>
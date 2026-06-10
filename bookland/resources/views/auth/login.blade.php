<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} — Sign in</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #f5f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* ── Card wrapper ── */
        .login-card {
            display: flex;
            width: 100%;
            max-width: 880px;
            min-height: 540px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 40px rgba(0,0,0,0.10);
        }

        /* ── Left panel ── */
        .left-panel {
            flex: 1;
            background: #1a1a2e;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(83,74,183,0.18);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(29,158,117,0.14);
        }

        .brand {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .brand-logo {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #534AB7, #1D9E75);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .brand-logo svg {
            width: 30px; height: 30px;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.5rem;
            letter-spacing: -0.3px;
        }

        .brand-desc {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
            max-width: 200px;
        }

        .decorative-dots {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 1;
        }

        .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }

        .dot.active {
            background: rgba(83,74,183,0.8);
            width: 20px;
            border-radius: 3px;
        }

        /* ── Right panel (form) ── */
        .right-panel {
            flex: 1.1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.75rem;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-size: 1.375rem;
            font-weight: 600;
            color: #111;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* ── Fields ── */
        .field {
            margin-bottom: 1.25rem;
        }

        .field label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .input-wrap .input-icon svg {
            width: 17px; height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.75;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .input-wrap input {
            width: 100%;
            height: 42px;
            padding: 0 40px 0 40px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f9fafb;
            color: #111827;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }

        .input-wrap input:focus {
            outline: none;
            border-color: #534AB7;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(83,74,183,0.12);
        }

        .input-wrap input.is-invalid {
            border-color: #ef4444;
            background: #fff5f5;
        }

        .input-wrap input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #9ca3af;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }

        .toggle-pw:hover { color: #6b7280; }

        .toggle-pw svg {
            width: 17px; height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.75;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .error-msg {
            font-size: 0.8125rem;
            color: #ef4444;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Session status ── */
        .status-msg {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: #15803d;
            margin-bottom: 1.5rem;
        }

        /* ── Remember / forgot row ── */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            font-size: 0.8125rem;
            color: #6b7280;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #534AB7;
            cursor: pointer;
            border-radius: 4px;
        }

        .forgot-link {
            font-size: 0.8125rem;
            color: #534AB7;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .forgot-link:hover { color: #3C3489; text-decoration: underline; }

        /* ── Submit button ── */
        .btn-primary {
            width: 100%;
            height: 43px;
            background: #534AB7;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-primary:hover { background: #3C3489; }
        .btn-primary:active { transform: scale(0.98); }

        .btn-primary svg {
            width: 17px; height: 17px;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            body { padding: 0; align-items: stretch; }
            .login-card { flex-direction: column; border-radius: 0; min-height: 100vh; box-shadow: none; }
            .left-panel { flex: none; min-height: 180px; padding: 2rem; }
            .brand-desc { display: none; }
            .decorative-dots { display: none; }
            .right-panel { flex: 1; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-card">

        {{-- ── Left branding panel ── --}}
        <div class="left-panel">
            <div class="brand">
                <div class="brand-logo">
                    {{-- Replace with your own logo/icon --}}
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <div class="brand-name">{{ config('app.name', 'Laravel') }}</div>
                <div class="brand-desc">A beautiful and secure place to manage your work.</div>
            </div>
            <div class="decorative-dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        {{-- ── Right form panel ── --}}
        <div class="right-panel">

            {{-- Session status (e.g. after password reset email) --}}
            @if (session('status'))
                <div class="status-msg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="form-header">
                <h1>Welcome back</h1>
                <p>Sign in with your email and password</p>
            </div>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email address</label>
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="email"
                            autofocus
                            required
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        />
                    </div>
                    @error('email')
                        <p class="error-msg" role="alert">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        />
                        <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <svg id="eye-icon" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-msg" role="alert">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember me + Forgot password --}}
                <div class="options-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }} />
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Sign in
                </button>

            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            // Eye-off paths when visible, eye paths when hidden
            icon.innerHTML = isHidden
                ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                   <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                   <line x1="1" y1="1" x2="23" y2="23"/>`
                : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                   <circle cx="12" cy="12" r="3"/>`;
        }
    </script>

</body>
</html>
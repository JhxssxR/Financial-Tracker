<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PF Trackers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: dark; }
        body { background-color: #0f172a; color: #e2e8f0; }
        .card { background-color: #1e293b; border-radius: 12px; border: 1px solid #334155; }
        .muted { color: #94a3b8; }
        .brand { color: #34d399; }
        .danger { color: #f87171; }
        .container { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .nav { background-color: #0b1220; border-bottom: 1px solid #243043; }
        .nav a { color: #cbd5e1; text-decoration: none; padding: 10px 14px; border-radius: 8px; }
        .nav a.active, .nav a:hover { background-color: #1f2937; color: #fff; }

        /* Page spacing utilities */
        .page-header { display:flex; align-items:center; gap:12px; margin: 8px 0 16px; }
        .stack-section { margin-top: 16px; }
        .page-grid-3 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
        .page-grid-2-1 { display:grid; grid-template-columns:2fr 1fr; gap:16px; }
        .card-pad { padding:16px; }
        .card-pad-lg { padding:18px; }

        /* Simple form styles */
    .form-card { background:#1e293b; border:1px solid #334155; border-radius:12px; padding:28px; width:100%; max-width:560px; min-height:460px; margin:0 auto; }
        .form-title { text-align:center; font-size:26px; font-weight:700; margin-top:8px; }
        .form-subtitle { text-align:center; color:#94a3b8; margin-bottom:18px; }
        .form-group { margin-bottom:14px; }
        .form-label { display:block; font-size:13px; color:#cbd5e1; margin-bottom:6px; }
        .form-input { width:100%; background:#0b1220; border:1px solid #334155; border-radius:10px; color:#e2e8f0; padding:12px 14px; outline:none; }
        .form-input:focus { border-color:#475569; box-shadow:0 0 0 2px rgba(71,85,105,.25); }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .form-actions { display:flex; align-items:center; justify-content:space-between; margin-top:10px; }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:12px 14px; border-radius:10px; border:1px solid transparent; cursor:pointer; }
        .btn-primary { background:#059669; border-color:#059669; color:white; width:100%; font-weight:600; }
        .btn-primary:hover { background:#0e7d57; border-color:#0e7d57; }
        .help-link { color:#34d399; text-decoration:none; }
        .help-link:hover { text-decoration:underline; }

        /* Input adornments */
        .input-wrap { position:relative; }
        .input-icon-left, .input-icon-right { position:absolute; top:50%; transform:translateY(-50%); color:#94a3b8; opacity:.9; }
        .input-icon-left { left:12px; }
        .input-icon-right { right:12px; cursor:pointer; }
        .pad-left-icon { padding-left:40px; }
        .pad-right-icon { padding-right:40px; }

        /* Auth layout centering */
        .auth-center { min-height:100vh; display:grid; place-items:center; }

        /* Floating chat button */
        .fab { position:fixed; right:22px; bottom:22px; width:48px; height:48px; border-radius:50%; background:#10b981; border:1px solid #0e7d57; color:#05261d; display:grid; place-items:center; box-shadow:0 8px 24px rgba(0,0,0,.25); cursor:pointer; }
        .fab:hover { background:#0fd197; }
    </style>
    @stack('head')
 </head>
<body class="h-full">
    @php
        $isAuth = request()->routeIs('login') || request()->routeIs('register');
    @endphp
    @unless($isAuth)
    <header class="nav">
        <div class="container" style="display:flex;align-items:center;gap:18px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:28px;height:28px;background:#10b981;border-radius:8px;display:grid;place-items:center;color:#06251d;font-weight:800;">¥</div>
                <strong>PF Trackers</strong>
            </div>
            <nav style="display:flex;gap:6px;">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Transactions</a>
                <a class="{{ request()->routeIs('budgets.*') ? 'active' : '' }}" href="{{ route('budgets.index') }}">Budgets</a>
                <a class="{{ request()->routeIs('savings.*') ? 'active' : '' }}" href="{{ route('savings.index') }}">Savings</a>
                <a class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Reports</a>
            </nav>
            <div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
                <span class="muted">JA</span>
            </div>
        </div>
    </header>
    @endunless

    <main class="container {{ $isAuth ? 'auth-center' : '' }}" style="{{ $isAuth ? 'max-width:100%;' : '' }}">
        @yield('content')
    </main>

    @unless($isAuth)
        <button id="ai-chat-trigger" class="fab" title="Chat with AI" aria-label="Open chatbot">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12c0 4.418-4.03 8-9 8-1.1 0-2.15-.167-3.11-.474L3 21l1.6-3.2C3.61 16.432 3 14.79 3 13c0-4.418 4.03-8 9-8s9 3.582 9 7z" stroke="#05261d" stroke-width="1.5" fill="#d1fae5"/>
                <circle cx="9" cy="12" r="1.25" fill="#065f46"/>
                <circle cx="12" cy="12" r="1.25" fill="#065f46"/>
                <circle cx="15" cy="12" r="1.25" fill="#065f46"/>
            </svg>
        </button>
        <script>
            // Placeholder hook for your chatbot
            document.getElementById('ai-chat-trigger')?.addEventListener('click', () => {
                console.log('Chatbot trigger clicked');
                // TODO: open your AI chat widget here
            });
        </script>
    @endunless

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>PF Trackers</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%2310b981'/%3E%3Ctext x='50' y='70' font-size='60' font-weight='bold' text-anchor='middle' fill='%2306251d' font-family='Arial'%3E₱%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
        .nav { background-color: #1e293b; border-bottom: 1px solid #334155; padding: 6px 0; position:sticky; top:0; z-index:3000; }
        .nav a { color: #cbd5e1; text-decoration: none; padding: 10px 18px; border-radius: 10px; font-weight: 500; transition: all 0.2s; }
        .nav a:hover { color: #fff; }
        .nav a.active { color: #10b981; font-weight: 600; }

        /* Page spacing utilities */
        .page-header { display:flex; align-items:center; gap:12px; margin: 8px 0 16px; }
        main.container { padding-top:72px; }
        /* auth pages center layout should not get the top padding */
        .auth-center { padding-top:0 !important; }
        .stack-section { margin-top: 16px; }
    .page-grid-3 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
    .page-grid-4 { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
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
        .fab { position:fixed; right:22px; bottom:22px; width:56px; height:56px; border-radius:50%; background:#10b981; border:1px solid #0e7d57; color:#05261d; display:grid; place-items:center; box-shadow:0 8px 24px rgba(0,0,0,.25); cursor:pointer; }
        .fab:hover { background:#0fd197; }

        /* Chat window */
        .chat-window { position:fixed; right:22px; bottom:92px; width:360px; max-width:92vw; height:520px; background:#071428; border:1px solid #243241; border-radius:12px; box-shadow:0 20px 60px rgba(2,6,23,.6); display:flex; flex-direction:column; overflow:hidden; z-index:1000; transform:translateY(12px); opacity:0; pointer-events:none; transition:all .22s ease; }
        .chat-window.active { transform:translateY(0); opacity:1; pointer-events:auto; }
        .chat-header { padding:14px 16px; background:linear-gradient(180deg,#071428,#07141a); display:flex; gap:10px; align-items:center; border-bottom:1px solid #17212b; }
        .chat-header .title { font-weight:700; color:#e6eef7; }
        .chat-header .sub { font-size:12px; color:#94a3b8; }
        .chat-messages { flex:1; padding:14px; overflow:auto; display:flex; flex-direction:column; gap:10px; background:linear-gradient(180deg,#071422,transparent); }
        .chat-bubble { max-width:78%; padding:12px 14px; border-radius:10px; color:#e6eef7; font-size:14px; line-height:1.7; white-space:normal; word-wrap:break-word; }
        .chat-bubble strong { font-weight:700; display:inline-block; margin-top:4px; }
        .chat-bubble.bot { background:#0f2a3a; align-self:flex-start; border:1px solid #203345; }
        .chat-bubble.user { background:#10b981; color:#06251d; align-self:flex-end; border:1px solid #0d6b4f; }
        .chat-input-area { padding:12px; border-top:1px solid #17212b; display:flex; gap:8px; background:#041018; }
        .chat-input { flex:1; padding:10px 12px; border-radius:10px; background:#071722; border:1px solid #21313f; color:#cfe9ff; outline:none; }
        .chat-send { width:44px; height:44px; border-radius:10px; background:#10b981; border:none; color:#06251d; display:grid; place-items:center; cursor:pointer; }

        /* Modal styles */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(3px); z-index:9999; display:none; align-items:center; justify-content:center; pointer-events:none; }
        .modal-overlay.active { display:flex; pointer-events:auto; }
        .modal-content { background:#1a2b3a; border:1px solid #2d3d4f; border-radius:16px; width:90%; max-width:550px; max-height:83vh; overflow-y:auto; position:relative; box-shadow:0 20px 60px rgba(0,0,0,.5); pointer-events:auto; }
        .modal-header { padding:18px 22px; border-bottom:1px solid #2d3d4f; display:flex; align-items:center; gap:10px; }
        .modal-header h2 { font-size:20px; font-weight:700; flex:1; color:#e2e8f0; }
        .modal-close { background:transparent; border:none; color:#94a3b8; cursor:pointer; font-size:22px; padding:4px 8px; line-height:1; width:32px; height:32px; border-radius:6px; }
        .modal-close:hover { color:#e2e8f0; background:#2d3d4f; }
        .modal-body { padding:20px 22px; }
        .modal-footer { padding:14px 22px; border-top:1px solid #2d3d4f; display:flex; gap:10px; justify-content:flex-end; }
        .type-toggle { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
        .type-btn { padding:14px 18px; border:2px solid #2d3d4f; border-radius:12px; background:#243442; color:#94a3b8; cursor:pointer; text-align:center; transition:all .2s; display:flex; flex-direction:column; align-items:center; gap:6px; }
        .type-btn.active { border-color:#10b981; background:rgba(16,185,129,.12); color:#34d399; }
        .type-btn.active.expense-active { border-color:#3b82f6; background:rgba(59,130,246,.15); color:#60a5fa; }
        .type-btn:hover:not(.active) { border-color:#3d4d5f; background:#2a3a4a; }
        .type-btn-icon { font-size:32px; }
        .type-btn-text { font-weight:600; font-size:15px; }
        .btn-cancel { background:#374151; border-color:#374151; color:#e2e8f0; padding:11px 18px; font-size:14px; }
        .btn-cancel:hover { background:#4b5563; }
        .modal-body .form-label { font-size:14px; font-weight:600; display:flex; align-items:center; gap:6px; margin-bottom:7px; color:#cbd5e1; }
        .modal-body .form-input { background:#2c3e50; border:1px solid #3d4d5f; padding:11px 14px; font-size:14px; color:#e2e8f0; }
        .modal-body .form-input::placeholder { color:#6b7a8f; }
        .modal-body .form-input:focus { border-color:#4d6a8f; }
        .modal-body .form-group { margin-bottom:16px; }
        .modal-body select.form-input { appearance:none; background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; padding-right:40px; }
        /* Responsive adjustments */
        .mobile-nav-label { display:none; margin-left:8px; font-size:15px; color:#cbd5e1; font-weight:600; }
        .brand-icon { flex:0 0 auto; }
        .brand-title { font-size:16px; }
        .mobile-nav-toggle { position:relative; z-index:3500; }
        .site-brand { display:flex; align-items:center; gap:10px; }
        @media (max-width: 900px) {
            .container { padding: 18px; }
            .page-grid-3, .page-grid-4, .page-grid-2-1 { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .form-card { max-width: 92%; padding:18px; min-height: auto; }
            .chat-window { right:12px; left:12px; width:auto; max-width:unset; height:60vh; bottom:86px; }
            .fab { right:14px; bottom:14px; }
            .modal-content { width:94%; max-width:94%; }
            .nav a { padding:10px 12px; }
        }

        @media (max-width: 640px) {
            .nav { padding:8px 0; }
            .nav .container { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
            /* show mobile toggle (override inline display) */
            .mobile-nav-toggle { display:flex !important; align-items:center; gap:6px; padding:8px 12px; margin:0 0 0 8px; flex-basis:100%; order:2; }
            /* Keep brand horizontal with logo next to text */
            .site-brand { flex-direction:row; gap:8px; flex:0 1 auto; order:1; }
            .brand-icon { width:32px; height:32px; margin:0; font-weight:900; border-radius:10px; display:grid; place-items:center; }
            .brand-title { font-size:18px; margin:0; line-height:1; white-space:nowrap; }
            .mobile-nav-icon { display:inline-flex; }
            .mobile-nav-label { display:inline-block; }
            /* Show profile and notification icons on mobile */
            .nav .container > div:last-child { display:flex !important; gap:12px; margin-left:auto !important; order:1; }
            /* mobile nav: start hidden using max-height so we can animate open/close */
            .nav-links { display:flex; flex-direction:column; position:absolute; left:0; right:0; top:70px; padding:0 12px; gap:8px; background:#1e293b; border-bottom:1px solid #334155; z-index:3200; max-height:0; overflow:hidden; transition:max-height .28s ease, padding .22s ease; }
            .nav-links.open { max-height:480px; padding:10px 12px; }
            .nav-links a { padding:12px 16px !important; }
            main.container { padding-top:78px; }
            .chat-input { font-size:14px; }
        }

        /* Chart helpers to ensure canvases are visible on small screens */
        .chart-wrap { position:relative; width:100%; min-height:260px; }
        .chart-wrap canvas { width:100% !important; height:100% !important; display:block; }

        @media (max-width: 640px) {
            .chart-wrap { min-height:220px; }
        }
    </style>
    <?php echo $__env->yieldPushContent('head'); ?>
 </head>
<body class="h-full">
    <?php
        $isAuth = request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request') || request()->routeIs('password.reset');
    ?>
    <?php if (! ($isAuth)): ?>
    <header class="nav">
        <div class="container" style="display:flex;align-items:center;gap:18px;position:relative;">
            <!-- Mobile toggle comes first -->
            <button id="mobile-nav-toggle" class="mobile-nav-toggle" aria-label="Toggle menu" style="display:none;background:transparent;border:none;color:#cbd5e1;cursor:pointer;padding:8px;border-radius:8px;">
                <span class="mobile-nav-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h18M3 12h18M3 18h18" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span class="mobile-nav-label">Menu</span>
            </button>

            <div class="site-brand" style="display:flex;align-items:center;gap:10px;">
                <div class="brand-icon" style="width:28px;height:28px;background:#10b981;border-radius:8px;display:grid;place-items:center;color:#06251d;font-weight:800;">₱</div>
                <strong class="brand-title">PF Trackers</strong>
            </div>
            <nav id="main-nav-links" class="nav-links" style="display:flex;gap:6px;">
                <a class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a class="<?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>" href="<?php echo e(route('transactions.index')); ?>">Transactions</a>
                <a class="<?php echo e(request()->routeIs('budgets.*') ? 'active' : ''); ?>" href="<?php echo e(route('budgets.index')); ?>">Budgets</a>
                <a class="<?php echo e(request()->routeIs('savings.*') ? 'active' : ''); ?>" href="<?php echo e(route('savings.index')); ?>">Savings</a>
                <a class="<?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index', ['cb' => now()->timestamp])); ?>">Reports</a>
            </nav>
            <div style="margin-left:auto;display:flex;align-items:center;gap:16px;">
                <a href="<?php echo e(route('notifications.index')); ?>" style="position:relative;background:transparent;border:none;padding:6px;cursor:pointer;display:grid;place-items:center;border-radius:6px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background='transparent'" title="Notifications">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php
                        $unreadCount = Auth::check() ? \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() : 0;
                    ?>
                    <?php if($unreadCount > 0): ?>
                    <span id="nav-notif-count" style="position:absolute;top:-2px;right:-2px;background:#ef4444;color:white;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 5px;border:2px solid #1e293b;"><?php echo e($unreadCount); ?></span>
                    <?php else: ?>
                    <span id="nav-notif-count" style="position:absolute;top:-2px;right:-2px;background:#ef4444;color:white;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:none;align-items:center;justify-content:center;padding:0 5px;border:2px solid #1e293b;"></span>
                    <?php endif; ?>
                </a>
                <?php if(auth()->guard()->check()): ?>
                <div style="position:relative;" id="user-menu">
                    <button onclick="toggleUserMenu()" style="width:32px;height:32px;background:#10b981;border:none;border-radius:50%;cursor:pointer;display:grid;place-items:center;color:#06251d;font-weight:700;font-size:13px;transition:background .2s;" onmouseover="this.style.background='#0fd197'" onmouseout="this.style.background='#10b981'" title="<?php echo e(Auth::user()->name); ?>">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                    </button>
                    <div id="user-dropdown" style="display:none;position:absolute;right:0;top:calc(100% + 8px);background:#1e293b;border:1px solid #334155;border-radius:8px;min-width:200px;box-shadow:0 4px 12px rgba(0,0,0,.3);z-index:3100;">
                        <div style="padding:12px;border-bottom:1px solid #334155;">
                            <div style="font-weight:600;color:#e2e8f0;"><?php echo e(Auth::user()->name); ?></div>
                            <div style="font-size:13px;color:#94a3b8;"><?php echo e(Auth::user()->email); ?></div>
                        </div>
                        <div style="padding:6px;">
                            <a href="<?php echo e(route('profile.index')); ?>" style="width:100%;text-align:left;padding:10px 12px;background:transparent;border:none;color:#e2e8f0;cursor:pointer;border-radius:6px;display:flex;align-items:center;gap:8px;text-decoration:none;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='transparent'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Profile
                            </a>
                            <a href="<?php echo e(route('settings.index')); ?>" style="width:100%;text-align:left;padding:10px 12px;background:transparent;border:none;color:#e2e8f0;cursor:pointer;border-radius:6px;display:flex;align-items:center;gap:8px;text-decoration:none;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='transparent'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Settings
                            </a>
                            <div style="border-top:1px solid #334155;margin:6px 0;"></div>
                            <form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="clearUserData(event)" style="width:100%;text-align:left;padding:10px 12px;background:transparent;border:none;color:#f87171;cursor:pointer;border-radius:6px;display:flex;align-items:center;gap:8px;" onmouseover="this.style.background='#3f1515';this.style.color='#fca5a5'" onmouseout="this.style.background='transparent';this.style.color='#f87171'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <?php endif; ?>

    <main class="container <?php echo e($isAuth ? 'auth-center' : ''); ?>" style="<?php echo e($isAuth ? 'max-width:100%;' : ''); ?>">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if (! ($isAuth)): ?>
        <button id="ai-chat-trigger" class="fab" title="Chat with AI" aria-label="Open chatbot">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12c0 1.54.36 3 .97 4.29L2 22l5.71-.97C9 21.64 10.46 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z" fill="white" stroke="#05261d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.5 11.5c.828 0 1.5-.672 1.5-1.5S9.328 8.5 8.5 8.5 7 9.172 7 10s.672 1.5 1.5 1.5zM15.5 11.5c.828 0 1.5-.672 1.5-1.5S16.328 8.5 15.5 8.5 14 9.172 14 10s.672 1.5 1.5 1.5z" fill="#05261d"/>
                <path d="M8 14c.5 1.5 2 2.5 4 2.5s3.5-1 4-2.5" stroke="#05261d" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </button>

        <div id="chat-window" class="chat-window" aria-hidden="true">
            <div class="chat-header">
                <div>
                    <div class="title">Financial Tracker Chatbot</div>
                    <div class="sub">Always here to help</div>
                </div>
                <button id="chat-close" class="modal-close" title="Close chat" aria-label="Close chat">&times;</button>
            </div>
            <div id="chat-messages" class="chat-messages">
                <div class="chat-bubble bot">Hello! I'm your AI Finance Assistant. I can help you with budgeting, saving, investing, and other finance-related questions. How can I assist you today?</div>
            </div>
            <div class="chat-input-area">
                <textarea id="chat-input" class="chat-input" rows="1" placeholder="Ask a finance question..."></textarea>
                <button id="chat-send" class="chat-send" title="Send">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 2L11 13" stroke="#06251d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" fill="#06251d"/>
                    </svg>
                </button>
            </div>
        </div>
        <script>
            // Chat widget toggle + AI-powered handlers
            (function(){
                const chatTrigger = document.getElementById('ai-chat-trigger');
                const chatWindow = document.getElementById('chat-window');
                const chatClose = document.getElementById('chat-close');
                const chatInput = document.getElementById('chat-input');
                const chatMessages = document.getElementById('chat-messages');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                let isProcessing = false;

                // Get unique storage key per user
                const userId = '<?php echo e(Auth::id()); ?>';
                const chatStorageKey = `chat_history_user_${userId}`;

                // Load chat history from localStorage
                function loadChatHistory() {
                    try {
                        const savedHistory = localStorage.getItem(chatStorageKey);
                        if (savedHistory) {
                            const messages = JSON.parse(savedHistory);
                            // Clear default welcome message
                            chatMessages.innerHTML = '';
                            // Restore messages
                            messages.forEach(msg => {
                                appendBubble(msg.text, msg.type, false); // false = don't save again
                            });
                        }
                    } catch (e) {
                        console.error('Error loading chat history:', e);
                    }
                }

                // Save chat history to localStorage
                function saveChatHistory(text, type) {
                    try {
                        let history = [];
                        const saved = localStorage.getItem(chatStorageKey);
                        if (saved) {
                            history = JSON.parse(saved);
                        }
                        history.push({ text, type, timestamp: Date.now() });
                        // Keep only last 50 messages
                        if (history.length > 50) {
                            history = history.slice(-50);
                        }
                        localStorage.setItem(chatStorageKey, JSON.stringify(history));
                    } catch (e) {
                        console.error('Error saving chat history:', e);
                    }
                }

                // Clear chat history (optional)
                window.clearChatHistory = function() {
                    localStorage.removeItem(chatStorageKey);
                    chatMessages.innerHTML = '<div class="chat-bubble bot">Hello! I\'m your AI Finance Assistant. I can help you with budgeting, saving, investing, and other finance-related questions. How can I assist you today?</div>';
                };

                function openChat() { chatWindow.classList.add('active'); chatWindow.setAttribute('aria-hidden','false'); chatInput && chatInput.focus(); }
                function closeChat() { chatWindow.classList.remove('active'); chatWindow.setAttribute('aria-hidden','true'); }

                chatTrigger?.addEventListener('click', function(e){ e.stopPropagation(); if (chatWindow.classList.contains('active')) { closeChat(); } else { openChat(); } });
                chatClose?.addEventListener('click', function(e){ e.stopPropagation(); closeChat(); });

                // Close when clicking outside
                document.addEventListener('click', function(ev){ if (!chatWindow.contains(ev.target) && !chatTrigger.contains(ev.target)) closeChat(); });
                document.addEventListener('keydown', function(ev){ if (ev.key === 'Escape') closeChat(); });

                // Append message bubble
                function appendBubble(text, who, shouldSave = true){ 
                    const d = document.createElement('div'); 
                    d.className = 'chat-bubble ' + (who === 'user' ? 'user' : 'bot'); 
                    
                    // Format text for better readability
                    let formatted = text
                        // First, handle numbered lists with bold text: "1. **Title:**" 
                        .replace(/(\d+)\.\s*\*\*(.*?)\*\*/g, '<br><br><strong style="color:#10b981;">$1. $2</strong>')
                        // Handle regular bold text
                        .replace(/\*\*(.*?)\*\*/g, '<strong style="color:#10b981;">$1</strong>')
                        // Convert asterisk bullets to proper bullets with spacing
                        .replace(/\n\s*\*\s+/g, '<br>• ')
                        // Add line breaks for regular newlines
                        .replace(/\n/g, '<br>')
                        // Clean up multiple consecutive <br> tags (max 2)
                        .replace(/(<br\s*\/?>){3,}/gi, '<br><br>');
                    
                    // Remove leading <br> if any
                    formatted = formatted.replace(/^(<br\s*\/?>)+/i, '');
                    
                    d.innerHTML = formatted; 
                    d.style.whiteSpace = 'normal';
                    d.style.wordWrap = 'break-word';
                    d.style.lineHeight = '1.7';
                    chatMessages.appendChild(d); 
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    
                    // Save to localStorage if needed
                    if (shouldSave) {
                        saveChatHistory(text, who);
                    }
                }

                // Show typing indicator
                function showTyping() {
                    const typing = document.createElement('div');
                    typing.id = 'typing-indicator';
                    typing.className = 'chat-bubble bot';
                    typing.innerHTML = '<span style="opacity:0.6">●●●</span>';
                    chatMessages.appendChild(typing);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

                function hideTyping() {
                    const typing = document.getElementById('typing-indicator');
                    if (typing) typing.remove();
                }

                // Send message to OpenAI via backend
                async function sendMessage(){ 
                    if(!chatInput || isProcessing) return; 
                    const v = chatInput.value.trim(); 
                    if(!v) return; 
                    
                    isProcessing = true;
                    appendBubble(v,'user'); 
                    chatInput.value = ''; 
                    chatInput.disabled = true;
                    showTyping();

                    try {
                        const response = await fetch('/chatbot/send', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: v })
                        });

                        hideTyping();

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success && data.message) {
                                appendBubble(data.message, 'bot');
                            } else {
                                appendBubble('I apologize, but I encountered an issue. Please try again.', 'bot');
                            }
                        } else {
                            appendBubble('I\'m having trouble connecting right now. Please try again later.', 'bot');
                        }
                    } catch (error) {
                        hideTyping();
                        appendBubble('Network error. Please check your connection and try again.', 'bot');
                    } finally {
                        isProcessing = false;
                        chatInput.disabled = false;
                        chatInput.focus();
                    }
                }

                document.getElementById('chat-send')?.addEventListener('click', function(e){ e.stopPropagation(); sendMessage(); });
                chatInput?.addEventListener('keydown', function(e){ if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
                
                // Load chat history when page loads
                loadChatHistory();
            })();

            // User menu toggle
            function toggleUserMenu() {
                const dropdown = document.getElementById('user-dropdown');
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const userMenu = document.getElementById('user-menu');
                const dropdown = document.getElementById('user-dropdown');
                if (userMenu && dropdown && !userMenu.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });

                // Mobile nav toggle (animated, accessible, label preserved)
                (function(){
                    const toggle = document.getElementById('mobile-nav-toggle');
                    const nav = document.getElementById('main-nav-links');
                    if (!toggle || !nav) return;

                    const hamburgerSVG = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h18M3 12h18M3 18h18" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    const closeSVG = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6l12 12M6 18L18 6" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

                    // helper to render the toggle content without removing the label
                    function renderClosed() {
                        const iconWrap = document.createElement('span');
                        iconWrap.className = 'mobile-nav-icon';
                        iconWrap.innerHTML = hamburgerSVG;
                        const label = document.createElement('span');
                        label.className = 'mobile-nav-label';
                        label.textContent = 'Menu';
                        toggle.innerHTML = '';
                        toggle.appendChild(iconWrap);
                        toggle.appendChild(label);
                    }

                    function renderOpen() {
                        const iconWrap = document.createElement('span');
                        iconWrap.className = 'mobile-nav-icon';
                        iconWrap.innerHTML = closeSVG;
                        const label = document.createElement('span');
                        label.className = 'mobile-nav-label';
                        label.textContent = 'Close';
                        toggle.innerHTML = '';
                        toggle.appendChild(iconWrap);
                        toggle.appendChild(label);
                    }

                    // initialize aria and icon, track open state via data-open
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.dataset.open = 'false';
                    if (!toggle.querySelector('.mobile-nav-label')) renderClosed();

                    function openNav(){ nav.classList.add('open'); toggle.setAttribute('aria-expanded','true'); toggle.dataset.open = 'true'; renderOpen(); }
                    function closeNav(){ nav.classList.remove('open'); toggle.setAttribute('aria-expanded','false'); toggle.dataset.open = 'false'; renderClosed(); }

                    // Unified handler that works for click/touch and child targets
                    function toggleHandler(e){
                        e.preventDefault();
                        e.stopPropagation();
                        if (nav.classList.contains('open') || toggle.dataset.open === 'true') {
                            closeNav();
                        } else {
                            openNav();
                        }
                    }

                    toggle.addEventListener('click', toggleHandler);
                    // support touchstart to improve responsiveness on mobile
                    toggle.addEventListener('touchstart', function(e){ toggleHandler(e); }, {passive:false});

                    // close when clicking outside
                    document.addEventListener('click', function(e){ if (!nav.contains(e.target) && e.target !== toggle) closeNav(); });

                    // close when a nav link is clicked
                    nav.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', function(){ closeNav(); }); });

                    // Ensure menu closes on resize to desktop and reset icon
                    window.addEventListener('resize', function(){ if (window.innerWidth > 640) { closeNav(); } });
                })();

            // Real-time notification badge updates (listens to localStorage + BroadcastChannel)
            (function() {
                function applyNotifPayload(payload) {
                    if (!payload) return;
                    const countEl = document.getElementById('nav-notif-count');
                    if (!countEl) return;

                    // payload may arrive as { newCount, latestUnread } or wrapped as { payload: { newCount... } }
                    const p = payload.payload ? payload.payload : payload;
                    const newCount = p && typeof p.newCount !== 'undefined' ? p.newCount : 0;

                    if (newCount > 0) {
                        countEl.textContent = newCount;
                        countEl.style.display = 'flex';
                    } else {
                        countEl.style.display = 'none';
                    }
                }

                // Initialize from localStorage
                try {
                    const raw = localStorage.getItem('notifications:latest');
                    if (raw) {
                        const parsed = JSON.parse(raw);
                        applyNotifPayload(parsed);
                    }
                } catch (e) {
                    // ignore parse errors
                }

                // Also fetch authoritative unread count from server (in case notifications
                // were created while this page wasn't open). This makes the badge show
                // correctly on pages that didn't receive the storage/broadcast events.
                try {
                    <?php
                        try {
                            $notifCountUrl = route('notifications.unreadCount');
                        } catch (\Throwable $e) {
                            $notifCountUrl = url('/notifications/unread-count');
                        }
                    ?>
                    fetch('<?php echo e($notifCountUrl); ?>', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(r => r.ok ? r.json() : null)
                        .then(json => {
                            if (json && typeof json.newCount !== 'undefined') {
                                applyNotifPayload(json);
                            }
                        }).catch(() => {});
                } catch (e) {
                    // ignore
                }

                // Storage event (cross-tab)
                window.addEventListener('storage', function(e) {
                    if (e.key === 'notifications:latest') {
                        try {
                            const parsed = JSON.parse(e.newValue);
                            applyNotifPayload(parsed);
                        } catch (err) {}
                    }
                });

                // BroadcastChannel for immediate updates
                if (typeof BroadcastChannel !== 'undefined') {
                    try {
                        const bc = new BroadcastChannel('notifications-updates');
                        bc.onmessage = function(ev) {
                            applyNotifPayload(ev.data);
                        };
                    } catch (err) {
                        // ignore
                    }
                }
            })();
        </script>
    <?php endif; ?>

    <script>
        // Clear localStorage on logout to prevent data leakage between users
        function clearUserData(event) {
            try {
                // Clear all localStorage including chat history
                localStorage.clear();
                sessionStorage.clear();
            } catch (e) {
                // Storage access may be blocked
            }
            // Let form submission proceed normally
            return true;
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Financial-Tracker\resources\views/layouts/app.blade.php ENDPATH**/ ?>
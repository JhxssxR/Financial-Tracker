<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>PF Trackers</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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

        /* Modal styles */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(3px); z-index:999; display:none; align-items:center; justify-content:center; }
        .modal-overlay.active { display:flex; }
        .modal-content { background:#1a2b3a; border:1px solid #2d3d4f; border-radius:16px; width:90%; max-width:550px; max-height:83vh; overflow-y:auto; position:relative; box-shadow:0 20px 60px rgba(0,0,0,.5); }
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
    </style>
    <?php echo $__env->yieldPushContent('head'); ?>
 </head>
<body class="h-full">
    <?php
        $isAuth = request()->routeIs('login') || request()->routeIs('register');
    ?>
    <?php if (! ($isAuth)): ?>
    <header class="nav">
        <div class="container" style="display:flex;align-items:center;gap:18px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:28px;height:28px;background:#10b981;border-radius:8px;display:grid;place-items:center;color:#06251d;font-weight:800;">¥</div>
                <strong>PF Trackers</strong>
            </div>
            <nav style="display:flex;gap:6px;">
                <a class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a class="<?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>" href="<?php echo e(route('transactions.index')); ?>">Transactions</a>
                <a class="<?php echo e(request()->routeIs('budgets.*') ? 'active' : ''); ?>" href="<?php echo e(route('budgets.index')); ?>">Budgets</a>
                <a class="<?php echo e(request()->routeIs('savings.*') ? 'active' : ''); ?>" href="<?php echo e(route('savings.index')); ?>">Savings</a>
                <a class="<?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index', ['cb' => now()->timestamp])); ?>">Reports</a>
            </nav>
            <div style="margin-left:auto;display:flex;align-items:center;gap:16px;">
                <a href="<?php echo e(route('notifications.index')); ?>" style="background:transparent;border:none;padding:6px;cursor:pointer;display:grid;place-items:center;border-radius:6px;transition:background .2s;text-decoration:none;" onmouseover="this.style.background='#1f2937'" onmouseout="this.style.background='transparent'" title="Notifications">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <?php if(auth()->guard()->check()): ?>
                <div style="position:relative;" id="user-menu">
                    <button onclick="toggleUserMenu()" style="width:32px;height:32px;background:#10b981;border:none;border-radius:50%;cursor:pointer;display:grid;place-items:center;color:#06251d;font-weight:700;font-size:13px;transition:background .2s;" onmouseover="this.style.background='#0fd197'" onmouseout="this.style.background='#10b981'" title="<?php echo e(Auth::user()->name); ?>">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                    </button>
                    <div id="user-dropdown" style="display:none;position:absolute;right:0;top:calc(100% + 8px);background:#1e293b;border:1px solid #334155;border-radius:8px;min-width:200px;box-shadow:0 4px 12px rgba(0,0,0,.3);z-index:100;">
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
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" style="width:100%;text-align:left;padding:10px 12px;background:transparent;border:none;color:#f87171;cursor:pointer;border-radius:6px;display:flex;align-items:center;gap:8px;" onmouseover="this.style.background='#3f1515';this.style.color='#fca5a5'" onmouseout="this.style.background='transparent';this.style.color='#f87171'">
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
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        </script>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/layouts/app.blade.php ENDPATH**/ ?>
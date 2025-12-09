<?php $__env->startSection('content'); ?>
<style>
    /* Notifications page mobile responsive styles */
    @media (max-width: 768px) {
        .notif-page-container { padding: 0 !important; }
        
        .notif-header { flex-direction: column; align-items: flex-start !important; gap: 16px !important; }
        .notif-header h1 { font-size: 24px !important; }
        .notif-header .muted { font-size: 13px !important; }
        
        .notif-actions { width: 100%; flex-direction: column; gap: 10px !important; }
        .notif-actions button, .notif-actions a { width: 100% !important; justify-content: center !important; }
        
        .notif-grid { grid-template-columns: 1fr !important; gap: 16px !important; }
        .notif-section-title { font-size: 20px !important; margin-bottom: 12px !important; }
        
        .notif-item { flex-direction: column !important; align-items: flex-start !important; padding: 14px !important; gap: 12px !important; }
        .notif-item > div:first-child { width: 100%; }
        .notif-item > div:last-child { width: 100%; justify-content: flex-end !important; }
        .notif-item .unread-dot { top: 10px !important; right: 10px !important; }
        .notif-item p { font-size: 13px !important; }
        
        .status-badge { font-size: 12px !important; padding: 4px 8px !important; }
        .status-badge span { width: 18px !important; height: 18px !important; font-size: 10px !important; }
        
        .empty-state { padding: 120px 40px !important; }
        .empty-state-icon { width: 60px !important; height: 60px !important; margin-bottom: 16px !important; }
        .empty-state-icon svg { width: 35px !important; height: 35px !important; }
        .empty-state p { font-size: 14px !important; }
        
        .custom-pager { flex-wrap: wrap; gap: 8px !important; }
        .custom-pager .page-link { padding: 8px 14px !important; font-size: 13px !important; }
        .custom-pager > div { order: 3; width: 100%; justify-content: center; margin-top: 8px; }
        .custom-pager .page-number { width: 36px !important; height: 36px !important; }
    }
    
    @media (max-width: 480px) {
        .notif-header h1 { font-size: 22px !important; }
        .notif-item { padding: 12px !important; }
        .notif-item p:first-of-type { font-size: 12px !important; }
        .status-badge { font-size: 11px !important; }
        .empty-state { padding: 100px 24px !important; }
        
        #toast-container { right: 10px !important; left: 10px !important; top: 10px !important; }
    }
</style>

<!-- Toast Container -->
<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;"></div>

<div class="notif-page-container" style="max-width:1400px;margin:0 auto;">
    <div class="notif-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:32px;font-weight:700;margin:0;">Notifications</h1>
            <?php if(isset($unreadCount) && $unreadCount > 0): ?>
                <p class="muted" style="margin:4px 0 0;font-size:14px;">You have <?php echo e($unreadCount); ?> unread notification<?php echo e($unreadCount > 1 ? 's' : ''); ?></p>
            <?php else: ?>
                <p class="muted" style="margin:4px 0 0;font-size:14px;">All caught up!</p>
            <?php endif; ?>
        </div>
        <div class="notif-actions" style="display:flex;gap:12px;align-items:center;">
            
            <?php if(isset($unreadCount) && $unreadCount > 0): ?>
                <button id="markAllReadBtn" onclick="markAllAsRead()" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#10b981;border:none;border-radius:8px;color:#fff;font-weight:600;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Mark All as Read
                </button>
            <?php endif; ?>
            <a href="<?php echo e(route('settings.index')); ?>" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1e293b;border:1px solid #334155;border-radius:8px;color:#e2e8f0;text-decoration:none;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Settings
            </a>
        </div>
    </div>

    <div class="notif-grid" style="display:grid;grid-template-columns:1fr 420px;gap:24px;margin-top:24px;">
        <!-- Left: Recent Notifications -->
        <div style="grid-column: 1 / -1;">
            <h2 class="notif-section-title" style="font-size:24px;font-weight:700;margin:0 0 16px;">Recent Notifications</h2>
            
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // default left accent color (can be customized per-notification)
                    $leftColor = '#475569';
                    $statusBadge = null;
                    $priorityBadge = null;

                    // Color accents by notification type or message text
                    $type = isset($notification->type) ? strtolower($notification->type) : '';
                    $title = isset($notification->title) ? strtolower($notification->title) : '';
                    $msg = isset($notification->message) ? strtolower($notification->message) : '';

                    // Budget notifications - critical / over budget (red)
                    if (
                        strpos($type, 'budget') !== false &&
                        (strpos($title, 'exceeded') !== false || strpos($title, 'over') !== false ||
                         strpos($msg, 'exceeded') !== false || strpos($msg, 'over budget') !== false ||
                         strpos($title, 'critical') !== false || strpos($msg, 'critical') !== false)
                    ) {
                        $leftColor = '#ef4444';
                        $statusBadge = 'Over Budget';
                    }
                    // Budget notifications - near limit / warning (orange)
                    elseif (
                        strpos($type, 'budget') !== false &&
                        (strpos($title, 'near') !== false || strpos($title, 'warning') !== false ||
                         strpos($title, 'limit') !== false || strpos($msg, 'near') !== false ||
                         strpos($msg, 'approaching') !== false || strpos($msg, 'almost') !== false)
                    ) {
                        $leftColor = '#f59e0b';
                        $statusBadge = 'Near Limit';
                    }
                    // Withdrawals (match type OR title OR message for common verbs)
                    elseif (
                        strpos($type, 'withdraw') !== false || strpos($type, 'withdrawal') !== false ||
                        strpos($title, 'withdraw') !== false || strpos($title, 'withdrew') !== false ||
                        strpos($msg, 'withdraw') !== false || strpos($msg, 'withdrew') !== false ||
                        strpos($msg, 'withdrawal') !== false
                    ) {
                        $leftColor = '#ef4444';
                        $statusBadge = 'Withdrawal';
                    }
                    // Deposits / savings should be green (match type/title/message)
                    elseif (
                        strpos($type, 'saving') !== false || strpos($type, 'savings') !== false ||
                        strpos($title, 'save') !== false || strpos($title, 'depos') !== false ||
                        strpos($msg, 'save') !== false || strpos($msg, 'depos') !== false || strpos($msg, 'deposited') !== false
                    ) {
                        $leftColor = '#10b981';
                        $statusBadge = 'Deposit';
                    }
                ?>

                <div class="card notif-item" data-id="<?php echo e($notification->id); ?>" style="position:relative;padding:18px;margin-bottom:12px;border-radius:12px;display:flex;justify-content:space-between;align-items:center;opacity:<?php echo e($notification->is_read ? '0.5' : '1'); ?>;<?php if(!$notification->is_read): ?>border-left:6px solid <?php echo e($leftColor); ?>;<?php endif; ?>">
                    <div>
                        <?php if($priorityBadge): ?>
                            <div style="margin-bottom:8px;"><span style="background:#2b2830;color:#d6c9b1;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700;text-transform:lowercase;"><?php echo e($priorityBadge); ?></span></div>
                        <?php endif; ?>

                        <?php if($statusBadge): ?>
                            <?php if($statusBadge === 'Over Budget'): ?>
                                <span class="status-badge" style="display:inline-flex;align-items:center;gap:6px;background:rgba(239,68,68,0.15);color:#ef4444;padding:6px 10px;border-radius:10px;border:1px solid rgba(239,68,68,0.2);font-size:13px;font-weight:700;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#ef4444;color:#fff;border-radius:50%;flex-shrink:0;font-size:11px;">!</span>
                                    <?php echo e($statusBadge); ?>

                                </span>
                            <?php elseif($statusBadge === 'Near Limit'): ?>
                                <span class="status-badge" style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,158,11,0.15);color:#f59e0b;padding:6px 10px;border-radius:10px;border:1px solid rgba(245,158,11,0.2);font-size:13px;font-weight:700;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#f59e0b;color:#111;border-radius:50%;flex-shrink:0;font-size:11px;">⚠</span>
                                    <?php echo e($statusBadge); ?>

                                </span>
                            <?php elseif($statusBadge === 'Withdrawal'): ?>
                                <span class="status-badge" style="display:inline-flex;align-items:center;gap:6px;background:rgba(239,68,68,0.15);color:#ef4444;padding:6px 10px;border-radius:10px;border:1px solid rgba(239,68,68,0.2);font-size:13px;font-weight:700;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#ef4444;color:#fff;border-radius:50%;flex-shrink:0;font-size:11px;">−</span>
                                    <?php echo e($statusBadge); ?>

                                </span>
                            <?php elseif($statusBadge === 'Deposit'): ?>
                                <span class="status-badge" style="display:inline-flex;align-items:center;gap:6px;background:rgba(16,185,129,0.15);color:#10b981;padding:6px 10px;border-radius:10px;border:1px solid rgba(16,185,129,0.2);font-size:13px;font-weight:700;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#10b981;color:#fff;border-radius:50%;flex-shrink:0;font-size:11px;">+</span>
                                    <?php echo e($statusBadge); ?>

                                </span>
                            <?php else: ?>
                                <span class="status-badge" style="display:inline-flex;align-items:center;gap:8px;background:#2a3238;color:#94a3b8;padding:6px 10px;border-radius:10px;border:1px solid rgba(148,163,184,0.08);font-size:13px;font-weight:700;">
                                    <?php echo e($statusBadge); ?>

                                </span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <p style="font-size:14px;margin:8px 0 0;color:#94a3b8;"><?php echo e($notification->message); ?></p>
                        <p style="font-size:12px;margin:10px 0 0;color:#64748b;"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                    </div>

                    <div style="display:flex;gap:12px;align-items:center;">
                        <?php if(!$notification->is_read): ?>
                            <button class="notif-action" data-action="read" data-id="<?php echo e($notification->id); ?>" title="Mark as read" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#e2e8f0;transition:all .15s;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;"><path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        <?php endif; ?>
                        <button class="notif-action" data-action="delete" data-id="<?php echo e($notification->id); ?>" title="Dismiss" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#cbd5e1;transition:all .15s;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;"><path d="M18 6L6 18M6 6l12 12" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>

                    <?php if(!$notification->is_read): ?>
                        <div class="unread-dot" style="position:absolute;top:14px;right:18px;width:8px;height:8px;background:#3b82f6;border-radius:50%;box-shadow:0 0 0 4px rgba(59,130,246,0.06);"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <!-- Empty State -->
            <div class="card" style="padding:200px 80px;text-align:center;">
                <div style="display:inline-flex;width:80px;height:80px;background:#1a2b3a;border-radius:50%;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="muted" style="margin:0;font-size:16px;">No notifications to display</p>
            </div>
            <?php endif; ?>

            <?php if(method_exists($notifications, 'hasPages') && $notifications->hasPages()): ?>
                <style>
                    .custom-pager{display:flex;justify-content:center;align-items:center;gap:12px;width:100%;margin-top:12px}
                    .custom-pager .page-link{padding:10px 16px;background:#0f172a;border:1px solid #334155;border-radius:12px;color:#e2e8f0;text-decoration:none;cursor:pointer;font-size:14px;transition:all .2s}
                    .custom-pager .page-link.disabled{opacity:0.45;color:#64748b;border-color:rgba(148,163,184,0.03);pointer-events:none}
                    .custom-pager .page-number{width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#0f172a;border:1px solid #334155;color:#e2e8f0;text-decoration:none;transition:all .2s;font-weight:600}
                    .custom-pager .page-number:hover{background:#1e293b;border-color:#10b981}
                    .custom-pager .page-number.active{background:#10b981;color:#fff;border-color:#10b981}
                </style>

                <nav class="custom-pager" aria-label="Notifications pagination">
                    <?php if($notifications->onFirstPage()): ?>
                        <span class="page-link disabled">Previous</span>
                    <?php else: ?>
                        <a class="page-link" href="<?php echo e($notifications->previousPageUrl()); ?>">Previous</a>
                    <?php endif; ?>

                    <div style="display:flex;gap:8px;">
                        <?php $__currentLoopData = range(1, $notifications->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $notifications->currentPage()): ?>
                                <span class="page-number active"><?php echo e($page); ?></span>
                            <?php else: ?>
                                <a class="page-number" href="<?php echo e($notifications->url($page)); ?>"><?php echo e($page); ?></a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($notifications->hasMorePages()): ?>
                        <a class="page-link" href="<?php echo e($notifications->nextPageUrl()); ?>">Next</a>
                    <?php else: ?>
                        <span class="page-link disabled">Next</span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

        </div>

       
<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/notifications.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Financial-Tracker\resources\views/notifications.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
<!-- Toast Container -->
<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;"></div>

<div style="max-width:1400px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:32px;font-weight:700;margin:0;">Notifications</h1>
            <p class="muted" style="margin:4px 0 0;font-size:14px;">All caught up!</p>
        </div>
        <a href="<?php echo e(route('settings.index')); ?>" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1e293b;border:1px solid #334155;border-radius:8px;color:#e2e8f0;text-decoration:none;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Settings
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 420px;gap:24px;margin-top:24px;">
        <!-- Left: Recent Notifications -->
        <div style="grid-column: 1 / -1;">
            <h2 style="font-size:24px;font-weight:700;margin:0 0 16px;">Recent Notifications</h2>
            
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card notif-item" data-id="<?php echo e($notification->id); ?>" style="padding:16px 20px;margin-bottom:12px;display:flex;align-items:center;gap:16px;<?php echo e($notification->is_read ? 'opacity:0.5;' : 'opacity:1;'); ?>">
                <div style="width:48px;height:48px;background:<?php echo e($notification->type === 'savings' ? '#059669' : ($notification->type === 'budget' ? '#3b82f6' : '#8b5cf6')); ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?php if($notification->type === 'savings'): ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php elseif($notification->type === 'budget'): ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php else: ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php endif; ?>
                </div>
                <div style="flex:1;">
                    <h3 style="font-size:16px;font-weight:600;margin:0 0 4px;color:#e2e8f0;"><?php echo e($notification->title); ?></h3>
                    <p style="font-size:14px;margin:0;color:#94a3b8;"><?php echo e($notification->message); ?></p>
                    <p style="font-size:12px;margin:6px 0 0;color:#64748b;"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;">
                    <?php if(!$notification->is_read): ?>
                    <div class="unread-dot" style="width:8px;height:8px;background:#3b82f6;border-radius:50%;flex-shrink:0;"></div>
                    <?php endif; ?>

                    
                    <div style="display:flex;gap:8px;align-items:center;">
                        <?php if(!$notification->is_read): ?>
                        <button class="notif-action" data-action="read" data-id="<?php echo e($notification->id); ?>" title="Mark as read" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#94a3b8;transition:all .2s;" onmouseover="this.style.background='#334155';this.style.color='#10b981'" onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <?php endif; ?>
                        <button class="notif-action" data-action="delete" data-id="<?php echo e($notification->id); ?>" title="Dismiss" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#f87171;transition:all .2s;" onmouseover="this.style.background='#3f1515';this.style.color='#fca5a5'" onmouseout="this.style.background='transparent';this.style.color='#f87171'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
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
        </div>

       
<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize all toggle switches
    document.querySelectorAll('.toggle-switch').forEach(checkbox => {
        const label = checkbox.closest('label');
        const track = label.querySelector('.toggle-track');
        const thumb = label.querySelector('.toggle-thumb');
        
        function updateToggle() {
            if (checkbox.checked) {
                track.style.background = '#334155';
                thumb.style.background = '#fff';
                thumb.style.transform = 'translateX(24px)';
            } else {
                track.style.background = '#1e293b';
                thumb.style.background = '#94a3b8';
                thumb.style.transform = 'translateX(0)';
            }
        }
        
        updateToggle();
        checkbox.addEventListener('change', updateToggle);
    });

    // Toast notification system
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        const bgColor = type === 'success' ? '#059669' : '#dc2626';
        const icon = type === 'success' 
            ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
            : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        
        toast.style.cssText = `
            background: ${bgColor};
            color: white;
            padding: 14px 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease-out;
            min-width: 280px;
            max-width: 400px;
        `;
        toast.innerHTML = `
            <div style="flex-shrink:0;">${icon}</div>
            <div style="flex:1;font-size:14px;font-weight:500;">${message}</div>
        `;

        container.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Notification action handlers (mark as read / dismiss)
    (function() {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        function updateNavBadge(decrementBy = 1) {
            const el = document.getElementById('nav-notif-count');
            if (!el) return;
            const current = parseInt(el.textContent || '0', 10) || 0;
            const next = Math.max(0, current - decrementBy);
            el.textContent = next > 0 ? next : '';
            if (next === 0) {
                el.style.display = 'none';
            }
        }

        async function sendRequest(url, method = 'POST') {
            const res = await fetch(url, {
                method,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                }
            });
            if (!res.ok) throw new Error('Request failed');
            return res.json();
        }

        // Confirmation dialog
        function confirmDelete() {
            return confirm('Are you sure you want to delete this notification? This action cannot be undone.');
        }

        document.querySelectorAll('.notif-action').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const action = btn.getAttribute('data-action');
                const id = btn.getAttribute('data-id');
                if (!id) return;

                try {
                    if (action === 'read') {
                        const url = `/notifications/${id}/read`;
                        await sendRequest(url, 'POST');
                        // Mark notification as read (change opacity and remove unread dot)
                        const item = document.querySelector(`.notif-item[data-id='${id}']`);
                        if (item) {
                            item.style.opacity = '0.5';
                            const unreadDot = item.querySelector('.unread-dot');
                            if (unreadDot) unreadDot.remove();
                            // Hide the check button after marking as read
                            const checkBtn = item.querySelector('.notif-action[data-action="read"]');
                            if (checkBtn) checkBtn.style.display = 'none';
                        }
                        updateNavBadge(1);
                        showToast('Notification marked as read', 'success');
                    }

                    if (action === 'delete') {
                        // Show confirmation dialog
                        if (!confirmDelete()) {
                            return; // User cancelled
                        }

                        const url = `/notifications/${id}`;
                        await sendRequest(url, 'DELETE');
                        const item = document.querySelector(`.notif-item[data-id='${id}']`);
                        if (item) item.remove();
                        updateNavBadge(1);
                        showToast('Notification deleted successfully', 'success');
                    }
                } catch (err) {
                    console.error('Notification action failed', err);
                    showToast('Failed to process notification. Please try again.', 'error');
                }
            });
        });
    })();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/notifications.blade.php ENDPATH**/ ?>
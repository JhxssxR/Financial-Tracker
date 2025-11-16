@extends('layouts.app')

@section('content')
<!-- Toast Container -->
<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;"></div>

<div style="max-width:1400px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:32px;font-weight:700;margin:0;">Notifications</h1>
            @if(isset($unreadCount) && $unreadCount > 0)
                <p class="muted" style="margin:4px 0 0;font-size:14px;">You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}</p>
            @else
                <p class="muted" style="margin:4px 0 0;font-size:14px;">All caught up!</p>
            @endif
        </div>
        <a href="{{ route('settings.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1e293b;border:1px solid #334155;border-radius:8px;color:#e2e8f0;text-decoration:none;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'">
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
            
            @forelse($notifications as $notification)
            @php
                // Determine left border color and badge text for visual emphasis
                // Apply warning/over rules based on title keywords for all notification types
                $leftColor = '#475569'; // default muted
                $statusBadge = null;
                $priorityBadge = null;

                $title = $notification->title ?? '';

                // Global keyword checks (apply to all notification types)
                if (stripos($title, 'Exceeded') !== false || stripos($title, 'Over') !== false) {
                    $leftColor = '#ef4444'; // red
                    $priorityBadge = 'high';
                    $statusBadge = 'Over Budget';
                } elseif (stripos($title, 'Near') !== false || stripos($title, 'Warning') !== false || stripos($title, 'Limit') !== false) {
                    $leftColor = '#f59e0b'; // orange
                    $priorityBadge = 'medium';
                    $statusBadge = 'Near Limit';
                } else {
                    // Fallback per type
                    if ($notification->type === 'savings') {
                        $leftColor = '#059669';
                        $priorityBadge = 'medium';
                    } elseif ($notification->type === 'budget') {
                        $leftColor = '#f59e0b';
                        $priorityBadge = 'medium';
                    } else {
                        $leftColor = '#475569';
                    }
                }

                // Remove the 'medium' badge for savings and withdraw notifications
                // (keep 'high' badges intact)
                if ($priorityBadge === 'medium' && (
                    $notification->type === 'savings' ||
                    stripos($title, 'withdraw') !== false ||
                    stripos($title, 'withdrawal') !== false
                )) {
                    $priorityBadge = null;
                }
            @endphp

            @php
                // Try to extract a category from title if present (e.g. "Budget Over: Transportation")
                $categoryName = null;
                if (strpos($title, ':') !== false) {
                    $parts = explode(':', $title, 2);
                    $categoryName = trim($parts[1]);
                }

                // Map some common categories to colors (fallbacks)
                $iconBgColor = '#3b82f6'; // default blue
                if ($categoryName) {
                    $cn = strtolower($categoryName);
                    if (strpos($cn, 'shopping') !== false) $iconBgColor = '#3b82f6';
                    elseif (strpos($cn, 'transport') !== false) $iconBgColor = '#f59e0b';
                    elseif (strpos($cn, 'housing') !== false || strpos($cn, 'rent') !== false) $iconBgColor = '#ef4444';
                    elseif (strpos($cn, 'education') !== false) $iconBgColor = '#06b6d4';
                    else $iconBgColor = '#374151';
                } else {
                    // fallback by type
                    if ($notification->type === 'savings') $iconBgColor = '#059669';
                    elseif ($notification->type === 'budget') $iconBgColor = '#3b82f6';
                    else $iconBgColor = '#8b5cf6';
                }
            @endphp

                <div class="card notif-item" data-id="{{ $notification->id }}" style="position:relative;padding:18px 22px;margin-bottom:14px;display:flex;align-items:center;gap:18px;border-radius:12px;border-left:6px solid {{ $leftColor }};background:#0f1724;">
                @php
                    // Avatar background: default to icon bg, but use yellow when Near Limit
                    $avatarBg = $iconBgColor;
                    if ($statusBadge === 'Near Limit') {
                        $avatarBg = '#f59e0b';
                    }
                @endphp
                <div style="width:48px;height:48px;background:{{ $avatarBg }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($notification->type === 'savings')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @elseif($notification->type === 'budget')
                        @if($statusBadge === 'Near Limit')
                            {{-- filled black triangle for Near Limit avatar (on yellow bg) --}}
                            <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.5L3.2 18.8h17.6L12 5.5z" fill="#111"/></svg>
                        @else
                            {{-- refined outlined triangle + exclamation (matches attached SVG) --}}
                            <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none">
                                <path d="M12 2.2c-.6 0-1.2.3-1.6.8L3 15.8c-.9 1.4.2 3.3 1.9 3.3h14.2c1.7 0 2.8-1.9 1.9-3.3L13.6 3c-.4-.5-1-.8-1.6-.8z" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                <path d="M12 8.6v4.2" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="12" cy="17" r="0.9" fill="#fff" />
                            </svg>
                        @endif
                    @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @endif
                </div>
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        {{-- status icon removed from title area; now rendered inside the status pill below --}}
                        <h3 style="font-size:16px;font-weight:700;margin:0;color:#e2e8f0;">{{ $notification->title }}</h3>
                        @if($priorityBadge)
                            <span style="background:#2b2830;color:#d6c9b1;padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700;text-transform:lowercase;">{{ $priorityBadge }}</span>
                        @endif
                        @if($statusBadge)
                            @if($statusBadge === 'Over Budget')
                                <span style="background:#ef4444;color:#fff;padding:4px 8px;border-radius:8px;font-size:12px;font-weight:700;">{{ $statusBadge }}</span>
                            @else
                                {{-- Near Limit pill: dark outer pill, yellow text, small yellow square with black triangle icon --}}
                                <span style="display:inline-flex;align-items:center;gap:8px;background:#2a3238;color:#f59e0b;padding:6px 10px;border-radius:10px;border:1px solid rgba(245,158,11,0.08);font-size:13px;font-weight:700;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:#f59e0b;color:#111;border-radius:50%;flex-shrink:0;font-size:12px;">
                                            {{-- circular yellow icon with centered black triangle --}}
                                            <svg width="12" height="12" viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="display:block;margin:auto;">
                                                <path d="M12 7l-5 8h10l-5-8z" fill="#111" />
                                            </svg>
                                        </span>
                                    {{ $statusBadge }}
                                </span>
                            @endif
                        @endif
                    </div>
                    <p style="font-size:14px;margin:8px 0 0;color:#94a3b8;">{{ $notification->message }}</p>
                    <p style="font-size:12px;margin:10px 0 0;color:#64748b;">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    {{-- action buttons: mark as read (check) and dismiss (x) --}}
                    @if(!$notification->is_read)
                    <button class="notif-action" data-action="read" data-id="{{ $notification->id }}" title="Mark as read" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#e2e8f0;transition:all .15s;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;"><path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    @endif
                    <button class="notif-action" data-action="delete" data-id="{{ $notification->id }}" title="Dismiss" style="background:transparent;border:0;padding:6px;border-radius:6px;cursor:pointer;color:#cbd5e1;transition:all .15s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;"><path d="M18 6L6 18M6 6l12 12" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>

                @if(!$notification->is_read)
                {{-- floating unread dot in top-right like prototype --}}
                <div class="unread-dot" style="position:absolute;top:14px;right:18px;width:8px;height:8px;background:#3b82f6;border-radius:50%;box-shadow:0 0 0 4px rgba(59,130,246,0.06);"></div>
                @endif
            </div>
            @empty
            <!-- Empty State -->
            <div class="card" style="padding:200px 80px;text-align:center;">
                <div style="display:inline-flex;width:80px;height:80px;background:#1a2b3a;border-radius:50%;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="muted" style="margin:0;font-size:16px;">No notifications to display</p>
            </div>
            @endforelse
        </div>

       
@push('scripts')
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

        // Set the nav badge and page header to an authoritative count
        function updateNavBadgeTo(newCount) {
            const el = document.getElementById('nav-notif-count');
            if (el) {
                el.textContent = newCount > 0 ? String(newCount) : '';
                el.style.display = newCount > 0 ? '' : 'none';
            }
            // Also update the page header summary (You have X unread notifications / All caught up!)
            try {
                const headerP = document.querySelector('h1 + p.muted');
                if (headerP) {
                    if (newCount > 0) {
                        headerP.textContent = `You have ${newCount} unread notification${newCount > 1 ? 's' : ''}`;
                    } else {
                        headerP.textContent = 'All caught up!';
                    }
                }
            } catch (e) {
                console.error('Failed to update header unread text', e);
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
                        const resp = await sendRequest(url, 'POST');
                        // Mark notification as read (change opacity and remove unread dot)
                        const item = document.querySelector(`.notif-item[data-id='${id}']`);
                        if (item) {
                            item.style.opacity = '0.5';
                            const unreadDot = item.querySelector('.unread-dot');
                            if (unreadDot) unreadDot.remove();
                            // Hide the check button after marking as read
                            const checkBtn = item.querySelector('.notif-action[data-action="read"]');
                            if (checkBtn) checkBtn.style.display = 'none';
                            // Remove the colored left border to indicate it's no longer active
                            try {
                                // Remove the CSS border and draw an inset left stripe (box-shadow)
                                // This preserves a closed, continuous-looking left bar while removing the accent color
                                item.style.borderLeft = 'none';
                                item.style.boxShadow = 'inset 6px 0 0 0 #475569';
                                // Ensure the left corners preserve the rounded shape so the stripe looks closed
                                item.style.borderTopLeftRadius = '12px';
                                item.style.borderBottomLeftRadius = '12px';
                                // Make sure overflow doesn't clip the rounded corners
                                item.style.overflow = 'hidden';
                            } catch (e) {
                                // ignore styling errors
                            }
                        }

                        // Use authoritative count from server response
                        const next = typeof resp.newCount === 'number' ? resp.newCount : null;
                        if (next !== null) {
                            updateNavBadgeTo(next);
                        }
                        showToast('Notification marked as read', 'success');

                        // Broadcast authoritative update to other tabs (so dashboard can update)
                        try {
                            const payload = { ts: Date.now(), action: 'read', newCount: next, latestUnread: resp.latestUnread || null };
                            localStorage.setItem('notifications:latest', JSON.stringify(payload));
                            if (typeof BroadcastChannel !== 'undefined') {
                                const bc = new BroadcastChannel('notifications-updates');
                                bc.postMessage(payload);
                                bc.close();
                            }
                        } catch (e) {
                            console.error('Notification broadcast failed', e);
                        }
                    }

                    if (action === 'delete') {
                        // Show confirmation dialog
                        if (!confirmDelete()) {
                            return; // User cancelled
                        }

                        const url = `/notifications/${id}`;
                        const resp = await sendRequest(url, 'DELETE');
                        const item = document.querySelector(`.notif-item[data-id='${id}']`);
                        if (item) item.remove();

                        const next = typeof resp.newCount === 'number' ? resp.newCount : null;
                        if (next !== null) {
                            updateNavBadgeTo(next);
                        }

                        showToast('Notification deleted successfully', 'success');

                        // Broadcast authoritative update to other tabs
                        try {
                            const payload = { ts: Date.now(), action: 'delete', newCount: next, latestUnread: resp.latestUnread || null };
                            localStorage.setItem('notifications:latest', JSON.stringify(payload));
                            if (typeof BroadcastChannel !== 'undefined') {
                                const bc = new BroadcastChannel('notifications-updates');
                                bc.postMessage(payload);
                                bc.close();
                            }
                        } catch (e) {
                            console.error('Notification broadcast failed', e);
                        }
                    }
                } catch (err) {
                    console.error('Notification action failed', err);
                    showToast('Failed to process notification. Please try again.', 'error');
                }
            });
        });
    })();
</script>
@endpush
@endsection

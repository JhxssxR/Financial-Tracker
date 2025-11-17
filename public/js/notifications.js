(function(){
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
    (function(){
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
    })();

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
                            try {
                                // Remove the CSS border and draw an inset left stripe (box-shadow)
                                // This preserves a closed, continuous-looking left bar while removing the accent color
                                // Preserve the actual left border color (so the read-state stripe matches the original)
                                // Remove the left accent entirely for read items
                                try {
                                    item.style.borderLeft = 'none';
                                    item.style.boxShadow = 'none';
                                } catch (e) {
                                    // ignore styling errors
                                }
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

                        // If there are no more notifications in the DOM, reload so the
                        // server-rendered empty state is shown ("No notifications to display").
                        try {
                            if (document.querySelectorAll('.notif-item').length === 0) {
                                // Small timeout to allow the toast to show briefly
                                setTimeout(() => location.reload(), 250);
                            }
                        } catch (e) {}

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

        // Mark-all handler (if present)
        const markAllBtn = document.getElementById('mark-all-read');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    const res = await sendRequest('/notifications/mark-all-read', 'POST');
                    if (res && res.success) {
                        // Update UI: dim all items, remove unread dots, hide read buttons
                        document.querySelectorAll('.notif-item').forEach(item => {
                            item.style.opacity = '0.5';
                            const unreadDot = item.querySelector('.unread-dot');
                            if (unreadDot) unreadDot.remove();
                            const checkBtn = item.querySelector('.notif-action[data-action="read"]');
                            if (checkBtn) checkBtn.style.display = 'none';
                            try {
                                // Preserve each item's left border color before removing it
                                let leftColor = '#475569';
                                try {
                                    // Remove left accent for all items when marking all as read
                                    item.style.borderLeft = 'none';
                                    item.style.boxShadow = 'none';
                                    item.style.borderTopLeftRadius = '12px';
                                    item.style.borderBottomLeftRadius = '12px';
                                    item.style.overflow = 'hidden';
                                } catch (e) {}
                            } catch (e) {}
                        });

                        // Update nav badge and header
                        if (typeof res.newCount === 'number') updateNavBadgeTo(res.newCount);

                        // Broadcast
                        try {
                            const payload = { ts: Date.now(), action: 'mark-all', newCount: res.newCount, latestUnread: res.latestUnread || null };
                            localStorage.setItem('notifications:latest', JSON.stringify(payload));
                            if (typeof BroadcastChannel !== 'undefined') {
                                const bc = new BroadcastChannel('notifications-updates');
                                bc.postMessage(payload);
                                bc.close();
                            }
                        } catch (e) { console.error(e); }

                        showToast('All notifications marked as read', 'success');
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Failed to mark all as read', 'error');
                }
            });
        }

    })();

})();

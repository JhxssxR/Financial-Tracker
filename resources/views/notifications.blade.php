@extends('layouts.app')

@section('content')
<div style="max-width:1400px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:32px;font-weight:700;margin:0;">Notifications</h1>
            <p class="muted" style="margin:4px 0 0;font-size:14px;">All caught up!</p>
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
        <div>
            <h2 style="font-size:20px;font-weight:700;margin:0 0 16px;">Recent Notifications</h2>
            
            <!-- Empty State -->
            <div class="card" style="padding:80px 40px;text-align:center;">
                <div style="display:inline-flex;width:80px;height:80px;background:#1a2b3a;border-radius:50%;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="muted" style="margin:0;font-size:16px;">No notifications to display</p>
            </div>
        </div>

        <!-- Right: Notification Settings -->
        <div>
            <div class="card card-pad-lg">
                <h2 style="font-size:18px;font-weight:700;margin:0 0 20px;">Notification Settings</h2>
                
                <!-- Notification Types Section -->
                <div style="margin-bottom:24px;">
                    <h3 style="font-size:15px;font-weight:700;margin:0 0 16px;color:#e2e8f0;">Notification Types</h3>
                    
                    <!-- Budget Alerts -->
                    <div style="margin-bottom:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Budget Alerts</div>
                                <div class="muted" style="font-size:12px;">Get notified when you exceed budget limits</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Goal Updates -->
                    <div style="margin-bottom:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Goal Updates</div>
                                <div class="muted" style="font-size:12px;">Progress updates on your financial goals</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Reminders -->
                    <div style="margin-bottom:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Payment Reminders</div>
                                <div class="muted" style="font-size:12px;">Reminders for upcoming payments</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Weekly Insights -->
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Weekly Insights</div>
                                <div class="muted" style="font-size:12px;">Weekly spending and saving insights</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Delivery Method Section -->
                <div>
                    <h3 style="font-size:15px;font-weight:700;margin:0 0 16px;color:#e2e8f0;">Delivery Method</h3>
                    
                    <!-- Email Notifications -->
                    <div style="margin-bottom:20px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Email Notifications</div>
                                <div class="muted" style="font-size:12px;">Receive notifications via email</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Push Notifications -->
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-weight:600;font-size:14px;margin-bottom:2px;">Push Notifications</div>
                                <div class="muted" style="font-size:12px;">Receive push notifications in browser</div>
                            </div>
                            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div style="max-width:1080px;margin:0 auto;">
    <div class="page-header">
        <h1 style="font-size:32px;font-weight:700;">Settings</h1>
    </div>
    <p class="muted" style="margin-top:-8px;margin-bottom:24px;">Manage your financial tracker preferences</p>

    <!-- Currency Section -->
    <div class="card card-pad-lg" style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:48px;height:48px;background:#1a2b3a;border-radius:12px;display:grid;place-items:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;margin:0;">Currency</h2>
                <p class="muted" style="margin:0;font-size:14px;">Select your preferred currency</p>
            </div>
        </div>

        <!-- Current Currency Display -->
        <div style="background:#0b1220;border:1px solid #334155;border-radius:12px;padding:16px;margin-bottom:20px;">
            <div class="muted" style="font-size:13px;margin-bottom:8px;">Current Currency</div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;background:#1e293b;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:14px;color:#10b981;">
                    EU
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;">Euro</div>
                    <div class="muted" style="font-size:13px;">€ EUR</div>
                </div>
            </div>
        </div>

        <!-- Currency Grid -->
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
            <!-- US Dollar -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        US
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">US Dollar</div>
                        <div class="muted" style="font-size:12px;">$ USD</div>
                    </div>
                </div>
            </div>

            <!-- Euro (Selected) -->
            <div style="background:rgba(16,185,129,.12);border:2px solid #10b981;border-radius:12px;padding:14px;cursor:pointer;position:relative;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#1a2b3a;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;color:#10b981;">
                        EU
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;color:#34d399;">Euro</div>
                        <div style="font-size:12px;color:#6ee7b7;">€ EUR</div>
                    </div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 13l4 4L19 7" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- British Pound -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        GB
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">British Pound</div>
                        <div class="muted" style="font-size:12px;">£ GBP</div>
                    </div>
                </div>
            </div>

            <!-- Japanese Yen -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        JP
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Japanese Yen</div>
                        <div class="muted" style="font-size:12px;">¥ JPY</div>
                    </div>
                </div>
            </div>

            <!-- Canadian Dollar -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        CA
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Canadian Dollar</div>
                        <div class="muted" style="font-size:12px;">$ CAD</div>
                    </div>
                </div>
            </div>

            <!-- Australian Dollar -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        AU
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Australian Dollar</div>
                        <div class="muted" style="font-size:12px;">$ AUD</div>
                    </div>
                </div>
            </div>

            <!-- Swiss Franc -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        CH
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Swiss Franc</div>
                        <div class="muted" style="font-size:12px;">Fr CHF</div>
                    </div>
                </div>
            </div>

            <!-- Chinese Yuan -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        CN
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Chinese Yuan</div>
                        <div class="muted" style="font-size:12px;">¥ CNY</div>
                    </div>
                </div>
            </div>

            <!-- Indian Rupee -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        IN
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Indian Rupee</div>
                        <div class="muted" style="font-size:12px;">₹ INR</div>
                    </div>
                </div>
            </div>

            <!-- Philippine Peso -->
            <div style="background:#1a2b3a;border:2px solid #2d3d4f;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;" onmouseover="this.style.borderColor='#3d4d5f'" onmouseout="this.style.borderColor='#2d3d4f'">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                        PH
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:14px;">Philippine Peso</div>
                        <div class="muted" style="font-size:12px;">₱ PHP</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="card card-pad-lg" style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:48px;height:48px;background:#1a2b3a;border-radius:12px;display:grid;place-items:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;margin:0;">Notifications</h2>
                <p class="muted" style="margin:0;font-size:14px;">Manage your notification preferences</p>
            </div>
        </div>

        <!-- Email Alerts -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Email Alerts</div>
                <div class="muted" style="font-size:13px;">Receive alerts via email</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
            </label>
        </div>

        <!-- Budget Alerts -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Budget Alerts</div>
                <div class="muted" style="font-size:13px;">Get notified when approaching budget limits</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
            </label>
        </div>

        <!-- Transaction Alerts -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Transaction Alerts</div>
                <div class="muted" style="font-size:13px;">Receive alerts for every transaction</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
            </label>
        </div>

        <!-- Weekly Reports -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Weekly Reports</div>
                <div class="muted" style="font-size:13px;">Get weekly financial summary reports</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
            </label>
        </div>
    </div>

    <!-- Privacy & Security Section -->
    <div class="card card-pad-lg" style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:48px;height:48px;background:#1a2b3a;border-radius:12px;display:grid;place-items:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;margin:0;">Privacy & Security</h2>
                <p class="muted" style="margin:0;font-size:14px;">Control your data and privacy</p>
            </div>
        </div>

        <!-- Show Balance -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Show Balance</div>
                <div class="muted" style="font-size:13px;">Display balance on dashboard</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" checked style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
            </label>
        </div>

        <!-- Public Profile -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Public Profile</div>
                <div class="muted" style="font-size:13px;">Make your profile visible to others</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
            </label>
        </div>

        <!-- Share Analytics Data -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
            <div>
                <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Share Analytics Data</div>
                <div class="muted" style="font-size:13px;">Help improve our service</div>
            </div>
            <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                <input type="checkbox" style="opacity:0;width:0;height:0;" class="toggle-switch">
                <span class="toggle-track" style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                <span class="toggle-thumb" style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
            </label>
        </div>
    </div>

    <!-- Account Section -->
    <div class="card card-pad-lg" style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div style="width:48px;height:48px;background:#1a2b3a;border-radius:12px;display:grid;place-items:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;margin:0;">Account</h2>
                <p class="muted" style="margin:0;font-size:14px;">Manage your account settings</p>
            </div>
        </div>

        <div style="border-radius:12px;overflow:hidden;border:1px solid #334155;">
            <!-- Change Password -->
            <div style="background:#1a2b3a;padding:18px;border-bottom:1px solid #334155;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#243442'" onmouseout="this.style.background='#1a2b3a'" onclick="openChangePassword()">
                <div style="font-weight:600;font-size:15px;color:#e2e8f0;">Change Password</div>
            </div>

            <!-- Update Email -->
            <div style="background:#1a2b3a;padding:18px;border-bottom:1px solid #334155;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#243442'" onmouseout="this.style.background='#1a2b3a'" onclick="openUpdateEmail()">
                <div style="font-weight:600;font-size:15px;color:#e2e8f0;">Update Email</div>
            </div>

            <!-- Delete Account -->
            <div style="background:#1a2b3a;padding:18px;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#243442'" onmouseout="this.style.background='#1a2b3a'" onclick="confirmDeleteAccount()">
                <div style="font-weight:600;font-size:15px;color:#f87171;">Delete Account</div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
        <button onclick="window.location.href='{{ route('dashboard') }}'" style="background:#374151;border:1px solid #4b5563;color:#e2e8f0;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:600;font-size:15px;transition:all .2s;" onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#374151'">
            Cancel
        </button>
        <button onclick="saveSettings()" style="background:#10b981;border:1px solid #059669;color:#fff;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:600;font-size:15px;display:inline-flex;align-items:center;gap:8px;transition:all .2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Save Changes
        </button>
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

    // Handle currency selection
    document.querySelectorAll('[style*="cursor:pointer"]').forEach(item => {
        item.addEventListener('click', function() {
            // Remove selection from all items
            document.querySelectorAll('[style*="cursor:pointer"]').forEach(el => {
                if (el !== this) {
                    el.style.background = '#1a2b3a';
                    el.style.border = '2px solid #2d3d4f';
                    // Reset text colors
                    const nameDiv = el.querySelector('div:nth-child(2) > div:first-child');
                    const codeDiv = el.querySelector('div:nth-child(2) > div:last-child');
                    const badge = el.querySelector('div:first-child > div');
                    if (nameDiv) nameDiv.style.color = '';
                    if (codeDiv) codeDiv.className = 'muted';
                    if (badge) badge.style.color = '';
                    // Remove checkmark
                    const svg = el.querySelector('svg');
                    if (svg) svg.remove();
                }
            });
            
            // Add selection to clicked item
            this.style.background = 'rgba(16,185,129,.12)';
            this.style.border = '2px solid #10b981';
            
            // Update text colors
            const nameDiv = this.querySelector('div:nth-child(2) > div:first-child');
            const codeDiv = this.querySelector('div:nth-child(2) > div:last-child');
            const badge = this.querySelector('div:first-child > div');
            if (nameDiv) nameDiv.style.color = '#34d399';
            if (codeDiv) {
                codeDiv.style.color = '#6ee7b7';
                codeDiv.className = '';
            }
            if (badge) badge.style.color = '#10b981';
            
            // Add checkmark if not exists
            if (!this.querySelector('svg')) {
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('width', '20');
                svg.setAttribute('height', '20');
                svg.setAttribute('viewBox', '0 0 24 24');
                svg.setAttribute('fill', 'none');
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', 'M5 13l4 4L19 7');
                path.setAttribute('stroke', '#10b981');
                path.setAttribute('stroke-width', '2.5');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                svg.appendChild(path);
                this.querySelector('div:first-child').appendChild(svg);
            }
        });
    });

    // Account actions
    function openChangePassword() {
        alert('Change Password feature will be implemented here.\n\nYou will be able to:\n• Enter your current password\n• Set a new password\n• Confirm the new password');
    }

    function openUpdateEmail() {
        alert('Update Email feature will be implemented here.\n\nYou will be able to:\n• Enter your new email address\n• Verify with current password\n• Receive confirmation email');
    }

    function confirmDeleteAccount() {
        if (confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you absolutely sure you want to delete your account?\n\nThis will permanently delete:\n• All your personal data\n• All transactions and budgets\n• All savings goals and reports\n• Your entire account history')) {
            if (confirm('This is your final confirmation.\n\nType DELETE in the next prompt to confirm deletion.')) {
                const confirmation = prompt('Type DELETE to confirm account deletion:');
                if (confirmation === 'DELETE') {
                    alert('Account deletion functionality will be implemented here.');
                    // window.location.href = '/delete-account';
                } else {
                    alert('Account deletion cancelled. The confirmation text did not match.');
                }
            }
        }
    }

    function saveSettings() {
        // Get selected currency
        const selectedCurrency = document.querySelector('[style*="rgba(16,185,129"]');
        if (selectedCurrency) {
            const currencyName = selectedCurrency.querySelector('div:nth-child(2) > div:first-child').textContent;
            alert('Settings saved successfully!\n\nCurrency: ' + currencyName);
        } else {
            alert('Settings saved successfully!');
        }
    }
</script>
@endpush
@endsection

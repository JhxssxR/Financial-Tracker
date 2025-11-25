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
        <div style="background:#0b1220;border:1px solid #334155;border-radius:12px;padding:16px;margin-bottom:20px;" id="current-currency-display">
            <div class="muted" style="font-size:13px;margin-bottom:8px;">Current Currency</div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;background:#1e293b;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:14px;color:#10b981;" id="current-currency-code">
                    {{ Auth::user()->currency_code ?? 'EUR' }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;" id="current-currency-name">
                        @php
                            $currencies = [
                                'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
                                'EUR' => ['name' => 'Euro', 'symbol' => '€'],
                                'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
                                'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
                                'CAD' => ['name' => 'Canadian Dollar', 'symbol' => '$'],
                                'AUD' => ['name' => 'Australian Dollar', 'symbol' => '$'],
                                'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'Fr'],
                                'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
                                'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
                                'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱'],
                            ];
                            $currentCode = Auth::user()->currency_code ?? 'EUR';
                            echo $currencies[$currentCode]['name'];
                        @endphp
                    </div>
                    <div class="muted" style="font-size:13px;" id="current-currency-symbol">{{ Auth::user()->currency_symbol ?? '€' }} {{ Auth::user()->currency_code ?? 'EUR' }}</div>
                </div>
            </div>
        </div>

        <!-- Currency Grid -->
        <div class="currency-grid" style="display:grid;gap:12px;">
            @php
                $userCurrency = Auth::user()->currency_code ?? 'EUR';
                $allCurrencies = [
                    ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'abbr' => 'US'],
                    ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'abbr' => 'EU'],
                    ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'abbr' => 'GB'],
                    ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'abbr' => 'JP'],
                    ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => '$', 'abbr' => 'CA'],
                    ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => '$', 'abbr' => 'AU'],
                    ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'Fr', 'abbr' => 'CH'],
                    ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'abbr' => 'CN'],
                    ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'abbr' => 'IN'],
                    ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱', 'abbr' => 'PH'],
                ];
            @endphp

            @foreach($allCurrencies as $curr)
                @php $isSelected = $userCurrency === $curr['code']; @endphp
                <!-- {{ $curr['name'] }} -->
                <div class="currency-option {{ $isSelected ? 'selected' : '' }}" 
                     data-code="{{ $curr['code'] }}" 
                     data-symbol="{{ $curr['symbol'] }}" 
                     data-name="{{ $curr['name'] }}"
                     data-abbr="{{ $curr['abbr'] }}"
                     style="background:#1a2b3a;border:2px solid {{ $isSelected ? '#10b981' : '#2d3d4f' }};border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;position:relative;"
                     onmouseover="if(!this.classList.contains('selected')) { this.style.background='#243442'; this.style.borderColor='#3d4d5f'; }" 
                     onmouseout="if(!this.classList.contains('selected')) { this.style.background='#1a2b3a'; this.style.borderColor='#2d3d4f'; }">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                            {{ $curr['abbr'] }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600;font-size:14px;">{{ $curr['name'] }}</div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $curr['symbol'] }} {{ $curr['code'] }}</div>
                        </div>
                        @if($isSelected)
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="selected-checkmark">
                            <path d="M5 13l4 4L19 7" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        @endif
                    </div>
                </div>
            @endforeach
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
        <div class="setting-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
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
        <div class="setting-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
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
        <div class="setting-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
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
        <div class="setting-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
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

    <!-- Success Notification -->
    <div id="success-notification" style="position:fixed;top:80px;right:24px;background:#10b981;color:#fff;padding:16px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3);display:none;align-items:center;gap:12px;z-index:1000;animation:slideIn 0.3s ease-out;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span style="font-weight:600;">Settings saved successfully!</span>
    </div>

    <!-- Action Buttons -->
    <div class="settings-actions" style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
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

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

/* Settings responsive helpers */
.currency-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
.currency-option { box-sizing: border-box; width:100%; }
.currency-option { transition: all .15s ease; }
.currency-option .selected-checkmark { margin-left:8px; }

/* Toggle and settings rows */
.setting-row { display:flex; justify-content:space-between; align-items:center; }

.settings-actions { display:flex; justify-content:flex-end; gap:12px; }

@media (max-width: 900px) {
    .currency-grid { grid-template-columns: 1fr; }
}

    @media (max-width: 640px) {
    .currency-grid { grid-template-columns: 1fr; gap:10px; }
    .currency-option { padding:12px; display:flex; align-items:center; }
    .currency-option > div { flex:1 1 auto; }
    .currency-option .selected-checkmark { position:absolute; right:12px; top:12px; }
    .setting-row { flex-direction:column; align-items:flex-start; gap:8px; padding:8px 0; }
    .setting-row label { margin-top:6px; }
    .settings-actions { flex-direction:column-reverse; gap:10px; align-items:stretch; }
    .settings-actions button { width:100%; }
    /* Make current currency display stack on small screens */
    #current-currency-display > div { display:flex; flex-direction:column; align-items:flex-start; gap:8px; }
    /* Reduce card padding for small screens */
    .card { padding:12px; }
}
</style>

@push('scripts')
<script>
    let selectedCurrency = null;

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

    // Handle currency selection (visual only, doesn't save yet)
    document.querySelectorAll('.currency-option').forEach(option => {
        option.addEventListener('click', function() {
            const code = this.dataset.code;
            const symbol = this.dataset.symbol;
            const name = this.dataset.name;
            const abbr = this.dataset.abbr;
            
            // Store selected currency for later save
            selectedCurrency = { code, symbol, name, abbr };
            
            // Update all currency options styling - Reset all to unselected
            document.querySelectorAll('.currency-option').forEach(opt => {
                opt.style.borderColor = '#2d3d4f';
                opt.classList.remove('selected');
                
                // Remove checkmark
                const checkmark = opt.querySelector('.selected-checkmark');
                if (checkmark) checkmark.remove();
            });
            
            // Apply green border to selected option
            this.style.borderColor = '#10b981';
            this.classList.add('selected');
            
            // Add green checkmark
            const checkmarkSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            checkmarkSvg.setAttribute('width', '20');
            checkmarkSvg.setAttribute('height', '20');
            checkmarkSvg.setAttribute('viewBox', '0 0 24 24');
            checkmarkSvg.setAttribute('fill', 'none');
            checkmarkSvg.classList.add('selected-checkmark');
            
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('d', 'M5 13l4 4L19 7');
            path.setAttribute('stroke', '#10b981');
            path.setAttribute('stroke-width', '2.5');
            path.setAttribute('stroke-linecap', 'round');
            path.setAttribute('stroke-linejoin', 'round');
            
            checkmarkSvg.appendChild(path);
            this.querySelector('div').appendChild(checkmarkSvg);
            
            // Update current currency display
            document.getElementById('current-currency-code').textContent = abbr;
            document.getElementById('current-currency-name').textContent = name;
            document.getElementById('current-currency-symbol').textContent = symbol + ' ' + code;
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

    // Show notification function
    function showNotification() {
        const notification = document.getElementById('success-notification');
        notification.style.display = 'flex';
        notification.style.animation = 'slideIn 0.3s ease-out';
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }, 1000);
    }

    // Save settings button - saves currency selection
    function saveSettings() {
        if (selectedCurrency) {
            console.log('Saving currency:', selectedCurrency);
            
            // Send AJAX request to update currency
            fetch('{{ route("settings.currency") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    currency_code: selectedCurrency.code
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show notification
                    showNotification();
                    
                    // Reload page after notification disappears
                    setTimeout(() => {
                        window.location.reload();
                    }, 1300);
                } else {
                    alert('Failed to save currency. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save settings. Please try again.');
            });
        } else {
            console.log('No currency selected');
            // If no currency change, just show notification
            showNotification();
        }
    }
</script>
@endpush
@endsection

<?php $__env->startSection('content'); ?>
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
                    <?php echo e(Auth::user()->currency_code ?? 'PHP'); ?>

                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;" id="current-currency-name">
                        <?php
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
                            $currentCode = Auth::user()->currency_code ?? 'PHP';
                            echo $currencies[$currentCode]['name'];
                        ?>
                    </div>
                    <div class="muted" style="font-size:13px;" id="current-currency-symbol"><?php echo e(Auth::user()->currency_symbol ?? '₱'); ?> <?php echo e(Auth::user()->currency_code ?? 'PHP'); ?></div>
                </div>
            </div>
        </div>

        <!-- Currency Grid -->
        <div class="currency-grid" style="display:grid;gap:12px;">
            <?php
                $userCurrency = Auth::user()->currency_code ?? 'PHP';
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
            ?>

            <?php $__currentLoopData = $allCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isSelected = $userCurrency === $curr['code']; ?>
                <!-- <?php echo e($curr['name']); ?> -->
                <div class="currency-option <?php echo e($isSelected ? 'selected' : ''); ?>" 
                     data-code="<?php echo e($curr['code']); ?>" 
                     data-symbol="<?php echo e($curr['symbol']); ?>" 
                     data-name="<?php echo e($curr['name']); ?>"
                     data-abbr="<?php echo e($curr['abbr']); ?>"
                     style="background:#1a2b3a;border:2px solid <?php echo e($isSelected ? '#10b981' : '#2d3d4f'); ?>;border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;position:relative;"
                     onmouseover="if(!this.classList.contains('selected')) { this.style.background='#243442'; this.style.borderColor='#3d4d5f'; }" 
                     onmouseout="if(!this.classList.contains('selected')) { this.style.background='#1a2b3a'; this.style.borderColor='#2d3d4f'; }">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;background:#243442;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:13px;">
                            <?php echo e($curr['abbr']); ?>

                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600;font-size:14px;"><?php echo e($curr['name']); ?></div>
                            <div style="font-size:12px;color:#94a3b8;"><?php echo e($curr['symbol']); ?> <?php echo e($curr['code']); ?></div>
                        </div>
                        <?php if($isSelected): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="selected-checkmark">
                            <path d="M5 13l4 4L19 7" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <div id="success-notification" style="position:fixed;top:80px;right:24px;left:auto;background:#10b981;color:#fff;padding:16px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3);display:none;align-items:center;gap:12px;z-index:10001;max-width:400px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span style="font-weight:600;">Settings saved successfully!</span>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;pointer-events:none;">
        <div class="modal" style="background:#1e293b;border-radius:16px;padding:28px;width:90%;max-width:500px;border:1px solid #334155;pointer-events:auto;position:relative;z-index:10000;">
            <h3 style="font-size:20px;font-weight:700;margin:0 0 20px;">Change Password</h3>
            <form id="changePasswordForm">
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">Current Password</label>
                    <input type="password" name="current_password" class="form-input" required style="background:#0f1a26;border:1px solid #334155;color:#e2e8f0;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">New Password</label>
                    <input type="password" name="new_password" class="form-input" required style="background:#0f1a26;border:1px solid #334155;color:#e2e8f0;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-input" required style="background:#0f1a26;border:1px solid #334155;color:#e2e8f0;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="display:flex;gap:12px;justify-content:flex-end;">
                    <button type="button" onclick="closeChangePassword()" style="background:#374151;border:1px solid #4b5563;color:#e2e8f0;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;">
                        Cancel
                    </button>
                    <button type="submit" style="background:#10b981;border:1px solid #059669;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Email Modal -->
    <div id="updateEmailModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;pointer-events:none;">
        <div class="modal" style="background:#1e293b;border-radius:16px;padding:28px;width:90%;max-width:500px;border:1px solid #334155;pointer-events:auto;position:relative;z-index:10000;">
            <h3 style="font-size:20px;font-weight:700;margin:0 0 20px;">Update Email</h3>
            <form id="updateEmailForm">
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">Current Email</label>
                    <input type="email" value="<?php echo e(Auth::user()->email); ?>" readonly style="background:#0f1a26;border:1px solid #334155;color:#94a3b8;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">New Email</label>
                    <input type="email" name="new_email" class="form-input" required style="background:#0f1a26;border:1px solid #334155;color:#e2e8f0;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="display:block;margin-bottom:6px;color:#e2e8f0;">Confirm Password</label>
                    <input type="password" name="password" class="form-input" required style="background:#0f1a26;border:1px solid #334155;color:#e2e8f0;padding:12px;border-radius:8px;width:100%;box-sizing:border-box;font-size:15px;">
                </div>
                <div style="display:flex;gap:12px;justify-content:flex-end;">
                    <button type="button" onclick="closeUpdateEmail()" style="background:#374151;border:1px solid #4b5563;color:#e2e8f0;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;">
                        Cancel
                    </button>
                    <button type="submit" style="background:#10b981;border:1px solid #059669;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;">
                        Update Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="settings-actions" style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
        <button onclick="window.location.href='<?php echo e(route('dashboard')); ?>'" style="background:#374151;border:1px solid #4b5563;color:#e2e8f0;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:600;font-size:15px;transition:all .2s;" onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#374151'">
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
    /* Adjust notification position for mobile */
    #success-notification { top:90px; right:12px; left:12px; max-width:none; }
}
</style>

<?php $__env->startPush('scripts'); ?>
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
        const modal = document.getElementById('changePasswordModal');
        modal.style.display = 'flex';
        modal.style.pointerEvents = 'auto';
    }

    function closeChangePassword() {
        const modal = document.getElementById('changePasswordModal');
        modal.style.display = 'none';
        modal.style.pointerEvents = 'none';
        document.getElementById('changePasswordForm').reset();
    }

    function openUpdateEmail() {
        const modal = document.getElementById('updateEmailModal');
        modal.style.display = 'flex';
        modal.style.pointerEvents = 'auto';
    }

    function closeUpdateEmail() {
        const modal = document.getElementById('updateEmailModal');
        modal.style.display = 'none';
        modal.style.pointerEvents = 'none';
        document.getElementById('updateEmailForm').reset();
    }

    // Change Password Form Submit
    document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('<?php echo e(route("settings.password")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });
            
            const data = await response.json();
            
            if (data.success) {
                closeChangePassword();
                showNotification();
            } else {
                alert(data.message || 'Failed to change password. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    // Update Email Form Submit
    document.getElementById('updateEmailForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('<?php echo e(route("settings.email")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });
            
            const data = await response.json();
            
            if (data.success) {
                closeUpdateEmail();
                showNotification();
                setTimeout(() => {
                    window.location.reload();
                }, 1300);
            } else {
                alert(data.message || 'Failed to update email. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    function confirmDeleteAccount() {
        if (confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you absolutely sure you want to delete your account?\n\nThis will permanently delete:\n• All your personal data\n• All transactions and budgets\n• All savings goals and reports\n• Your entire account history')) {
            if (confirm('This is your final confirmation.\n\nType DELETE in the next prompt to confirm deletion.')) {
                const confirmation = prompt('Type DELETE to confirm account deletion:');
                if (confirmation === 'DELETE') {
                    fetch('<?php echo e(route("settings.delete-account")); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Your account has been deleted.');
                            window.location.href = '/';
                        } else {
                            alert(data.message || 'Failed to delete account.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
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
            fetch('<?php echo e(route("settings.currency")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Financial-Tracker\resources\views/settings.blade.php ENDPATH**/ ?>
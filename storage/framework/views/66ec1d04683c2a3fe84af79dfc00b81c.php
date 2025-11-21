

<?php $__env->startSection('content'); ?>
<div style="max-width:1400px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <h1 style="font-size:32px;font-weight:700;margin:0;">Profile Settings</h1>
        <button class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;width:auto;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Edit Profile
        </button>
    </div>

    <div style="display:grid;grid-template-columns:380px 1fr;gap:24px;">
        <!-- Left Sidebar -->
        <div>
            <!-- Profile Card -->
            <div class="card card-pad-lg" style="text-align:center;margin-bottom:24px;">
                <div style="width:120px;height:120px;background:#3d4a5a;border-radius:50%;display:grid;place-items:center;margin:0 auto 16px;color:#94a3b8;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 style="font-size:20px;font-weight:700;margin:0 0 4px;"><?php echo e(Auth::user()->name); ?></h2>
                <p class="muted" style="margin:0 0 12px;font-size:14px;"><?php echo e(Auth::user()->email); ?></p>
                <div style="display:flex;align-items:center;justify-content:center;gap:6px;color:#94a3b8;font-size:13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Member since <?php echo e(Auth::user()->created_at->format('F Y')); ?>

                </div>
            </div>

            <!-- Account Summary -->
            <div class="card card-pad-lg">
                <h3 style="font-size:16px;font-weight:700;margin:0 0 16px;">Account Summary</h3>
                
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #334155;">
                    <span class="muted" style="font-size:14px;">Total Transactions</span>
                </div>
                
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #334155;">
                    <span class="muted" style="font-size:14px;">Budgets Created</span>
                </div>
                
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #334155;">
                    <span class="muted" style="font-size:14px;">Goals Achieved</span>
                </div>
                
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;">
                    <span class="muted" style="font-size:14px;">Data Export</span>
                    <a href="#" class="help-link" style="font-size:14px;font-weight:600;">Download</a>
                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div>
            <!-- Personal Information -->
            <div class="card card-pad-lg" style="margin-bottom:24px;">
                <h3 style="font-size:18px;font-weight:700;margin:0 0 20px;">Personal Information</h3>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-input" value="John" readonly style="background:#0f1a26;">
                    </div>
                    <div>
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-input" value="Doe" readonly style="background:#0f1a26;">
                    </div>
                </div>

                <div>
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <span class="input-icon-left">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="email" class="form-input pad-left-icon" value="john.doe@email.com" readonly style="background:#0f1a26;">
                    </div>
                </div>
            </div>

            <!-- App Preferences -->
            <div class="card card-pad-lg" style="margin-bottom:24px;">
                <h3 style="font-size:18px;font-weight:700;margin:0 0 20px;">App Preferences</h3>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label">Default Currency</label>
                        <select class="form-input" style="background:#0f1a26;">
                            <option>USD</option>
                            <option>EUR</option>
                            <option>GBP</option>
                            <option>JPY</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Timezone</label>
                        <select class="form-input" style="background:#0f1a26;">
                            <option>Eastern Time (ET)</option>
                            <option>Central Time (CT)</option>
                            <option>Mountain Time (MT)</option>
                            <option>Pacific Time (PT)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Privacy & Security -->
            <div class="card card-pad-lg">
                <h3 style="font-size:18px;font-weight:700;margin:0 0 20px;">Privacy & Security</h3>
                
                <!-- Dark Mode -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
                    <div>
                        <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Dark Mode</div>
                        <div class="muted" style="font-size:13px;">Use dark theme across the app</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                        <input type="checkbox" checked style="opacity:0;width:0;height:0;">
                        <span style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                        <span style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                    </label>
                </div>

                <!-- Email Notifications -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
                    <div>
                        <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Email Notifications</div>
                        <div class="muted" style="font-size:13px;">Receive notifications via email</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                        <input type="checkbox" checked style="opacity:0;width:0;height:0;">
                        <span style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                        <span style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                    </label>
                </div>

                <!-- Data Sharing -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
                    <div>
                        <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Data Sharing</div>
                        <div class="muted" style="font-size:13px;">Share anonymous usage data</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                        <input type="checkbox" style="opacity:0;width:0;height:0;">
                        <span style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                        <span style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
                    </label>
                </div>

                <!-- Auto Backup -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #334155;">
                    <div>
                        <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Auto Backup</div>
                        <div class="muted" style="font-size:13px;">Automatically backup your data</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                        <input type="checkbox" checked style="opacity:0;width:0;height:0;">
                        <span style="position:absolute;inset:0;background:#334155;border-radius:24px;transition:.3s;"></span>
                        <span style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#fff;border-radius:50%;transition:.3s;transform:translateX(24px);"></span>
                    </label>
                </div>

                <!-- Two-Factor Authentication -->
                <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 0;">
                    <div>
                        <div style="font-weight:600;font-size:15px;margin-bottom:2px;">Two-Factor Authentication</div>
                        <div class="muted" style="font-size:13px;">Add extra security to your account</div>
                    </div>
                    <label style="position:relative;display:inline-block;width:48px;height:24px;cursor:pointer;">
                        <input type="checkbox" style="opacity:0;width:0;height:0;">
                        <span style="position:absolute;inset:0;background:#1e293b;border-radius:24px;transition:.3s;"></span>
                        <span style="position:absolute;left:4px;bottom:4px;width:16px;height:16px;background:#94a3b8;border-radius:50%;transition:.3s;"></span>
                    </label>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card" style="margin-top:24px;border:1px solid #7f1d1d;background:#1a0f0f;">
                <div style="padding:18px;border-bottom:1px solid #7f1d1d;">
                    <h3 style="font-size:18px;font-weight:700;margin:0;color:#f87171;">Danger Zone</h3>
                </div>
                <div style="padding:18px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-weight:600;font-size:15px;margin-bottom:2px;color:#e2e8f0;">Delete Account</div>
                            <div class="muted" style="font-size:13px;">Permanently delete your account and all data</div>
                        </div>
                        <button onclick="confirmDelete()" style="background:#991b1b;border:1px solid #7f1d1d;color:#fee;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#7f1d1d'" onmouseout="this.style.background='#991b1b'">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Toggle switches functionality
    document.querySelectorAll('label:has(input[type="checkbox"])').forEach(label => {
        const checkbox = label.querySelector('input[type="checkbox"]');
        const track = label.querySelectorAll('span')[0];
        const thumb = label.querySelectorAll('span')[1];
        
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

    // Delete account confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you absolutely sure you want to delete your account?\n\nThis will permanently delete:\n• All your personal data\n• All transactions and budgets\n• All savings goals and reports\n• Your entire account history')) {
            if (confirm('This is your final confirmation.\n\nType DELETE in the next prompt to confirm deletion.')) {
                const confirmation = prompt('Type DELETE to confirm account deletion:');
                if (confirmation === 'DELETE') {
                    // Submit delete request
                    alert('Account deletion functionality will be implemented here.');
                    // window.location.href = '/delete-account';
                } else {
                    alert('Account deletion cancelled. The confirmation text did not match.');
                }
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/profile.blade.php ENDPATH**/ ?>
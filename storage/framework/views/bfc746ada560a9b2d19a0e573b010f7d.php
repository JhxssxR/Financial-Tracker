

<?php $__env->startSection('content'); ?>
<div class="form-card" style="max-width:520px; min-height:auto;padding:24px;">
    <!-- Currency Icon -->
    <div style="display:flex;justify-content:center;margin-bottom:12px;">
        <div style="width:48px;height:48px;background:#10b981;color:#ffffff;border-radius:12px;display:grid;place-items:center;font-size:24px;font-weight:700;">₱</div>
    </div>

    <!-- Header -->
    <div class="form-title" style="font-size:24px;margin-bottom:4px;">Create Account</div>
    <div class="form-subtitle" style="margin-bottom:16px;">Join PF Trackers and start managing your finances</div>

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
        <div style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);border-radius:8px;padding:12px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="color:#f87171;font-size:14px;"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="POST" action="<?php echo e(route('register')); ?>">
        <?php echo csrf_field(); ?>
        
        <!-- First Name & Last Name Row -->
        <div class="form-row" style="margin-bottom:10px;">
            <div>
                <label class="form-label" style="font-size:13px;font-weight:500;margin-bottom:4px;">First Name</label>
                <div class="input-wrap">
                    <span class="input-icon-left" style="font-size:18px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                            <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <input class="form-input pad-left-icon" type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" placeholder="First name" required style="padding:13px 14px 13px 44px;" />
                </div>
            </div>
            <div>
                <label class="form-label" style="font-size:13px;font-weight:500;margin-bottom:4px;">Last Name</label>
                <div class="input-wrap">
                    <input class="form-input" type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" placeholder="Last name" required style="padding:13px 14px;" />
                </div>
            </div>
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:13px;font-weight:500;margin-bottom:4px;">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon" type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Enter your email" required style="padding:13px 14px 13px 44px;" />
            </div>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:13px;font-weight:500;margin-bottom:4px;">Password</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 15V17M6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V13C20 12.4696 19.7893 11.9609 19.4142 11.5858C19.0391 11.2107 18.5304 11 18 11H6C5.46957 11 4.96086 11.2107 4.58579 11.5858C4.21071 11.9609 4 12.4696 4 13V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21ZM16 11V7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7V11H16Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password" name="password" type="password" placeholder="Create password" required style="padding:13px 44px 13px 44px;" />
                <span class="input-icon-right" onclick="togglePassword('reg-password', 'eye-icon-1')" style="cursor:pointer;display:flex;align-items:center;">
                    <svg id="eye-icon-1" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:13px;font-weight:500;margin-bottom:4px;">Confirm Password</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 15V17M6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V13C20 12.4696 19.7893 11.9609 19.4142 11.5858C19.0391 11.2107 18.5304 11 18 11H6C5.46957 11 4.96086 11.2107 4.58579 11.5858C4.21071 11.9609 4 12.4696 4 13V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21ZM16 11V7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7V11H16Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password2" name="password_confirmation" type="password" placeholder="Confirm password" required style="padding:13px 44px 13px 44px;" />
                <span class="input-icon-right" onclick="togglePassword('reg-password2', 'eye-icon-2')" style="cursor:pointer;display:flex;align-items:center;">
                    <svg id="eye-icon-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Terms of Service Checkbox -->
        <div class="form-group" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;">
            <input type="checkbox" name="terms" required style="width:16px;height:16px;margin-top:2px;cursor:pointer;accent-color:#10b981;">
            <div style="font-size:13px;line-height:1.4;">
                <span style="color:#cbd5e1;">I agree to the </span>
                <a href="#" onclick="showTermsModal(event)" class="help-link" style="font-size:13px;">Terms of Service</a>
                <span style="color:#cbd5e1;"> and </span>
                <a href="#" onclick="showPrivacyModal(event)" class="help-link" style="font-size:13px;">Privacy Policy</a>
            </div>
        </div>

        <!-- Updates Checkbox -->
        <div class="form-group" style="display:flex;gap:8px;align-items:flex-start;margin-bottom:14px;">
            <input type="checkbox" name="updates" style="width:16px;height:16px;margin-top:2px;cursor:pointer;accent-color:#10b981;">
            <div style="font-size:13px;color:#cbd5e1;line-height:1.4;">
                I want to receive updates about new features and financial tips
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group" style="margin-bottom:12px;">
            <button class="btn btn-primary" type="submit" style="padding:14px;font-size:15px;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 9V7C18 5.89543 17.1046 5 16 5H8C6.89543 5 6 5.89543 6 7V9M18 9H6M18 9C19.1046 9 20 9.89543 20 11V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V11C4 9.89543 4.89543 9 6 9M12 16H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Create Account
            </button>
        </div>
    </form>

    <!-- Sign In Link -->
    <div style="text-align:center;font-size:14px;" class="muted">
        Already have an account? <a class="help-link" href="<?php echo e(route('login')); ?>" style="font-weight:600;">Sign in</a>
    </div>
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:10000;padding:20px;overflow-y:auto;">
    <div style="max-width:800px;margin:40px auto;background:#0f172a;border-radius:12px;border:1px solid #334155;position:relative;">
        <div style="padding:24px;border-bottom:1px solid #334155;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:24px;font-weight:700;color:#e2e8f0;margin:0;">Terms of Service</h2>
            <button onclick="closeTermsModal()" style="background:transparent;border:none;color:#94a3b8;font-size:32px;cursor:pointer;padding:0;line-height:1;width:32px;height:32px;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#94a3b8'">&times;</button>
        </div>
        <div style="padding:24px;color:#cbd5e1;line-height:1.7;max-height:calc(100vh - 240px);overflow-y:auto;">
            <p style="margin-bottom:16px;"><strong style="color:#e2e8f0;">Effective Date:</strong> December 8, 2025</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">1. Acceptance of Terms</h3>
            <p style="margin-bottom:16px;">By accessing and using PF Trackers ("the Service"), you accept and agree to be bound by the terms and provisions of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">2. Use License</h3>
            <p style="margin-bottom:16px;">Permission is granted to temporarily access PF Trackers for personal, non-commercial use only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
            <ul style="margin:12px 0;padding-left:24px;">
                <li style="margin-bottom:8px;">Modify or copy the materials</li>
                <li style="margin-bottom:8px;">Use the materials for any commercial purpose</li>
                <li style="margin-bottom:8px;">Attempt to decompile or reverse engineer any software contained on PF Trackers</li>
                <li style="margin-bottom:8px;">Remove any copyright or other proprietary notations from the materials</li>
                <li style="margin-bottom:8px;">Transfer the materials to another person or "mirror" the materials on any other server</li>
            </ul>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">3. User Account and Security</h3>
            <p style="margin-bottom:16px;">You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account. PF Trackers reserves the right to refuse service, terminate accounts, or remove content at our sole discretion.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">4. Financial Data and Privacy</h3>
            <p style="margin-bottom:16px;">Your financial data is stored securely and is only accessible by you. We do not share, sell, or distribute your personal financial information to third parties. However, you acknowledge that no method of transmission over the internet is 100% secure.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">5. Disclaimer</h3>
            <p style="margin-bottom:16px;">The materials on PF Trackers are provided on an 'as is' basis. PF Trackers makes no warranties, expressed or implied, and hereby disclaims all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property.</p>
            <p style="margin-bottom:16px;"><strong style="color:#e2e8f0;">PF Trackers is not a financial advisor.</strong> The information and tools provided are for informational purposes only and should not be considered financial advice. Always consult with a qualified financial professional before making financial decisions.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">6. Limitations</h3>
            <p style="margin-bottom:16px;">In no event shall PF Trackers or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on PF Trackers.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">7. Accuracy of Materials</h3>
            <p style="margin-bottom:16px;">The materials appearing on PF Trackers could include technical, typographical, or photographic errors. PF Trackers does not warrant that any of the materials on its website are accurate, complete, or current.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">8. Modifications</h3>
            <p style="margin-bottom:16px;">PF Trackers may revise these terms of service at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">9. Contact Information</h3>
            <p style="margin-bottom:16px;">If you have any questions about these Terms of Service, please contact us through the support channels provided in the application.</p>
        </div>
        <div style="padding:20px 24px;border-top:1px solid #334155;display:flex;justify-content:flex-end;">
            <button onclick="closeTermsModal()" class="btn btn-primary" style="padding:10px 24px;">I Understand</button>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div id="privacyModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:10000;padding:20px;overflow-y:auto;">
    <div style="max-width:800px;margin:40px auto;background:#0f172a;border-radius:12px;border:1px solid #334155;position:relative;">
        <div style="padding:24px;border-bottom:1px solid #334155;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:24px;font-weight:700;color:#e2e8f0;margin:0;">Privacy Policy</h2>
            <button onclick="closePrivacyModal()" style="background:transparent;border:none;color:#94a3b8;font-size:32px;cursor:pointer;padding:0;line-height:1;width:32px;height:32px;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#94a3b8'">&times;</button>
        </div>
        <div style="padding:24px;color:#cbd5e1;line-height:1.7;max-height:calc(100vh - 240px);overflow-y:auto;">
            <p style="margin-bottom:16px;"><strong style="color:#e2e8f0;">Effective Date:</strong> December 8, 2025</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">1. Information We Collect</h3>
            <p style="margin-bottom:16px;">We collect information that you provide directly to us, including:</p>
            <ul style="margin:12px 0;padding-left:24px;">
                <li style="margin-bottom:8px;"><strong style="color:#e2e8f0;">Account Information:</strong> Name, email address, and password</li>
                <li style="margin-bottom:8px;"><strong style="color:#e2e8f0;">Financial Data:</strong> Transaction records, budgets, savings goals, and categories</li>
                <li style="margin-bottom:8px;"><strong style="color:#e2e8f0;">Usage Data:</strong> How you interact with our service, features used, and preferences</li>
            </ul>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">2. How We Use Your Information</h3>
            <p style="margin-bottom:16px;">We use the information we collect to:</p>
            <ul style="margin:12px 0;padding-left:24px;">
                <li style="margin-bottom:8px;">Provide, maintain, and improve our services</li>
                <li style="margin-bottom:8px;">Process transactions and send related information</li>
                <li style="margin-bottom:8px;">Send technical notices, updates, and support messages</li>
                <li style="margin-bottom:8px;">Respond to your comments and questions</li>
                <li style="margin-bottom:8px;">Generate analytics to understand how users interact with our service</li>
            </ul>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">3. Data Security</h3>
            <p style="margin-bottom:16px;">We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. Your password is encrypted and stored securely. However, no method of transmission over the Internet is 100% secure.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">4. Data Sharing and Disclosure</h3>
            <p style="margin-bottom:16px;"><strong style="color:#e2e8f0;">We do not sell your personal information.</strong> We may share your information only in the following circumstances:</p>
            <ul style="margin:12px 0;padding-left:24px;">
                <li style="margin-bottom:8px;">With your consent</li>
                <li style="margin-bottom:8px;">To comply with legal obligations</li>
                <li style="margin-bottom:8px;">To protect the rights and safety of PF Trackers and our users</li>
            </ul>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">5. Data Retention</h3>
            <p style="margin-bottom:16px;">We retain your personal information for as long as your account is active or as needed to provide you services. You may request deletion of your account and associated data at any time through your account settings.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">6. Your Rights</h3>
            <p style="margin-bottom:16px;">You have the right to:</p>
            <ul style="margin:12px 0;padding-left:24px;">
                <li style="margin-bottom:8px;">Access and receive a copy of your personal data</li>
                <li style="margin-bottom:8px;">Correct inaccurate or incomplete data</li>
                <li style="margin-bottom:8px;">Request deletion of your data</li>
                <li style="margin-bottom:8px;">Object to processing of your data</li>
                <li style="margin-bottom:8px;">Export your data in a portable format</li>
            </ul>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">7. Cookies and Tracking</h3>
            <p style="margin-bottom:16px;">We use cookies and similar tracking technologies to track activity on our service and store certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">8. Children's Privacy</h3>
            <p style="margin-bottom:16px;">Our service is not intended for users under the age of 18. We do not knowingly collect personal information from children under 18. If you become aware that a child has provided us with personal information, please contact us.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">9. Changes to This Privacy Policy</h3>
            <p style="margin-bottom:16px;">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Effective Date" at the top.</p>
            
            <h3 style="font-size:18px;font-weight:700;color:#e2e8f0;margin:24px 0 12px 0;">10. Contact Us</h3>
            <p style="margin-bottom:16px;">If you have any questions about this Privacy Policy, please contact us through the support channels provided in the application.</p>
        </div>
        <div style="padding:20px 24px;border-top:1px solid #334155;display:flex;justify-content:flex-end;">
            <button onclick="closePrivacyModal()" class="btn btn-primary" style="padding:10px 24px;">I Understand</button>
        </div>
    </div>
</div>

<script>
function showTermsModal(e) {
    e.preventDefault();
    document.getElementById('termsModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeTermsModal() {
    document.getElementById('termsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showPrivacyModal(e) {
    e.preventDefault();
    document.getElementById('privacyModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePrivacyModal() {
    document.getElementById('privacyModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'termsModal') {
        closeTermsModal();
    }
    if (e.target.id === 'privacyModal') {
        closePrivacyModal();
    }
});

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M13.875 18.825C13.2664 18.9417 12.6418 19 12 19C7.52281 19 3.73251 16.0571 2.45825 12C2.94936 10.3456 3.82894 8.82777 5.02188 7.56406M9.87868 9.87868C10.4216 9.33579 11.1716 9 12 9C13.6569 9 15 10.3431 15 12C15 12.8284 14.6642 13.5784 14.1213 14.1213M9.87868 9.87868L14.1213 14.1213M9.87868 9.87868L6.5 6.5M14.1213 14.1213L17.5 17.5M14.1213 14.1213L17.5 17.5M6.5 6.5L3 3M6.5 6.5C8.02125 5.52625 9.95625 5 12 5C16.4776 5 20.2679 7.94291 21.5421 12C20.8658 14.2083 19.4765 16.0766 17.5 17.5M17.5 17.5L21 21" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/register.blade.php ENDPATH**/ ?>
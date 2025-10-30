

<?php $__env->startSection('content'); ?>
<div class="form-card" style="max-width:520px; min-height:440px;">
    <!-- Currency Icon -->
    <div style="display:flex;justify-content:center;margin-bottom:24px;">
        <div style="width:56px;height:56px;background:#10b981;color:#ffffff;border-radius:12px;display:grid;place-items:center;font-size:28px;font-weight:700;">¥</div>
    </div>

    <!-- Header -->
    <div class="form-title" style="font-size:28px;margin-bottom:8px;">Welcome Back</div>
    <div class="form-subtitle" style="margin-bottom:28px;">Sign in to your PF Trackers account</div>

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

    <?php if(session('success')): ?>
        <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:8px;padding:12px;margin-bottom:16px;color:#10b981;font-size:14px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        
        <!-- Email Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:14px;font-weight:500;margin-bottom:8px;">Email Address</label>
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
            <label class="form-label" style="font-size:14px;font-weight:500;margin-bottom:8px;">Password</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 15V17M6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V13C20 12.4696 19.7893 11.9609 19.4142 11.5858C19.0391 11.2107 18.5304 11 18 11H6C5.46957 11 4.96086 11.2107 4.58579 11.5858C4.21071 11.9609 4 12.4696 4 13V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21ZM16 11V7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7V11H16Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon pad-right-icon" id="login-password" name="password" type="password" placeholder="Enter your password" required style="padding:13px 44px 13px 44px;" />
                <span class="input-icon-right" onclick="togglePassword()" style="cursor:pointer;display:flex;align-items:center;">
                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Remember & Forgot -->
        <div class="form-actions" style="margin-bottom:24px;font-size:14px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="remember" style="width:18px;height:18px;cursor:pointer;accent-color:#10b981;"> 
                <span style="color:#94a3b8;">Remember me</span>
            </label>
            <a href="#" class="help-link" style="font-size:14px;">Forgot password?</a>
        </div>

        <!-- Submit Button -->
        <div class="form-group" style="margin-bottom:20px;">
            <button class="btn btn-primary" type="submit" style="padding:14px;font-size:15px;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 16L15 12M15 12L11 8M15 12H3M8 20H19C20.1046 20 21 19.1046 21 18V6C21 4.89543 20.1046 4 19 4H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Sign In
            </button>
        </div>
    </form>

    <!-- Sign Up Link -->
    <div style="text-align:center;font-size:14px;" class="muted">
        Don't have an account? <a class="help-link" href="<?php echo e(route('register')); ?>" style="font-weight:600;">Sign up</a>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('login-password');
    const icon = document.getElementById('eye-icon');
    
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/login.blade.php ENDPATH**/ ?>
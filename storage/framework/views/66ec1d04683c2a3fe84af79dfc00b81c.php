

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>">

<div class="profile-container">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-avatar">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="profile-name"><?php echo e(Auth::user()->name); ?></h2>
        <p class="profile-email"><?php echo e(Auth::user()->email); ?></p>
        <div class="profile-member-since">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Member since <?php echo e(Auth::user()->created_at->format('F Y')); ?>

        </div>
    </div>

    <!-- Account Summary -->
    <div class="summary-card">
        <h3 class="summary-title">Account Summary</h3>
        
        <div class="summary-row">
            <span class="summary-label">Total Transactions</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Budgets Created</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Goals Achieved</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Data Export</span>
            <a href="#" class="summary-link">Download</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/profile.blade.php ENDPATH**/ ?>
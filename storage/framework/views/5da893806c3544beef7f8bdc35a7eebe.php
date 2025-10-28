

<?php $__env->startSection('content'); ?>
<h1 style="font-size:28px;font-weight:700;" class="page-header">Savings Account</h1>

<section class="page-grid-3">
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Balance</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Available for withdrawal</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Deposits</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">All time deposits</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Withdrawals</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">All time withdrawals</div>
	</div>
</section>

<div style="display:flex;gap:10px;margin: 12px 0 16px;">
	<button class="card" style="padding:10px 12px;background:#059669;border-color:#059669;display:flex;align-items:center;gap:6px;cursor:pointer;">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
		</svg>
		Deposit Money
	</button>
	<button class="card" style="padding:10px 12px;background:#334155;border-color:#334155;display:flex;align-items:center;gap:6px;cursor:pointer;">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
		</svg>
		Withdraw Money
	</button>
 </div>

<section class="card" style="padding:24px;text-align:center;" class="stack-section">
	<div class="muted">No savings transactions yet<br>Start saving by making your first deposit!</div>
 </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/savings.blade.php ENDPATH**/ ?>
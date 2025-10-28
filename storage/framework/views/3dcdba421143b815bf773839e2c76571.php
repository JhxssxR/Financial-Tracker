

<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Budget Management</h1>
	<div style="margin-left:auto;">
		<button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;"> + Create Budget</button>
	</div>
</div>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Budgeted</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">This month</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Spent</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">0.0% of budget</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Remaining</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency(0)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">100.0% remaining</div>
	</div>
</section>

<section class="stack-section">
	<div class="muted" style="font-weight:700; margin-bottom:8px;">Budget Categories</div>
	<div class="card" style="padding:24px;text-align:center;" class="muted">No categories yet</div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/budgets.blade.php ENDPATH**/ ?>
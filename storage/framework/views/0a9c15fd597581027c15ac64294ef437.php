

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('head'); ?>
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="expires" content="0" />
<style>
	/* Reports page responsive tweaks */
	.reports-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
	.chart-wrap { width:100%; }
	.chart-wrap canvas { width:100% !important; height:auto !important; max-width:100%; }
	@media (max-width: 900px) {
		.reports-stats { grid-template-columns:1fr !important; gap:12px; }
	}
	@media (max-width: 640px) {
		.page-grid-2-1 { grid-template-columns:1fr !important; }
		.page-header { flex-direction:column; align-items:flex-start; gap:8px; }
		.page-header .card, .page-header a { margin-left:0; }
		/* Make the filter select and export button smaller and touch-friendly on phones */
		.page-header select.card, .page-header a.card {
			padding:8px 10px !important;
			font-size:14px !important;
			min-width:unset !important;
			border-radius:10px !important;
		}
		.page-header a.card { display:inline-flex; align-items:center; gap:8px; }
		.page-header select.card { width:auto; }
	}
</style>
<?php $__env->stopPush(); ?>
<!-- reports-cb: <?php echo e($cb ?? now()->timestamp); ?> -->

<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Financial Reports</h1>
	<div style="margin-left:auto;display:flex;gap:8px;">
		<select class="card" style="background:#0b1220;border:1px solid #334155;border-radius:8px;padding:8px 12px;color:#e2e8f0;">
			<option>Last 6 Months</option>
			<option>This Year</option>
			<option>Last Year</option>
		</select>
		<a href="<?php echo e(route('reports.export')); ?>" class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:none;color:white;transition:all .2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#0e7d57'">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5m0 0l5-5m-5 5V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			Export Report
		</a>
	</div>
</div>

<section class="reports-stats">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Transactions</div>
		<div id="reportTotalTransactions" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e($totalTransactions ?? 0); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Income</div>
		<div id="reportTotalIncome" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalIncome ?? 0)); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Savings</div>
		<div id="reportTotalSavings" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalSavings ?? 0)); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Expenses</div>
		<div id="reportTotalExpenses" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalExpenses ?? 0)); ?></div>
	</div>
</section>

<section class="page-grid-2-1 stack-section">
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Income vs Expenses Trend</div>
		<div class="chart-wrap" style="min-height:140px;">
			<canvas id="reportsTrend" aria-label="Income vs Expenses Trend"></canvas>
		</div>
	</div>
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Expense Categories</div>
		<?php if(isset($expenseCategoryData) && count($expenseCategoryData['labels']) > 0): ?>
			<div class="chart-wrap" style="min-height:200px;">
				<canvas id="expenseDonut" aria-label="Expense Categories"></canvas>
			</div>
		<?php else: ?>
			<div class="muted" style="text-align:center;margin-top:60px;padding:40px 20px;">No expense data available</div>
		<?php endif; ?>
	</div>
</section>



<?php $__env->startPush('scripts'); ?>
	<script>
		window.__REPORTS_DATA = {
			monthlyData: <?php echo json_encode($monthlyData ?? ['months' => [], 'income' => [], 'expenses' => []]) ?>,
			expenseCategoryData: <?php echo json_encode($expenseCategoryData ?? ['labels' => [], 'data' => [], 'colors' => []]) ?>,
			currencyCode: '<?php echo e($currencyCode ?? "USD"); ?>'
		};
	</script>
	<script src="<?php echo e(asset('js/reports.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/reports.blade.php ENDPATH**/ ?>
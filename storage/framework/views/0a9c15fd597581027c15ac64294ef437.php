

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('head'); ?>
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="expires" content="0" />
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
		<button class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;cursor:pointer;display:flex;align-items:center;gap:6px;">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5m0 0l5-5m-5 5V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			Export Report
		</button>
	</div>
</div>

<section style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Transactions</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e($totalTransactions ?? 0); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Income</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalIncome ?? 0)); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Savings</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalSavings ?? 0)); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;font-size:14px;">Total Expenses</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalExpenses ?? 0)); ?></div>
	</div>
</section>

<section class="page-grid-2-1 stack-section">
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Income vs Expenses Trend</div>
		<canvas id="reportsTrend" height="140"></canvas>
	</div>
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Expense Categories</div>
		<div class="muted" style="text-align:center;margin-top:8px;">No expense data available</div>
		<canvas id="expensePie" height="140" style="display:none;"></canvas>
	</div>
</section>



<?php $__env->startPush('scripts'); ?>
<script>
	const reportsTrend = document.getElementById('reportsTrend');
	if (reportsTrend && window.Chart) {
		const monthlyData = <?php echo json_encode($monthlyData ?? ['months' => [], 'income' => [], 'expenses' => []]) ?>;
		
		new Chart(reportsTrend, {
			type: 'line',
			data: {
				labels: monthlyData.months,
				datasets: [
					{ label: 'Income', data: monthlyData.income, borderColor: '#34d399', tension: 0.3 },
					{ label: 'Expenses', data: monthlyData.expenses, borderColor: '#f87171', tension: 0.3 },
				]
			},
			options: {
				plugins: { legend: { labels: { color: '#cbd5e1' } } },
				scales: {
					x: { ticks: { color: '#94a3b8' }, grid: { color: '#243043' } },
					y: { ticks: { color: '#94a3b8' }, grid: { color: '#243043' } },
				}
			}
		});
	}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/reports.blade.php ENDPATH**/ ?>
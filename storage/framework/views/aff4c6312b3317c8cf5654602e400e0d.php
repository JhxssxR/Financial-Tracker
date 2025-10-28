

<?php $__env->startSection('content'); ?>
<h1 style="font-size:28px;font-weight:700;" class="page-header">Dashboard</h1>

<section class="page-grid-3">
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Total Savings</div>
        <div style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;"><?php echo e(format_currency($totalSavings)); ?></div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format($savingsPercentage, 1)); ?>% of income</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Income</div>
        <div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalIncome)); ?></div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">This month</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Expenses</div>
        <div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalExpenses)); ?></div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format($expensePercentage, 1)); ?>% of income</div>
    </div>
</section>

<section class="card card-pad-lg stack-section">
    <div style="font-weight:700;margin-bottom:10px;">Income vs. Expenses</div>
    <canvas id="trendChart" height="120"></canvas>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #334155;font-weight:600;">Recent Transactions</div>
    <div style="padding:12px 18px;">
        <table style="width:100%;border-collapse:collapse;">
            <thead class="muted" style="font-size:12px;text-transform:uppercase;">
                <tr>
                    <th style="text-align:left;padding:10px 8px;">Date</th>
                    <th style="text-align:left;padding:10px 8px;">Description</th>
                    <th style="text-align:left;padding:10px 8px;">Category</th>
                    <th style="text-align:right;padding:10px 8px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #1e293b;">
                    <td style="padding:14px 8px;color:#cbd5e1;"><?php echo e($transaction->transaction_date->format('M d, Y')); ?></td>
                    <td style="padding:14px 8px;"><?php echo e($transaction->description); ?></td>
                    <td style="padding:14px 8px;">
                        <span style="background:#1e293b;padding:4px 10px;border-radius:6px;font-size:12px;">
                            <?php echo e($transaction->category->name ?? 'Uncategorized'); ?>

                        </span>
                    </td>
                    <td style="padding:14px 8px;text-align:right;font-weight:600;color:<?php echo e($transaction->type === 'income' ? '#10b981' : '#f87171'); ?>;">
                        <?php echo e($transaction->type === 'income' ? '+' : '-'); ?><?php echo e(format_currency($transaction->amount)); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align:center;padding:32px;" class="muted">No transactions found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
 </section>

<?php $__env->startPush('scripts'); ?>
<script>
    const ctx = document.getElementById('trendChart');
    if (ctx && window.Chart) {
        const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;
        
        new Chart(ctx, {
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/dashboard.blade.php ENDPATH**/ ?>
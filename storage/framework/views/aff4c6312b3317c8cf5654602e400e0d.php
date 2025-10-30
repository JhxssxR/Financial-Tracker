

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
                        <?php
                            $categoryName = $transaction->category->name ?? 'Uncategorized';
                            $bgColor = '#374151'; // Default dark gray
                            $textColor = '#9ca3af'; // Muted gray text
                            
                            // Income categories - colored
                            if ($categoryName === 'Salary') {
                                $bgColor = '#10b981';
                                $textColor = '#ffffff';
                            } elseif ($categoryName === 'Investment') {
                                $bgColor = '#8b5cf6';
                                $textColor = '#ffffff';
                            } elseif ($categoryName === 'Freelance') {
                                $bgColor = '#3b82f6';
                                $textColor = '#ffffff';
                            }
                            // Expense categories - colored
                            elseif ($categoryName === 'Education') {
                                $bgColor = '#06b6d4';
                                $textColor = '#ffffff';
                            } elseif ($categoryName === 'Housing') {
                                $bgColor = '#ef4444';
                                $textColor = '#ffffff';
                            } elseif ($categoryName === 'Transportation') {
                                $bgColor = '#f59e0b';
                                $textColor = '#ffffff';
                            }
                        ?>
                        <span style="background:<?php echo e($bgColor); ?>;padding:6px 16px;border-radius:18px;font-size:13px;color:<?php echo e($textColor); ?>;font-weight:400;">
                            <?php echo e($categoryName); ?>

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
                    { 
                        label: 'Income', 
                        data: monthlyData.income, 
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#10b981',
                        pointHoverRadius: 7
                    },
                    { 
                        label: 'Expenses', 
                        data: monthlyData.expenses, 
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#ef4444',
                        pointHoverRadius: 7
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { 
                            color: '#e2e8f0',
                            font: { size: 14, weight: '500' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'rect'
                        } 
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#e2e8f0',
                        bodyColor: '#e2e8f0',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#94a3b8', font: { size: 12 } }, 
                        grid: { color: '#2d3748', drawBorder: false }
                    },
                    y: { 
                        ticks: { color: '#94a3b8', font: { size: 12 } }, 
                        grid: { color: '#2d3748', drawBorder: false },
                        beginAtZero: true
                    },
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/dashboard.blade.php ENDPATH**/ ?>
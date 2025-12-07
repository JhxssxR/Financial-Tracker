

<?php $__env->startSection('content'); ?>
<h1 style="font-size:28px;font-weight:700;" class="page-header">Dashboard</h1>

<section class="page-grid-3">
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Total Savings</div>
        <div id="savingsTotal" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;"><?php echo e(format_currency($totalSavings)); ?></div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format($savingsPercentage, 1)); ?>% of income</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Income</div>
        <div id="incomeTotal" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalIncome)); ?></div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Year 2025</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Expenses</div>
        <div id="expensesTotal" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalExpenses)); ?></div>
        <div id="expensesMeta" class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format($expensePercentage, 1)); ?>% of income</div>
    </div>
</section>

<section class="card card-pad-lg stack-section">
    <div style="font-weight:700;margin-bottom:10px;">Income vs. Expenses</div>
    <div class="chart-wrap" style="min-height:320px;">
        <canvas id="trendChart" aria-label="Income vs. Expenses" style="width:100%;height:320px;max-width:100%;"></canvas>
    </div>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #334155;">
        
        <div id="dashboard-notif-area" style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <?php if(isset($unreadCount) && $unreadCount > 0 && isset($latestUnread) && $latestUnread): ?>
                    <?php
                        // Choose colors based on title or type for budget warnings
                        $nTitle = $latestUnread->title ?? 'Notification';
                        $nMessage = $latestUnread->message ?? '';
                        $nTime = $latestUnread->created_at ? $latestUnread->created_at->diffForHumans() : '';
                        $badgeBg = '#ef4444'; // default red for important
                        $badgeText = 'Over Budget';
                        if (stripos($nTitle, 'Near') !== false || stripos($nTitle, 'Warning') !== false || stripos($nTitle, 'Limit') !== false) {
                            $badgeBg = '#f59e0b';
                            $badgeText = 'Near Limit';
                        } elseif (stripos($nTitle, 'Exceeded') !== false || stripos($nTitle, 'Over') !== false) {
                            $badgeBg = '#ef4444';
                            $badgeText = 'Over Budget';
                        } else {
                            $badgeBg = '#334155';
                            $badgeText = ucfirst($latestUnread->type ?? 'info');
                        }
                    ?>

                    <div style="display:flex;gap:12px;align-items:flex-start;">
                        <div style="width:44px;height:44px;border-radius:10px;background:#0f172a;display:flex;align-items:center;justify-content:center;border:1px solid #334155;">
                            <?php if(stripos($nTitle, 'withdraw') !== false || stripos($nTitle, 'withdrawal') !== false): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8v8m0 0l-3-3m3 3l3-3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php elseif(stripos($nTitle, 'deposit') !== false || stripos($nTitle, 'saving') !== false): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 16V8m0 0l-3 3m3-3l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php elseif(stripos($nTitle, 'exceeded') !== false || stripos($nTitle, 'over') !== false): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php elseif(stripos($nTitle, 'near') !== false || stripos($nTitle, 'warning') !== false): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php else: ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="font-weight:700;color:#e2e8f0;font-size:16px;"><?php echo e($nTitle); ?></div>
                                <div style="background:<?php echo e($badgeBg); ?>;color:#fff;padding:4px 8px;border-radius:8px;font-size:12px;font-weight:700;"><?php echo e($badgeText); ?></div>
                                <div style="color:#94a3b8;font-size:13px;margin-left:6px;"><?php echo e($nTime); ?></div>
                            </div>
                            <div style="color:#b8c2ce;margin-top:6px;max-width:820px;"><?php echo e($nMessage); ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="font-weight:700;font-size:16px;color:#e2e8f0;">Recent Transactions</div>
                    <div style="color:#94a3b8;margin-left:8px;"><?php if(isset($unreadCount) && $unreadCount > 0): ?> You have <?php echo e($unreadCount); ?> unread notification<?php echo e($unreadCount > 1 ? 's' : ''); ?> <?php endif; ?></div>
                <?php endif; ?>
            </div>
            <div style="text-align:right;">
                <?php if(isset($unreadCount) && $unreadCount > 0): ?>
                    <a href="<?php echo e(route('notifications.index')); ?>" style="background:#ef4444;color:white;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">View (<?php echo e($unreadCount); ?>)</a>
                <?php else: ?>
                    <div style="color:#94a3b8;font-weight:600;">All caught up!</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
    // Provide chart data to external script
    window.__DASHBOARD_DATA = <?php echo json_encode($monthlyData, 15, 512) ?>;
</script>
<script src="<?php echo e(asset('js/dashboard.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/dashboard.blade.php ENDPATH**/ ?>
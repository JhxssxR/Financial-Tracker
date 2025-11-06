<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1a202c;
            line-height: 1.4;
        }
        
        .container {
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #10b981;
        }
        
        .header h1 {
            font-size: 20px;
            color: #10b981;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header p {
            font-size: 9px;
            color: #64748b;
        }
        
        .user-info {
            background: #f8fafc;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 3px solid #10b981;
        }
        
        .user-info strong {
            color: #1e293b;
            font-size: 10px;
        }
        
        .user-info span {
            color: #64748b;
            font-size: 9px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-spacing: 8px 0;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-card {
            display: table-cell;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            width: 33.33%;
        }
        
        .summary-card .label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .summary-card .value {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }
        
        .summary-card.income .value {
            color: #10b981;
        }
        
        .summary-card.expense .value {
            color: #ef4444;
        }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        
        .table th {
            background: #f1f5f9;
            color: #475569;
            font-size: 8px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 2px solid #cbd5e1;
            font-weight: 600;
        }
        
        .table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .category-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 500;
            background: #e2e8f0;
            color: #475569;
        }
        
        .amount-income {
            color: #10b981;
            font-weight: 600;
        }
        
        .amount-expense {
            color: #ef4444;
            font-weight: 600;
        }
        
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .type-badge.income {
            background: #d1fae5;
            color: #065f46;
        }
        
        .type-badge.expense {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            position: fixed;
            bottom: 10px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 9px;
            font-style: italic;
        }
        
        .stats-box {
            background: #f8fafc;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #3b82f6;
        }
        
        .stats-box .stat-label {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 2px;
        }
        
        .stats-box .stat-value {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💳 TRANSACTIONS REPORT</h1>
            <p>Complete Transaction History</p>
        </div>
        
        <!-- User Info -->
        <div class="user-info">
            <strong><?php echo e($user->name); ?></strong> <span>(<?php echo e($user->email); ?>)</span><br>
            <span>Report Generated: <?php echo e($generatedDate); ?></span>
        </div>
        
        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-card">
                    <div class="label">Total Transactions</div>
                    <div class="value"><?php echo e($totalTransactions); ?></div>
                </div>
                <div class="summary-card income">
                    <div class="label">Total Income</div>
                    <div class="value"><?php echo e(format_currency($totalIncome)); ?></div>
                </div>
                <div class="summary-card expense">
                    <div class="label">Total Expenses</div>
                    <div class="value"><?php echo e(format_currency($totalExpenses)); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="stats-box">
            <div class="stat-label">NET BALANCE</div>
            <div class="stat-value" style="color: <?php echo e(($totalIncome - $totalExpenses) >= 0 ? '#10b981' : '#ef4444'); ?>">
                <?php echo e(format_currency($totalIncome - $totalExpenses)); ?>

            </div>
        </div>
        
        <!-- Income Transactions -->
        <?php if($incomeTransactions->count() > 0): ?>
        <div class="section">
            <div class="section-title">
                💰 Income Transactions (<?php echo e($incomeTransactions->count()); ?>)
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $incomeTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($transaction->transaction_date->format('M d, Y')); ?></td>
                        <td><?php echo e($transaction->description); ?></td>
                        <td>
                            <span class="category-badge"><?php echo e($transaction->category->name ?? 'Uncategorized'); ?></span>
                        </td>
                        <td style="text-align: right;" class="amount-income">
                            +<?php echo e(format_currency($transaction->amount)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Expense Transactions -->
        <?php if($expenseTransactions->count() > 0): ?>
        <div class="section">
            <div class="section-title">
                💸 Expense Transactions (<?php echo e($expenseTransactions->count()); ?>)
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $expenseTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($transaction->transaction_date->format('M d, Y')); ?></td>
                        <td><?php echo e($transaction->description); ?></td>
                        <td>
                            <span class="category-badge"><?php echo e($transaction->category->name ?? 'Uncategorized'); ?></span>
                        </td>
                        <td style="text-align: right;" class="amount-expense">
                            -<?php echo e(format_currency($transaction->amount)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- All Transactions -->
        <?php if($transactions->count() > 0): ?>
        <div class="section">
            <div class="section-title">
                📋 All Transactions (Chronological)
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($transaction->transaction_date->format('M d, Y')); ?></td>
                        <td>
                            <span class="type-badge <?php echo e($transaction->type); ?>"><?php echo e($transaction->type); ?></span>
                        </td>
                        <td><?php echo e($transaction->description); ?></td>
                        <td>
                            <span class="category-badge"><?php echo e($transaction->category->name ?? 'Uncategorized'); ?></span>
                        </td>
                        <td style="text-align: right;" class="<?php echo e($transaction->type === 'income' ? 'amount-income' : 'amount-expense'); ?>">
                            <?php echo e($transaction->type === 'income' ? '+' : '-'); ?><?php echo e(format_currency($transaction->amount)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">No transactions found</div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>PF Trackers - Personal Finance Tracker | Generated on <?php echo e($generatedDate); ?></p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/transactions-pdf.blade.php ENDPATH**/ ?>
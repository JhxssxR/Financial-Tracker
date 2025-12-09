<?php $__env->startSection('content'); ?>
<style>
	/* Transactions page mobile responsive styles */
	@media (max-width: 768px) {
		.page-header { flex-wrap:wrap; gap:10px !important; }
		.page-header h1 { font-size:22px !important; flex:1 1 100%; }
		.page-header > div { margin-left:0 !important; width:100%; flex-direction:column; }
		.page-header a, .page-header button { width:100%; justify-content:center; }
		
		.filter-section-header { flex-direction:column; align-items:flex-start !important; gap:8px !important; }
		.filter-section-header span { font-size:14px !important; }
		
		.filters-container { flex-direction:column !important; gap:10px !important; align-items:stretch !important; }
		.filters-container > div { width:100% !important; max-width:100% !important; min-width:0 !important; flex:none !important; }
		.filters-container > select { width:100% !important; max-width:100% !important; min-width:0 !important; }
		.filters-container > button { width:100% !important; margin:0 !important; }
		.filters-container input { font-size:14px !important; }
		
		.transactions-table { display:block; overflow-x:auto; -webkit-overflow-scrolling:touch; }
		.transactions-table table { min-width:600px; }
		.transactions-table th, .transactions-table td { font-size:12px !important; padding:10px 6px !important; }
		.transactions-table td:first-child { white-space:nowrap; }
		
		.summary-cards .card > div:first-child { font-size:13px !important; }
		.summary-cards .card > div:nth-child(2) { font-size:22px !important; }
		
		.pagination-container { flex-wrap:wrap; padding:16px 0 !important; }
		.pagination-numbers { order:3; width:100%; justify-content:center; margin-top:12px; }
	}
	
	@media (max-width: 480px) {
		.page-header h1 { font-size:20px !important; }
		.page-header a span, .page-header button { font-size:14px !important; }
		
		.transactions-table th:nth-child(3), .transactions-table td:nth-child(3) { display:none; }
		.transactions-table td:nth-child(2) { max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
		.transactions-table td:nth-child(5) button { padding:6px 8px !important; }
		.transactions-table td:nth-child(5) svg { width:14px !important; height:14px !important; }
		
		.summary-cards .card { padding:12px !important; }
		.summary-cards .card > div:nth-child(2) { font-size:20px !important; }
	}
</style>

<div class="page-header" style="display:flex;align-items:center;gap:12px;">
    <h1 style="font-size:28px;font-weight:700;">Transactions</h1>
    <div style="margin-left:auto;display:flex;gap:8px;">
        <a href="<?php echo e(route('transactions.export')); ?>" class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:none;color:white;transition:all .2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#0e7d57'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5m0 0l5-5m-5 5V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Export
        </a>
        <button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;cursor:pointer;transition:all .2s;font-weight:600;" onclick="openTransactionModal()" onmouseover="this.style.background='#10b981'" onmouseout="this.style.background='#059669'">+ Add Transaction</button>
    </div>
 </div>
 
<section class="page-grid-3 summary-cards">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Transactions</div>
		<div id="totalTransactions" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;"><?php echo e($totalTransactions); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Income</div>
		<div id="totalIncome" class="brand" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;"><?php echo e(format_currency($totalIncome)); ?></div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Expenses</div>
		<div id="totalExpenses" class="danger" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;"><?php echo e(format_currency($totalExpenses)); ?></div>
	</div>
</section>

<section class="card card-pad stack-section">
	<div class="filter-section-header" style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V6.58579C21 6.851 20.8946 7.10536 20.7071 7.29289L14.2929 13.7071C14.1054 13.8946 14 14.149 14 14.4142V17L10 21V14.4142C10 14.149 9.89464 13.8946 9.70711 13.7071L3.29289 7.29289C3.10536 7.10536 3 6.851 3 6.58579V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<span style="font-weight:600;font-size:16px;">Filters & Search</span>
	</div>

	<div class="filters-container" style="display:flex;align-items:center;gap:12px;">
        <div style="position:relative;flex:1;max-width:350px;">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#64748b;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <input id="searchInput" onkeyup="filterTransactions()" placeholder="Search transactions..." style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px 10px 38px;width:100%;color:#e2e8f0;font-size:15px;">
        </div>
        <select id="typeFilter" onchange="filterTransactions()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;min-width:260px;max-width:320px;font-size:15px;">
            <option value="">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <select id="categoryFilter" onchange="filterTransactions()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;min-width:260px;max-width:320px;font-size:15px;">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $categories->flatten()->unique('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    <button onclick="clearFilters()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;cursor:pointer;font-size:15px;white-space:nowrap;font-weight:500;">Clear Filters</button>
	</div>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
	<div style="padding:16px 18px;border-bottom:1px solid #334155;font-weight:600;">Transaction History</div>
	<div class="transactions-table" style="padding:12px 18px;">
		<table style="width:100%;border-collapse:collapse;">
			<thead class="muted" style="font-size:12px;text-transform:uppercase;">
				<tr>
					<th style="text-align:left;padding:10px 8px;">Date</th>
					<th style="text-align:left;padding:10px 8px;">Description</th>
					<th style="text-align:left;padding:10px 8px;">Category</th>
					<th style="text-align:right;padding:10px 8px;">Amount</th>
					<th style="text-align:center;padding:10px 8px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<tr style="border-bottom:1px solid #1e293b;" data-category-id="<?php echo e($transaction->category_id); ?>" data-type="<?php echo e($transaction->type); ?>">
					<td style="padding:14px 8px;color:#cbd5e1;"><?php echo e($transaction->transaction_date->format('M d, Y')); ?></td>
					<td style="padding:14px 8px;" class="transaction-description"><?php echo e($transaction->description); ?></td>
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
					<td style="padding:14px 8px;text-align:center;">
						<div style="display:flex;gap:8px;justify-content:center;align-items:center;">
							<button onclick="editTransaction(<?php echo e($transaction->id); ?>)" style="background:#1e293b;border:1px solid #334155;border-radius:6px;padding:8px 12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'" title="Edit">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
							<button onclick="deleteTransaction(<?php echo e($transaction->id); ?>)" style="background:#1e293b;border:1px solid #334155;border-radius:6px;padding:8px 12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'" title="Delete">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M10 11v6M14 11v6" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</div>
					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
				<tr>
					<td colspan="5" style="text-align:center;padding:32px;" class="muted">No transactions found</td>
				</tr>
				<?php endif; ?>
			</tbody>
        </table>
    </div>

	<!-- Pagination -->
	<?php if($transactions->hasPages()): ?>
		<div class="pagination-container" style="display:flex; justify-content:center; align-items:center; gap:8px; padding:24px 0;">
			<?php if($transactions->onFirstPage()): ?>
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Previous
				</button>
			<?php else: ?>
				<a href="<?php echo e($transactions->previousPageUrl()); ?>" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Previous
				</a>
			<?php endif; ?>

			<div class="pagination-numbers" style="display:flex; gap:4px;">
				<?php $__currentLoopData = range(1, $transactions->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php if($page == $transactions->currentPage()): ?>
						<span style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#10b981; border-radius:8px; color:white; font-weight:600; font-size:14px;">
							<?php echo e($page); ?>

						</span>
					<?php else: ?>
						<a href="<?php echo e($transactions->url($page)); ?>" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
							<?php echo e($page); ?>

						</a>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>

			<?php if($transactions->hasMorePages()): ?>
				<a href="<?php echo e($transactions->nextPageUrl()); ?>" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Next
				</a>
			<?php else: ?>
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Next
				</button>
			<?php endif; ?>
		</div>

		<div style="text-align:center; color:#64748b; font-size:13px; padding-bottom:16px;">
			Showing <?php echo e($transactions->firstItem()); ?> to <?php echo e($transactions->lastItem()); ?> of <?php echo e($transactions->total()); ?> transactions
		</div>
	<?php endif; ?>
</section>

<!-- Add Transaction Modal -->
<div id="transactionModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <svg style="color:#10b981;" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" stroke="currentColor" stroke-width="2"/>
                <path d="M8.5 14.5c0 1.105.895 2 2 2h2c1.105 0 2-.895 2-2s-.895-2-2-2h-1c-1.105 0-2-.895-2-2s.895-2 2-2h2c1.105 0 2 .895 2 2M12 6v2m0 8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <h2 id="modalTitle">Add Transaction</h2>
            <button class="modal-close" onclick="closeTransactionModal()" type="button">✕</button>
        </div>
        <div class="modal-body">
            <form id="transactionForm" method="POST" action="<?php echo e(route('transactions.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="transaction_id" id="transactionId" value="">
                <input type="hidden" name="type" id="transactionType" value="income">
                
                <div class="form-group">
                    <label class="form-label" style="color:#cbd5e1;">Transaction Type</label>
                    <div class="type-toggle">
                        <div class="type-btn active" data-type="income" onclick="selectType('income')">
                            <svg class="type-btn-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="type-btn-text">Income</div>
                        </div>
                        <div class="type-btn" data-type="expense" onclick="selectType('expense')">
                            <svg class="type-btn-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7L7 17M7 17H15M7 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="type-btn-text">Expense</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Description</span>
                    </label>
                    <input class="form-input" id="descriptionInput" name="description" type="text" placeholder="e.g., Monthly Salary" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span>Amount</span>
                    </label>
                    <input class="form-input" id="amountInput" name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Category</span>
                    </label>
                    <select class="form-input" id="categorySelect" name="category_id" style="cursor:pointer;" required>
                        <option value="">Select a category...</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Date</span>
                    </label>
                    <input class="form-input" id="dateInput" name="transaction_date" type="date" value="<?php echo e(date('Y-m-d')); ?>" required />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeTransactionModal()" type="button">Cancel</button>
            <button class="btn btn-primary" id="submitBtn" onclick="submitTransaction()" type="button" style="width:auto;min-width:150px;padding:11px 20px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;">
                <svg id="submitBtnIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span id="submitBtnText">Add Income</span>
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Pass server data to external script
    window.__TRANSACTIONS_DATA = {
        categories: <?php echo json_encode($categories, 15, 512) ?>
    };
    
    // Debug: Log categories data
    console.log('Categories data loaded:', window.__TRANSACTIONS_DATA.categories);
</script>
<script src="<?php echo e(asset('js/transactions.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Financial-Tracker\resources\views/transactions.blade.php ENDPATH**/ ?>
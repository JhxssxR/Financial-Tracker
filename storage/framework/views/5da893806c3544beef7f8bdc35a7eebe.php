

<?php $__env->startSection('content'); ?>
<h1 style="font-size:28px;font-weight:700;" class="page-header">Savings Account</h1>

<section class="page-grid-3">
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Balance</div>
		<div id="totalBalance" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalBalance)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Available for withdrawal</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Deposits</div>
		<div id="totalDeposits" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalDeposits)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">All time deposits</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Withdrawals</div>
		<div id="totalWithdrawals" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalWithdrawals)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">All time withdrawals</div>
	</div>
</section>

<div style="display:flex;gap:10px;margin:24px 0;">
	<button onclick="openDepositModal()" class="card" style="padding:10px 12px;background:#059669;border-color:#059669;display:flex;align-items:center;gap:6px;cursor:pointer;">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12 19V5M5 12l7 7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		Deposit Money
	</button>
	<button id="withdrawBtn" onclick="openWithdrawModal()" class="card" style="padding:10px 12px;background:#334155;border-color:#334155;display:flex;align-items:center;gap:6px;cursor:<?php echo e($totalBalance <= 0 ? 'not-allowed' : 'pointer'); ?>;opacity:<?php echo e($totalBalance <= 0 ? '0.5' : '1'); ?>;" <?php if($totalBalance <= 0): ?> disabled <?php endif; ?>>
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12 5v14M5 12l7-7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		Withdraw Money
	</button>
</div>

<section class="card stack-section" style="padding:0;overflow:hidden;">
	<div style="padding:16px 18px;border-bottom:1px solid #334155;font-weight:600;">Transaction History</div>
	<div style="padding:12px 18px;">
		<table style="width:100%;border-collapse:collapse;">
			<thead class="muted" style="font-size:12px;text-transform:uppercase;">
				<tr>
					<th style="text-align:left;padding:10px 8px;">Date</th>
					<th style="text-align:left;padding:10px 8px;">Type</th>
					<th style="text-align:left;padding:10px 8px;">Description</th>
					<th style="text-align:right;padding:10px 8px;">Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<tr style="border-bottom:1px solid #1e293b;">
					<td style="padding:14px 8px;color:#cbd5e1;"><?php echo e($transaction->created_at->format('M d, Y')); ?></td>
					<td style="padding:14px 8px;">
						<span style="background:<?php echo e($transaction->type === 'deposit' ? '#059669' : '#ef4444'); ?>;padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;">
							<?php echo e(ucfirst($transaction->type)); ?>

						</span>
					</td>
					<td style="padding:14px 8px;color:#cbd5e1;"><?php echo e($transaction->description ?? '-'); ?></td>
					<td style="padding:14px 8px;text-align:right;font-weight:600;color:<?php echo e($transaction->type === 'deposit' ? '#10b981' : '#f87171'); ?>;">
						<?php echo e($transaction->type === 'deposit' ? '+' : '-'); ?><?php echo e(format_currency($transaction->amount)); ?>

					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
				<tr>
					<td colspan="4" style="text-align:center;padding:32px;" class="muted">No savings transactions yet<br>Start saving by making your first deposit!</td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</section>

<!-- Deposit Modal -->
<div id="depositModal" class="modal-overlay">
	<div class="modal-content">
		<div class="modal-header">
			<h2>Deposit Money</h2>
			<button class="modal-close" onclick="closeDepositModal()">✕</button>
		</div>
		<div class="modal-body">
			<p class="muted" style="margin-bottom:20px;">Add money to your savings account</p>
			<form id="depositForm" method="POST" action="<?php echo e(route('savings.deposit')); ?>">
				<?php echo csrf_field(); ?>
				<div class="form-group">
					<label class="form-label">Amount</label>
					<input class="form-input" name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required />
				</div>
				<div class="form-group" style="margin-bottom:0;">
					<label class="form-label">Description (Optional)</label>
					<textarea class="form-input" name="description" rows="3" placeholder="e.g., Monthly savings, Bonus deposit..." style="resize:vertical;"></textarea>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-cancel" onclick="closeDepositModal()">Cancel</button>
			<button class="btn btn-primary" onclick="submitDeposit()" style="background:#059669;border-color:#059669;width:auto;min-width:120px;padding:10px 20px;">Deposit</button>
		</div>
	</div>
</div>

<!-- Withdraw Modal -->
<div id="withdrawModal" class="modal-overlay">
	<div class="modal-content">
		<div class="modal-header">
			<h2>Withdraw Money</h2>
			<button class="modal-close" onclick="closeWithdrawModal()">✕</button>
		</div>
		<div class="modal-body">
			<p class="muted" style="margin-bottom:20px;">Withdraw money from your savings account</p>
			<div style="background:#1e293b;padding:16px;border-radius:8px;margin-bottom:20px;">
				<div class="muted" style="font-size:14px;margin-bottom:4px;">Available Balance</div>
				<div style="font-size:24px;font-weight:700;"><?php echo e(format_currency($totalBalance)); ?></div>
			</div>
			<form id="withdrawForm" method="POST" action="<?php echo e(route('savings.withdraw')); ?>">
				<?php echo csrf_field(); ?>
				<div class="form-group">
					<label class="form-label">Amount</label>
					<input id="withdrawAmount" class="form-input" name="amount" type="number" step="0.01" min="0.01" max="<?php echo e($totalBalance); ?>" placeholder="0.00" oninput="validateWithdrawAmount()" required />
					<div id="withdrawError" style="color:#ef4444;font-size:14px;margin-top:8px;display:none;">
						<svg style="display:inline;vertical-align:middle;" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
							<path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
						<span style="vertical-align:middle;margin-left:4px;">Amount exceeds available balance</span>
					</div>
				</div>
				<div class="form-group" style="margin-bottom:0;">
					<label class="form-label">Description (Optional)</label>
					<textarea class="form-input" name="description" rows="3" placeholder="e.g., Emergency expense, Investment..." style="resize:vertical;"></textarea>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-cancel" onclick="closeWithdrawModal()">Cancel</button>
			<button id="withdrawSubmitBtn" class="btn btn-primary" onclick="submitWithdraw()" style="background:#ef4444;border-color:#ef4444;width:auto;min-width:120px;padding:10px 20px;">Withdraw</button>
		</div>
	</div>
</div>

<script>
	const availableBalance = <?php echo e($totalBalance); ?>;

	function openDepositModal() {
		document.getElementById('depositModal').classList.add('active');
		document.body.style.overflow = 'hidden';
	}

	function closeDepositModal() {
		document.getElementById('depositModal').classList.remove('active');
		document.body.style.overflow = '';
		document.getElementById('depositForm').reset();
	}

	function openWithdrawModal() {
		document.getElementById('withdrawModal').classList.add('active');
		document.body.style.overflow = 'hidden';
		validateWithdrawAmount(); // Check initial state
	}

	function closeWithdrawModal() {
		document.getElementById('withdrawModal').classList.remove('active');
		document.body.style.overflow = '';
		document.getElementById('withdrawForm').reset();
		document.getElementById('withdrawError').style.display = 'none';
		document.getElementById('withdrawSubmitBtn').disabled = false;
		document.getElementById('withdrawSubmitBtn').style.opacity = '1';
	}

	function submitDeposit() {
		const form = document.getElementById('depositForm');
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		const formData = new FormData(form);
		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				closeDepositModal();
				showToast('Deposit successful!', 'success');
				setTimeout(() => window.location.reload(), 1000);
			} else {
				showToast(data.message || 'Error processing deposit', 'error');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showToast('Error processing deposit. Please try again.', 'error');
		});
	}

	function submitWithdraw() {
		const form = document.getElementById('withdrawForm');
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		const amount = parseFloat(document.getElementById('withdrawAmount').value);
		if (amount > availableBalance) {
			showToast('Amount exceeds available balance', 'error');
			return;
		}

		const formData = new FormData(form);
		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				closeWithdrawModal();
				showToast('Withdrawal successful!', 'success');
				setTimeout(() => window.location.reload(), 1000);
			} else {
				showToast(data.message || 'Error processing withdrawal', 'error');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showToast('Error processing withdrawal. Please try again.', 'error');
		});
	}tyle.textContent = `
		@keyframes slideIn {
			from { transform: translateX(400px); opacity: 0; }
			to { transform: translateX(0); opacity: 1; }
		}
		@keyframes slideOut {
			from { transform: translateX(0); opacity: 1; }
			to { transform: translateX(400px); opacity: 0; }
		}
	`;
	document.head.appendChild(style);

	function submitDeposit() {
		const form = document.getElementById('depositForm');
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		const formData = new FormData(form);
		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				closeDepositModal();
				window.location.reload();
			} else {
				alert(data.message || 'Error processing deposit. Please try again.');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error processing deposit. Please try again.');
		});
	}

	function submitWithdraw() {
		const form = document.getElementById('withdrawForm');
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		const formData = new FormData(form);
		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				closeWithdrawModal();
				window.location.reload();
			} else {
				alert(data.message || 'Error processing withdrawal. Please try again.');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error processing withdrawal. Please try again.');
		});
	}

	// Close modals on overlay click
	document.getElementById('depositModal')?.addEventListener('click', (e) => {
		if (e.target.id === 'depositModal') closeDepositModal();
	});

	document.getElementById('withdrawModal')?.addEventListener('click', (e) => {
		if (e.target.id === 'withdrawModal') closeWithdrawModal();
	});

	// Close modals on Escape key
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (document.getElementById('depositModal').classList.contains('active')) {
				closeDepositModal();
			}
			if (document.getElementById('withdrawModal').classList.contains('active')) {
				closeWithdrawModal();
			}
		}
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/savings.blade.php ENDPATH**/ ?>
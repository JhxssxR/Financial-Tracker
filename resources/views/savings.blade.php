@extends('layouts.app')

@section('content')
<!-- Toast Container -->
<div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;"></div>

<h1 style="font-size:28px;font-weight:700;" class="page-header">Savings Account</h1>

<section class="page-grid-3">
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Balance</div>
		<div id="totalBalance" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalBalance) }}</div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Available for withdrawal</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Deposits</div>
		<div id="totalDeposits" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalDeposits) }}</div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">All time deposits</div>
	</div>
	<div class="card" style="padding:15px;">
		<div class="muted" style="font-weight:600;">Total Withdrawals</div>
		<div id="totalWithdrawals" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalWithdrawals) }}</div>
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
	<button id="withdrawBtn" onclick="openWithdrawModal()" class="card" style="padding:10px 12px;background:#334155;border-color:#334155;display:flex;align-items:center;gap:6px;cursor:{{ $totalBalance <= 0 ? 'not-allowed' : 'pointer' }};opacity:{{ $totalBalance <= 0 ? '0.5' : '1' }};" @if($totalBalance <= 0) disabled @endif>
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
				@forelse($transactions as $transaction)
				<tr style="border-bottom:1px solid #1e293b;">
					<td style="padding:14px 8px;color:#cbd5e1;">{{ $transaction->created_at->format('M d, Y') }}</td>
					<td style="padding:14px 8px;">
						<span style="background:{{ $transaction->type === 'deposit' ? '#059669' : '#ef4444' }};padding:4px 12px;border-radius:12px;font-size:13px;font-weight:500;">
							{{ ucfirst($transaction->type) }}
						</span>
					</td>
					<td style="padding:14px 8px;color:#cbd5e1;">{{ $transaction->description ?? '-' }}</td>
					<td style="padding:14px 8px;text-align:right;font-weight:600;color:{{ $transaction->type === 'deposit' ? '#10b981' : '#f87171' }};">
						{{ $transaction->type === 'deposit' ? '+' : '-' }}{{ format_currency($transaction->amount) }}
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="4" style="text-align:center;padding:32px;" class="muted">No savings transactions yet<br>Start saving by making your first deposit!</td>
				</tr>
				@endforelse
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
			<form id="depositForm" method="POST" action="{{ route('savings.deposit') }}">
				@csrf
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
				<div style="font-size:24px;font-weight:700;">{{ format_currency($totalBalance) }}</div>
			</div>
			<form id="withdrawForm" method="POST" action="{{ route('savings.withdraw') }}">
				@csrf
				<div class="form-group">
					<label class="form-label">Amount</label>
					<input id="withdrawAmount" class="form-input" name="amount" type="number" step="0.01" min="0.01" max="{{ $totalBalance }}" placeholder="0.00" oninput="validateWithdrawAmount()" required />
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
	const availableBalance = {{ $totalBalance }};

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

	function validateWithdrawAmount() {
		const amount = parseFloat(document.getElementById('withdrawAmount').value) || 0;
		const errorDiv = document.getElementById('withdrawError');
		const submitBtn = document.getElementById('withdrawSubmitBtn');
		
		if (amount > availableBalance) {
			errorDiv.style.display = 'block';
			submitBtn.disabled = true;
			submitBtn.style.opacity = '0.5';
			submitBtn.style.cursor = 'not-allowed';
		} else {
			errorDiv.style.display = 'none';
			submitBtn.disabled = false;
			submitBtn.style.opacity = '1';
			submitBtn.style.cursor = 'pointer';
		}
	}

	// Toast notification system
	function showToast(message, type = 'success') {
		const container = document.getElementById('toast-container');
		if (!container) return;

		const toast = document.createElement('div');
		const bgColor = type === 'success' ? '#059669' : '#dc2626';
		const icon = type === 'success' 
			? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
			: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		
		toast.style.cssText = `
			background: ${bgColor};
			color: white;
			padding: 14px 18px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			gap: 12px;
			box-shadow: 0 10px 25px rgba(0,0,0,0.3);
			animation: slideIn 0.3s ease-out;
			min-width: 280px;
			max-width: 400px;
		`;
		toast.innerHTML = `
			<div style="flex-shrink:0;">${icon}</div>
			<div style="flex:1;font-size:14px;font-weight:500;">${message}</div>
		`;

		container.appendChild(toast);

		// Auto remove after 3 seconds
		setTimeout(() => {
			toast.style.animation = 'slideOut 0.3s ease-in';
			setTimeout(() => toast.remove(), 300);
		}, 3000);
	}

	// Update notification badge
	function updateNavBadge(incrementBy = 1) {
		const el = document.getElementById('nav-notif-count');
		if (!el) return;
		
		const current = parseInt(el.textContent || '0', 10) || 0;
		const next = current + incrementBy;
		
		el.textContent = next;
		el.style.display = 'flex';
	}

	// Add animation styles
	const style = document.createElement('style');
	style.textContent = `
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
				showToast('Deposit successful!', 'success');
				updateNavBadge(1);
				setTimeout(() => window.location.reload(), 1500);
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
				updateNavBadge(1);
				setTimeout(() => window.location.reload(), 1500);
			} else {
				showToast(data.message || 'Error processing withdrawal', 'error');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showToast('Error processing withdrawal. Please try again.', 'error');
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

	// Page load animation for summary cards
	document.addEventListener('DOMContentLoaded', function() {
		const totalBalanceEl = document.getElementById('totalBalance');
		const totalDepositsEl = document.getElementById('totalDeposits');
		const totalWithdrawalsEl = document.getElementById('totalWithdrawals');
		
		// Animate on page load
		setTimeout(() => {
			if (totalBalanceEl) {
				totalBalanceEl.style.transition = 'all 0.3s ease';
				totalBalanceEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalBalanceEl.style.transform = 'scale(1)', 300);
			}
			if (totalDepositsEl) {
				totalDepositsEl.style.transition = 'all 0.3s ease';
				totalDepositsEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalDepositsEl.style.transform = 'scale(1)', 300);
			}
			if (totalWithdrawalsEl) {
				totalWithdrawalsEl.style.transition = 'all 0.3s ease';
				totalWithdrawalsEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalWithdrawalsEl.style.transform = 'scale(1)', 300);
			}
		}, 100);
	});
</script>
@endsection

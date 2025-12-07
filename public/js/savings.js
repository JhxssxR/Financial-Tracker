// Get available balance from window object
const availableBalance = window.savingsData?.availableBalance || 0;

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

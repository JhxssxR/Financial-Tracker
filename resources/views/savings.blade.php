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
		<!-- Pagination -->
		@if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
			<style>
				.custom-pager{display:flex;justify-content:center;align-items:center;gap:12px;width:100%;margin-top:12px}
				.custom-pager .page-link{padding:10px 16px;background:#0f172a;border:1px solid #334155;border-radius:12px;color:#e2e8f0;text-decoration:none;cursor:pointer;font-size:14px;transition:all .2s}
				.custom-pager .page-link.disabled{opacity:0.45;color:#64748b;border-color:rgba(148,163,184,0.03);pointer-events:none}
				.custom-pager .page-number{width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#0f172a;border:1px solid #334155;color:#e2e8f0;text-decoration:none;transition:all .2s;font-weight:600}
				.custom-pager .page-number:hover{background:#1e293b;border-color:#10b981}
				.custom-pager .page-number.active{background:#10b981;color:#fff;border-color:#10b981}
			</style>

			<nav class="custom-pager" aria-label="Savings pagination">
				@if($transactions->onFirstPage())
					<span class="page-link disabled">Previous</span>
				@else
					<a class="page-link" href="{{ $transactions->previousPageUrl() }}">Previous</a>
				@endif

				<div style="display:flex;gap:8px;">
					@foreach(range(1, $transactions->lastPage()) as $page)
						@if($page == $transactions->currentPage())
							<span class="page-number active">{{ $page }}</span>
						@else
							<a class="page-number" href="{{ $transactions->url($page) }}">{{ $page }}</a>
						@endif
					@endforeach
				</div>

				@if($transactions->hasMorePages())
					<a class="page-link" href="{{ $transactions->nextPageUrl() }}">Next</a>
				@else
					<span class="page-link disabled">Next</span>
				@endif
			</nav>

		@endif

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
	// Pass PHP data to JavaScript
	window.savingsData = {
		availableBalance: {{ $totalBalance }}
	};
</script>
<script src="{{ asset('js/savings.js') }}" defer></script>
@endsection

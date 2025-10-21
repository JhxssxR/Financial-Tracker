@extends('layouts.app')

@section('content')
<h1 style="font-size:28px;font-weight:700;" class="page-header">Savings Account</h1>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Balance</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
		<div class="muted" style="font-size:12px;margin-top:4px;">Available for withdrawal</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Deposits</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
		<div class="muted" style="font-size:12px;margin-top:4px;">All time deposits</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Withdrawals</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
		<div class="muted" style="font-size:12px;margin-top:4px;">All time withdrawals</div>
	</div>
</section>

<div style="display:flex;gap:10px;margin: 12px 0 16px;">
	<button class="card" style="padding:10px 12px;background:#059669;border-color:#059669;">+ Deposit Money</button>
	<button class="card" style="padding:10px 12px;background:#334155;border-color:#334155;">− Withdraw Money</button>
 </div>

<section class="card" style="padding:24px;text-align:center;" class="stack-section">
	<div class="muted">No savings transactions yet<br>Start saving by making your first deposit!</div>
 </section>
@endsection

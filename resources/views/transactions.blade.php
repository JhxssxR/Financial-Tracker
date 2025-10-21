@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Transactions</h1>
	<div style="margin-left:auto;display:flex;gap:8px;">
		<button class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;">Export</button>
		<button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;">Add Transaction</button>
	</div>
 </div>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Transactions</div>
		<div style="font-size:28px;font-weight:700; margin-top:6px;">0</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Income</div>
		<div class="brand" style="font-size:28px;font-weight:700; margin-top:6px;">$0</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Expenses</div>
		<div class="danger" style="font-size:28px;font-weight:700; margin-top:6px;">$0</div>
	</div>
</section>

<section class="card card-pad stack-section">
	<div class="muted" style="margin-bottom:8px;">Filters & Search</div>
	<div style="display:flex;gap:10px;flex-wrap:wrap;">
		<input placeholder="Search transactions..." style="background:#0b1220;border:1px solid #334155;border-radius:8px;padding:10px 12px;flex:1;min-width:240px;color:#e2e8f0;">
		<select style="background:#0b1220;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;">
			<option>All Types</option>
		</select>
		<select style="background:#0b1220;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;">
			<option>All Categories</option>
		</select>
		<button class="card" style="padding:10px 12px;">Clear Filters</button>
	</div>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
	<div style="padding:16px 18px;border-bottom:1px solid #334155;font-weight:600;">Transaction History</div>
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
				<tr>
					<td colspan="4" style="text-align:center;padding:32px;" class="muted">No transactions found</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>
@endsection

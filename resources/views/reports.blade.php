@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Financial Reports</h1>
	<div style="margin-left:auto;display:flex;gap:8px;">
		<select class="card" style="background:#0b1220;border:1px solid #334155;border-radius:8px;padding:8px 12px;color:#e2e8f0;">
			<option>Last 6 Months</option>
			<option>This Year</option>
			<option>Last Year</option>
		</select>
		<button class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;">Export Report</button>
	</div>
</div>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Transactions</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;">0</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Income</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Expenses</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
	</div>
</section>

<section class="page-grid-2-1 stack-section">
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Income vs Expenses Trend</div>
		<canvas id="reportsTrend" height="140"></canvas>
	</div>
	<div class="card" style="padding:16px;">
		<div style="font-weight:700;margin-bottom:10px;">Expense Categories</div>
		<div class="muted" style="text-align:center;margin-top:8px;">No expense data available</div>
		<canvas id="expensePie" height="140" style="display:none;"></canvas>
	</div>
</section>

<section class="card card-pad stack-section">
	<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
		<div>
			<div style="font-weight:700;margin-bottom:6px;">Financial Analysis</div>
			<div class="muted" style="font-weight:700;">Key Insights</div>
			<ul style="margin-top:8px;line-height:1.8;">
				<li>Your savings rate has improved by 5.2% compared to last period</li>
				<li>Housing expenses account for 44% of total spending</li>
				<li>Income growth is outpacing expense growth</li>
				<li>You're on track to meet your annual savings goal</li>
			</ul>
		</div>
		<div>
			<div class="muted" style="font-weight:700;">Recommendations</div>
			<ul style="margin-top:8px;line-height:1.8;">
				<li>Consider reducing shopping expenses by 15%</li>
				<li>Food budget could be optimized for better savings</li>
				<li>Emergency fund goal: $15,000 (3 months expenses)</li>
				<li>Investment allocation: 20% of monthly income</li>
			</ul>
		</div>
	</div>
</section>

@push('scripts')
<script>
	const reportsTrend = document.getElementById('reportsTrend');
	if (reportsTrend && window.Chart) {
		new Chart(reportsTrend, {
			type: 'line',
			data: {
				labels: ['Jan','Feb','Mar','Apr','May','Jun'],
				datasets: [
					{ label: 'Income', data: [5800, 6200, 5900, 6500, 6100, 6700], borderColor: '#34d399', tension: 0.3 },
					{ label: 'Expenses', data: [4100, 4400, 4000, 4700, 4200, 5100], borderColor: '#f87171', tension: 0.3 },
				]
			},
			options: {
				plugins: { legend: { labels: { color: '#cbd5e1' } } },
				scales: {
					x: { ticks: { color: '#94a3b8' }, grid: { color: '#243043' } },
					y: { ticks: { color: '#94a3b8' }, grid: { color: '#243043' } },
				}
			}
		});
	}
</script>
@endpush
@endsection

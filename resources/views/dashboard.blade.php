@extends('layouts.app')

@section('content')
<h1 style="font-size:28px;font-weight:700;" class="page-header">Dashboard</h1>

<section class="page-grid-3">
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Total Savings</div>
        <div style="font-size:28px;font-weight:700; margin-top:6px;">$0.00</div>
        <div class="muted" style="font-size:12px;margin-top:4px;">+0.0% of income</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Income</div>
        <div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
        <div class="muted" style="font-size:12px;margin-top:4px;">This month</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Expenses</div>
        <div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;">$0.00</div>
        <div class="muted" style="font-size:12px;margin-top:4px;">0.0% of income</div>
    </div>
</section>

<section class="card card-pad-lg stack-section">
    <div style="font-weight:700;margin-bottom:10px;">Income vs. Expenses</div>
    <canvas id="trendChart" height="120"></canvas>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #334155;font-weight:600;">Recent Transactions</div>
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

@push('scripts')
<script>
    const ctx = document.getElementById('trendChart');
    if (ctx && window.Chart) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun'],
                datasets: [
                    { label: 'Income', data: [0, 0, 0, 0, 0, 0], borderColor: '#34d399', tension: 0.3 },
                    { label: 'Expenses', data: [0, 0, 0, 0, 0, 0], borderColor: '#f87171', tension: 0.3 },
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

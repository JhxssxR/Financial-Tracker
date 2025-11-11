@extends('layouts.app')

@section('content')
<h1 style="font-size:28px;font-weight:700;" class="page-header">Dashboard</h1>

<section class="page-grid-3">
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Total Savings</div>
        <div id="savingsTotal" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">{{ format_currency($totalSavings) }}</div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">{{ number_format($savingsPercentage, 1) }}% of income</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Income</div>
        <div id="incomeTotal" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalIncome) }}</div>
        <div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Year 2025</div>
    </div>
    <div class="card card-pad-lg">
        <div class="muted" style="font-weight:600;">Expenses</div>
        <div id="expensesTotal" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalExpenses) }}</div>
        <div id="expensesMeta" class="muted" style="font-size:12px;margin-top:4px;text-align:right;">{{ number_format($expensePercentage, 1) }}% of income</div>
    </div>
</section>

<section class="card card-pad-lg stack-section">
    <div style="font-weight:700;margin-bottom:10px;">Income vs. Expenses</div>
    <canvas id="trendChart" height="120"></canvas>
</section>

<section class="card stack-section" style="padding:0;overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #334155;">
        {{-- Notifications summary/banner above recent transactions --}}
        <div id="dashboard-notif-area" style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                @if(isset($unreadCount) && $unreadCount > 0 && isset($latestUnread) && $latestUnread)
                    @php
                        // Choose colors based on title or type for budget warnings
                        $nTitle = $latestUnread->title ?? 'Notification';
                        $nMessage = $latestUnread->message ?? '';
                        $nTime = $latestUnread->created_at ? $latestUnread->created_at->diffForHumans() : '';
                        $badgeBg = '#ef4444'; // default red for important
                        $badgeText = 'Over Budget';
                        if (stripos($nTitle, 'Near') !== false || stripos($nTitle, 'Warning') !== false || stripos($nTitle, 'Limit') !== false) {
                            $badgeBg = '#f59e0b';
                            $badgeText = 'Near Limit';
                        } elseif (stripos($nTitle, 'Exceeded') !== false || stripos($nTitle, 'Over') !== false) {
                            $badgeBg = '#ef4444';
                            $badgeText = 'Over Budget';
                        } else {
                            $badgeBg = '#334155';
                            $badgeText = ucfirst($latestUnread->type ?? 'info');
                        }
                    @endphp

                    <div style="display:flex;gap:12px;align-items:flex-start;">
                        <div style="width:44px;height:44px;border-radius:10px;background:#0f172a;display:flex;align-items:center;justify-content:center;border:1px solid #334155;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 14l2-2 4 4" stroke="#e2e8f0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="font-weight:700;color:#e2e8f0;font-size:16px;">{{ $nTitle }}</div>
                                <div style="background:{{ $badgeBg }};color:#fff;padding:4px 8px;border-radius:8px;font-size:12px;font-weight:700;">{{ $badgeText }}</div>
                                <div style="color:#94a3b8;font-size:13px;margin-left:6px;">{{ $nTime }}</div>
                            </div>
                            <div style="color:#b8c2ce;margin-top:6px;max-width:820px;">{{ $nMessage }}</div>
                        </div>
                    </div>
                @else
                    <div style="font-weight:700;font-size:16px;color:#e2e8f0;">Recent Transactions</div>
                    <div style="color:#94a3b8;margin-left:8px;">@if(isset($unreadCount) && $unreadCount > 0) You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }} @endif</div>
                @endif
            </div>
            <div style="text-align:right;">
                @if(isset($unreadCount) && $unreadCount > 0)
                    <a href="{{ route('notifications.index') }}" style="background:#ef4444;color:white;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">View ({{ $unreadCount }})</a>
                @else
                    <div style="color:#94a3b8;font-weight:600;">All caught up!</div>
                @endif
            </div>
        </div>
    </div>
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
                @forelse($recentTransactions as $transaction)
                <tr style="border-bottom:1px solid #1e293b;">
                    <td style="padding:14px 8px;color:#cbd5e1;">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                    <td style="padding:14px 8px;">{{ $transaction->description }}</td>
                    <td style="padding:14px 8px;">
                        @php
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
                        @endphp
                        <span style="background:{{ $bgColor }};padding:6px 16px;border-radius:18px;font-size:13px;color:{{ $textColor }};font-weight:400;">
                            {{ $categoryName }}
                        </span>
                    </td>
                    <td style="padding:14px 8px;text-align:right;font-weight:600;color:{{ $transaction->type === 'income' ? '#10b981' : '#f87171' }};">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ format_currency($transaction->amount) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:32px;" class="muted">No transactions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 </section>

@push('scripts')
<script>
    const ctx = document.getElementById('trendChart');
    if (ctx && window.Chart) {
        const monthlyData = @json($monthlyData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.months,
                datasets: [
                    { 
                        label: 'Income', 
                        data: monthlyData.income, 
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#10b981',
                        pointHoverRadius: 7
                    },
                    { 
                        label: 'Expenses', 
                        data: monthlyData.expenses, 
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#ef4444',
                        pointHoverRadius: 7
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { 
                            color: '#e2e8f0',
                            font: { size: 14, weight: '500' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'rect'
                        } 
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#e2e8f0',
                        bodyColor: '#e2e8f0',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#94a3b8', font: { size: 12 } }, 
                        grid: { color: '#2d3748', drawBorder: false }
                    },
                    y: { 
                        ticks: { color: '#94a3b8', font: { size: 12 } }, 
                        grid: { color: '#2d3748', drawBorder: false },
                        beginAtZero: true
                    },
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
    }
</script>
    <script>
        // helper to apply totals payload to DOM
        function applyTotalsPayload(t) {
            try {
                console.log('Dashboard: Applying totals update', t);
                const incomeEl = document.getElementById('incomeTotal');
                const expensesEl = document.getElementById('expensesTotal');
                const metaEl = document.getElementById('expensesMeta');

                if (incomeEl && Object.prototype.hasOwnProperty.call(t, 'totalIncome')) {
                    incomeEl.textContent = t.totalIncome;
                    // Add visual feedback
                    incomeEl.style.transition = 'all 0.3s ease';
                    incomeEl.style.transform = 'scale(1.1)';
                    setTimeout(() => incomeEl.style.transform = 'scale(1)', 300);
                }
                if (expensesEl && Object.prototype.hasOwnProperty.call(t, 'totalExpenses')) {
                    expensesEl.textContent = t.totalExpenses;
                    // Add visual feedback
                    expensesEl.style.transition = 'all 0.3s ease';
                    expensesEl.style.transform = 'scale(1.1)';
                    setTimeout(() => expensesEl.style.transform = 'scale(1)', 300);
                }
                if (metaEl && Object.prototype.hasOwnProperty.call(t, 'rawIncome') && Object.prototype.hasOwnProperty.call(t, 'rawExpenses')) {
                    const pct = t.rawIncome > 0 ? ((t.rawExpenses / t.rawIncome) * 100).toFixed(1) : '0.0';
                    metaEl.textContent = `${pct}% of income`;
                }
                console.log('Dashboard: Totals updated successfully');
            } catch (err) {
                console.error('applyTotalsPayload error', err);
            }
        }

        // Read existing localStorage on load to pick up latest totals
        try {
            const raw = localStorage.getItem('transactions:latest');
            console.log('Dashboard: Reading localStorage', raw);
            if (raw) {
                const payload = JSON.parse(raw);
                console.log('Dashboard: Parsed payload', payload);
                if (payload && payload.totals) {
                    console.log('Dashboard: Found totals, applying...');
                    applyTotalsPayload(payload.totals);
                }
            }
        } catch (err) {
            console.error('Dashboard: localStorage read error', err);
        }

        // Listen for transactions updates written to localStorage by the transactions page
        window.addEventListener('storage', (e) => {
            console.log('Dashboard: storage event received', e.key, e.newValue);
            if (e.key !== 'transactions:latest') return;
            try {
                const payload = JSON.parse(e.newValue || e.oldValue || '{}');
                console.log('Dashboard: storage payload', payload);
                if (!payload.totals) return;
                applyTotalsPayload(payload.totals);
            } catch (err) {
                console.error('Error parsing transactions:latest payload', err);
            }
        });

        // BroadcastChannel for immediate cross-tab updates (faster than storage event)
        if (typeof BroadcastChannel !== 'undefined') {
            const bc = new BroadcastChannel('transactions-updates');
            bc.onmessage = (ev) => {
                console.log('Dashboard: BroadcastChannel message received', ev.data);
                if (ev.data && ev.data.totals) {
                    applyTotalsPayload(ev.data.totals);
                }
            };
            console.log('Dashboard: BroadcastChannel listener active');
        } else {
            console.log('Dashboard: BroadcastChannel not supported');
        }

        // Listen for notification updates (read/delete) so we can update banner and "All caught up!"
        function handleNotificationUpdate(payload) {
            try {
                console.log('Dashboard: notification update', payload);
                const area = document.getElementById('dashboard-notif-area');
                if (!area) return;

                const newCount = typeof payload.newCount === 'number' ? payload.newCount : null;
                if (newCount === 0) {
                    // Replace left side with header and show All caught up!
                    area.innerHTML = `
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="font-weight:700;font-size:16px;color:#e2e8f0;">Recent Transactions</div>
                            <div style="color:#94a3b8;margin-left:8px;">All caught up!</div>
                        </div>
                        <div style="text-align:right;color:#94a3b8;font-weight:600;">All caught up!</div>
                    `;
                    return;
                }

                if (typeof newCount === 'number' && newCount > 0) {
                    // Update view link count on the right
                    const right = area.querySelector('div[style*="text-align:right"]');
                    if (right) {
                        right.innerHTML = `<a href="/notifications" style="background:#ef4444;color:white;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">View (${newCount})</a>`;
                    }
                }
            } catch (err) {
                console.error('handleNotificationUpdate error', err);
            }
        }

        // storage event listener for notifications
        window.addEventListener('storage', (e) => {
            if (e.key !== 'notifications:latest') return;
            try {
                const payload = JSON.parse(e.newValue || e.oldValue || '{}');
                console.log('Dashboard: storage payload (notifications)', payload);
                handleNotificationUpdate(payload);
            } catch (err) {
                console.error('Error parsing notifications:latest payload', err);
            }
        });

        // BroadcastChannel listener for notifications
        if (typeof BroadcastChannel !== 'undefined') {
            const nbc = new BroadcastChannel('notifications-updates');
            nbc.onmessage = (ev) => {
                console.log('Dashboard: notifications BroadcastChannel', ev.data);
                handleNotificationUpdate(ev.data);
            };
            console.log('Dashboard: notifications BroadcastChannel listener active');
        }

        // Page load animation for savings card (same as income card)
        document.addEventListener('DOMContentLoaded', function() {
            const savingsEl = document.getElementById('savingsTotal');
            const incomeEl = document.getElementById('incomeTotal');
            const expensesEl = document.getElementById('expensesTotal');
            
            // Animate on page load
            setTimeout(() => {
                if (savingsEl) {
                    savingsEl.style.transition = 'all 0.3s ease';
                    savingsEl.style.transform = 'scale(1.1)';
                    setTimeout(() => savingsEl.style.transform = 'scale(1)', 300);
                }
                if (incomeEl) {
                    incomeEl.style.transition = 'all 0.3s ease';
                    incomeEl.style.transform = 'scale(1.1)';
                    setTimeout(() => incomeEl.style.transform = 'scale(1)', 300);
                }
                if (expensesEl) {
                    expensesEl.style.transition = 'all 0.3s ease';
                    expensesEl.style.transform = 'scale(1.1)';
                    setTimeout(() => expensesEl.style.transform = 'scale(1)', 300);
                }
            }, 100);
        });
    </script>
@endpush
@endsection

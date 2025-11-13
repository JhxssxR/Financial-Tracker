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
    // Provide chart data to external script
    window.__DASHBOARD_DATA = @json($monthlyData);
</script>
<script src="{{ asset('js/dashboard.js') }}" defer></script>
@endpush
@endsection

(function(){
    // Chart initialization using server-passed data on window.__DASHBOARD_DATA
    try {
        const monthlyData = window.__DASHBOARD_DATA || { months: [], income: [], expenses: [] };
        const ctx = document.getElementById('trendChart');
        if (ctx && window.Chart) {
            window.__DASHBOARD_CHART = new Chart(ctx, {
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
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                        x: { ticks: { color: '#94a3b8', font: { size: 12 } }, grid: { color: '#2d3748', drawBorder: false } },
                        y: { ticks: { color: '#94a3b8', font: { size: 12 } }, grid: { color: '#2d3748', drawBorder: false }, beginAtZero: true }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        }
    } catch (err) {
        console.error('Dashboard: chart init error', err);
    }

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

    // storage event listener for transactions
    window.addEventListener('storage', (e) => {
        try {
            console.log('Dashboard: storage event received', e.key, e.newValue);
            if (e.key === 'transactions:latest') {
                const payload = JSON.parse(e.newValue || e.oldValue || '{}');
                console.log('Dashboard: storage payload', payload);
                if (!payload.totals) return;
                applyTotalsPayload(payload.totals);
            }
            if (e.key === 'notifications:latest') {
                const payload = JSON.parse(e.newValue || e.oldValue || '{}');
                console.log('Dashboard: storage payload (notifications)', payload);
                handleNotificationUpdate(payload);
            }
        } catch (err) {
            console.error('Dashboard storage handler error', err);
        }
    });

    // BroadcastChannel for immediate cross-tab updates (faster than storage event)
    if (typeof BroadcastChannel !== 'undefined') {
        try {
            const bc = new BroadcastChannel('transactions-updates');
            bc.onmessage = (ev) => {
                console.log('Dashboard: BroadcastChannel message received', ev.data);
                if (ev.data && ev.data.totals) {
                    applyTotalsPayload(ev.data.totals);
                }
            };
            console.log('Dashboard: BroadcastChannel listener active');

            const nbc = new BroadcastChannel('notifications-updates');
            nbc.onmessage = (ev) => {
                console.log('Dashboard: notifications BroadcastChannel', ev.data);
                handleNotificationUpdate(ev.data);
            };
            console.log('Dashboard: notifications BroadcastChannel listener active');
        } catch (err) {
            console.error('Dashboard: BroadcastChannel setup error', err);
        }
    } else {
        console.log('Dashboard: BroadcastChannel not supported');
    }

    
    function handleNotificationUpdate(payload) {
        try {
            console.log('Dashboard: notification update', payload);
            const area = document.getElementById('dashboard-notif-area');
            if (!area) return;

            const newCount = typeof payload.newCount === 'number' ? payload.newCount : null;
            if (newCount === 0) {
                
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
               
                const right = area.querySelector('div[style*="text-align:right"]');
                if (right) {
                    right.innerHTML = `<a href="/notifications" style="background:#ef4444;color:white;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">View (${newCount})</a>`;
                }
            }
        } catch (err) {
            console.error('handleNotificationUpdate error', err);
        }
    }

    
    document.addEventListener('DOMContentLoaded', function() {
        const savingsEl = document.getElementById('savingsTotal');
        const incomeEl = document.getElementById('incomeTotal');
        const expensesEl = document.getElementById('expensesTotal');

        
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

   
    function safeResizeDashboardChart() {
        try {
            if (window.__DASHBOARD_CHART && typeof window.__DASHBOARD_CHART.resize === 'function') window.__DASHBOARD_CHART.resize();
        } catch (e) { }
    }

    let __dResizeTimer = null;
    window.addEventListener('resize', function() { clearTimeout(__dResizeTimer); __dResizeTimer = setTimeout(safeResizeDashboardChart, 150); });
    window.addEventListener('orientationchange', function() { setTimeout(safeResizeDashboardChart, 300); });

})();

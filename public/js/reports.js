// reports.js - extracted from reports.blade.php
// Expects `window.__REPORTS_DATA` to be set by the Blade view before this file is loaded.
(function(){
    const data = window.__REPORTS_DATA || {};
    const monthlyData = data.monthlyData || { months: [], income: [], expenses: [] };
    const expenseCategoryData = data.expenseCategoryData || { labels: [], data: [], colors: [] };
    const currencyCode = data.currencyCode || 'USD';

    try {
        const reportsTrend = document.getElementById('reportsTrend');
        if (reportsTrend && window.Chart) {
            new Chart(reportsTrend, {
                type: 'line',
                data: {
                    labels: monthlyData.months,
                    datasets: [
                        { label: 'Income', data: monthlyData.income, borderColor: '#34d399', backgroundColor: 'rgba(52, 211, 153, 0.1)', tension: 0.3, fill: true },
                        { label: 'Expenses', data: monthlyData.expenses, borderColor: '#f87171', backgroundColor: 'rgba(248, 113, 113, 0.1)', tension: 0.3, fill: true },
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
    } catch (e) {
        console.error('Failed to render reports trend chart', e);
    }

    try {
        const expenseDonut = document.getElementById('expenseDonut');
        if (expenseDonut && window.Chart) {
            if ((expenseCategoryData.labels || []).length > 0) {
                new Chart(expenseDonut, {
                    type: 'doughnut',
                    data: {
                        labels: expenseCategoryData.labels,
                        datasets: [{
                            data: expenseCategoryData.data,
                            backgroundColor: expenseCategoryData.colors.length > 0 ? expenseCategoryData.colors : [
                                '#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
                            ],
                            borderColor: '#1e293b',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#cbd5e1',
                                    padding: 15,
                                    font: { size: 12 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) label += ': ';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a,b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        label += new Intl.NumberFormat('en-US', { style: 'currency', currency: currencyCode }).format(value);
                                        label += ` (${percentage}%)`;
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    } catch (e) {
        console.error('Failed to render expense donut chart', e);
    }

    // Page load animations
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const reportTotalTransactionsEl = document.getElementById('reportTotalTransactions');
            const reportTotalIncomeEl = document.getElementById('reportTotalIncome');
            const reportTotalSavingsEl = document.getElementById('reportTotalSavings');
            const reportTotalExpensesEl = document.getElementById('reportTotalExpenses');
            setTimeout(() => {
                if (reportTotalTransactionsEl) { reportTotalTransactionsEl.style.transition = 'all 0.3s ease'; reportTotalTransactionsEl.style.transform = 'scale(1.1)'; setTimeout(() => reportTotalTransactionsEl.style.transform = 'scale(1)', 300); }
                if (reportTotalIncomeEl) { reportTotalIncomeEl.style.transition = 'all 0.3s ease'; reportTotalIncomeEl.style.transform = 'scale(1.1)'; setTimeout(() => reportTotalIncomeEl.style.transform = 'scale(1)', 300); }
                if (reportTotalSavingsEl) { reportTotalSavingsEl.style.transition = 'all 0.3s ease'; reportTotalSavingsEl.style.transform = 'scale(1.1)'; setTimeout(() => reportTotalSavingsEl.style.transform = 'scale(1)', 300); }
                if (reportTotalExpensesEl) { reportTotalExpensesEl.style.transition = 'all 0.3s ease'; reportTotalExpensesEl.style.transform = 'scale(1.1)'; setTimeout(() => reportTotalExpensesEl.style.transform = 'scale(1)', 300); }
            }, 100);
        } catch (e) { console.error('Reports page animation failed', e); }
    });
})();

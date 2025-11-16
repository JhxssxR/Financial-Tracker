// budgets.js - extracted from budgets.blade.php
// Expects `window.__BUDGETS` to be set by the Blade view before this file is loaded.

// Ensure functions referenced by onclick attributes are available on window
window.isEditMode = false;
window.currentBudgetId = null;

window.updateDateRange = function() {
    const periodEl = document.getElementById('budgetPeriod');
    if (!periodEl) return;
    const period = periodEl.value;
    const startDateInput = document.getElementById('budgetStartDate');
    const endDateInput = document.getElementById('budgetEndDate');
    const today = new Date();
    const startDate = new Date(today.getFullYear(), today.getMonth(), 1);
    let endDate;
    if (period === 'monthly') {
        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    } else if (period === 'weekly') {
        startDate.setDate(today.getDate() - today.getDay());
        endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);
    } else if (period === 'yearly') {
        endDate = new Date(today.getFullYear(), 11, 31);
    }
    if (startDateInput) startDateInput.value = startDate.toISOString().split('T')[0];
    if (endDateInput) endDateInput.value = endDate.toISOString().split('T')[0];
};

window.openBudgetModal = function() {
    window.isEditMode = false;
    window.currentBudgetId = null;
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) modalTitle.textContent = 'Create New Budget';
    const submitBtn = document.getElementById('submitBudgetBtn');
    if (submitBtn) submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/><circle cx="12" cy="12" r="6" stroke="white" stroke-width="2"/><circle cx="12" cy="12" r="2" fill="white"/></svg> Create Budget';
    const form = document.getElementById('budgetForm');
    if (form) form.reset();
    const methodEl = document.getElementById('budgetMethod');
    if (methodEl) methodEl.value = 'POST';
    if (form) form.action = document.getElementById('budgetForm')?.getAttribute('action') || '/budgets';
    window.updateDateRange();
    const modal = document.getElementById('budgetModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
};

window.closeBudgetModal = function() {
    const modal = document.getElementById('budgetModal');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = '';
};

window.editBudget = function(id) {
    const budgets = window.__BUDGETS || [];
    const budget = budgets.find(b => b.id === id);
    if (!budget) {
        console.error('Budget not found with id:', id);
        return;
    }
    window.isEditMode = true;
    window.currentBudgetId = id;
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) modalTitle.textContent = 'Edit Budget';
    const submitBtn = document.getElementById('submitBudgetBtn');
    if (submitBtn) submitBtn.textContent = 'Update Budget';
    const budgetIdEl = document.getElementById('budgetId');
    if (budgetIdEl) budgetIdEl.value = id;
    const methodEl = document.getElementById('budgetMethod');
    if (methodEl) methodEl.value = 'PUT';
    const form = document.getElementById('budgetForm');
    if (form) form.action = `/budgets/${id}`;
    document.getElementById('budgetName').value = budget.name || '';
    document.getElementById('budgetCategory').value = budget.category_id || '';
    document.getElementById('budgetAmount').value = budget.amount ?? '';
    document.getElementById('budgetPeriod').value = budget.period || 'monthly';
    document.getElementById('budgetStartDate').value = budget.start_date || '';
    document.getElementById('budgetEndDate').value = budget.end_date || '';
    const modal = document.getElementById('budgetModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
};

window.submitBudget = function() {
    const form = document.getElementById('budgetForm');
    if (!form) return;
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const categorySelect = document.getElementById('budgetCategory');
    const categoryText = categorySelect.options[categorySelect.selectedIndex].text || '';
    const period = document.getElementById('budgetPeriod').value;
    const periodText = period.charAt(0).toUpperCase() + period.slice(1);
    document.getElementById('budgetName').value = `${periodText} ${categoryText}`;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBudgetBtn');
    const originalHTML = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = 'Saving...'; }
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.notifications) {
                try {
                    localStorage.setItem('notifications:latest', JSON.stringify({ ts: Date.now(), payload: data.notifications }));
                    if (typeof BroadcastChannel !== 'undefined') {
                        const nb = new BroadcastChannel('notifications-updates');
                        nb.postMessage(data.notifications);
                        nb.close();
                    }
                } catch (e) { console.error('Budgets: Error broadcasting notification', e); }
            }
            window.closeBudgetModal();
            window.location.reload();
        } else {
            alert('Error saving budget. Please try again.');
            if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalHTML; }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving budget. Please try again.');
        if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalHTML; }
    });
};

window.deleteBudget = function(id) {
    if (!confirm('Are you sure you want to delete this budget?')) return;
    fetch(`/budgets/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error deleting budget. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting budget. Please try again.');
    });
};

// Overlay close handler
(function() {
    const modal = document.getElementById('budgetModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'budgetModal') {
                window.closeBudgetModal();
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('budgetModal')?.classList.contains('active')) {
            window.closeBudgetModal();
        }
    });

    // Filter budgets by category
    window.filterBudgets = function() {
        const categoryValue = document.getElementById('categoryFilter')?.value || '';
        const budgetCards = document.querySelectorAll('.card[data-budget-id]');
        budgetCards.forEach(card => {
            const budgetCategoryId = card.getAttribute('data-category-id');
            if (categoryValue === '' || budgetCategoryId === categoryValue) card.style.display = '';
            else card.style.display = 'none';
        });
    };

    // Page load animations
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const totalBudgetedEl = document.getElementById('totalBudgeted');
            const totalSpentEl = document.getElementById('totalSpent');
            const totalRemainingEl = document.getElementById('totalRemaining');
            setTimeout(() => {
                if (totalBudgetedEl) { totalBudgetedEl.style.transition = 'all 0.3s ease'; totalBudgetedEl.style.transform = 'scale(1.1)'; setTimeout(() => totalBudgetedEl.style.transform = 'scale(1)', 300); }
                if (totalSpentEl) { totalSpentEl.style.transition = 'all 0.3s ease'; totalSpentEl.style.transform = 'scale(1.1)'; setTimeout(() => totalSpentEl.style.transform = 'scale(1)', 300); }
                if (totalRemainingEl) { totalRemainingEl.style.transition = 'all 0.3s ease'; totalRemainingEl.style.transform = 'scale(1.1)'; setTimeout(() => totalRemainingEl.style.transform = 'scale(1)', 300); }
            }, 100);
        } catch (e) {
            console.error('Budgets page animation failed', e);
        }
    });
})();

(function(){
    let currentType = 'income';

    // Categories from server-provided data
    const categoriesData = (window.__TRANSACTIONS_DATA && window.__TRANSACTIONS_DATA.categories) ? window.__TRANSACTIONS_DATA.categories : {};

    function updateCategories() {
        const select = document.getElementById('categorySelect');
        if (!select) {
            console.error('Category select not found');
            return;
        }
        select.innerHTML = '<option value="">Select a category...</option>';

        const typeCategories = categoriesData[currentType] || [];
        console.log('Loading categories for type:', currentType, 'Count:', typeCategories.length);
        
        const seenNames = new Set();
        typeCategories.forEach(cat => {
            if (!cat || typeof cat.id === 'undefined') return;
            const nameKey = (cat.name || '').trim().toLowerCase();
            if (!nameKey) return;
            if (seenNames.has(nameKey)) return; // skip duplicate names
            seenNames.add(nameKey);

            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            select.appendChild(option);
        });

        if (typeCategories.length === 0) {
            console.warn('No categories found for type:', currentType);
        }
    }

    function openTransactionModal() {
        const modal = document.getElementById('transactionModal');
        if (!modal) {
            console.error('Transaction modal not found');
            return;
        }
        
        console.log('Opening transaction modal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Reset form and select income by default
        const form = document.getElementById('transactionForm');
        if (form) form.reset();
        selectType('income');
        updateCategories();
    }

    function closeTransactionModal() {
        const modal = document.getElementById('transactionModal');
        if (!modal) return;
        modal.classList.remove('active');
        document.body.style.overflow = '';
        const form = document.getElementById('transactionForm');
        if (form) form.reset();
        selectType('income');
    }

    function selectType(type) {
        currentType = type;
        const hidden = document.getElementById('transactionType');
        if (hidden) hidden.value = type;

        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active', 'expense-active');
        });

        const selectedBtn = document.querySelector(`.type-btn[data-type="${type}"]`);
        if (selectedBtn) selectedBtn.classList.add('active');

        if (type === 'expense' && selectedBtn) {
            selectedBtn.classList.add('expense-active');
        }

        // Update placeholder text
        const descInput = document.getElementById('descriptionInput');
        if (descInput) {
            descInput.placeholder = type === 'expense' ? 'e.g., Grocery Shopping' : 'e.g., Monthly Salary';
        }

        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            if (type === 'income') {
                submitBtn.innerHTML = '<svg style="margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Add Income';
                submitBtn.style.background = '#10b981';
                submitBtn.style.borderColor = '#10b981';
            } else {
                submitBtn.innerHTML = '<svg style="margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 7L7 17M7 17H15M7 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Add Expense';
                submitBtn.style.background = '#3b82f6';
                submitBtn.style.borderColor = '#3b82f6';
            }
        }

        updateCategories();
    }

    async function submitTransaction() {
        const form = document.getElementById('transactionForm');
        if (!form) {
            console.error('Transaction form not found');
            return;
        }

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        // ensure the current type is always submitted
        formData.set('type', currentType);
        
        const submitBtn = document.getElementById('submitBtn');
        if (!submitBtn) {
            console.error('Submit button not found');
            return;
        }
        const originalContent = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Saving...</span>';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content
                }
            });

            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            const data = await res.json();

            if (data.success) {
                // Write totals to localStorage to notify other pages (dashboard) to update
                try {
                    localStorage.setItem('transactions:latest', JSON.stringify({ ts: Date.now(), totals: data.totals }));

                    // BroadcastChannel for immediate cross-tab updates
                    if (typeof BroadcastChannel !== 'undefined') {
                        const bc = new BroadcastChannel('transactions-updates');
                        bc.postMessage({ totals: data.totals });
                        bc.close();
                    }

                    // If server returned notifications (e.g., budget threshold crossed), persist and broadcast them
                    if (data.notifications) {
                        try {
                            const notifPayload = data.notifications;
                            localStorage.setItem('notifications:latest', JSON.stringify({ ts: Date.now(), payload: notifPayload }));

                            if (typeof BroadcastChannel !== 'undefined') {
                                const nb = new BroadcastChannel('notifications-updates');
                                nb.postMessage(notifPayload);
                                nb.close();
                            }
                        } catch (ne) {
                            console.error('Transactions: Error broadcasting notifications', ne);
                        }
                    }
                } catch (e) {
                    console.error('Transactions: Error broadcasting update', e);
                }

                // Close modal and reload transactions page
                closeTransactionModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error adding transaction. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error adding transaction: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    }

    // Filter transactions
    function filterTransactions() {
        const searchEl = document.getElementById('searchInput');
        const typeEl = document.getElementById('typeFilter');
        const catEl = document.getElementById('categoryFilter');
        const searchValue = searchEl ? searchEl.value.toLowerCase() : '';
        const typeValue = typeEl ? typeEl.value.toLowerCase() : '';
        const categoryValue = catEl ? catEl.value : '';

        const rows = document.querySelectorAll('.card tbody tr');

        rows.forEach(row => {
            // Skip the empty state row
            if (!row.hasAttribute('data-type')) {
                return;
            }

            // Get row data
            const description = row.querySelector('.transaction-description')?.textContent.toLowerCase() || '';
            const type = row.getAttribute('data-type')?.toLowerCase() || '';
            const categoryId = row.getAttribute('data-category-id') || '';

            // Check all filter conditions
            const matchesSearch = description.includes(searchValue);
            const matchesType = typeValue === '' || type === typeValue;
            const matchesCategory = categoryValue === '' || categoryId === categoryValue;

            // Show/hide row based on filters
            if (matchesSearch && matchesType && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Clear all filters
    function clearFilters() {
        const searchEl = document.getElementById('searchInput');
        const typeEl = document.getElementById('typeFilter');
        const catEl = document.getElementById('categoryFilter');
        if (searchEl) searchEl.value = '';
        if (typeEl) typeEl.value = '';
        if (catEl) catEl.value = '';
        filterTransactions(); // Reset display
    }

    // Close modal on overlay click
    (function attachOverlayClose(){
        const modal = document.getElementById('transactionModal');
        if (!modal) return;
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'transactionModal') {
                closeTransactionModal();
            }
        });
    })();

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('transactionModal');
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeTransactionModal();
        }
    });

    // Page load animation for summary cards
    document.addEventListener('DOMContentLoaded', function() {
        const totalTransactionsEl = document.getElementById('totalTransactions');
        const totalIncomeEl = document.getElementById('totalIncome');
        const totalExpensesEl = document.getElementById('totalExpenses');

        // Animate on page load
        setTimeout(() => {
            if (totalTransactionsEl) {
                totalTransactionsEl.style.transition = 'all 0.3s ease';
                totalTransactionsEl.style.transform = 'scale(1.1)';
                setTimeout(() => totalTransactionsEl.style.transform = 'scale(1)', 300);
            }
            if (totalIncomeEl) {
                totalIncomeEl.style.transition = 'all 0.3s ease';
                totalIncomeEl.style.transform = 'scale(1.1)';
                setTimeout(() => totalIncomeEl.style.transform = 'scale(1)', 300);
            }
            if (totalExpensesEl) {
                totalExpensesEl.style.transition = 'all 0.3s ease';
                totalExpensesEl.style.transform = 'scale(1.1)';
                setTimeout(() => totalExpensesEl.style.transform = 'scale(1)', 300);
            }
        }, 100);
    });

    // Expose functions globally so inline attributes still work
    window.openTransactionModal = openTransactionModal;
    window.closeTransactionModal = closeTransactionModal;
    window.selectType = selectType;
    window.submitTransaction = submitTransaction;
    window.filterTransactions = filterTransactions;
    window.clearFilters = clearFilters;

})();

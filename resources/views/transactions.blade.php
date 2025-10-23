@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
    <h1 style="font-size:28px;font-weight:700;">Transactions</h1>
    <div style="margin-left:auto;display:flex;gap:8px;">
        <button class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;cursor:pointer;display:flex;align-items:center;gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5m0 0l5-5m-5 5V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Export
        </button>
        <button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;cursor:pointer;" onclick="openTransactionModal()">+ Add Transaction</button>
    </div>
 </div><section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Transactions</div>
		<div style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">0</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Income</div>
		<div class="brand" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">$0</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Expenses</div>
		<div class="danger" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">$0</div>
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

<!-- Add Transaction Modal -->
<div id="transactionModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <svg style="color:#10b981;" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" stroke="currentColor" stroke-width="2"/>
                <path d="M8.5 14.5c0 1.105.895 2 2 2h2c1.105 0 2-.895 2-2s-.895-2-2-2h-1c-1.105 0-2-.895-2-2s.895-2 2-2h2c1.105 0 2 .895 2 2M12 6v2m0 8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <h2>Add Transaction</h2>
            <button class="modal-close" onclick="closeTransactionModal()">✕</button>
        </div>
        <div class="modal-body">
            <form id="transactionForm" onsubmit="return false;">
                <div class="form-group">
                    <label class="form-label" style="color:#cbd5e1;">Transaction Type</label>
                    <div class="type-toggle">
                        <div class="type-btn active" data-type="income" onclick="selectType('income')">
                            <svg class="type-btn-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="type-btn-text">Income</div>
                        </div>
                        <div class="type-btn" data-type="expense" onclick="selectType('expense')">
                            <svg class="type-btn-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7L7 17M7 17H15M7 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="type-btn-text">Expense</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Description</span>
                    </label>
                    <input class="form-input" id="descriptionInput" type="text" placeholder="e.g., Monthly Salary" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span>Amount</span>
                    </label>
                    <input class="form-input" type="text" placeholder="$0.00" pattern="^\$?[0-9]*\.?[0-9]{0,2}$" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Category</span>
                    </label>
                    <select class="form-input" id="categorySelect" style="cursor:pointer;" required>
                        <option value="">Select a category...</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Date</span>
                    </label>
                    <input class="form-input" type="date" value="2025-10-21" required />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeTransactionModal()">Cancel</button>
            <button class="btn btn-primary" id="submitBtn" style="width:auto;min-width:150px;padding:11px 20px;font-size:14px;font-weight:600;">
                <span style="margin-right:6px;">📈</span> Add Income
            </button>
        </div>
    </div>
</div>

<script>
    let currentType = 'income';

    const categories = {
        income: [
            'Salary',
            'Freelance',
            'Investment',
            'Bonus',
            'Gift',
            'Refund',
            'Other Income'
        ],
        expense: [
            'Food & Dining',
            'Transportation',
            'Utilities',
            'Entertainment',
            'Shopping',
            'Healthcare',
            'Education',
            'Housing',
            'Insurance',
            'Personal Care',
            'Other Expense'
        ]
    };

    function updateCategories() {
        const select = document.getElementById('categorySelect');
        select.innerHTML = '<option value="">Select a category...</option>';
        
        categories[currentType].forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.toLowerCase().replace(/ /g, '_');
            option.textContent = cat;
            select.appendChild(option);
        });
    }

    function openTransactionModal() {
        document.getElementById('transactionModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        updateCategories();
    }

    function closeTransactionModal() {
        document.getElementById('transactionModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('transactionForm').reset();
        selectType('income');
    }

    function selectType(type) {
        currentType = type;
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active', 'expense-active');
        });
        
        const selectedBtn = document.querySelector(`.type-btn[data-type="${type}"]`);
        selectedBtn.classList.add('active');
        
        if (type === 'expense') {
            selectedBtn.classList.add('expense-active');
        }
        
        // Update placeholder text
        const descInput = document.getElementById('descriptionInput');
        if (type === 'expense') {
            descInput.placeholder = 'e.g., Grocery Shopping';
        } else {
            descInput.placeholder = 'e.g., Monthly Salary';
        }
        
        const submitBtn = document.getElementById('submitBtn');
        if (type === 'income') {
            submitBtn.innerHTML = '<svg style="margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Add Income';
            submitBtn.style.background = '#10b981';
            submitBtn.style.borderColor = '#10b981';
        } else {
            submitBtn.innerHTML = '<svg style="margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 7L7 17M7 17H15M7 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Add Expense';
            submitBtn.style.background = '#3b82f6';
            submitBtn.style.borderColor = '#3b82f6';
        }
        
        updateCategories();
    }

    // Close modal on overlay click
    document.getElementById('transactionModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'transactionModal') {
            closeTransactionModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('transactionModal').classList.contains('active')) {
            closeTransactionModal();
        }
    });
</script>
@endsection
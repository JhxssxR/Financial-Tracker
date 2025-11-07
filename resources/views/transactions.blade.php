@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
    <h1 style="font-size:28px;font-weight:700;">Transactions</h1>
    <div style="margin-left:auto;display:flex;gap:8px;">
        <a href="{{ route('transactions.export') }}" class="card" style="padding:8px 12px;background:#0e7d57;border-color:#0e7d57;cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:none;color:white;transition:all .2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#0e7d57'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5m0 0l5-5m-5 5V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Export
        </a>
        <button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;cursor:pointer;" onclick="openTransactionModal()">+ Add Transaction</button>
    </div>
 </div><section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Transactions</div>
		<div style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">{{ $totalTransactions }}</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Income</div>
		<div class="brand" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">{{ format_currency($totalIncome) }}</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Expenses</div>
		<div class="danger" style="font-size:28px;font-weight:700; margin-top:6px;text-align:right;">{{ format_currency($totalExpenses) }}</div>
	</div>
</section>

<section class="card card-pad stack-section">
	<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V6.58579C21 6.851 20.8946 7.10536 20.7071 7.29289L14.2929 13.7071C14.1054 13.8946 14 14.149 14 14.4142V17L10 21V14.4142C10 14.149 9.89464 13.8946 9.70711 13.7071L3.29289 7.29289C3.10536 7.10536 3 6.851 3 6.58579V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<span style="font-weight:600;font-size:16px;">Filters & Search</span>
	</div>

	<div style="display:flex;align-items:center;gap:12px;">
        <div style="position:relative;flex:1;max-width:350px;">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#64748b;" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <input id="searchInput" onkeyup="filterTransactions()" placeholder="Search transactions..." style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px 10px 38px;width:100%;color:#e2e8f0;">
        </div>
        <select id="typeFilter" onchange="filterTransactions()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;min-width:260px;max-width:320px;font-size:15px;">
            <option value="">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <select id="categoryFilter" onchange="filterTransactions()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;min-width:260px;max-width:320px;font-size:15px;">
            <option value="">All Categories</option>
            @foreach($categories->flatten()->unique('name') as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    <button onclick="clearFilters()" style="background:#1e293b;border:1px solid #334155;border-radius:8px;padding:10px 12px;color:#e2e8f0;cursor:pointer;font-size:15px;white-space:nowrap;margin-left:auto;margin-right:32px;">Clear Filters</button>
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
				@forelse($transactions as $transaction)
				<tr style="border-bottom:1px solid #1e293b;" data-category-id="{{ $transaction->category_id }}" data-type="{{ $transaction->type }}">
					<td style="padding:14px 8px;color:#cbd5e1;">{{ $transaction->transaction_date->format('M d, Y') }}</td>
					<td style="padding:14px 8px;" class="transaction-description">{{ $transaction->description }}</td>
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

	<!-- Pagination -->
	@if($transactions->hasPages())
		<div style="display:flex; justify-content:center; align-items:center; gap:8px; padding:24px 0;">
			@if($transactions->onFirstPage())
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Previous
				</button>
			@else
				<a href="{{ $transactions->previousPageUrl() }}" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Previous
				</a>
			@endif

			<div style="display:flex; gap:4px;">
				@foreach(range(1, $transactions->lastPage()) as $page)
					@if($page == $transactions->currentPage())
						<span style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#10b981; border-radius:8px; color:white; font-weight:600; font-size:14px;">
							{{ $page }}
						</span>
					@else
						<a href="{{ $transactions->url($page) }}" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
							{{ $page }}
						</a>
					@endif
				@endforeach
			</div>

			@if($transactions->hasMorePages())
				<a href="{{ $transactions->nextPageUrl() }}" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Next
				</a>
			@else
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Next
				</button>
			@endif
		</div>

		<div style="text-align:center; color:#64748b; font-size:13px; padding-bottom:16px;">
			Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
		</div>
	@endif
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
            <form id="transactionForm" method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <input type="hidden" name="type" id="transactionType" value="income">
                
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
                    <input class="form-input" id="descriptionInput" name="description" type="text" placeholder="e.g., Monthly Salary" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span>Amount</span>
                    </label>
                    <input class="form-input" id="amountInput" name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required />
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Category</span>
                    </label>
                    <select class="form-input" id="categorySelect" name="category_id" style="cursor:pointer;" required>
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
                    <input class="form-input" name="transaction_date" type="date" value="{{ date('Y-m-d') }}" required />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeTransactionModal()">Cancel</button>
            <button class="btn btn-primary" id="submitBtn" onclick="submitTransaction()" style="width:auto;min-width:150px;padding:11px 20px;font-size:14px;font-weight:600;">
                <span style="margin-right:6px;">📈</span> Add Income
            </button>
        </div>
    </div>
</div>

<script>
    let currentType = 'income';

    // Categories from database
    const categoriesData = @json($categories);

    function updateCategories() {
        const select = document.getElementById('categorySelect');
        select.innerHTML = '<option value="">Select a category...</option>';

        const typeCategories = categoriesData[currentType] || [];
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
        document.getElementById('transactionType').value = type;
        
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

    function submitTransaction() {
        const form = document.getElementById('transactionForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
    const formData = new FormData(form);
    // ensure the current type is always submitted
    formData.set('type', currentType);
        const submitBtn = document.getElementById('submitBtn');
        const originalContent = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Saving...</span>';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Write totals to localStorage to notify other pages (dashboard) to update
                try {
                    console.log('Transactions: Response data', data);
                    console.log('Transactions: Writing to localStorage', data.totals);
                    localStorage.setItem('transactions:latest', JSON.stringify({ ts: Date.now(), totals: data.totals }));
                    console.log('Transactions: localStorage written successfully');
                    
                    // BroadcastChannel for immediate cross-tab updates
                    if (typeof BroadcastChannel !== 'undefined') {
                        const bc = new BroadcastChannel('transactions-updates');
                        console.log('Transactions: Posting to BroadcastChannel', data.totals);
                        bc.postMessage({ totals: data.totals });
                        bc.close();
                        console.log('Transactions: BroadcastChannel message sent');
                    } else {
                        console.log('Transactions: BroadcastChannel not supported');
                    }
                } catch (e) {
                    console.error('Transactions: Error broadcasting update', e);
                }

                // Close modal and reload transactions page
                closeTransactionModal();
                window.location.reload();
            } else {
                alert('Error adding transaction. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding transaction. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        });
    }

    // Filter transactions
    function filterTransactions() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const typeValue = document.getElementById('typeFilter').value.toLowerCase();
        const categoryValue = document.getElementById('categoryFilter').value;
        
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
        document.getElementById('searchInput').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        filterTransactions(); // Reset display
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
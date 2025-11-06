@extends('layouts.app')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Budget Management</h1>
	<div style="margin-left:auto;">
		<button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;cursor:pointer;" onclick="openBudgetModal()">+ Create Budget</button>
	</div>
</div>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Budgeted</div>
		<div style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalBudgeted) }}</div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Active budgets</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Spent</div>
		<div class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalSpent) }}</div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">{{ number_format($spentPercentage, 1) }}% of budget</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Remaining</div>
		<div class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;">{{ format_currency($totalRemaining) }}</div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">{{ number_format(100 - $spentPercentage, 1) }}% remaining</div>
	</div>
</section>

<section class="stack-section">
	<div class="muted" style="font-weight:700; margin-bottom:8px;">Budget Categories</div>
	
	@forelse($budgets as $budget)
		@php
			// Determine color based on percentage
			if ($budget->percentage > 100) {
				$barColor = '#ef4444'; // Red - over budget
				$textColor = '#ef4444';
				$statusIcon = '🚨';
			} elseif ($budget->percentage > 90) {
				$barColor = '#f59e0b'; // Orange - critical
				$textColor = '#f59e0b';
				$statusIcon = '⚠️';
			} elseif ($budget->percentage > 75) {
				$barColor = '#f59e0b'; // Orange - warning
				$textColor = '#f59e0b';
				$statusIcon = '⚡';
			} else {
				$barColor = '#10b981'; // Green - safe
				$textColor = '#10b981';
				$statusIcon = '✓';
			}
		@endphp
		
		<div class="card" style="padding:20px; margin-bottom:16px;">
			<!-- Header -->
			<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
				<div style="display:flex; align-items:center; gap:10px;">
					<span style="font-size:24px;">{{ $budget->category->icon ?? '💰' }}</span>
					<h3 style="font-weight:600; font-size:18px;">{{ $budget->category->name ?? $budget->name }}</h3>
					<span style="font-size:14px;">{{ $statusIcon }}</span>
				</div>
				<div style="display:flex; gap:8px;">
					<button onclick="editBudget({{ $budget->id }})" style="padding:6px 12px; background:#334155; border:1px solid #475569; border-radius:6px; color:#e2e8f0; cursor:pointer; font-size:13px;">
						Edit
					</button>
					<button onclick="deleteBudget({{ $budget->id }})" style="padding:6px 12px; background:#1e293b; border:1px solid #475569; border-radius:6px; color:#ef4444; cursor:pointer; font-size:13px;">
						Delete
					</button>
				</div>
			</div>
			
			<!-- Amount Display -->
			<div style="margin-bottom:12px;">
				<div style="display:flex; justify-content:space-between; align-items:baseline;">
					<span style="font-size:24px; font-weight:700; color:{{ $textColor }}">
						{{ format_currency($budget->spent) }}
					</span>
					<span style="color:#94a3b8; font-size:14px;">
						of {{ format_currency($budget->amount) }}
					</span>
				</div>
			</div>
			
			<!-- PROGRESS BAR -->
			<div style="width:100%; height:12px; background:#1e293b; border-radius:6px; overflow:hidden; margin-bottom:12px;">
				<div style="
					width: {{ min($budget->percentage, 100) }}%; 
					height: 100%; 
					background: {{ $barColor }};
					transition: width 0.3s ease;
					border-radius: 6px;
				"></div>
			</div>
			
			<!-- Footer Info -->
			<div style="display:flex; justify-content:space-between; font-size:13px; color:#94a3b8;">
				<span style="color:{{ $textColor }}; font-weight:600;">{{ number_format($budget->percentage, 1) }}% used</span>
				<span>
					{{ format_currency(abs($budget->remaining)) }} {{ $budget->remaining >= 0 ? 'remaining' : 'over budget' }} 
					• {{ $budget->days_remaining }} days left
				</span>
			</div>
			
			<!-- Period Badge -->
			<div style="margin-top:10px;">
				<span style="background:#1e293b; padding:4px 10px; border-radius:4px; font-size:11px; text-transform:uppercase; color:#94a3b8;">
					{{ $budget->period }}
				</span>
				<span style="color:#64748b; font-size:12px; margin-left:8px;">
					{{ $budget->start_date->format('M d') }} - {{ $budget->end_date->format('M d, Y') }}
				</span>
			</div>
		</div>
	@empty
		<div class="card" style="padding:40px; text-align:center;">
			<div style="font-size:48px; margin-bottom:12px;">📊</div>
			<div class="muted" style="font-size:16px; margin-bottom:8px;">No budgets yet</div>
			<div style="color:#64748b; font-size:14px; margin-bottom:20px;">
				Create your first budget to start tracking your spending
			</div>
			<button class="card" style="padding:10px 20px; background:#059669; border-color:#059669; cursor:pointer;" onclick="openBudgetModal()">
				+ Create Your First Budget
			</button>
		</div>
	@endforelse
</section>

<!-- Budget Modal -->
<div id="budgetModal" class="modal-overlay">
	<div class="modal-container" style="max-width:500px;">
		<div class="modal-header">
			<h2 id="modalTitle">Create Budget</h2>
			<button class="modal-close" onclick="closeBudgetModal()">×</button>
		</div>
		<div class="modal-body">
			<form id="budgetForm" action="{{ route('budgets.store') }}" method="POST">
				@csrf
				<input type="hidden" id="budgetId" name="budget_id">
				<input type="hidden" id="budgetMethod" name="_method" value="POST">
				
				<div class="form-group">
					<label class="form-label">Budget Name</label>
					<input class="form-input" id="budgetName" name="name" type="text" placeholder="e.g., Monthly Groceries" required />
				</div>
				
				<div class="form-group">
					<label class="form-label">Category</label>
					<select class="form-input" id="budgetCategory" name="category_id" required>
						<option value="">Select a category...</option>
						@foreach($categories as $category)
							<option value="{{ $category->id }}">{{ $category->icon ?? '📁' }} {{ $category->name }}</option>
						@endforeach
					</select>
				</div>
				
				<div class="form-group">
					<label class="form-label">Budget Amount</label>
					<input class="form-input" id="budgetAmount" name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required />
				</div>
				
				<div class="form-group">
					<label class="form-label">Period</label>
					<select class="form-input" id="budgetPeriod" name="period" onchange="updateDateRange()" required>
						<option value="monthly">Monthly</option>
						<option value="weekly">Weekly</option>
						<option value="yearly">Yearly</option>
					</select>
				</div>
				
				<div class="form-row" style="display:flex; gap:12px;">
					<div class="form-group" style="flex:1;">
						<label class="form-label">Start Date</label>
						<input class="form-input" id="budgetStartDate" name="start_date" type="date" required />
					</div>
					<div class="form-group" style="flex:1;">
						<label class="form-label">End Date</label>
						<input class="form-input" id="budgetEndDate" name="end_date" type="date" required />
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-cancel" onclick="closeBudgetModal()">Cancel</button>
			<button class="btn btn-primary" id="submitBudgetBtn" onclick="submitBudget()">
				Create Budget
			</button>
		</div>
	</div>
</div>

<script>
	let isEditMode = false;
	let currentBudgetId = null;

	// Auto-set date range based on period
	function updateDateRange() {
		const period = document.getElementById('budgetPeriod').value;
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
		
		startDateInput.value = startDate.toISOString().split('T')[0];
		endDateInput.value = endDate.toISOString().split('T')[0];
	}

	function openBudgetModal() {
		isEditMode = false;
		currentBudgetId = null;
		document.getElementById('modalTitle').textContent = 'Create Budget';
		document.getElementById('submitBudgetBtn').textContent = 'Create Budget';
		document.getElementById('budgetForm').reset();
		document.getElementById('budgetMethod').value = 'POST';
		document.getElementById('budgetForm').action = '{{ route("budgets.store") }}';
		updateDateRange(); // Set default dates
		document.getElementById('budgetModal').classList.add('active');
		document.body.style.overflow = 'hidden';
	}

	function closeBudgetModal() {
		document.getElementById('budgetModal').classList.remove('active');
		document.body.style.overflow = '';
	}

	function editBudget(id) {
		// Find budget data from the page
		const budgets = @json($budgets);
		const budget = budgets.find(b => b.id === id);
		
		if (!budget) return;
		
		isEditMode = true;
		currentBudgetId = id;
		
		document.getElementById('modalTitle').textContent = 'Edit Budget';
		document.getElementById('submitBudgetBtn').textContent = 'Update Budget';
		document.getElementById('budgetId').value = id;
		document.getElementById('budgetMethod').value = 'PUT';
		document.getElementById('budgetForm').action = `/budgets/${id}`;
		
		document.getElementById('budgetName').value = budget.name;
		document.getElementById('budgetCategory').value = budget.category_id;
		document.getElementById('budgetAmount').value = budget.amount;
		document.getElementById('budgetPeriod').value = budget.period;
		document.getElementById('budgetStartDate').value = budget.start_date;
		document.getElementById('budgetEndDate').value = budget.end_date;
		
		document.getElementById('budgetModal').classList.add('active');
		document.body.style.overflow = 'hidden';
	}

	function submitBudget() {
		const form = document.getElementById('budgetForm');
		
		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}
		
		const formData = new FormData(form);
		const submitBtn = document.getElementById('submitBudgetBtn');
		const originalText = submitBtn.textContent;
		
		submitBtn.disabled = true;
		submitBtn.textContent = 'Saving...';
		
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
				closeBudgetModal();
				window.location.reload();
			} else {
				alert('Error saving budget. Please try again.');
				submitBtn.disabled = false;
				submitBtn.textContent = originalText;
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error saving budget. Please try again.');
			submitBtn.disabled = false;
			submitBtn.textContent = originalText;
		});
	}

	function deleteBudget(id) {
		if (!confirm('Are you sure you want to delete this budget?')) {
			return;
		}
		
		fetch(`/budgets/${id}`, {
			method: 'DELETE',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
	}

	// Close modal on overlay click
	document.getElementById('budgetModal')?.addEventListener('click', (e) => {
		if (e.target.id === 'budgetModal') {
			closeBudgetModal();
		}
	});

	// Close modal on Escape key
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && document.getElementById('budgetModal').classList.contains('active')) {
			closeBudgetModal();
		}
	});
</script>
@endsection

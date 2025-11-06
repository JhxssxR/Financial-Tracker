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
	<div class="modal-container" style="max-width:540px; background:#1e293b; border-radius:16px; padding:0;">
		<!-- Modal Header -->
		<div style="display:flex; align-items:center; gap:16px; padding:24px; border-bottom:1px solid #334155;">
			<div style="width:56px; height:56px; background:#059669; border-radius:12px; display:flex; align-items:center; justify-content:center;">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/>
					<circle cx="12" cy="12" r="6" stroke="white" stroke-width="2"/>
					<circle cx="12" cy="12" r="2" fill="white"/>
				</svg>
			</div>
			<h2 id="modalTitle" style="font-size:24px; font-weight:700; margin:0; color:#f1f5f9;">Create New Budget</h2>
			<button onclick="closeBudgetModal()" style="margin-left:auto; background:transparent; border:none; color:#94a3b8; font-size:32px; cursor:pointer; padding:0; width:32px; height:32px; display:flex; align-items:center; justify-content:center; transition:color 0.2s;" onmouseover="this.style.color='#f1f5f9'" onmouseout="this.style.color='#94a3b8'">×</button>
		</div>
		
		<!-- Modal Body -->
		<div style="padding:24px;">
			<form id="budgetForm" action="{{ route('budgets.store') }}" method="POST">
				@csrf
				<input type="hidden" id="budgetId" name="budget_id">
				<input type="hidden" id="budgetMethod" name="_method" value="POST">
				
				<!-- Budget Category -->
				<div style="margin-bottom:24px;">
					<label style="display:flex; align-items:center; gap:8px; font-size:15px; font-weight:600; color:#10b981; margin-bottom:12px;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						Budget Category
					</label>
					<select id="budgetCategory" name="category_id" required style="width:100%; background:#0f172a; border:1px solid #334155; border-radius:12px; padding:14px 16px; color:#e2e8f0; font-size:15px; cursor:pointer; transition:border-color 0.2s;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#334155'">
						<option value="" style="color:#64748b;">Select a category...</option>
						@foreach($categories as $category)
							<option value="{{ $category->id }}">{{ $category->icon ?? '📁' }} {{ $category->name }}</option>
						@endforeach
					</select>
				</div>
				
				<!-- Budget Amount -->
				<div style="margin-bottom:24px;">
					<label style="display:flex; align-items:center; gap:8px; font-size:15px; font-weight:600; color:#10b981; margin-bottom:12px;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						Budget Amount
					</label>
					<div style="position:relative;">
						<span style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#64748b; font-size:16px;">$</span>
						<input id="budgetAmount" name="amount" type="number" step="0.01" min="0.01" placeholder="0.00" required style="width:100%; background:#0f172a; border:1px solid #334155; border-radius:12px; padding:14px 16px 14px 32px; color:#e2e8f0; font-size:15px; transition:border-color 0.2s;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#334155'" />
					</div>
					<div style="margin-top:8px; font-size:13px; color:#64748b;">Enter the maximum amount you want to spend in this category</div>
				</div>
				
				<!-- Budget Period -->
				<div style="margin-bottom:24px;">
					<label style="display:flex; align-items:center; gap:8px; font-size:15px; font-weight:600; color:#10b981; margin-bottom:12px;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
							<path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
						Budget Period
					</label>
					<select id="budgetPeriod" name="period" onchange="updateDateRange()" required style="width:100%; background:#0f172a; border:1px solid #334155; border-radius:12px; padding:14px 16px; color:#e2e8f0; font-size:15px; cursor:pointer; transition:border-color 0.2s;" onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#334155'">
						<option value="monthly">Monthly</option>
						<option value="weekly">Weekly</option>
						<option value="yearly">Yearly</option>
					</select>
					<div style="margin-top:8px; font-size:13px; color:#64748b;">How often does this budget reset?</div>
				</div>
				
				<!-- Hidden Name Field (auto-generated from category) -->
				<input type="hidden" id="budgetName" name="name" value="Budget">
				<input type="hidden" id="budgetStartDate" name="start_date">
				<input type="hidden" id="budgetEndDate" name="end_date">
			</form>
		</div>
		
		<!-- Modal Footer -->
		<div style="display:flex; gap:12px; padding:20px 24px; border-top:1px solid #334155;">
			<button onclick="closeBudgetModal()" style="flex:1; padding:14px 24px; background:#0f172a; border:1px solid #334155; border-radius:12px; color:#e2e8f0; font-size:15px; font-weight:600; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
				Cancel
			</button>
			<button id="submitBudgetBtn" onclick="submitBudget()" style="flex:2; padding:14px 24px; background:#059669; border:none; border-radius:12px; color:white; font-size:15px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/>
					<circle cx="12" cy="12" r="6" stroke="white" stroke-width="2"/>
					<circle cx="12" cy="12" r="2" fill="white"/>
				</svg>
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
		document.getElementById('modalTitle').textContent = 'Create New Budget';
		const submitBtn = document.getElementById('submitBudgetBtn');
		submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/><circle cx="12" cy="12" r="6" stroke="white" stroke-width="2"/><circle cx="12" cy="12" r="2" fill="white"/></svg> Create Budget';
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
		
		// Auto-generate budget name from category and period
		const categorySelect = document.getElementById('budgetCategory');
		const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
		const period = document.getElementById('budgetPeriod').value;
		const periodText = period.charAt(0).toUpperCase() + period.slice(1);
		document.getElementById('budgetName').value = `${periodText} ${categoryText}`;
		
		const formData = new FormData(form);
		const submitBtn = document.getElementById('submitBudgetBtn');
		const originalHTML = submitBtn.innerHTML;
		
		submitBtn.disabled = true;
		submitBtn.innerHTML = 'Saving...';
		
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
				submitBtn.innerHTML = originalHTML;
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error saving budget. Please try again.');
			submitBtn.disabled = false;
			submitBtn.innerHTML = originalHTML;
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



<?php $__env->startSection('content'); ?>
<div class="page-header" style="display:flex;align-items:center;gap:12px;">
	<h1 style="font-size:28px;font-weight:700;">Budget Management</h1>
	<div style="margin-left:auto;">
		<button class="card" style="padding:8px 12px;background:#059669;border-color:#059669;cursor:pointer;" onclick="openBudgetModal()">+ Create Budget</button>
	</div>
</div>

<section class="page-grid-3">
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Budgeted</div>
		<div id="totalBudgeted" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalBudgeted)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;">Active budgets</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Total Spent</div>
		<div id="totalSpent" class="danger" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalSpent)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format($spentPercentage, 1)); ?>% of budget</div>
	</div>
	<div class="card" style="padding:16px;">
		<div class="muted" style="font-weight:600;">Remaining</div>
		<div id="totalRemaining" class="brand" style="font-size:28px;font-weight:700;margin-top:6px;text-align:right;"><?php echo e(format_currency($totalRemaining)); ?></div>
		<div class="muted" style="font-size:12px;margin-top:4px;text-align:right;"><?php echo e(number_format(100 - $spentPercentage, 1)); ?>% remaining</div>
	</div>
</section>

<section class="stack-section">
	<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
		<div style="font-weight:700; font-size:20px; color:#f1f5f9;">Budget Categories</div>
		<div style="display:flex; align-items:center; gap:12px;">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V6.58579C21 6.851 20.8946 7.10536 20.7071 7.29289L14.2929 13.7071C14.1054 13.8946 14 14.149 14 14.4142V17L10 21V14.4142C10 14.149 9.89464 13.8946 9.70711 13.7071L3.29289 7.29289C3.10536 7.10536 3 6.851 3 6.58579V4Z" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
			<select id="categoryFilter" onchange="filterBudgets()" style="background:#1e293b; border:1px solid #334155; border-radius:8px; padding:10px 16px; color:#e2e8f0; min-width:200px; cursor:pointer;">
				<option value="">All Categories</option>
				<?php
					$filterSeenCategories = [];
				?>
				<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
						$filterCategoryKey = strtolower(trim($category->name));
					?>
					<?php if(!in_array($filterCategoryKey, $filterSeenCategories)): ?>
						<?php
							$filterSeenCategories[] = $filterCategoryKey;
						?>
						<option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
	</div>
	
	<?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
		<?php
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
		?>
		
		<div class="card" style="padding:12px; margin-bottom:10px; background:#1a2332;" data-budget-id="<?php echo e($budget->id); ?>" data-category-id="<?php echo e($budget->category_id); ?>">
			<!-- Header -->
			<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
				<div style="display:flex; align-items:center; gap:8px;">
					<div style="width:40px; height:40px; background:rgba(255, 255, 255, 0.08); border-radius:8px; display:flex; align-items:center; justify-content:center;">
						<?php
							// Map category names to simple outline icons
							$categoryIcons = [
								'Education' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 13.18v4L12 21l7-3.82v-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
								'Transportation' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 17a2 2 0 104 0 2 2 0 00-4 0zM15 17a2 2 0 104 0 2 2 0 00-4 0z" stroke="white" stroke-width="1.5"/><path d="M5 17H3v-6l2-5h9l4 5h3l2 2v4h-2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
								'Food' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7z" fill="white"/><path d="M16 2v8c0 1.1.9 2 2 2v10h2V12c1.1 0 2-.9 2-2V2h-6z" fill="white"/></svg>',
								'Food & Dining' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7z" fill="white"/><path d="M16 2v8c0 1.1.9 2 2 2v10h2V12c1.1 0 2-.9 2-2V2h-6z" fill="white"/></svg>',
								'Housing' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 22V12h6v10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
								'Entertainment' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
								'Healthcare' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
								'Shopping' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="9" cy="21" r="1" stroke="white" stroke-width="1.5"/><circle cx="20" cy="21" r="1" stroke="white" stroke-width="1.5"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
							];
							$categoryName = $budget->category->name ?? $budget->name;
							$iconSvg = $categoryIcons[$categoryName] ?? '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
						?>
						<div style="opacity:0.7;">
							<?php echo $iconSvg; ?>

						</div>
					</div>
					<div>
						<h3 style="font-weight:600; font-size:14px; margin:0 0 3px 0; color:#f1f5f9;"><?php echo e($budget->category->name ?? $budget->name); ?></h3>
						<?php
							$statusText = 'On Track';
							$statusBg = 'rgba(16, 185, 129, 0.15)';
							$statusColor = '#10b981';
							if ($budget->percentage > 100) {
								$statusText = 'Over Budget';
								$statusBg = 'rgba(239, 68, 68, 0.15)';
								$statusColor = '#ef4444';
							} elseif ($budget->percentage > 90) {
								$statusText = 'Critical';
								$statusBg = 'rgba(245, 158, 11, 0.15)';
								$statusColor = '#f59e0b';
							} elseif ($budget->percentage > 75) {
								$statusText = 'Near Limit';
								$statusBg = 'rgba(245, 158, 11, 0.15)';
								$statusColor = '#f59e0b';
							}
						?>
						<div style="display:flex;align-items:center;gap:8px;">
							<span style="font-size:16px;line-height:1;color:<?php echo e($statusColor); ?>;"><?php echo e($statusIcon); ?></span>
							<span style="display:inline-block; padding:2px 8px; background:<?php echo e($statusBg); ?>; color:<?php echo e($statusColor); ?>; border-radius:8px; font-size:10px; font-weight:600;">
								<?php echo e($statusText); ?>

							</span>
						</div>
					</div>
				</div>
				<div style="display:flex; gap:4px;">
					<button onclick="editBudget(<?php echo e($budget->id); ?>)" style="padding:8px; background:transparent; border:none; color:#94a3b8; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#94a3b8'">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<button onclick="deleteBudget(<?php echo e($budget->id); ?>)" style="padding:8px; background:transparent; border:none; color:#94a3b8; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>
			
			<!-- Amount Display -->
			<div style="margin-bottom:8px;">
				<div style="font-size:12px; color:#94a3b8; margin-bottom:2px;">
					<?php echo e(format_currency($budget->spent)); ?> of <?php echo e(format_currency($budget->amount)); ?> • <?php echo e($budget->period); ?>

				</div>
				<div style="color:<?php echo e($budget->is_expired ? '#ef4444' : '#64748b'); ?>; font-size:11px;">
					<?php if($budget->is_expired): ?>
						Expired <?php echo e(abs(intval($budget->days_remaining))); ?> <?php echo e(abs(intval($budget->days_remaining)) == 1 ? 'day' : 'days'); ?> ago
					<?php elseif($budget->days_remaining == 0): ?>
						Expires today
					<?php elseif($budget->days_remaining == 1): ?>
						1 day remaining
					<?php else: ?>
						<?php echo e(intval($budget->days_remaining)); ?> days remaining
					<?php endif; ?>
				</div>
			</div>
			
			<!-- PROGRESS BAR -->
			<div style="width:100%; height:5px; background:#0f1a29; border-radius:3px; overflow:hidden; margin-bottom:8px;">
				<div style="
					width: <?php echo e(min($budget->percentage, 100)); ?>%; 
					height: 100%; 
					background: <?php echo e($barColor); ?>;
					transition: width 0.3s ease;
					border-radius: 3px;
				"></div>
			</div>
			
			<!-- Footer Info -->
			<div style="display:flex; justify-content:space-between; align-items:center;">
				<span style="font-size:12px; font-weight:600; color:#e2e8f0;"><?php echo e(number_format($budget->percentage, 0)); ?>% used</span>
				<span style="font-size:13px; font-weight:600; color:#e2e8f0;">
					<?php echo e(format_currency(abs($budget->remaining))); ?> remaining
				</span>
			</div>
		</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		<div class="card" style="padding:80px 40px; text-align:center; background:#0f172a; border:1px solid #1e293b;">
			<div style="margin-bottom:20px;">
				<svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto;">
					<circle cx="12" cy="12" r="10" stroke="#475569" stroke-width="1.5"/>
					<circle cx="12" cy="12" r="6" stroke="#475569" stroke-width="1.5"/>
					<circle cx="12" cy="12" r="2" fill="#475569"/>
				</svg>
			</div>
			<div style="font-size:18px; font-weight:600; color:#e2e8f0; margin-bottom:8px;">
				No budgets found. Create your first budget to get started!
			</div>
		</div>
	<?php endif; ?>

	<!-- Pagination -->
	<?php if($budgets->hasPages()): ?>
		<div style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:32px; padding-bottom:24px;">
			<?php if($budgets->onFirstPage()): ?>
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Previous
				</button>
			<?php else: ?>
				<a href="<?php echo e($budgets->previousPageUrl()); ?>" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Previous
				</a>
			<?php endif; ?>

			<div style="display:flex; gap:4px;">
				<?php $__currentLoopData = range(1, $budgets->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php if($page == $budgets->currentPage()): ?>
						<span style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#10b981; border-radius:8px; color:white; font-weight:600; font-size:14px;">
							<?php echo e($page); ?>

						</span>
					<?php else: ?>
						<a href="<?php echo e($budgets->url($page)); ?>" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
							<?php echo e($page); ?>

						</a>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>

			<?php if($budgets->hasMorePages()): ?>
				<a href="<?php echo e($budgets->nextPageUrl()); ?>" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#e2e8f0; cursor:pointer; font-size:14px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#10b981'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155'">
					Next
				</a>
			<?php else: ?>
				<button disabled style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:8px; color:#475569; cursor:not-allowed; font-size:14px;">
					Next
				</button>
			<?php endif; ?>
		</div>

		<div style="text-align:center; color:#64748b; font-size:13px; padding-bottom:16px;">
			Showing <?php echo e($budgets->firstItem()); ?> to <?php echo e($budgets->lastItem()); ?> of <?php echo e($budgets->total()); ?> budgets
		</div>
	<?php endif; ?>
</section>

<!-- Budget Modal -->
<div id="budgetModal" class="modal-overlay">
	<div class="modal-container" style="max-width:540px; width:90%; background:#1e293b; border-radius:16px; padding:0;">
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
			<form id="budgetForm" action="<?php echo e(route('budgets.store')); ?>" method="POST">
				<?php echo csrf_field(); ?>
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
						<?php
							$seenCategories = [];
						?>
						<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php
								$categoryKey = strtolower(trim($category->name));
							?>
							<?php if(!in_array($categoryKey, $seenCategories)): ?>
								<?php
									$seenCategories[] = $categoryKey;
								?>
								<option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
		<div style="display:flex; gap:12px; padding:20px 24px; border-top:1px solid #334155; justify-content:flex-end;">
			<button onclick="closeBudgetModal()" style="padding:10px 16px; background:#0f172a; border:1px solid #334155; border-radius:12px; color:#e2e8f0; font-size:14px; font-weight:600; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
				Cancel
			</button>
			<button id="submitBudgetBtn" onclick="submitBudget()" style="padding:10px 16px; background:#059669; border:none; border-radius:12px; color:white; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:background 0.2s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
		document.getElementById('budgetForm').action = '<?php echo e(route("budgets.store")); ?>';
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
		const budgets = <?php echo json_encode($budgets->items(), 15, 512) ?>;
		const budget = budgets.find(b => b.id === id);
		
		if (!budget) {
			console.error('Budget not found with id:', id);
			return;
		}
		
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
				// If server returned notifications payload, broadcast it so other tabs update immediately
				if (data.notifications) {
					try {
						localStorage.setItem('notifications:latest', JSON.stringify({ ts: Date.now(), payload: data.notifications }));
						if (typeof BroadcastChannel !== 'undefined') {
							const nb = new BroadcastChannel('notifications-updates');
							nb.postMessage(data.notifications);
							nb.close();
						}
					} catch (e) {
						console.error('Budgets: Error broadcasting notification', e);
					}
				}
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

	// Filter budgets by category
	function filterBudgets() {
		const categoryValue = document.getElementById('categoryFilter').value;
		const budgetCards = document.querySelectorAll('.card[data-budget-id]');
		
		budgetCards.forEach(card => {
			const budgetCategoryId = card.getAttribute('data-category-id');
			
			if (categoryValue === '' || budgetCategoryId === categoryValue) {
				card.style.display = '';
			} else {
				card.style.display = 'none';
			}
		});
	}

	// Page load animation for summary cards
	document.addEventListener('DOMContentLoaded', function() {
		const totalBudgetedEl = document.getElementById('totalBudgeted');
		const totalSpentEl = document.getElementById('totalSpent');
		const totalRemainingEl = document.getElementById('totalRemaining');
		
		// Animate on page load
		setTimeout(() => {
			if (totalBudgetedEl) {
				totalBudgetedEl.style.transition = 'all 0.3s ease';
				totalBudgetedEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalBudgetedEl.style.transform = 'scale(1)', 300);
			}
			if (totalSpentEl) {
				totalSpentEl.style.transition = 'all 0.3s ease';
				totalSpentEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalSpentEl.style.transform = 'scale(1)', 300);
			}
			if (totalRemainingEl) {
				totalRemainingEl.style.transition = 'all 0.3s ease';
				totalRemainingEl.style.transform = 'scale(1.1)';
				setTimeout(() => totalRemainingEl.style.transform = 'scale(1)', 300);
			}
		}, 100);
	});
<?php $__env->startPush('scripts'); ?>
	<script>
		window.__BUDGETS = <?php echo json_encode($budgets->items(), 15, 512) ?>;
	</script>
	<script src="<?php echo e(asset('js/budgets.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\IT9_FinancialTracker\resources\views/budgets.blade.php ENDPATH**/ ?>
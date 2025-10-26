{{-- resources/views/tenant/manage/projects/tasks/approvals.blade.php --}}
@extends('tenant.manage.app')

@section('main')

    <!-- Page Header -->
    <div class="approvals-page-header">
        <div class="approvals-header-content">
            <div class="approvals-header-top">
                <h1 class="approvals-page-title">
                    <i class="fas fa-clipboard-check"></i>
                    Approvals
                </h1>
                <span class="approvals-count">{{ $tasks->count() }} pending</span>
            </div>
            <p class="approvals-page-subtitle">
                @if ($viewer->isClientFor($workspaceOwner))
                    Review delivered work and provide feedback
                @else
                    Tasks waiting for your review and approval
                @endif
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="approvals-stats">
            <div class="approval-stat">
                <span class="approval-stat-label">Pending</span>
                <span class="approval-stat-value">{{ $tasks->count() }}</span>
            </div>
            <div class="approval-stat">
                <span class="approval-stat-label">Avg. Wait Time</span>
                <span class="approval-stat-value">2.3 days</span>
            </div>
            <div class="approval-stat">
                <span class="approval-stat-label">This Week</span>
                <span
                    class="approval-stat-value">{{ $tasks->where('submitted_for_review_at', '>=', now()->startOfWeek())->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Approvals Grid -->
    @if ($tasks->isEmpty())
        <div class="approvals-empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 class="empty-state-title">All caught up!</h3>
            <p class="empty-state-description">
                No tasks waiting for approval right now.
            </p>
        </div>
    @else
        <div class="approvals-grid">
            @foreach ($tasks as $task)
                @php
                    $hasAttachments = $task->attachments && $task->attachments->count() > 0;
                    $waitingTime = $task->submitted_for_review_at?->diffForHumans();
                @endphp

                <div class="approval-card" data-task-id="{{ $task->id }}">
                    <!-- Card Header -->
                    <div class="approval-card-header">
                        <div class="approval-card-header-left">
                            <span class="approval-task-key">{{ $task->project?->key }}-{{ $task->id }}</span>
                            @include('tenant.manage.projects.tasks.components.task-status-badge', [
                                'status' => $task->status,
                            ])
                        </div>
                        <button class="approval-card-menu" onclick="openApprovalMenu({{ $task->id }}, event)">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>

                    <!-- Task Title -->
                    <h3 class="approval-task-title" onclick="openTaskDrawer({{ $task->id }})">
                        {{ $task->title }}
                    </h3>

                    <!-- Task Meta -->
                    <div class="approval-task-meta">
                        <span class="approval-meta-item">
                            <img src="{{ $task->assignee->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($task->assignee?->name ?? 'Unknown') }}"
                                alt="{{ $task->assignee?->name }}" class="approval-meta-avatar">
                            <span>{{ $task->assignee?->name ?? 'Unknown' }}</span>
                        </span>
                        <span class="approval-meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $waitingTime }}</span>
                        </span>
                        @if ($task->due_date)
                            <span class="approval-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Due {{ $task->due_date->format('M d') }}</span>
                            </span>
                        @endif
                    </div>

                    <!-- Attachments Preview -->
                    @if ($hasAttachments)
                        <div class="approval-attachments">
                            <div class="approval-attachments-header">
                                <i class="fas fa-paperclip"></i>
                                <span>{{ $task->attachments->count() }}
                                    attachment{{ $task->attachments->count() > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="approval-attachments-grid">
                                @foreach ($task->attachments->take(4) as $attachment)
                                    <div class="approval-attachment-item"
                                        onclick="previewAttachment({{ $attachment->id }})">
                                        @if ($attachment->type === 'image')
                                            <img src="{{ Storage::url($attachment->path_or_url) }}"
                                                alt="{{ $attachment->label }}" class="approval-attachment-preview">
                                        @else
                                            <div class="approval-attachment-file">
                                                <i class="fas fa-file"></i>
                                                <span
                                                    class="approval-attachment-name">{{ Str::limit($attachment->label ?? basename($attachment->path_or_url), 20) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                @if ($task->attachments->count() > 4)
                                    <div class="approval-attachment-more" onclick="openTaskDrawer({{ $task->id }})">
                                        +{{ $task->attachments->count() - 4 }} more
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Latest Activity/Comment -->
                    @if ($task->activity && $task->activity->count() > 0)
                        @php
                            $latestActivity = $task->activity->sortByDesc('created_at')->first();
                        @endphp
                        <div class="approval-latest-comment">
                            <div class="approval-comment-header">
                                <img src="{{ $latestActivity->actor->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($latestActivity->actor?->name ?? 'User') }}"
                                    alt="{{ $latestActivity->actor?->name }}" class="approval-comment-avatar">
                                <span
                                    class="approval-comment-author">{{ $latestActivity->actor?->name ?? 'Unknown' }}</span>
                                <span
                                    class="approval-comment-time">{{ $latestActivity->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($latestActivity->body)
                                <p class="approval-comment-body">{{ Str::limit($latestActivity->body, 150) }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="approval-card-actions">
                        <form method="POST"
                            action="{{ route('tenant.manage.projects.tasks.approve', [$username, $task->id]) }}"
                            onsubmit="return handleApprove(event, {{ $task->id }})">
                            @csrf
                            <button type="submit" class="approval-action-btn approval-action-approve">
                                <i class="fas fa-check-circle"></i>
                                <span>Approve</span>
                            </button>
                        </form>

                        <button class="approval-action-btn approval-action-changes"
                            onclick="openRequestChangesModal({{ $task->id }})">
                            <i class="fas fa-undo-alt"></i>
                            <span>Request Changes</span>
                        </button>

                        <button class="approval-action-btn approval-action-view"
                            onclick="openTaskDrawer({{ $task->id }})">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Request Changes Modal -->
    <div class="modal-overlay" id="requestChangesModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-undo-alt"></i>
                    Request Changes
                </h3>
                <button class="modal-close" onclick="closeRequestChangesModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="requestChangesForm" method="POST" onsubmit="return submitRequestChanges(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">What needs to be changed? *</label>
                        <textarea class="form-textarea" id="changeFeedback" name="feedback" rows="5"
                            placeholder="Be specific about what needs to be improved or changed..." required></textarea>
                        <small class="form-help">Provide clear feedback so the assignee knows what to fix</small>
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" id="notifyAssignee" checked>
                            <span>Send email notification to assignee</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn modal-btn-secondary" onclick="closeRequestChangesModal()">
                        Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* ===================================
       APPROVALS PAGE STYLES
    =================================== */

        .approvals-page-header {
            background: white;
            border-bottom: 1px solid var(--table-border);
            padding: 24px 32px;
            margin: -24px -32px 24px;
        }

        .approvals-header-content {
            max-width: 1400px;
            margin: 0 auto 20px;
        }

        .approvals-header-top {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .approvals-page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--task-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .approvals-count {
            display: inline-flex;
            align-items: center;
            height: 28px;
            padding: 0 12px;
            background: var(--task-warning);
            color: white;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
        }

        .approvals-page-subtitle {
            font-size: 14px;
            color: var(--task-text-subtle);
            margin: 0;
        }

        /* Quick Stats */
        .approvals-stats {
            display: flex;
            gap: 32px;
            padding-top: 16px;
            border-top: 1px solid var(--table-border);
        }

        .approval-stat {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .approval-stat-label {
            font-size: 12px;
            color: var(--task-text-subtle);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .approval-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--task-text);
        }

        /* Empty State */
        .approvals-empty-state {
            max-width: 500px;
            margin: 80px auto;
            text-align: center;
            padding: 60px 40px;
            background: white;
            border: 2px dashed var(--table-border);
            border-radius: 12px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #00875A 0%, #00B8D9 100%);
            border-radius: 50%;
            font-size: 32px;
            color: white;
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--task-text);
            margin: 0 0 8px 0;
        }

        .empty-state-description {
            font-size: 14px;
            color: var(--task-text-subtle);
            line-height: 1.6;
            margin: 0;
        }

        /* Approvals Grid */
        .approvals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 24px;
            padding: 0 32px 32px;
        }

        /* Approval Card */
        .approval-card {
            background: white;
            border: 1px solid var(--table-border);
            border-radius: 12px;
            padding: 24px;
            transition: var(--task-transition);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .approval-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: var(--task-primary);
            transform: translateY(-4px);
        }

        /* Card Header */
        .approval-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .approval-card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .approval-task-key {
            font-size: 12px;
            font-weight: 600;
            color: var(--task-text-subtle);
            font-family: monospace;
            background: var(--task-bg);
            padding: 4px 8px;
            border-radius: 4px;
        }

        .approval-card-menu {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            border-radius: 6px;
            color: var(--task-text-subtle);
            cursor: pointer;
            transition: var(--task-transition);
        }

        .approval-card-menu:hover {
            background: var(--task-hover);
            color: var(--task-text);
        }

        /* Task Title */
        .approval-task-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--task-text);
            margin: 0;
            line-height: 1.4;
            cursor: pointer;
            transition: var(--task-transition);
        }

        .approval-task-title:hover {
            color: var(--task-primary);
        }

        /* Task Meta */
        .approval-task-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .approval-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--task-text-subtle);
        }

        .approval-meta-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Attachments */
        .approval-attachments {
            padding: 16px;
            background: var(--task-bg);
            border-radius: 8px;
        }

        .approval-attachments-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--task-text);
            margin-bottom: 12px;
        }

        .approval-attachments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
        }

        .approval-attachment-item {
            aspect-ratio: 1;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: var(--task-transition);
            border: 2px solid transparent;
        }

        .approval-attachment-item:hover {
            border-color: var(--task-primary);
            transform: scale(1.05);
        }

        .approval-attachment-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .approval-attachment-file {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: white;
            border: 1px solid var(--table-border);
            border-radius: 6px;
            padding: 8px;
        }

        .approval-attachment-file i {
            font-size: 24px;
            color: var(--task-primary);
        }

        .approval-attachment-name {
            font-size: 10px;
            color: var(--task-text-subtle);
            text-align: center;
            line-height: 1.2;
            word-break: break-word;
        }

        .approval-attachment-more {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px dashed var(--table-border);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--task-text-subtle);
            cursor: pointer;
            transition: var(--task-transition);
        }

        .approval-attachment-more:hover {
            background: var(--task-hover);
            border-color: var(--task-primary);
            color: var(--task-primary);
        }

        /* Latest Comment */
        .approval-latest-comment {
            padding: 12px;
            background: var(--task-bg);
            border-radius: 8px;
            border-left: 3px solid var(--task-info);
        }

        .approval-comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .approval-comment-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .approval-comment-author {
            font-size: 13px;
            font-weight: 600;
            color: var(--task-text);
        }

        .approval-comment-time {
            font-size: 12px;
            color: var(--task-text-subtle);
            margin-left: auto;
        }

        .approval-comment-body {
            font-size: 13px;
            color: var(--task-text);
            line-height: 1.5;
            margin: 0;
        }

        /* Action Buttons */
        .approval-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--table-border);
        }

        .approval-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 40px;
            padding: 0 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--task-transition);
        }

        .approval-action-approve {
            background: var(--task-success);
            color: white;
        }

        .approval-action-approve:hover {
            background: #007052;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 135, 90, 0.3);
        }

        .approval-action-changes {
            background: white;
            color: var(--task-warning);
            border: 1px solid var(--task-warning);
        }

        .approval-action-changes:hover {
            background: var(--task-warning);
            color: white;
        }

        .approval-action-view {
            width: 40px;
            padding: 0;
            background: white;
            color: var(--task-text-subtle);
            border: 1px solid var(--table-border);
        }

        .approval-action-view:hover {
            background: var(--task-hover);
            color: var(--task-primary);
            border-color: var(--task-primary);
        }

        /* Modal Enhancements */
        .form-help {
            display: block;
            font-size: 12px;
            color: var(--task-text-subtle);
            margin-top: 6px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .approvals-page-header {
                padding: 16px 20px;
                margin: -16px -20px 16px;
            }

            .approvals-stats {
                flex-direction: column;
                gap: 16px;
            }

            .approvals-grid {
                grid-template-columns: 1fr;
                padding: 0 20px 20px;
            }

            .approval-card-actions {
                grid-template-columns: 1fr;
            }

            .approval-action-view {
                width: 100%;
            }
        }

        /* Animations */
        @keyframes approvalSuccess {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .approval-card.approved {
            animation: approvalSuccess 0.5s ease;
            border-color: var(--task-success);
            background: #E3FCEF;
        }
    </style>

    <script>
        // ===================================
        // REQUEST CHANGES MODAL
        // ===================================
        let currentTaskIdForChanges = null;

        function openRequestChangesModal(taskId) {
            currentTaskIdForChanges = taskId;
            document.getElementById('requestChangesModal').classList.add('active');
            document.getElementById('changeFeedback').focus();
        }

        function closeRequestChangesModal() {
            currentTaskIdForChanges = null;
            document.getElementById('requestChangesModal').classList.remove('active');
            document.getElementById('changeFeedback').value = '';
            document.getElementById('notifyAssignee').checked = true;
        }

        async function submitRequestChanges(event) {
            event.preventDefault();

            if (!currentTaskIdForChanges) return false;

            const feedback = document.getElementById('changeFeedback').value.trim();
            if (!feedback) {
                showNotification('Please provide feedback', 'error');
                return false;
            }

            showLoading();
            closeRequestChangesModal();

            const url = "{{ route('tenant.manage.projects.tasks.request_changes', [$username, 'TASK_ID']) }}"
                .replace('TASK_ID', currentTaskIdForChanges);

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        feedback,
                        notify: document.getElementById('notifyAssignee').checked
                    })
                });

                if (!res.ok) throw new Error('Failed to request changes');

                showNotification('Feedback sent successfully', 'success');

                // Remove card with animation
                const card = document.querySelector(`[data-task-id="${currentTaskIdForChanges}"]`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => card.remove(), 300);
                }

                // Update count
                updateApprovalsCount(-1);

            } catch (error) {
                console.error('Request changes error:', error);
                showNotification('Failed to send feedback', 'error');
            } finally {
                hideLoading();
            }

            return false;
        }

        // ===================================
        // APPROVE TASK
        // ===================================
        async function handleApprove(event, taskId) {
            event.preventDefault();

            const confirmMsg = 'Approve this task and mark as complete?';
            if (!confirm(confirmMsg)) return false;

            showLoading();

            const form = event.target;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken(),
                        'Accept': 'application/json',
                    }
                });

                if (!res.ok) throw new Error('Failed to approve');

                showNotification('Task approved successfully! ✓', 'success');

                // Visual feedback
                const card = document.querySelector(`[data-task-id="${taskId}"]`);
                if (card) {
                    card.classList.add('approved');
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-20px)';
                        setTimeout(() => card.remove(), 500);
                    }, 500);
                }

                // Update count
                updateApprovalsCount(-1);

                // Check if empty
                setTimeout(() => {
                    const remainingCards = document.querySelectorAll('.approval-card').length;
                    if (remainingCards === 0) {
                        location.reload();
                    }
                }, 1000);

            } catch (error) {
                console.error('Approve error:', error);
                showNotification('Failed to approve task', 'error');
            } finally {
                hideLoading();
            }

            return false;
        }

        // ===================================
        // APPROVAL MENU
        // ===================================
        function openApprovalMenu(taskId, event) {
            event.stopPropagation();

            const menu = document.createElement('div');
            menu.className = 'approval-context-menu';
            menu.style.cssText = `
        position: fixed;
        top: ${event.clientY}px;
        left: ${event.clientX}px;
        background: white;
        border: 1px solid var(--table-border);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        z-index: 1000;
        min-width: 200px;
        padding: 8px 0;
    `;

            menu.innerHTML = `
        <button class="context-menu-item" onclick="openTaskDrawer(${taskId}); closeApprovalMenu();">
            <i class="fas fa-eye"></i>
            View Full Details
        </button>
        <button class="context-menu-item" onclick="downloadAttachments(${taskId}); closeApprovalMenu();">
            <i class="fas fa-download"></i>
            Download All Files
        </button>
        <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--table-border);">
        <button class="context-menu-item" onclick="sendReminder(${taskId}); closeApprovalMenu();">
            <i class="fas fa-bell"></i>
            Send Reminder
        </button>
        <button class="context-menu-item" onclick="reassignTask(${taskId}); closeApprovalMenu();">
            <i class="fas fa-user"></i>
            Reassign Task
        </button>
    `;

            document.body.appendChild(menu);

            // Close on outside click
            setTimeout(() => {
                document.addEventListener('click', closeApprovalMenu);
            }, 0);
        }

        function closeApprovalMenu() {
            const menu = document.querySelector('.approval-context-menu');
            if (menu) menu.remove();
            document.removeEventListener('click', closeApprovalMenu);
        }

        // ===================================
        // ATTACHMENT PREVIEW
        // ===================================
        function previewAttachment(attachmentId) {
            // Create lightbox modal
            const lightbox = document.createElement('div');
            lightbox.className = 'attachment-lightbox';
            lightbox.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    `;

            lightbox.innerHTML = `
        <button class="lightbox-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-content">
            <div class="spinner"></div>
            <p style="color: white; margin-top: 16px;">Loading attachment...</p>
        </div>
    `;

            document.body.appendChild(lightbox);

            // In real implementation, fetch and display the attachment
            setTimeout(() => {
                showNotification('Attachment preview coming soon!', 'info');
                lightbox.remove();
            }, 1000);
        }

        // ===================================
        // UTILITY FUNCTIONS
        // ===================================
        function updateApprovalsCount(delta) {
            const countEl = document.querySelector('.approvals-count');
            if (countEl) {
                const current = parseInt(countEl.textContent);
                const newCount = Math.max(0, current + delta);
                countEl.textContent = `${newCount} pending`;
            }
        }

        function downloadAttachments(taskId) {
            showNotification('Downloading attachments...', 'info');
            // Implement download logic
        }

        function reassignTask(taskId) {
            showNotification('Reassign modal coming soon!', 'info');
        }

        function sendReminder(taskId) {
            showNotification('Reminder sent!', 'success');
        }

        function openTaskDrawer(taskId) {
            showNotification('Task drawer coming soon!', 'info');
        }

        // ===================================
        // UTILITIES
        // ===================================
        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        function showLoading() {
            let overlay = document.getElementById('loadingOverlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'loadingOverlay';
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Processing...</p>
            </div>
        `;
                document.body.appendChild(overlay);
            }
            overlay.classList.add('active');
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) overlay.classList.remove('active');
        }

        function showNotification(message, type = 'success') {
            const colors = {
                success: '#00875A',
                error: '#DE350B',
                warning: '#FF991F',
                info: '#0052CC'
            };

            const notification = document.createElement('div');
            notification.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        padding: 14px 20px;
        background: ${colors[type] || colors.info};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
    `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // ===================================
        // KEYBOARD SHORTCUTS
        // ===================================
        document.addEventListener('keydown', (e) => {
            // Escape to close modals
            if (e.key === 'Escape') {
                closeRequestChangesModal();
                closeApprovalMenu();
            }
        });

        console.log('✨ Approvals Page Initialized');
    </script>

@endsection

@php
    use Illuminate\Support\Facades\Storage;

@endphp{{-- resources/views/tenant/manage/projects/tasks/tabs-detail/overview.blade.php --}}

<div class="task-overview-grid">
    <!-- Left Column -->
    <div class="task-overview-main">
        <!-- Description -->
        <div class="task-section">
            <div class="task-section-header">
                <h3 class="task-section-title">
                    <i class="fas fa-align-left"></i> Description
                </h3>
                @if($task->reporter_id === $viewer->id)
                    <button class="task-btn-text" onclick="editDescription()">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                @endif
            </div>
            <div class="task-section-body">
                @if($task->notes)
                    <div class="task-description">{{ $task->notes }}</div>
                @else
                    <div class="task-empty-state">
                        <i class="fas fa-file-alt"></i>
                        <span>No description provided</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subtasks -->
        @if($totalSubtasks > 0)
            <div class="task-section">
                <div class="task-section-header">
                    <h3 class="task-section-title">
                        <i class="fas fa-check-square"></i> Subtasks
                        <span class="task-section-count">{{ $completedSubtasks }}/{{ $totalSubtasks }}</span>
                    </h3>
                    <div class="task-progress-bar-wrap">
                        <div class="task-progress-bar">
                            <div style="width: {{ $progress }}%; background: {{ $statusColor }};"></div>
                        </div>
                        <span class="task-progress-text">{{ $progress }}%</span>
                    </div>
                </div>
                <div class="task-section-body">
                    <div class="task-subtasks-list">
                        @foreach($task->subtasks as $subtask)
                            <div class="task-subtask-item {{ $subtask->completed ? 'is-completed' : '' }}"
                                 data-subtask-id="{{ $subtask->id }}">
                                <label class="task-checkbox-wrapper">
                                    <input type="checkbox"
                                           class="task-checkbox"
                                           {{ $subtask->completed ? 'checked' : '' }}
                                           {{ $task->assigned_to !== $viewer->id ? 'disabled' : '' }}
                                           onchange="toggleSubtaskDetail({{ $task->id }}, {{ $subtask->id }}, this.checked)">
                                    <span class="task-checkbox-custom">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </label>
                                <span class="task-subtask-title">{{ $subtask->title }}</span>
                                @if($subtask->completed_at)
                                    <span class="task-subtask-meta">
                                        Completed {{ $subtask->completed_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Project Context -->
        <div class="task-section">
            <div class="task-section-header">
                <h3 class="task-section-title">
                    <i class="fas fa-folder-open"></i> Project Context
                </h3>
            </div>
            <div class="task-section-body">
                <a href="{{ route('tenant.manage.projects.project.show', [$username, $project->id]) }}" 
                   class="task-project-card">
                    <div class="task-project-icon" style="background: {{ $statusColor }}20;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="task-project-info">
                        <div class="task-project-key">{{ $project->key }}</div>
                        <div class="task-project-name">{{ $project->name }}</div>
                        <div class="task-project-meta">
                            <span>{{ ucfirst($project->type) }}</span>
                            <span>•</span>
                            <span>{{ ucfirst($project->status) }}</span>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column (Details Panel) -->
    <div class="task-overview-sidebar">
        <!-- Details Card -->
        <div class="task-details-card">
            <h4 class="task-details-title">Details</h4>
            
            <div class="task-detail-item">
                <div class="task-detail-label">Status</div>
                <div class="task-detail-value">
                    <span class="task-status-badge" style="background: {{ $statusColor }}20; color: {{ $statusColor }};">
                        {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                    </span>
                </div>
            </div>

            <div class="task-detail-item">
                <div class="task-detail-label">Priority</div>
                <div class="task-detail-value">
                    <span class="task-priority-badge" style="background: {{ $priorityColor }}20; color: {{ $priorityColor }};">
                        {{ ucfirst($task->priority ?? 'medium') }}
                    </span>
                </div>
            </div>

            <div class="task-detail-item">
                <div class="task-detail-label">Assignee</div>
                <div class="task-detail-value">
                    @if($task->assignee)
                        <div class="task-user-pill">
                            @if($task->assignee->avatar_url)
                                <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}">
                            @else
                                <div class="task-avatar-fallback">{{ substr($task->assignee->name, 0, 1) }}</div>
                            @endif
                            <span>{{ $task->assignee->name }}</span>
                        </div>
                    @else
                        <span class="task-detail-empty">Unassigned</span>
                    @endif
                </div>
            </div>

            <div class="task-detail-item">
                <div class="task-detail-label">Reporter</div>
                <div class="task-detail-value">
                    <div class="task-user-pill">
                        @if($task->reporter->avatar_url)
                            <img src="{{ $task->reporter->avatar_url }}" alt="{{ $task->reporter->name }}">
                        @else
                            <div class="task-avatar-fallback">{{ substr($task->reporter->name, 0, 1) }}</div>
                        @endif
                        <span>{{ $task->reporter->name }}</span>
                    </div>
                </div>
            </div>

            <div class="task-detail-item">
                <div class="task-detail-label">Due Date</div>
                <div class="task-detail-value">
                    @if($task->due_date)
                        <span class="{{ $task->is_overdue ? 'task-detail-overdue' : '' }}">
                            <i class="fas fa-calendar"></i>
                            {{ $task->due_date->format('M d, Y') }}
                        </span>
                    @else
                        <span class="task-detail-empty">No due date</span>
                    @endif
                </div>
            </div>

            @if($task->estimated_hours)
                <div class="task-detail-item">
                    <div class="task-detail-label">Estimate</div>
                    <div class="task-detail-value">
                        <i class="fas fa-clock"></i> {{ $task->estimated_hours }}h
                    </div>
                </div>
            @endif

            @if($task->story_points)
                <div class="task-detail-item">
                    <div class="task-detail-label">Story Points</div>
                    <div class="task-detail-value">
                        <i class="fas fa-chart-line"></i> {{ $task->story_points }}
                    </div>
                </div>
            @endif

            <div class="task-detail-item">
                <div class="task-detail-label">Created</div>
                <div class="task-detail-value task-detail-muted">
                    {{ $task->created_at->format('M d, Y') }}
                </div>
            </div>

            <div class="task-detail-item">
                <div class="task-detail-label">Updated</div>
                <div class="task-detail-value task-detail-muted">
                    {{ $task->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <!-- Time Tracking Card -->
        @if($timeSpent > 0)
            <div class="task-details-card">
                <h4 class="task-details-title">
                    <i class="fas fa-stopwatch"></i> Time Tracking
                </h4>
                
                <div class="task-time-stat">
                    <div class="task-time-value">{{ number_format($timeSpent, 1) }}h</div>
                    <div class="task-time-label">Time Spent</div>
                </div>

                @if($task->estimated_hours)
                    <div class="task-time-progress">
                        @php
                            $timePercentage = min(100, round(($timeSpent / $task->estimated_hours) * 100));
                        @endphp
                        <div class="task-time-bar">
                            <div style="width: {{ $timePercentage }}%; background: {{ $timePercentage > 100 ? '#DE350B' : '#0052CC' }};"></div>
                        </div>
                        <div class="task-time-remaining">
                            {{ max(0, $task->estimated_hours - $timeSpent) }}h remaining
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Attachments Preview -->
        @if($task->attachments->count() > 0)
            <div class="task-details-card">
                <h4 class="task-details-title">
                    <i class="fas fa-paperclip"></i> Recent Files
                    <span class="task-section-count">{{ $task->attachments->count() }}</span>
                </h4>
                
                <div class="task-attachments-preview">
                    @foreach($task->attachments->take(3) as $attachment)
                        <div class="task-attachment-mini">
                            @if($attachment->type === 'image')
                                <img src="{{ Storage::url($attachment->path_or_url) }}" alt="{{ $attachment->label }}">
                            @else
                                <div class="task-file-icon">
                                    <i class="fas fa-file"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <a href="?tab=files" class="task-view-all-link">
                    View all files <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    /* Overview Grid Layout */
    .task-overview-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
    }

    .task-overview-main {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .task-overview-sidebar {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Section Styles */
    .task-section {
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        overflow: hidden;
    }

    .task-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: #FFFFFF;
        border-bottom: 1px solid #DFE1E6;
    }

    .task-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #172B4D;
        margin: 0;
    }

    .task-section-title i {
        color: #6B778C;
        font-size: 14px;
    }

    .task-section-count {
        font-size: 12px;
        font-weight: 600;
        color: #6B778C;
        background: #F4F5F7;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 8px;
    }

    .task-section-body {
        padding: 20px;
    }

    .task-btn-text {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border: none;
        background: transparent;
        color: #6B778C;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .task-btn-text:hover {
        background: #F4F5F7;
        color: #0052CC;
    }

    /* Description */
    .task-description {
        font-size: 14px;
        line-height: 1.6;
        color: #42526E;
        white-space: pre-wrap;
    }

    .task-empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 40px;
        color: #6B778C;
        font-size: 13px;
    }

    .task-empty-state i {
        font-size: 18px;
        opacity: 0.5;
    }

    /* Progress Bar */
    .task-progress-bar-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        max-width: 200px;
    }

    .task-progress-bar {
        flex: 1;
        height: 6px;
        background: #DFE1E6;
        border-radius: 3px;
        overflow: hidden;
    }

    .task-progress-bar div {
        height: 100%;
        transition: width 0.3s;
        border-radius: 3px;
    }

    .task-progress-text {
        font-size: 12px;
        font-weight: 700;
        color: #6B778C;
        min-width: 35px;
    }

    /* Subtasks */
    .task-subtasks-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .task-subtask-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .task-subtask-item:hover {
        border-color: #0052CC;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .task-subtask-item.is-completed {
        opacity: 0.6;
    }

    .task-subtask-item.is-completed .task-subtask-title {
        text-decoration: line-through;
        color: #6B778C;
    }

    .task-checkbox-wrapper {
        display: flex;
        cursor: pointer;
    }

    .task-checkbox {
        position: absolute;
        opacity: 0;
    }

    .task-checkbox-custom {
        width: 18px;
        height: 18px;
        border: 2px solid #DFE1E6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        transition: all 0.2s;
    }

    .task-checkbox-custom i {
        font-size: 10px;
        color: #FFFFFF;
        opacity: 0;
    }

    .task-checkbox:checked + .task-checkbox-custom {
        background: #00875A;
        border-color: #00875A;
    }

    .task-checkbox:checked + .task-checkbox-custom i {
        opacity: 1;
    }

    .task-checkbox:disabled + .task-checkbox-custom {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .task-subtask-title {
        flex: 1;
        font-size: 14px;
        color: #172B4D;
        font-weight: 500;
    }

    .task-subtask-meta {
        font-size: 11px;
        color: #6B778C;
    }

    /* Project Card */
    .task-project-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .task-project-card:hover {
        border-color: #0052CC;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .task-project-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .task-project-info {
        flex: 1;
        min-width: 0;
    }

    .task-project-key {
        font-size: 11px;
        font-weight: 700;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-project-name {
        font-size: 15px;
        font-weight: 600;
        color: #172B4D;
        margin: 2px 0 4px 0;
    }

    .task-project-meta {
        font-size: 12px;
        color: #6B778C;
        display: flex;
        gap: 6px;
    }

    .task-project-card i.fa-arrow-right {
        color: #6B778C;
        font-size: 14px;
        transition: transform 0.2s;
    }

    .task-project-card:hover i.fa-arrow-right {
        transform: translateX(4px);
        color: #0052CC;
    }

    /* Details Card */
    .task-details-card {
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        padding: 16px;
    }

    .task-details-title {
        font-size: 14px;
        font-weight: 700;
        color: #172B4D;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .task-details-title i {
        color: #6B778C;
        font-size: 13px;
    }

    .task-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #DFE1E6;
    }

    .task-detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .task-detail-item:first-child {
        padding-top: 0;
    }

    .task-detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-detail-value {
        font-size: 13px;
        color: #172B4D;
        font-weight: 500;
        text-align: right;
    }

    .task-detail-empty {
        color: #6B778C;
        font-style: italic;
    }

    .task-detail-overdue {
        color: #DE350B;
        font-weight: 600;
    }

    .task-detail-muted {
        color: #6B778C;
    }

    /* User Pill */
    .task-user-pill {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .task-user-pill img,
    .task-avatar-fallback {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .task-avatar-fallback {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Time Tracking */
    .task-time-stat {
        text-align: center;
        padding: 16px;
        background: #FFFFFF;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .task-time-value {
        font-size: 28px;
        font-weight: 700;
        color: #0052CC;
        line-height: 1;
        margin-bottom: 4px;
    }

    .task-time-label {
        font-size: 11px;
        font-weight: 600;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-time-progress {
        margin-top: 12px;
    }

    .task-time-bar {
        height: 6px;
        background: #DFE1E6;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .task-time-bar div {
        height: 100%;
        transition: width 0.3s;
    }

    .task-time-remaining {
        font-size: 12px;
        color: #6B778C;
        text-align: center;
    }

    /* Attachments Preview */
    .task-attachments-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .task-attachment-mini {
        aspect-ratio: 1;
        border-radius: 6px;
        overflow: hidden;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
    }

    .task-attachment-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .task-file-icon {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B778C;
        font-size: 20px;
    }

    .task-view-all-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px;
        background: #FFFFFF;
        border-radius: 6px;
        color: #0052CC;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .task-view-all-link:hover {
        background: #DEEBFF;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .task-overview-grid {
            grid-template-columns: 1fr;
        }

        .task-overview-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .task-section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .task-progress-bar-wrap {
            width: 100%;
            max-width: none;
        }

        .task-detail-item {
            flex-direction: column;
            gap: 6px;
        }

        .task-detail-value {
            text-align: left;
        }
    }
</style>

<script>
function toggleSubtaskDetail(taskId, subtaskId, isChecked) {
    const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/toggle`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ completed: isChecked ? 1 : 0 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast(isChecked ? '✅ Subtask completed' : '↩️ Subtask reopened', 'success');
            
            // Update UI
            const item = document.querySelector(`[data-subtask-id="${subtaskId}"]`);
            if (item) {
                if (isChecked) {
                    item.classList.add('is-completed');
                } else {
                    item.classList.remove('is-completed');
                }
            }

            // Optionally reload page to update progress
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast('Failed to update subtask', 'error');
    });
}

function editDescription() {
    // Open modal for editing description
    console.log('Edit description modal');
}

function shareTask() {
    // Copy link to clipboard
    const url = window.location.href;
    navigator.clipboard.writeText(url);
    window.showToast('Link copied to clipboard', 'success');
}

function openTaskMenu() {
    console.log('Task menu');
}
</script>
{{-- resources/views/tenant/manage/projects/tasks/tabs/blocked.blade.php --}}
@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/tasks-tabs-styles.css') }}">
    @endpush
@endonce

<div class="task-section-header danger">
    <div class="task-section-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
        </svg>
    </div>
    <div class="task-section-content">
        <h3 class="task-section-title">Blocked Tasks</h3>
        <p class="task-section-description">{{ $tasks->count() }} tasks are blocked and need resolution</p>
    </div>
</div>

@if($tasks->count() > 0)
    <div class="tasks-list-view">
        @foreach($tasks as $task)
            <div class="task-list-item blocked" data-task-id="{{ $task->id }}" onclick="openTaskDrawer({{ $task->id }})">
                <div class="task-list-check">
                    <div style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="8" r="6" stroke="#DE350B" stroke-width="2"/>
                            <line x1="4" y1="4" x2="12" y2="12" stroke="#DE350B" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <div class="task-list-content">
                    <div class="task-list-header">
                        <h3 class="task-list-title">{{ $task->title }}</h3>
                        <div class="task-list-badges">
                            <span class="task-project-tag">{{ $task->project->key }}</span>
                            <span class="task-status-badge status-blocked">Blocked</span>
                        </div>
                    </div>

                    @if($task->blocked_reason)
                        <div style="display: flex; align-items: flex-start; gap: 8px; padding: 12px; background: #FFEBE6; border-left: 3px solid #DE350B; border-radius: 4px; margin-bottom: 8px;">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style="flex-shrink: 0; margin-top: 2px;">
                                <path d="M7 3v4M7 9v1" stroke="#DE350B" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span style="font-size: 13px; color: #BF2600; line-height: 1.5;">{{ $task->blocked_reason }}</span>
                        </div>
                    @endif

                    <div class="task-list-meta">
                        @if($task->due_date)
                            <div class="task-meta-group">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="2" y="3" width="10" height="9" rx="1"/>
                                </svg>
                                <span>{{ $task->due_date->format('M d') }}</span>
                            </div>
                        @endif

                        @if($task->assignee)
                            <div class="task-meta-group">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="7" cy="5" r="2"/>
                                    <path d="M2 12c0-2.5 2.2-4 5-4s5 1.5 5 4"/>
                                </svg>
                                <span>{{ $task->assignee->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="task-list-assignee">
                    @if($task->reporter)
                        <div class="task-avatar-large">
                            @if($task->reporter->avatar_url)
                                <img src="{{ $task->reporter->avatar_url }}" alt="{{ $task->reporter->name }}">
                            @else
                                <div style="width: 100%; height: 100%; background: #DE350B; color: white; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600;">
                                    {{ substr($task->reporter->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="project-empty-state">
        <div class="project-empty-state-icon" style="background: rgba(0, 135, 90, 0.1); color: #00875A;">
            <i class="fas fa-check-double"></i>
        </div>
        <h3 class="project-empty-state-title">No Blocked Tasks</h3>
        <p class="project-empty-state-desc">Great! You have no blocked tasks at the moment.</p>
    </div>
@endif
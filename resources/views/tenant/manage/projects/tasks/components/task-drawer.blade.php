<div class="task-drawer"
     style="position:fixed;right:0;top:0;height:100vh;width:400px;max-width:90%;
            background:var(--card);border-left:1px solid var(--border);
            box-shadow:-4px 0 24px rgba(0,0,0,.2);z-index:9999;
            display:flex;flex-direction:column;">

    <!-- Header -->
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
        <div style="min-width:0;">
            <div style="font-size:var(--fs-micro);font-family:monospace;color:var(--text-muted);margin-bottom:4px;">
                {{ $task->project?->key ?? 'TASK' }}-{{ $task->id }}
            </div>
            <div style="font-size:var(--fs-body);font-weight:var(--fw-semibold);color:var(--text-heading);line-height:1.4;word-break:break-word;">
                {{ $task->title }}
            </div>

            <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                @include('tenant.manage.projects.tasks.components.task-status-badge', ['status' => $task->status])

                <div style="font-size:var(--fs-subtle);color:var(--text-muted);display:flex;align-items:center;gap:4px;">
                    <i class="fas fa-calendar"></i>
                    <span>Due {{ $task->due_date?->format('M d, Y') ?? '—' }}</span>
                </div>

                @if($task->assignee)
                <div style="display:flex;align-items:center;gap:6px;">
                    <img src="{{ $task->assignee->avatar_url ?? asset('images/avatar-fallback.png') }}"
                         alt="{{ $task->assignee->name }}"
                         style="width:24px;height:24px;border-radius:50%;border:2px solid var(--bg);object-fit:cover;">
                    <span style="font-size:var(--fs-subtle);color:var(--text-body);font-weight:var(--fw-medium);">
                        {{ $task->assignee->name }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <button onclick="closeTaskDrawer()" class="project-icon-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Body scroll -->
    <div style="flex:1 1 auto;overflow-y:auto;padding:16px 20px;">

        {{-- Description / notes --}}
        @if($task->notes)
            <div style="margin-bottom:20px;">
                <div style="font-size:var(--fs-subtle);color:var(--text-muted);font-weight:var(--fw-semibold);margin-bottom:6px;">
                    Description
                </div>
                <div style="font-size:var(--fs-body);color:var(--text-body);line-height:1.5;white-space:pre-line;">
                    {{ $task->notes }}
                </div>
            </div>
        @endif

        {{-- Subtasks checklist --}}
        @if($task->subtasks->count())
            <div style="margin-bottom:20px;">
                <div style="font-size:var(--fs-subtle);color:var(--text-muted);font-weight:var(--fw-semibold);margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-list-check"></i>
                    <span>Subtasks ({{ $task->subtasks->where('completed',true)->count() }}/{{ $task->subtasks->count() }})</span>
                </div>

                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                    @foreach($task->subtasks as $sub)
                        <li style="display:flex;align-items:flex-start;gap:8px;">
                            <input type="checkbox" disabled @checked($sub->completed)
                                   style="margin-top:2px;">
                            <span style="font-size:var(--fs-body);color:var(--text-body);line-height:1.4;">
                                {{ $sub->title }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Attachments --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:var(--fs-subtle);color:var(--text-muted);font-weight:var(--fw-semibold);margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-paperclip"></i>
                <span>Attachments ({{ $task->attachments->count() }})</span>
            </div>

            @if($task->attachments->count() === 0)
                <div style="font-size:var(--fs-subtle);color:var(--text-muted);">No files yet</div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($task->attachments as $att)
                        <div style="display:flex;align-items:flex-start;gap:8px;font-size:var(--fs-body);color:var(--text-body);">
                            <div style="
                                width:32px;height:32px;border-radius:8px;
                                background:var(--bg);border:1px solid var(--border);
                                display:flex;align-items:center;justify-content:center;
                                color:var(--text-muted);flex-shrink:0;
                            ">
                                <i class="fas {{ $att->type === 'image' ? 'fa-image' : 'fa-file' }}"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:var(--fw-medium);color:var(--text-heading);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $att->label ?? basename($att->path_or_url) }}
                                </div>
                                <div style="font-size:var(--fs-subtle);color:var(--text-muted);">
                                    Uploaded by {{ $att->uploader?->name ?? 'Unknown' }}
                                    • {{ $att->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Activity feed --}}
        <div style="margin-bottom:20px;">
            <div style="font-size:var(--fs-subtle);color:var(--text-muted);font-weight:var(--fw-semibold);margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-history"></i>
                <span>Activity</span>
            </div>

            @if($task->activity->count() === 0)
                <div style="font-size:var(--fs-subtle);color:var(--text-muted);">No activity yet</div>
            @else
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($task->activity as $log)
                        <div style="display:flex;gap:8px;align-items:flex-start;">
                            <div style="
                                width:32px;height:32px;border-radius:50%;
                                background:var(--bg);border:1px solid var(--border);
                                display:flex;align-items:center;justify-content:center;
                                font-size:12px;color:var(--text-muted);flex-shrink:0;
                                overflow:hidden;
                            ">
                                @if($log->actor && $log->actor->avatar_url)
                                    <img src="{{ $log->actor->avatar_url }}"
                                         alt="{{ $log->actor->name }}"
                                         style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:var(--fs-body);color:var(--text-body);line-height:1.4;">
                                    <strong>{{ $log->actor?->name ?? 'System' }}</strong>
                                    <span style="color:var(--text-muted);font-weight:var(--fw-medium);">
                                        {{ $log->type }}
                                    </span>
                                </div>
                                @if($log->body)
                                    <div style="font-size:var(--fs-body);color:var(--text-heading);white-space:pre-line;">
                                        {{ $log->body }}
                                    </div>
                                @endif
                                <div style="font-size:var(--fs-micro);color:var(--text-muted);margin-top:2px;">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Footer Actions -->
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;flex-wrap:wrap;gap:8px;">
        {{-- Assignee quick actions --}}
        @if($task->assigned_to === $viewer->id)
            @if(!in_array($task->status, ['review','done']))
                <button class="project-btn project-btn-primary" onclick="openSubmitWorkModal({{ $task->id }})">
                    <i class="fas fa-paper-plane"></i>
                    <span>Submit Work</span>
                </button>
            @endif
            <button class="project-btn project-btn-secondary" onclick="openPostponeModal({{ $task->id }})">
                <i class="fas fa-clock"></i>
                <span>Postpone</span>
            </button>
            <button class="project-btn project-btn-secondary" onclick="openBlockedModal({{ $task->id }})">
                <i class="fas fa-ban"></i>
                <span>Blocked</span>
            </button>
        @endif

        {{-- Approver actions --}}
        @if($viewer->canApproveTasksFor($workspaceOwner) && $task->status === 'review')
            <form method="POST"
                  action="{{ route('tenant.manage.projects.tasks.approve', [$workspaceOwner->username, $task->id]) }}">
                @csrf
                <button class="project-btn project-btn-primary">
                    <i class="fas fa-check-circle"></i>
                    <span>Approve</span>
                </button>
            </form>

            <button class="project-btn project-btn-secondary"
                    onclick="openRequestChangesModal({{ $task->id }})">
                <i class="fas fa-undo-alt"></i>
                <span>Request Changes</span>
            </button>
        @endif

        {{-- Reminder (lead / PM only) --}}
        @if($viewer->canSeeAllTasks($workspaceOwner))
            <button class="project-btn project-btn-ghost"
                    onclick="openReminderModal({{ $task->id }})">
                <i class="fas fa-bell"></i>
                <span>Send Reminder</span>
            </button>
        @endif
    </div>
</div>

<script>
    // basic JS hooks (you'll implement modals / ajax yourself)
    function closeTaskDrawer() {
        const drawer = document.querySelector('.task-drawer');
        if (drawer) drawer.remove();
    }
</script>

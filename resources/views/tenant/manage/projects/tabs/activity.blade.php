{{-- resources/views/tenant/manage/projects/tabs/activity.blade.php --}}

@php
// Mock activity data - replace with actual activity logs from database
$activities = collect([
    (object)[
        'id' => 1,
        'type' => 'task_created',
        'user' => $project->user,
        'description' => 'created task',
        'subject' => 'Update homepage design',
        'created_at' => now()->subHours(2),
    ],
    (object)[
        'id' => 2,
        'type' => 'task_completed',
        'user' => $project->team->first(),
        'description' => 'completed task',
        'subject' => 'Fix login bug',
        'created_at' => now()->subHours(5),
    ],
    (object)[
        'id' => 3,
        'type' => 'comment',
        'user' => $project->user,
        'description' => 'commented on',
        'subject' => 'API Integration',
        'comment' => 'Great progress! Let\'s schedule a review meeting.',
        'created_at' => now()->subHours(8),
    ],
    (object)[
        'id' => 4,
        'type' => 'file_uploaded',
        'user' => $project->team->first(),
        'description' => 'uploaded file',
        'subject' => 'design-mockup-v2.pdf',
        'created_at' => now()->subDay(),
    ],
    (object)[
        'id' => 5,
        'type' => 'task_assigned',
        'user' => $project->user,
        'description' => 'assigned task to',
        'subject' => 'Database optimization',
        'assignee' => $project->team->first(),
        'created_at' => now()->subDay(),
    ],
]);
@endphp

<div class="pro-activity">
    <!-- Activity Header -->
    <div class="pro-activity-header">
        <div class="pro-activity-info">
            <h2><i class="fas fa-history"></i> Activity Feed</h2>
            <p>Recent updates and changes to this project</p>
        </div>
        <div class="pro-activity-filters">
            <button class="pro-filter-btn active" data-filter="all">
                All Activity
            </button>
            <button class="pro-filter-btn" data-filter="tasks">
                Tasks
            </button>
            <button class="pro-filter-btn" data-filter="comments">
                Comments
            </button>
            <button class="pro-filter-btn" data-filter="files">
                Files
            </button>
        </div>
    </div>

    @if($activities->count() > 0)
        <!-- Activity Timeline -->
        <div class="pro-activity-timeline">
            @foreach($activities as $activity)
                <div class="pro-activity-item" data-type="{{ $activity->type }}">
                    <div class="pro-activity-icon {{ $activity->type }}">
                        @switch($activity->type)
                            @case('task_created')
                                <i class="fas fa-plus"></i>
                                @break
                            @case('task_completed')
                                <i class="fas fa-check"></i>
                                @break
                            @case('task_assigned')
                                <i class="fas fa-user-plus"></i>
                                @break
                            @case('comment')
                                <i class="fas fa-comment"></i>
                                @break
                            @case('file_uploaded')
                                <i class="fas fa-file-upload"></i>
                                @break
                            @default
                                <i class="fas fa-circle"></i>
                        @endswitch
                    </div>

                    <div class="pro-activity-content">
                        <div class="pro-activity-main">
                            <div class="pro-activity-user">
                                @if($activity->user->avatar_url ?? false)
                                    <img src="{{ $activity->user->avatar_url }}" alt="{{ $activity->user->name }}" 
                                    
                                    referrerpolicy="no-referrer"
                                    crossorigin="anonymous"
                                    onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';"/>
                                @else
                                    <div class="pro-avatar-fallback">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</div>
                                @endif
                            </div>

                            <div class="pro-activity-text">
                                <p>
                                    <strong>{{ $activity->user->name }}</strong>
                                    {{ $activity->description }}
                                    @if($activity->assignee ?? false)
                                        <strong>{{ $activity->assignee->name }}</strong>
                                    @endif
                                    @if($activity->subject)
                                        <span class="pro-activity-subject">{{ $activity->subject }}</span>
                                    @endif
                                </p>
                                
                                @if($activity->comment ?? false)
                                    <div class="pro-activity-comment">
                                        <i class="fas fa-quote-left"></i>
                                        {{ $activity->comment }}
                                    </div>
                                @endif

                                <span class="pro-activity-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More -->
        <div class="pro-activity-footer">
            <button class="pro-btn pro-btn-secondary" onclick="loadMoreActivity()">
                <i class="fas fa-chevron-down"></i> Load More Activity
            </button>
        </div>
    @else
        <div class="pro-empty">
            <i class="fas fa-history"></i>
            <h3>No Activity Yet</h3>
            <p>Activity will appear here as your team works on this project</p>
        </div>
    @endif
</div>

<style>
/* Activity Styles */
.pro-activity {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.pro-activity-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.pro-activity-info h2 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pro-activity-info h2 i {
    color: var(--accent);
}

.pro-activity-info p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

.pro-activity-filters {
    display: flex;
    gap: 4px;
    background: var(--bg);
    padding: 3px;
    border-radius: 6px;
    flex-wrap: wrap;
}

.pro-filter-btn {
    padding: 6px 12px;
    background: transparent;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
}

.pro-filter-btn:hover {
    background: var(--card);
    color: var(--text-body);
}

.pro-filter-btn.active {
    background: var(--accent);
    color: #fff;
}

/* Activity Timeline */
.pro-activity-timeline {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
}

.pro-activity-item {
    display: flex;
    gap: 14px;
    position: relative;
    padding-bottom: 24px;
}

.pro-activity-item:last-child {
    padding-bottom: 0;
}

.pro-activity-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 40px;
    bottom: 0;
    width: 2px;
    background: var(--border);
}

.pro-activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.pro-activity-icon.task_created {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.pro-activity-icon.task_completed {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.pro-activity-icon.task_assigned {
    background: rgba(139, 92, 246, 0.15);
    color: #8b5cf6;
}

.pro-activity-icon.comment {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.pro-activity-icon.file_uploaded {
    background: rgba(236, 72, 153, 0.15);
    color: #ec4899;
}

.pro-activity-content {
    flex: 1;
    min-width: 0;
}

.pro-activity-main {
    display: flex;
    gap: 10px;
}

.pro-activity-user img,
.pro-activity-user .pro-avatar-fallback {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.pro-activity-user .pro-avatar-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: #fff;
    font-size: 13px;
    font-weight: 700;
}

.pro-activity-text {
    flex: 1;
    min-width: 0;
}

.pro-activity-text p {
    font-size: 14px;
    color: var(--text-body);
    margin: 0 0 6px 0;
    line-height: 1.5;
}

.pro-activity-text strong {
    font-weight: 600;
    color: var(--text-heading);
}

.pro-activity-subject {
    display: inline;
    padding: 2px 6px;
    background: var(--bg);
    border-radius: 3px;
    font-weight: 600;
    color: var(--text-heading);
}

.pro-activity-comment {
    position: relative;
    padding: 10px 12px 10px 30px;
    background: var(--bg);
    border-left: 3px solid var(--accent);
    border-radius: 4px;
    font-size: 13px;
    line-height: 1.5;
    color: var(--text-body);
    margin: 8px 0;
}

.pro-activity-comment i {
    position: absolute;
    left: 10px;
    top: 12px;
    font-size: 10px;
    color: var(--accent);
    opacity: 0.5;
}

.pro-activity-time {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: var(--text-muted);
}

.pro-activity-time i {
    font-size: 10px;
}

.pro-activity-footer {
    display: flex;
    justify-content: center;
    padding: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .pro-activity-header {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-activity-filters {
        width: 100%;
    }

    .pro-activity-timeline {
        padding: 16px;
    }

    .pro-activity-item {
        gap: 10px;
    }

    .pro-activity-icon {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .pro-activity-item:not(:last-child)::before {
        left: 15px;
        top: 36px;
    }

    .pro-activity-main {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<script>
function loadMoreActivity() {
    console.log('Load more activity');
}

// Activity filter
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.pro-activity-filters .pro-filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const items = document.querySelectorAll('.pro-activity-item');
            items.forEach(item => {
                const type = item.dataset.type;
                
                if (filter === 'all') {
                    item.style.display = '';
                } else if (filter === 'tasks' && type.includes('task')) {
                    item.style.display = '';
                } else if (filter === 'comments' && type === 'comment') {
                    item.style.display = '';
                } else if (filter === 'files' && type === 'file_uploaded') {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
{{-- resources/views/tenant/manage/projects/tabs/overview.blade.php --}}

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$pinnedNotes = $project->notes()->where('pinned', true)->with('author')->latest()->get();
$regularNotes = $project->notes()->where('pinned', false)->with('author')->latest()->get();
$clientMedia = $project->media()->where('visibility', 'client')->orderBy('sort_order')->orderBy('id')->get();
$internalMedia = $project->media()->where('visibility', 'internal')->orderBy('sort_order')->orderBy('id')->get();

$tasksByStatus = [
    'todo' => $project->tasks->where('status', 'todo'),
    'in-progress' => $project->tasks->where('status', 'in-progress'),
    'review' => $project->tasks->where('status', 'review'),
    'done' => $project->tasks->where('status', 'done'),
    'blocked' => $project->tasks->where('status', 'blocked'),
    'postponed' => $project->tasks->where('status', 'postponed'),
];

$statusConfig = [
    'todo' => ['label' => 'To Do', 'color' => '#6B778C', 'icon' => 'circle'],
    'in-progress' => ['label' => 'In Progress', 'color' => '#0052CC', 'icon' => 'clock'],
    'review' => ['label' => 'Review', 'color' => '#FF991F', 'icon' => 'eye'],
    'done' => ['label' => 'Done', 'color' => '#00875A', 'icon' => 'check-circle'],
    'blocked' => ['label' => 'Blocked', 'color' => '#DE350B', 'icon' => 'alert-circle'],
    'postponed' => ['label' => 'Postponed', 'color' => '#8777D9', 'icon' => 'pause-circle'],
];

$totalTasks = $project->tasks->count();
$totalNotes = $pinnedNotes->count() + $regularNotes->count();
$totalMedia = $clientMedia->count() + $internalMedia->count();
@endphp

<div class="pro-overview">
    <!-- Info Grid -->
    <div class="pro-grid">
        <!-- Description -->
        <div class="pro-card pro-card-full">
            <div class="pro-card-header">
                <i class="fas fa-align-left"></i>
                <h3>Description</h3>
            </div>
            @if($project->description)
                <div class="pro-description">{{ $project->description }}</div>
            @else
                <div class="pro-placeholder">
                    <i class="fas fa-file-alt"></i>
                    <span>No description added</span>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="pro-card">
            <div class="pro-card-header">
                <i class="fas fa-chart-bar"></i>
                <h3>Stats</h3>
            </div>
            <div class="pro-stats">
                <div class="pro-stat">
                    <div class="pro-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="pro-stat-info">
                        <div class="pro-stat-value">{{ $project->tasks->count() }}</div>
                        <div class="pro-stat-label">Tasks</div>
                    </div>
                </div>
                <div class="pro-stat">
                    <div class="pro-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="pro-stat-info">
                        <div class="pro-stat-value">{{ $project->tasks->where('status', 'done')->count() }}</div>
                        <div class="pro-stat-label">Done</div>
                    </div>
                </div>
                <div class="pro-stat">
                    <div class="pro-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="pro-stat-info">
                        <div class="pro-stat-value">{{ $project->team->count() }}</div>
                        <div class="pro-stat-label">Team</div>
                    </div>
                </div>
                @if($project->budget)
                <div class="pro-stat">
                    <div class="pro-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="pro-stat-info">
                        <div class="pro-stat-value">{{ $project->currency }}{{ number_format($project->budget) }}</div>
                        <div class="pro-stat-label">Budget</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="pro-card">
            <div class="pro-card-header">
                <i class="fas fa-info-circle"></i>
                <h3>Details</h3>
            </div>
            <div class="pro-details">
                <div class="pro-detail">
                    <span class="pro-detail-label">Start Date</span>
                    <span class="pro-detail-value">{{ optional($project->start_date)->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="pro-detail">
                    <span class="pro-detail-label">Due Date</span>
                    <span class="pro-detail-value">{{ optional($project->due_date)->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="pro-detail">
                    <span class="pro-detail-label">Priority</span>
                    <span class="pro-badge pro-badge-priority priority-{{ $project->priority ?? 'medium' }}">
                        {{ ucfirst($project->priority ?? 'medium') }}
                    </span>
                </div>
                <div class="pro-detail">
                    <span class="pro-detail-label">Status</span>
                    <span class="pro-badge pro-badge-status status-{{ $project->status ?? 'active' }}">
                        {{ ucfirst($project->status ?? 'active') }}
                    </span>
                </div>
                @if($project->estimated_hours)
                <div class="pro-detail">
                    <span class="pro-detail-label">Est. Hours</span>
                    <span class="pro-detail-value">{{ $project->estimated_hours }}h</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    @if($totalTasks > 0)
    <div class="pro-section">
        <div class="pro-section-header">
            <div>
                <h2><i class="fas fa-tasks"></i> Tasks</h2>
                <p>{{ $totalTasks }} {{ Str::plural('task', $totalTasks) }} across all statuses</p>
            </div>
            <div class="pro-view-toggle">
                <button class="pro-toggle-btn active" onclick="switchView('status')">
                    <i class="fas fa-th-large"></i> Status
                </button>
                <button class="pro-toggle-btn" onclick="switchView('list')">
                    <i class="fas fa-list"></i> List
                </button>
            </div>
        </div>

        <!-- Status View -->
        <div id="statusView" class="pro-tasks-status">
            @foreach($statusConfig as $statusKey => $statusInfo)
                @php
                    $statusTasks = $tasksByStatus[$statusKey];
                    $statusCount = $statusTasks->count();
                @endphp
                @if($statusCount > 0)
                <div class="pro-status-column">
                    <div class="pro-status-header" style="border-left-color: {{ $statusInfo['color'] }};">
                        <i class="fas fa-{{ $statusInfo['icon'] }}" style="color: {{ $statusInfo['color'] }};"></i>
                        <span>{{ $statusInfo['label'] }}</span>
                        <span class="pro-status-count">{{ $statusCount }}</span>
                    </div>
                    <div class="pro-status-list">
                        @foreach($statusTasks as $task)
                        <div class="pro-task-card" onclick="openTaskDetail({{ $task->id }})">
                            <div class="pro-task-header">
                                <span class="pro-task-key">{{ $project->key }}-{{ $task->id }}</span>
                                @if($task->is_overdue)
                                    <i class="fas fa-exclamation-circle pro-task-overdue"></i>
                                @endif
                            </div>
                            <h4 class="pro-task-title">{{ $task->title }}</h4>
                            @if($task->subtasks->count() > 0)
                                @php
                                    $totalSubs = $task->subtasks->count();
                                    $completedSubs = $task->subtasks->where('completed', true)->count();
                                @endphp
                                <div class="pro-task-progress">
                                    <i class="fas fa-check-square"></i>
                                    <span>{{ $completedSubs }}/{{ $totalSubs }}</span>
                                    <div class="pro-task-bar">
                                        <div style="width: {{ round(($completedSubs / $totalSubs) * 100) }}%; background: {{ $statusInfo['color'] }};"></div>
                                    </div>
                                </div>
                            @endif
                            <div class="pro-task-footer">
                                <div class="pro-task-meta">
                                    @if($task->due_date)
                                        <span class="{{ $task->is_overdue ? 'overdue' : '' }}">
                                            <i class="fas fa-calendar"></i> {{ $task->due_date->format('M d') }}
                                        </span>
                                    @endif
                                    @if($task->attachments->count() > 0)
                                        <span><i class="fas fa-paperclip"></i> {{ $task->attachments->count() }}</span>
                                    @endif
                                </div>
                                @if($task->assignee)
                                    <div class="pro-task-avatar" title="{{ $task->assignee->name }}">
                                        @if($task->assignee->avatar_url)
                                            <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}">
                                        @else
                                            <div class="pro-avatar-text">{{ strtoupper(substr($task->assignee->name, 0, 1)) }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- List View -->
        <div id="listView" class="pro-tasks-list" style="display: none;">
            @foreach($project->tasks as $task)
                @php $statusInfo = $statusConfig[$task->status] ?? $statusConfig['todo']; @endphp
                <div class="pro-task-row" onclick="openTaskDetail({{ $task->id }})">
                    <div class="pro-task-status" style="background: {{ $statusInfo['color'] }};"></div>
                    <div class="pro-task-content">
                        <div class="pro-task-main">
                            <span class="pro-task-key">{{ $project->key }}-{{ $task->id }}</span>
                            <h4>{{ $task->title }}</h4>
                            @if($task->is_overdue)
                                <span class="pro-overdue-badge"><i class="fas fa-exclamation-circle"></i> Overdue</span>
                            @endif
                        </div>
                        <div class="pro-task-info">
                            <span class="pro-badge" style="background: {{ $statusInfo['color'] }}20; color: {{ $statusInfo['color'] }};">
                                {{ $statusInfo['label'] }}
                            </span>
                            @if($task->due_date)
                                <span><i class="fas fa-calendar"></i> {{ $task->due_date->format('M d, Y') }}</span>
                            @endif
                            @if($task->subtasks->count() > 0)
                                <span><i class="fas fa-check-square"></i> {{ $task->subtasks->where('completed', true)->count() }}/{{ $task->subtasks->count() }}</span>
                            @endif
                            @if($task->assignee)
                                <div class="pro-assignee">
                                    <div class="pro-task-avatar">
                                        @if($task->assignee->avatar_url)
                                            <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}">
                                        @else
                                            <div class="pro-avatar-text">{{ strtoupper(substr($task->assignee->name, 0, 1)) }}</div>
                                        @endif
                                    </div>
                                    <span>{{ $task->assignee->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Notes Section -->
    @if($totalNotes > 0)
    <div class="pro-section">
        <div class="pro-section-header">
            <div>
                <h2><i class="fas fa-sticky-note"></i> Notes</h2>
                <p>{{ $totalNotes }} {{ Str::plural('note', $totalNotes) }}</p>
            </div>
            <button class="pro-btn pro-btn-secondary"><i class="fas fa-plus"></i> Add Note</button>
        </div>
        
        <div class="pro-notes">
            @foreach($pinnedNotes as $note)
            <div class="pro-note pinned">
                <div class="pro-note-pin"><i class="fas fa-thumbtack"></i> Pinned</div>
                <div class="pro-note-header">
                    <img src="{{ $note->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($note->author->name ?? 'Unknown') }}" alt="{{ $note->author->name ?? 'Unknown' }}">
                    <div class="pro-note-meta">
                        <strong>{{ $note->author->name ?? 'Unknown' }}</strong>
                        <span>{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    @if($note->is_internal)
                        <span class="pro-badge pro-badge-internal"><i class="fas fa-lock"></i> Internal</span>
                    @endif
                </div>
                <div class="pro-note-body">{{ $note->body }}</div>
                <div class="pro-note-actions">
                    <button><i class="fas fa-thumbtack"></i></button>
                    <button><i class="fas fa-edit"></i></button>
                    <button class="delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @endforeach

            @foreach($regularNotes as $note)
            <div class="pro-note">
                <div class="pro-note-header">
                    <img src="{{ $note->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($note->author->name ?? 'Unknown') }}" alt="{{ $note->author->name ?? 'Unknown' }}">
                    <div class="pro-note-meta">
                        <strong>{{ $note->author->name ?? 'Unknown' }}</strong>
                        <span>{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    @if($note->is_internal)
                        <span class="pro-badge pro-badge-internal"><i class="fas fa-lock"></i> Internal</span>
                    @endif
                </div>
                <div class="pro-note-body">{{ $note->body }}</div>
                <div class="pro-note-actions">
                    <button><i class="fas fa-thumbtack"></i></button>
                    <button><i class="fas fa-edit"></i></button>
                    <button class="delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Media Section -->
    @if($totalMedia > 0)
    <div class="pro-section">
        <div class="pro-section-header">
            <div>
                <h2><i class="fas fa-images"></i> Files</h2>
                <p>{{ $totalMedia }} {{ Str::plural('file', $totalMedia) }}</p>
            </div>
            <div class="pro-filter">
                <button class="pro-filter-btn active" data-filter="all">All</button>
                <button class="pro-filter-btn" data-filter="client">Client</button>
                <button class="pro-filter-btn" data-filter="internal">Internal</button>
            </div>
        </div>

        <div class="pro-media">
            @foreach($project->media as $media)
            <div class="pro-media-card" data-visibility="{{ $media->visibility }}">
                <div class="pro-media-preview">
                    @if(Str::startsWith($media->mime_type, 'image/'))
                        <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->original_name }}">
                    @else
                        <div class="pro-file-icon">
                            <i class="fas fa-{{ $media->type === 'video' ? 'play-circle' : ($media->type === 'document' ? 'file-pdf' : 'file') }}"></i>
                            <span>{{ pathinfo($media->original_name, PATHINFO_EXTENSION) }}</span>
                        </div>
                    @endif
                    <div class="pro-media-actions">
                        <button onclick="downloadMedia({{ $media->id }})"><i class="fas fa-download"></i></button>
                        <button onclick="viewMedia({{ $media->id }})"><i class="fas fa-eye"></i></button>
                        <button onclick="deleteMedia({{ $media->id }})" class="delete"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="pro-media-info">
                    <div class="pro-media-name" title="{{ $media->original_name }}">{{ Str::limit($media->original_name, 25) }}</div>
                    <div class="pro-media-meta">
                        <span>{{ number_format($media->size_bytes / 1024, 1) }} KB</span>
                        <span class="pro-badge pro-badge-{{ $media->visibility }}">
                            <i class="fas fa-{{ $media->visibility === 'client' ? 'eye' : 'lock' }}"></i>
                            {{ ucfirst($media->visibility) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($totalNotes === 0 && $totalMedia === 0 && $totalTasks === 0)
    <div class="pro-empty-large">
        <i class="fas fa-inbox"></i>
        <h3>Get Started</h3>
        <p>Add tasks, notes, and files to your project</p>
        <div class="pro-empty-actions">
            <button class="pro-btn pro-btn-primary"><i class="fas fa-plus"></i> Add Task</button>
            <button class="pro-btn pro-btn-secondary"><i class="fas fa-sticky-note"></i> Add Note</button>
        </div>
    </div>
    @endif
</div>

<style>
/* ===== PROFESSIONAL OVERVIEW STYLES ===== */

.pro-overview {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Grid */
.pro-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.pro-card-full {
    grid-column: 1 / -1;
}

/* Cards */
.pro-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 16px;
}

.pro-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}

.pro-card-header i {
    font-size: 16px;
    color: var(--accent);
}

.pro-card-header h3 {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
}

.pro-description {
    font-size: 14px;
    line-height: 1.6;
    color: var(--text-body);
    white-space: pre-wrap;
}

.pro-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 30px;
    color: var(--text-muted);
    font-size: 13px;
    background: var(--bg);
    border-radius: 6px;
}

/* Stats */
.pro-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.pro-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    background: var(--bg);
    border-radius: 6px;
}

.pro-stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.pro-stat-info {
    flex: 1;
}

.pro-stat-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-heading);
    line-height: 1;
}

.pro-stat-label {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Details */
.pro-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.pro-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.pro-detail-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pro-detail-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-body);
}

.pro-badge-priority {
    padding: 3px 8px;
}

.pro-badge-priority.priority-urgent {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.pro-badge-priority.priority-high {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.pro-badge-priority.priority-medium {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.pro-badge-priority.priority-low {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

/* Section */
.pro-section {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 16px;
}

.pro-section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    gap: 12px;
}

.pro-section-header h2 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0 0 4px 0;
}

.pro-section-header h2 i {
    font-size: 16px;
    color: var(--accent);
}

.pro-section-header p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

/* View Toggle */
.pro-view-toggle {
    display: flex;
    gap: 4px;
    background: var(--bg);
    padding: 3px;
    border-radius: 6px;
}

.pro-toggle-btn {
    display: flex;
    align-items: center;
    gap: 5px;
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

.pro-toggle-btn:hover {
    background: var(--card);
    color: var(--text-body);
}

.pro-toggle-btn.active {
    background: var(--accent);
    color: #fff;
}

/* Tasks Status View */
.pro-tasks-status {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
}

.pro-status-column {
    background: var(--bg);
    border-radius: 8px;
    overflow: hidden;
}

.pro-status-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: var(--card);
    border-left: 3px solid;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
}

.pro-status-count {
    margin-left: auto;
    padding: 2px 7px;
    background: var(--bg);
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
}

.pro-status-list {
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 500px;
    overflow-y: auto;
}

/* Task Card */
.pro-task-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.pro-task-card:hover {
    border-color: var(--accent);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transform: translateY(-1px);
}

.pro-task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.pro-task-key {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pro-task-overdue {
    color: #ef4444;
    font-size: 12px;
    animation: pulse 2s infinite;
}

.pro-task-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 8px 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pro-task-progress {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 8px;
    padding: 6px;
    background: var(--bg);
    border-radius: 4px;
}

.pro-task-progress i {
    color: var(--accent);
}

.pro-task-bar {
    flex: 1;
    height: 3px;
    background: var(--border);
    border-radius: 2px;
    overflow: hidden;
}

.pro-task-bar div {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s;
}

.pro-task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 8px;
    border-top: 1px solid var(--border);
}

.pro-task-meta {
    display: flex;
    gap: 8px;
    font-size: 11px;
    color: var(--text-muted);
}

.pro-task-meta span {
    display: flex;
    align-items: center;
    gap: 3px;
}

.pro-task-meta .overdue {
    color: #ef4444;
    font-weight: 600;
}

.pro-task-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--card);
}

.pro-task-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pro-avatar-text {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
}

/* Tasks List View */
.pro-tasks-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.pro-task-row {
    display: flex;
    gap: 12px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.pro-task-row:hover {
    border-color: var(--accent);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pro-task-status {
    width: 4px;
    border-radius: 2px;
    flex-shrink: 0;
}

.pro-task-content {
    flex: 1;
    min-width: 0;
}

.pro-task-main {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.pro-task-main h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0;
    flex: 1;
    min-width: 200px;
}

.pro-overdue-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.pro-task-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    font-size: 12px;
    color: var(--text-muted);
}

.pro-task-info span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.pro-assignee {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 3px 8px 3px 3px;
    background: var(--card);
    border-radius: 20px;
}

.pro-assignee span {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-body);
}

/* Notes */
.pro-notes {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.pro-note {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 14px;
    position: relative;
}

.pro-note.pinned {
    border-color: #f59e0b;
    background: linear-gradient(to bottom, rgba(245, 158, 11, 0.05), var(--bg));
}

.pro-note-pin {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
}

.pro-note-pin i {
    transform: rotate(45deg);
    font-size: 9px;
}

.pro-note-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.pro-note-header img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.pro-note-meta {
    flex: 1;
    min-width: 0;
}

.pro-note-meta strong {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
}

.pro-note-meta span {
    font-size: 11px;
    color: var(--text-muted);
}

.pro-badge-internal {
    padding: 3px 8px;
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.pro-note-body {
    font-size: 13px;
    line-height: 1.6;
    color: var(--text-body);
    margin-bottom: 12px;
    white-space: pre-wrap;
}

.pro-note-actions {
    display: flex;
    gap: 6px;
    padding-top: 12px;
    border-top: 1px solid var(--border);
}

.pro-note-actions button {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
    font-size: 12px;
}

.pro-note-actions button:hover {
    background: var(--accent-light);
    color: var(--accent);
}

.pro-note-actions button.delete:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Filter */
.pro-filter {
    display: flex;
    gap: 4px;
    background: var(--bg);
    padding: 3px;
    border-radius: 6px;
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

/* Media */
.pro-media {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}

.pro-media-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s;
}

.pro-media-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.pro-media-preview {
    position: relative;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: var(--card);
}

.pro-media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pro-file-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    gap: 8px;
    color: var(--accent);
}

.pro-file-icon i {
    font-size: 36px;
}

.pro-file-icon span {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-heading);
}

.pro-media-actions {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.75));
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    opacity: 0;
    transition: opacity 0.2s;
}

.pro-media-card:hover .pro-media-actions {
    opacity: 1;
}

.pro-media-actions button {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 6px;
    color: var(--text-heading);
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
}

.pro-media-actions button:hover {
    background: #fff;
    transform: scale(1.1);
}

.pro-media-actions button.delete {
    background: rgba(239, 68, 68, 0.95);
    color: #fff;
}

.pro-media-actions button.delete:hover {
    background: #ef4444;
}

.pro-media-info {
    padding: 10px;
}

.pro-media-name {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-media-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11px;
}

.pro-media-meta span:first-child {
    color: var(--text-muted);
}

.pro-badge-client {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.pro-badge-internal {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

/* Empty State */
.pro-empty-large {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg);
    border-radius: 8px;
}

.pro-empty-large i {
    font-size: 48px;
    color: var(--text-muted);
    opacity: 0.5;
    margin-bottom: 12px;
}

.pro-empty-large h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 6px 0;
}

.pro-empty-large p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0 0 20px 0;
}

.pro-empty-actions {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pro-btn-primary {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
}

.pro-btn-primary:hover {
    background: var(--accent-dark);
    border-color: var(--accent-dark);
}

/* Responsive */
@media (max-width: 768px) {
    .pro-grid {
        grid-template-columns: 1fr;
    }

    .pro-section-header {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-view-toggle,
    .pro-filter {
        width: 100%;
    }

    .pro-tasks-status {
        grid-template-columns: 1fr;
    }

    .pro-media {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }

    .pro-task-main {
        flex-direction: column;
        align-items: flex-start;
    }

    .pro-task-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>

<script>
function switchView(view) {
    const statusView = document.getElementById('statusView');
    const listView = document.getElementById('listView');
    const buttons = document.querySelectorAll('.pro-toggle-btn');

    buttons.forEach(btn => {
        const btnText = btn.textContent.toLowerCase();
        btn.classList.toggle('active', btnText.includes(view));
    });

    if (view === 'status') {
        statusView.style.display = 'grid';
        listView.style.display = 'none';
    } else {
        statusView.style.display = 'none';
        listView.style.display = 'flex';
    }
}

function openTaskDetail(taskId) {
    console.log('Opening task:', taskId);
}

// Media Filter
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.pro-filter-btn');
    const mediaCards = document.querySelectorAll('.pro-media-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            mediaCards.forEach(card => {
                const visibility = card.dataset.visibility;
                
                if (filter === 'all' || filter === visibility) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function downloadMedia(mediaId) {
    window.location.href = `/{{ $username }}/manage/projects/media/${mediaId}/download`;
}

function viewMedia(mediaId) {
    console.log('View media:', mediaId);
}

function deleteMedia(mediaId) {
    if (confirm('Delete this file?')) {
        fetch(`/{{ $username }}/manage/projects/media/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-media-id="${mediaId}"]`)?.remove();
            }
        });
    }
}
</script>
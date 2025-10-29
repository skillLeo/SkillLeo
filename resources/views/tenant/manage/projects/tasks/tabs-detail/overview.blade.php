{{-- resources/views/tenant/manage/projects/tasks/tabs-detail/overview.blade.php --}}
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="task-overview-grid">
    <!-- Left Column -->
    <div class="task-overview-main">
        <!-- Description -->
        <div class="task-section">
            <div class="task-section-header">
                <h3 class="task-section-title">
                    <i class="fas fa-align-left"></i> Description
                </h3>
                @if ($task->reporter_id === $viewer->id)
                    <button class="task-btn-text" onclick="editDescription()">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                @endif
            </div>
            <div class="task-section-body">
                @if ($task->notes)
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
        @if ($totalSubtasks > 0)
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
                        @foreach ($task->subtasks as $subtask)
                            <div class="task-subtask-item {{ $subtask->completed ? 'is-completed' : '' }}"
                                data-subtask-id="{{ $subtask->id }}">
                                <label class="task-checkbox-wrapper">
                                    <input type="checkbox" class="task-checkbox"
                                        {{ $subtask->completed ? 'checked' : '' }}
                                        {{ $task->assigned_to !== $viewer->id ? 'disabled' : '' }}
                                        onchange="toggleSubtaskDetail({{ $task->id }}, {{ $subtask->id }}, this.checked)">
                                    <span class="task-checkbox-custom">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </label>
                                <span class="task-subtask-title">{{ $subtask->title }}</span>
                                @if ($subtask->completed_at)
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

    <!-- Right Column (Activity Feed / sidebar) -->
    <div class="task-overview-sidebar">
        <!-- Activity Feed Card -->
        <div class="task-details-card">
            <h4 class="task-details-title">
                <i class="fas fa-comments"></i> Activity & Updates
                <span class="task-section-count">{{ $task->activities->count() }}</span>
            </h4>

            @php
                /**
                 * Icon + color for the little badge in the avatar corner
                 */
                function getActivityIcon($type)
                {
                    return match ($type) {
                        'created' => ['icon' => 'fa-plus-circle', 'color' => '#00875A'],
                        'comment' => ['icon' => 'fa-comment-dots', 'color' => '#0052CC'],
                        'completed', 'done' => ['icon' => 'fa-check-circle', 'color' => '#00875A'],
                        'blocked' => ['icon' => 'fa-exclamation-circle', 'color' => '#DE350B'],
                        'postponed' => ['icon' => 'fa-clock', 'color' => '#FF991F'],
                        'review' => ['icon' => 'fa-eye', 'color' => '#6554C0'],
                        'status_change' => ['icon' => 'fa-arrow-right', 'color' => '#00B8D9'],
                        'reassigned' => ['icon' => 'fa-user-check', 'color' => '#0052CC'],
                        'attachment_uploaded' => ['icon' => 'fa-paperclip', 'color' => '#6B778C'],
                        default => ['icon' => 'fa-circle', 'color' => '#8993A4'],
                    };
                }

                /**
                 * Label/icon/color for file attachments in bubbles
                 */
                function getMediaTypeLabel($type)
                {
                    return match ($type) {
                        'image', 'jpg', 'jpeg', 'png', 'gif' => [
                            'icon' => 'fa-image',
                            'label' => 'Photo',
                            'color' => '#00875A',
                        ],
                        'pdf' => ['icon' => 'fa-file-pdf', 'label' => 'PDF', 'color' => '#DE350B'],
                        'doc', 'docx' => ['icon' => 'fa-file-word', 'label' => 'Document', 'color' => '#2B579A'],
                        'xls', 'xlsx' => ['icon' => 'fa-file-excel', 'label' => 'Spreadsheet', 'color' => '#217346'],
                        'ppt', 'pptx' => [
                            'icon' => 'fa-file-powerpoint',
                            'label' => 'Presentation',
                            'color' => '#D24726',
                        ],
                        'zip', 'rar' => ['icon' => 'fa-file-archive', 'label' => 'Archive', 'color' => '#FFA500'],
                        'mp4', 'avi', 'mov' => ['icon' => 'fa-video', 'label' => 'Video', 'color' => '#6554C0'],
                        default => ['icon' => 'fa-file', 'label' => 'File', 'color' => '#6B778C'],
                    };
                }
            @endphp

            <div class="activity-feed">
                @forelse($task->activities->take(10) as $activity)
                    @php
                        $iconData = getActivityIcon($activity->type);

                        // try to resolve a "main attachment" for this activity if metadata points to it
                        $attachment = null;
                        if (isset($activity->metadata['attachment_id'])) {
                            $attachment = $task->attachments->find($activity->metadata['attachment_id']);
                        }

                        // special handling for 'reassigned' activities:
                        $isReassign = $activity->type === 'reassigned';

                        $payload = [];
                        $fromUserId = null;
                        $toUserId = null;
                        $noteText = null;
                        $fromUser = null;
                        $toUser = null;

                        if ($isReassign) {
                            // we stored JSON in task_activities.body like:
                            // {"from_user_id":"1","to_user_id":"3","note":"my note"}
                            $decoded = json_decode($activity->body, true);
                            if (is_array($decoded)) {
                                $payload    = $decoded;
                                $fromUserId = $decoded['from_user_id'] ?? null;
                                $toUserId   = $decoded['to_user_id'] ?? null;
                                $noteText   = $decoded['note'] ?? null;
                            }

                            // map those user ids to actual User models from $reassignmentUsers
                            $fromUser = $fromUserId ? ($reassignmentUsers[$fromUserId] ?? null) : null;
                            $toUser   = $toUserId   ? ($reassignmentUsers[$toUserId]   ?? null) : null;
                        }
                    @endphp

                    <div class="activity-message {{ $activity->actor_id === auth()->id() ? 'is-own' : '' }}">
                        <div class="activity-avatar">
                            @if ($activity->actor->avatar_url ?? $activity->actor->profile_photo_path)
                                <img src="{{ $activity->actor->avatar_url ?? Storage::url($activity->actor->profile_photo_path) }}"
                                     alt="{{ $activity->actor->name }}"
                                     referrerpolicy="no-referrer"
                                     crossorigin="anonymous"
                                     onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                            @else
                                <div class="avatar-fallback" style="background: {{ $iconData['color'] }};">
                                    {{ strtoupper(substr($activity->actor->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="activity-badge" style="background: {{ $iconData['color'] }};">
                                <i class="fas {{ $iconData['icon'] }}"></i>
                            </div>
                        </div>

                        <div class="message-bubble">
                            <div class="message-header">
                                <span class="message-author">{{ $activity->actor->name }}</span>
                                <span class="message-time">{{ $activity->created_at->diffForHumans(null, true, true) }}</span>
                            </div>

                            {{-- REASSIGN CARD VIEW --}}
                            @if ($isReassign)
                                <div class="reassign-mini-card">
                                    <div class="reassign-mini-head">
                                        <i class="fas fa-user-check"></i>
                                        <span>Task Reassigned</span>
                                    </div>

                                    <div class="reassign-mini-body">
                                        <div class="reassign-mini-pair">
                                            {{-- FROM --}}
                                            <div class="re-mini-user re-mini-from">
                                                <div class="re-mini-avatar">
                                                    @if ($fromUser && $fromUser->avatar_url)
                                                        <img src="{{ $fromUser->avatar_url }}"
                                                             alt="{{ $fromUser->name }}"
                                                             referrerpolicy="no-referrer" crossorigin="anonymous"
                                                             onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                                                    @else
                                                        <div class="re-mini-fallback re-mini-from-bg">
                                                            {{ $fromUser ? strtoupper(substr($fromUser->name, 0, 1)) : '?' }}
                                                        </div>
                                                    @endif
                                                </div>
                                         
                                            </div>

                                            <div class="re-mini-arrow">
                                                <i class="fas fa-arrow-right"></i>
                                            </div>

                                            {{-- TO --}}
                                            <div class="re-mini-user re-mini-to">
                                                <div class="re-mini-avatar">
                                                    @if ($toUser && $toUser->avatar_url)
                                                        <img src="{{ $toUser->avatar_url }}"
                                                             alt="{{ $toUser->name }}"
                                                             referrerpolicy="no-referrer" crossorigin="anonymous"
                                                             onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                                                    @else
                                                        <div class="re-mini-fallback re-mini-to-bg">
                                                            {{ $toUser
                                                                ? strtoupper(substr($toUser->name, 0, 1))
                                                                : ($toUserId ? '?' : '∅') }}
                                                        </div>
                                                    @endif
                                                </div>
                                   
                                            </div>
                                        </div>

                                        @if ($noteText)
                                            <div class="re-mini-note">
                                                <div class="re-mini-note-head">
                                                    <i class="fas fa-sticky-note"></i>
                                                    <span>Note</span>
                                                </div>
                                                <div class="re-mini-note-text">{{ $noteText }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- NORMAL ACTIVITY BUBBLE CONTENT --}}

                                {{-- attachment type badge, if any --}}
                                @if ($attachment)
                                    @php $mediaType = getMediaTypeLabel($attachment->type); @endphp
                                    <div class="media-type-indicator">
                                        <i class="fas {{ $mediaType['icon'] }}"
                                            style="color: {{ $mediaType['color'] }};"></i>
                                        <span style="color: {{ $mediaType['color'] }};">{{ $mediaType['label'] }}</span>
                                    </div>
                                @endif

                                {{-- message body / text --}}
                                @if ($activity->body)
                                    {{-- for non-reassign types this is usually plain text/comment/etc --}}
                                    <div class="message-text">{{ $activity->body }}</div>
                                @endif

                                {{-- inline attachment preview --}}
                                @if ($attachment)
                                    @if (in_array($attachment->type, ['image', 'jpg', 'jpeg', 'png', 'gif']))
                                        <div class="attachment-preview image-preview"
                                            onclick="openMediaModal('{{ Storage::url($attachment->path_or_url) }}', 'image', '{{ $attachment->label }}')">
                                            <img src="{{ Storage::url($attachment->path_or_url) }}"
                                                alt="{{ $attachment->label }}">
                                            <div class="preview-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $fileName = $attachment->label ?: basename($attachment->path_or_url);
                                            $fileSize = $attachment->file_size
                                                ? number_format($attachment->file_size / 1024, 1) . ' KB'
                                                : '';
                                        @endphp
                                        <div class="attachment-preview file-preview"
                                            onclick="openMediaModal('{{ Storage::url($attachment->path_or_url) }}', '{{ $attachment->type }}', '{{ $fileName }}')">
                                            <div class="file-preview-icon"
                                                style="background: {{ $mediaType['color'] }}15;">
                                                <i class="fas {{ $mediaType['icon'] }}"
                                                    style="color: {{ $mediaType['color'] }};"></i>
                                            </div>
                                            <div class="file-preview-info">
                                                <div class="file-preview-name">
                                                    {{ \Illuminate\Support\Str::limit($fileName, 30) }}</div>
                                                @if ($fileSize)
                                                    <div class="file-preview-size">{{ $fileSize }}</div>
                                                @endif
                                            </div>
                                            <div class="file-preview-action">
                                                <i class="fas fa-external-link-alt"></i>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                {{-- status change inline chip --}}
                                @if ($activity->type === 'status_change' && isset($activity->metadata['from'], $activity->metadata['to']))
                                    <div class="status-badge-inline">
                                        <span class="status-old">{{ ucfirst($activity->metadata['from']) }}</span>
                                        <i class="fas fa-arrow-right"></i>
                                        <span class="status-new">{{ ucfirst($activity->metadata['to']) }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="activity-empty">
                        <i class="fas fa-inbox"></i>
                        <span>No activity yet</span>
                        <p>Updates will appear here</p>
                    </div>
                @endforelse
            </div>

            @if ($task->activities->count() > 10)
                <button class="load-more-btn" onclick="switchToActivityTab()">
                    <span>View All</span>
                    <span class="count-badge">{{ $task->activities->count() }}</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            @if ($timeSpent > 0)
                <div class="stat-card">
                    <i class="fas fa-stopwatch"></i>
                    <div>
                        <div class="stat-value">{{ number_format($timeSpent, 1) }}h</div>
                        <div class="stat-label">Time Spent</div>
                    </div>
                </div>
            @endif

            @if ($task->attachments->count() > 0)
                <div class="stat-card" onclick="window.location.href='?tab=files'">
                    <i class="fas fa-paperclip"></i>
                    <div>
                        <div class="stat-value">{{ $task->attachments->count() }}</div>
                        <div class="stat-label">Files</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .task-overview-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

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

        .task-section-count {
            font-size: 12px;
            font-weight: 600;
            color: #6B778C;
            background: #F4F5F7;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
        }

        .activity-feed {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 500px;
            overflow-y: auto;
            padding: 4px;
        }

        .activity-feed::-webkit-scrollbar {
            width: 4px;
        }

        .activity-feed::-webkit-scrollbar-thumb {
            background: #DFE1E6;
            border-radius: 2px;
        }

        .activity-message {
            display: flex;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .activity-avatar {
            position: relative;
            flex-shrink: 0;
        }

        .activity-avatar img,
        .avatar-fallback {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            font-size: 14px;
            font-weight: 700;
        }

        .activity-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FFF;
        }

        .activity-badge i {
            font-size: 9px;
            color: #FFF;
            line-height: 1;
        }

        .message-bubble {
            flex: 1;
            background: #FFF;
            border: 1px solid #DFE1E6;
            border-radius: 12px;
            padding: 10px 12px;
            max-width: 225px !important;
            transition: all 0.2s;
        }

        .activity-message.is-own .message-bubble {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            border-color: #90CAF9;
        }

        .message-bubble:hover {
            border-color: #0052CC;
            box-shadow: 0 2px 8px rgba(0, 82, 204, 0.1);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .message-author {
            font-size: 12px;
            font-weight: 700;
            color: #172B4D;
        }

        .message-time {
            font-size: 10px;
            font-weight: 600;
            color: #8993A4;
        }

        .media-type-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #F4F5F7;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .media-type-indicator i {
            font-size: 12px;
        }

        .message-text {
            font-size: 13px;
            line-height: 1.5;
            color: #42526E;
            word-wrap: break-word;
            margin-bottom: 8px;
        }

        .attachment-preview {
            margin-top: 8px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
        }

        .attachment-preview:hover {
            transform: scale(1.02);
        }

        .image-preview {
            position: relative;
            max-width: 100%;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            max-height: 180px;
            object-fit: cover;
            display: block;
            border-radius: 8px;
        }

        .preview-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            opacity: 0;
            border-radius: 8px;
        }

        .image-preview:hover .preview-overlay {
            background: rgba(0, 0, 0, 0.5);
            opacity: 1;
        }

        .preview-overlay i {
            color: #FFF;
            font-size: 24px;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #F4F5F7;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
        }

        .file-preview:hover {
            background: #E8EAED;
            border-color: #0052CC;
        }

        .file-preview-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .file-preview-icon i {
            font-size: 20px;
        }

        .file-preview-info {
            flex: 1;
            min-width: 0;
        }

        .file-preview-name {
            font-size: 12px;
            font-weight: 600;
            color: #172B4D;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-preview-size {
            font-size: 10px;
            color: #6B778C;
            margin-top: 2px;
        }

        .file-preview-action {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFF;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .file-preview-action i {
            font-size: 11px;
            color: #0052CC;
        }

        .status-badge-inline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #F4F5F7;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
        }

        .status-old {
            color: #8993A4;
        }

        .status-badge-inline i {
            color: #00B8D9;
            font-size: 9px;
        }

        .status-new {
            color: #00875A;
        }

        .activity-empty {
            text-align: center;
            padding: 48px 20px;
            color: #6B778C;
        }

        .activity-empty i {
            font-size: 32px;
            opacity: 0.5;
            margin-bottom: 12px;
        }

        .activity-empty span {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .activity-empty p {
            font-size: 12px;
            margin: 0;
        }

        .load-more-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            margin-top: 12px;
            background: linear-gradient(135deg, #0052CC, #0065FF);
            border: none;
            border-radius: 8px;
            color: #FFF;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .load-more-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
        }

        .count-badge {
            padding: 2px 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            font-size: 11px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: linear-gradient(135deg, #FAFBFC, #F4F5F7);
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: #0052CC;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 82, 204, 0.1);
        }

        .stat-card i {
            font-size: 20px;
            color: #0052CC;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #172B4D;
            line-height: 1;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 600;
            color: #6B778C;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== REASSIGN MINI CARD (sidebar bubble version) ===== */

        .reassign-mini-card {
            background: #FFFFFF;
            border: 1px solid #DFE1E6;
            border-radius: 8px;
            overflow: hidden;
            font-size: 12px;
        }

        .reassign-mini-head {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 10px;
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            border-bottom: 1px solid #90CAF9;
            font-weight: 700;
            color: #0052CC;
            font-size: 12px;
        }

        .reassign-mini-head i {
            font-size: 13px;
        }

        .reassign-mini-body {
            padding: 10px;
        }

        .reassign-mini-pair {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .re-mini-user {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
            background: #F4F5F7;
            border-radius: 6px;
            padding: 8px;
            border: 2px solid transparent;
        }

        .re-mini-from {
            background: #FFF4E6;
            border-color: #FF991F;
        }

        .re-mini-to {
            background: #E3FCEF;
            border-color: #00875A;
        }

        .re-mini-avatar {
            flex-shrink: 0;
        }

        .re-mini-avatar img,
        .re-mini-fallback {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            font-size: 12px;
            font-weight: 700;
            color: #FFF;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .re-mini-fallback.re-mini-from-bg {
            background: #FF991F;
        }

        .re-mini-fallback.re-mini-to-bg {
            background: #00875A;
        }

        .re-mini-meta {
            min-width: 0;
        }

        .re-mini-label {
            font-size: 9px;
            font-weight: 700;
            color: #6B778C;
            text-transform: uppercase;
            letter-spacing: .5px;
            line-height: 1.2;
        }

        .re-mini-name {
            font-size: 12px;
            font-weight: 700;
            color: #172B4D;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.3;
        }

        .re-mini-arrow {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #0052CC, #0065FF);
            border-radius: 50%;
            color: #FFF;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,82,204,0.25);
        }

        .re-mini-note {
            background: #F7F8F9;
            border: 1px solid #DFE1E6;
            border-radius: 6px;
            padding: 8px;
            margin-top: 10px;
        }

        .re-mini-note-head {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            color: #6B778C;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .re-mini-note-head i {
            font-size: 11px;
            color: #6B778C;
        }

        .re-mini-note-text {
            font-size: 12px;
            line-height: 1.4;
            color: #172B4D;
            white-space: pre-wrap;
        }

        /* Responsive layout tweaks for reassign mini */
        @media (max-width: 400px) {
            .reassign-mini-pair {
                flex-direction: column;
            }

            .re-mini-arrow {
                transform: rotate(90deg);
                margin: 4px 0;
            }

            .re-mini-user {
                width: 100%;
            }
        }

    </style>
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

    /* Recent Activity list (legacy mini list styles, still used in some layouts) */
    .task-recent-activity-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 480px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .task-recent-activity-list::-webkit-scrollbar {
        width: 4px;
    }

    .task-recent-activity-list::-webkit-scrollbar-track {
        background: #F4F5F7;
        border-radius: 2px;
    }

    .task-recent-activity-list::-webkit-scrollbar-thumb {
        background: #DFE1E6;
        border-radius: 2px;
    }

    .task-recent-activity-list::-webkit-scrollbar-thumb:hover {
        background: #C1C7D0;
    }

    .task-activity-mini-item {
        display: flex;
        gap: 10px;
        padding: 10px;
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .task-activity-mini-item:hover {
        background: #FFFFFF;
        border-color: #0052CC;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .task-activity-mini-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .task-activity-mini-icon i {
        font-size: 14px;
    }

    .task-activity-mini-content {
        flex: 1;
        min-width: 0;
    }

    .task-activity-mini-header {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 4px;
    }

    .task-activity-mini-actor {
        font-weight: 700;
        color: #172B4D;
    }

    .task-activity-mini-action {
        color: #6B778C;
    }

    .task-activity-mini-body {
        font-size: 11px;
        color: #5E6C84;
        line-height: 1.4;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .task-activity-mini-time {
        font-size: 10px;
        color: #8993A4;
        font-weight: 600;
    }

    .task-activity-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 32px 16px;
        color: #6B778C;
        font-size: 12px;
    }

    .task-activity-empty i {
        font-size: 24px;
        opacity: 0.5;
    }

    /* Show All Button */
    .task-show-all-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px 14px;
        margin-top: 8px;
        background: linear-gradient(135deg, #0052CC 0%, #0065FF 100%);
        border: none;
        border-radius: 6px;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        gap: 8px;
    }

    .task-show-all-btn:hover {
        background: linear-gradient(135deg, #0747A6 0%, #0052CC 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
    }

    .task-show-all-count {
        padding: 2px 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .task-show-all-btn i {
        font-size: 12px;
        transition: transform 0.2s;
    }

    .task-show-all-btn:hover i {
        transform: translateX(2px);
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

    /* (older) Details Card styling reused elsewhere */
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

    /* User pill mini avatar */
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

    /* Time tracking mini */
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

    /* Attachments quick preview grid */
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

        .task-recent-activity-list {
            max-height: 360px;
        }

        .message-bubble {
            max-width: 100%;
        }

        .reassign-mini-pair {
            flex-direction: column;
        }

        .re-mini-arrow {
            transform: rotate(90deg);
            margin: 4px 0;
        }

        .re-mini-user {
            width: 100%;
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
            body: JSON.stringify({
                completed: isChecked ? 1 : 0
            })
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
        // open modal for editing task description
        console.log('Edit description modal');
    }

    function switchToActivityTab() {
        // try to trigger the real "Activity" tab in UI
        const activityTab = document.querySelector('[data-tab="activity"]');
        if (activityTab) {
            activityTab.click();
            setTimeout(() => {
                const tabContent = document.querySelector('.task-detail-content');
                if (tabContent) {
                    tabContent.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }, 100);
        } else {
            // fallback: navigate with ?tab=activity
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'activity');
            window.location.href = url.toString();
        }
    }

    function openMediaModal(url, type, name) {
        // TODO: hook into your modal viewer / lightbox
        console.log('Preview media:', { url, type, name });
        window.open(url, '_blank');
    }
</script>

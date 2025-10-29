{{-- resources/views/tenant/manage/projects/tasks/tabs-detail/activity.blade.php --}}
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

@endphp
<div class="task-activity-container">
    <div class="task-activity-header">
        <h3>Activity Feed</h3>
        <p>All updates, comments, and changes to this task</p>
    </div>

    <!-- Add Comment Box -->
<!-- Add Comment Box with File Upload -->
<div class="task-comment-box">
    <div class="task-comment-avatar">
        @if($viewer->avatar_url)
            <img src="{{ $viewer->avatar_url }}" alt="{{ $viewer->name }}"
            referrerpolicy="no-referrer"
            crossorigin="anonymous"
            onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
        @else
            <div class="task-avatar-fallback">{{ substr($viewer->name, 0, 1) }}</div>
        @endif
    </div>
    <div class="task-comment-input-wrap">
        <!-- Selected Files Preview (above textarea) -->
        <div id="commentFilesPreview" class="comment-files-preview" style="display: none;"></div>
        
        <textarea id="taskCommentInput" 
                  class="task-comment-input" 
                  placeholder="Add a comment..."
                  rows="3"></textarea>
        
        <div class="task-comment-actions">
            <!-- File Upload Button (WhatsApp style) -->
            <label for="commentFileInput" class="comment-attach-btn" title="Attach files">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                </svg>
                <span>Attach</span>
            </label>
            <input type="file" 
                   id="commentFileInput" 
                   multiple 
                   accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                   style="display: none;"
                   onchange="handleCommentFiles(event)">
            
            <button class="task-btn task-btn-primary" onclick="submitComment()">
                <i class="fas fa-paper-plane"></i> Comment
            </button>
        </div>
    </div>
</div>




    <!-- Activity Timeline -->
    <div class="task-activity-timeline">
        @forelse($task->activities as $activity)
            <div class="task-activity-item {{ $activity->type }}">
                <div class="task-activity-avatar">
                    @if($activity->actor->avatar_url)
                        <img src="{{ $activity->actor->avatar_url }}" alt="{{ $activity->actor->name }}"
                        referrerpolicy="no-referrer"
                        crossorigin="anonymous"
                        onerror="this.onerror=null; this.src='{{ asset('images/avatar-fallback.png') }}';">
                    @else
                        <div class="task-avatar-fallback">{{ substr($activity->actor->name, 0, 1) }}</div>
                    @endif
                </div>

                <div class="task-activity-content">
                    <div class="task-activity-header">
                        <strong>{{ $activity->actor->name }}</strong>
                        <span class="task-activity-action">{{ getActivityLabel($activity->type) }}</span>
                        <span class="task-activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>

                    @if($activity->type === 'comment')
                    <div class="task-activity-comment">
                        {{ $activity->body }}
                    </div>
                    
                    {{-- 🔥 SHOW FILES FOR COMMENTS TOO --}}
                    @php
                        $activityAttachments = $task->attachments
                            ->whereBetween('created_at', [
                                $activity->created_at->copy()->subSeconds(2),
                                $activity->created_at->copy()->addSeconds(2)
                            ])
                            ->where('uploaded_by', $activity->actor_id);
                    @endphp
                
                    @if($activityAttachments->count() > 0)
                        <div class="task-activity-attachments" style="margin-top: 12px;">
                            <div class="task-attachments-label">
                                <i class="fas fa-paperclip"></i>
                                {{ $activityAttachments->count() }} {{ Str::plural('attachment', $activityAttachments->count()) }}
                            </div>
                            <div class="task-attachments-grid">
                                @foreach($activityAttachments as $attachment)
                                    @if($attachment->type === 'image')
                                        {{-- Image Preview --}}
                                        <div class="task-attachment-preview">
                                            <img src="{{ Storage::url($attachment->path_or_url) }}" 
                                                 alt="{{ $attachment->label }}"
                                                 onclick="openImageViewer('{{ Storage::url($attachment->path_or_url) }}')"
                                                 loading="lazy">
                                            <div class="task-attachment-overlay">
                                                <button class="task-attachment-action" 
                                                        onclick="event.stopPropagation(); downloadAttachment({{ $attachment->id }})">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                            <div class="task-attachment-name">{{ $attachment->label }}</div>
                                        </div>
                                    @else
                                        {{-- File Item --}}
                                        <div class="task-attachment-file">
                                            @php
                                                $ext = pathinfo($attachment->label, PATHINFO_EXTENSION);
                                                $iconClass = match($ext) {
                                                    'pdf' => 'fa-file-pdf',
                                                    'doc', 'docx' => 'fa-file-word',
                                                    'xls', 'xlsx' => 'fa-file-excel',
                                                    'zip', 'rar' => 'fa-file-archive',
                                                    'txt' => 'fa-file-alt',
                                                    default => 'fa-file'
                                                };
                                                $iconColor = match($ext) {
                                                    'pdf' => '#DE350B',
                                                    'doc', 'docx' => '#0052CC',
                                                    'xls', 'xlsx' => '#00875A',
                                                    'zip', 'rar' => '#6B778C',
                                                    default => '#5E6C84'
                                                };
                                            @endphp
                                            <div class="task-file-icon" style="color: {{ $iconColor }};">
                                                <i class="fas {{ $iconClass }}"></i>
                                            </div>
                                            <div class="task-file-details">
                                                <div class="task-file-name">{{ $attachment->label }}</div>
                                                <div class="task-file-size">{{ $ext }}</div>
                                            </div>
                                            <button class="task-file-download" onclick="downloadAttachment({{ $attachment->id }})">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                @elseif(in_array($activity->type, ['completed', 'blocked', 'postponed', 'review']))
                        {{-- Status updates with remark --}}
                        <div class="task-activity-status-update">
                            <div class="task-status-update-badge {{ $activity->type }}">
                                @switch($activity->type)
                                    @case('completed')
                                        <i class="fas fa-check-circle"></i>
                                        <span>Marked as Done</span>
                                        @break
                                    @case('blocked')
                                        <i class="fas fa-ban"></i>
                                        <span>Marked as Blocked</span>
                                        @break
                                    @case('postponed')
                                        <i class="fas fa-clock"></i>
                                        <span>Postponed</span>
                                        @break
                                    @case('review')
                                        <i class="fas fa-eye"></i>
                                        <span>Submitted for Review</span>
                                        @break
                                @endswitch
                            </div>
                            
                            @if($activity->body)
                                <div class="task-activity-remark">
                                    <div class="task-remark-label">Note:</div>
                                    <div class="task-remark-text">{{ $activity->body }}</div>
                                </div>
                            @endif

                            {{-- Display attached files --}}
                            @php
                                $activityAttachments = $task->attachments
    ->whereBetween('created_at', [
        $activity->created_at->copy()->subSeconds(2), // Tighter window since we're matching timestamps
        $activity->created_at->copy()->addSeconds(2)
    ])
    ->where('uploaded_by', $activity->actor_id);
                            @endphp

                            @if($activityAttachments->count() > 0)
                                <div class="task-activity-attachments">
                                    <div class="task-attachments-label">
                                        <i class="fas fa-paperclip"></i>
                                        {{ $activityAttachments->count() }} {{ Str::plural('attachment', $activityAttachments->count()) }}
                                    </div>
                                    <div class="task-attachments-grid">
                                        @foreach($activityAttachments as $attachment)
                                            @if($attachment->type === 'image')
                                                {{-- Image Preview --}}
                                                <div class="task-attachment-preview">
                                                    <img src="{{ Storage::url($attachment->path_or_url) }}" 
                                                         alt="{{ $attachment->label }}"
                                                         onclick="openImageViewer('{{ Storage::url($attachment->path_or_url) }}')"
                                                         loading="lazy"
                                                         >
                                                    <div class="task-attachment-overlay">
                                                        <button class="task-attachment-action" 
                                                                onclick="event.stopPropagation(); downloadAttachment({{ $attachment->id }})">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    </div>
                                                    <div class="task-attachment-name">{{ $attachment->label }}</div>
                                                </div>
                                            @else
                                                {{-- File Item --}}
                                                <div class="task-attachment-file">
                                                    @php
                                                        $ext = pathinfo($attachment->label, PATHINFO_EXTENSION);
                                                        $iconClass = match($ext) {
                                                            'pdf' => 'fa-file-pdf',
                                                            'doc', 'docx' => 'fa-file-word',
                                                            'xls', 'xlsx' => 'fa-file-excel',
                                                            'zip', 'rar' => 'fa-file-archive',
                                                            'txt' => 'fa-file-alt',
                                                            default => 'fa-file'
                                                        };
                                                        $iconColor = match($ext) {
                                                            'pdf' => '#DE350B',
                                                            'doc', 'docx' => '#0052CC',
                                                            'xls', 'xlsx' => '#00875A',
                                                            'zip', 'rar' => '#6B778C',
                                                            default => '#5E6C84'
                                                        };
                                                    @endphp
                                                    <div class="task-file-icon" style="color: {{ $iconColor }};">
                                                        <i class="fas {{ $iconClass }}"></i>
                                                    </div>
                                                    <div class="task-file-details">
                                                        <div class="task-file-name">{{ $attachment->label }}</div>
                                                        <div class="task-file-size">{{ $ext }}</div>
                                                    </div>
                                                    <button class="task-file-download" onclick="downloadAttachment({{ $attachment->id }})">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            
                        
                                @endif
                        </div>
                    @elseif($activity->type === 'status_change')
                        <div class="task-activity-change">
                            Status changed to <strong>{{ $activity->body }}</strong>
                        </div>
                    @elseif($activity->type === 'reassigned')
                        <div class="task-activity-change">
                            {{ $activity->body }}
                        </div>
                    @elseif($activity->type === 'attachment_uploaded')
                        <div class="task-activity-change">
                            <i class="fas fa-paperclip"></i> {{ $activity->body }}
                        </div>
                    @else
                        <div class="task-activity-change">
                            {{ $activity->body }}
                        </div>
                    @endif
                </div>

                <div class="task-activity-icon {{ $activity->type }}">
                    @switch($activity->type)
                        @case('created')
                            <i class="fas fa-plus"></i>
                            @break
                        @case('comment')
                            <i class="fas fa-comment"></i>
                            @break
                        @case('completed')
                        @case('done')
                            <i class="fas fa-check-circle"></i>
                            @break
                        @case('blocked')
                            <i class="fas fa-ban"></i>
                            @break
                        @case('postponed')
                            <i class="fas fa-clock"></i>
                            @break
                        @case('review')
                            <i class="fas fa-eye"></i>
                            @break
                        @case('status_change')
                            <i class="fas fa-exchange-alt"></i>
                            @break
                        @case('reassigned')
                            <i class="fas fa-user-plus"></i>
                            @break
                        @case('subtask_completed')
                            <i class="fas fa-check"></i>
                            @break
                        @case('attachment_uploaded')
                            <i class="fas fa-paperclip"></i>
                            @break
                        @default
                            <i class="fas fa-circle"></i>
                    @endswitch
                </div>
            </div>
        @empty
            <div class="task-empty-state">
                <i class="fas fa-history"></i>
                <p>No activity yet</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Image Viewer Modal (if not already in files tab) -->
<div class="task-image-viewer" id="taskImageViewer" style="display: none;" onclick="closeImageViewer()">
    <button class="task-viewer-close">&times;</button>
    <img src="" alt="" id="taskViewerImage">
</div>

@php
    function getActivityLabel($type) {
        $labels = [
            'created' => 'created this task',
            'comment' => 'commented',
            'completed' => 'marked as done',
            'done' => 'marked as done',
            'blocked' => 'marked as blocked',
            'postponed' => 'postponed task',
            'review' => 'submitted for review',
            'status_change' => 'changed status',
            'reassigned' => 'reassigned task',
            'subtask_completed' => 'completed subtask',
            'subtask_reopened' => 'reopened subtask',
            'attachment_uploaded' => 'uploaded file',
            'attachment_deleted' => 'deleted file',
        ];
        return $labels[$type] ?? 'updated';
    }
@endphp

<style>
    /* Comment Attach Button (WhatsApp Style) */
.comment-attach-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #F4F5F7;
    color: #5E6C84;
    border: 1px solid #DFE1E6;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.comment-attach-btn:hover {
    background: #DEEBFF;
    color: #0052CC;
    border-color: #0052CC;
}

.comment-attach-btn svg {
    transition: transform 0.2s;
}

.comment-attach-btn:hover svg {
    transform: rotate(-15deg);
}

/* Files Preview (Fiverr Style - Compact Pills) */
.comment-files-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px;
    background: #F7F8F9;
    border: 1px solid #DFE1E6;
    border-radius: 6px 6px 0 0;
    margin-bottom: -1px;
}

.comment-file-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    background: #FFFFFF;
    border: 1px solid #DFE1E6;
    border-radius: 20px;
    font-size: 12px;
    max-width: 200px;
    transition: all 0.2s;
}

.comment-file-pill:hover {
    border-color: #0052CC;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.comment-file-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.comment-file-icon svg {
    width: 16px;
    height: 16px;
}

/* File type colors */
.comment-file-icon.file-image {
    color: #00875A;
}

.comment-file-icon.file-pdf {
    color: #DE350B;
}

.comment-file-icon.file-doc {
    color: #0052CC;
}

.comment-file-icon.file-excel {
    color: #00875A;
}

.comment-file-icon.file-default {
    color: #6B778C;
}

.comment-file-name {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #172B4D;
    font-weight: 500;
}

.comment-file-remove {
    width: 18px;
    height: 18px;
    border: none;
    background: transparent;
    color: #6B778C;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    flex-shrink: 0;
    transition: all 0.2s;
}

.comment-file-remove:hover {
    background: #FFEBE6;
    color: #DE350B;
}

.comment-file-remove svg {
    width: 12px;
    height: 12px;
}

/* Adjust textarea when files are attached */
.comment-files-preview + .task-comment-input {
    border-radius: 0 0 6px 6px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .comment-attach-btn span {
        display: none;
    }
    
    .comment-attach-btn {
        padding: 8px;
        width: 36px;
        height: 36px;
        justify-content: center;
    }
    
    .comment-file-pill {
        max-width: 150px;
    }
}
    .task-activity-container {
        max-width: 800px;
    }

    .task-activity-header {
        margin-bottom: 24px;
    }

    .task-activity-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #172B4D;
        margin: 0 0 4px 0;
    }

    .task-activity-header p {
        font-size: 13px;
        color: #6B778C;
        margin: 0;
    }

    /* Comment Box */
    .task-comment-box {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .task-comment-avatar {
        flex-shrink: 0;
    }

    .task-comment-avatar img,
    .task-comment-avatar .task-avatar-fallback {
        width: 36px;
        height: 36px;
        border-radius: 50%;
    }

    .task-avatar-fallback {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #FFFFFF;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .task-comment-input-wrap {
        flex: 1;
    }

    .task-comment-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        transition: all 0.2s;
    }

    .task-comment-input:focus {
        outline: none;
        border-color: #0052CC;
        box-shadow: 0 0 0 3px rgba(0,82,204,0.1);
    }

    .task-comment-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
    }

    /* Activity Timeline */
    .task-activity-timeline {
        position: relative;
    }

    .task-activity-timeline::before {
        content: '';
        position: absolute;
        left: 18px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #DFE1E6;
    }

    .task-activity-item {
        position: relative;
        display: flex;
        gap: 12px;
        padding: 0 0 24px 0;
    }

    .task-activity-item:last-child {
        padding-bottom: 0;
    }

    .task-activity-avatar {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }

    .task-activity-avatar img,
    .task-activity-avatar .task-avatar-fallback {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #FFFFFF;
    }

    .task-activity-content {
        flex: 1;
        min-width: 0;
    }

    .task-activity-header {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .task-activity-header strong {
        color: #172B4D;
        font-weight: 600;
    }

    .task-activity-action {
        color: #6B778C;
    }

    .task-activity-time {
        color: #6B778C;
        margin-left: auto;
    }

    .task-activity-comment {
        padding: 12px;
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.5;
        color: #42526E;
        /* white-space: pre-wrap; */
    }

    /* Status Update Card */
    .task-activity-status-update {
        background: #FFFFFF;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        overflow: hidden;
    }

    .task-status-update-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 1px solid #DFE1E6;
    }

    .task-status-update-badge.completed {
        background: #E3FCEF;
        color: #00875A;
    }

    .task-status-update-badge.blocked {
        background: #FFEBE6;
        color: #DE350B;
    }

    .task-status-update-badge.postponed {
        background: #EAE6FF;
        color: #8777D9;
    }

    .task-status-update-badge.review {
        background: #FFFAE6;
        color: #FF991F;
    }

    .task-activity-remark {
        padding: 12px 16px;
        border-bottom: 1px solid #DFE1E6;
    }

    .task-remark-label {
        font-size: 11px;
        font-weight: 700;
        color: #6B778C;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .task-remark-text {
        font-size: 14px;
        line-height: 1.5;
        color: #172B4D;
        white-space: pre-wrap;
    }

    /* Activity Attachments */
    .task-activity-attachments {
        padding: 12px 16px;
    }

    .task-attachments-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #6B778C;
        margin-bottom: 12px;
    }

    .task-attachments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    /* Image Preview in Activity */
    .task-attachment-preview {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 6px;
        overflow: hidden;
        background: #F4F5F7;
        cursor: pointer;
        transition: all 0.2s;
    }

    .task-attachment-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .task-attachment-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .task-attachment-preview:hover img {
        transform: scale(1.05);
    }

    .task-attachment-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .task-attachment-preview:hover .task-attachment-overlay {
        opacity: 1;
    }

    .task-attachment-action {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        color: #172B4D;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .task-attachment-action:hover {
        background: #FFFFFF;
        transform: scale(1.1);
    }

    .task-attachment-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 6px 8px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: #FFFFFF;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* File Item in Activity */
    .task-attachment-file {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #F4F5F7;
        border: 1px solid #DFE1E6;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .task-attachment-file:hover {
        background: #FFFFFF;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .task-file-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .task-file-details {
        flex: 1;
        min-width: 0;
    }

    .task-file-name {
        font-size: 12px;
        font-weight: 600;
        color: #172B4D;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .task-file-size {
        font-size: 10px;
        color: #6B778C;
        text-transform: uppercase;
    }

    .task-file-download {
        width: 28px;
        height: 28px;
        border: 1px solid #DFE1E6;
        border-radius: 4px;
        background: #FFFFFF;
        color: #6B778C;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .task-file-download:hover {
        background: #0052CC;
        border-color: #0052CC;
        color: #FFFFFF;
    }

    .task-activity-change {
        font-size: 13px;
        color: #6B778C;
        padding: 8px 12px;
        background: #F4F5F7;
        border-radius: 4px;
        display: inline-block;
    }

    .task-activity-change strong {
        color: #172B4D;
    }

    .task-activity-icon {
        position: absolute;
        right: 0;
        top: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        background: #F4F5F7;
        color: #6B778C;
    }

    .task-activity-icon.comment {
        background: #DEEBFF;
        color: #0052CC;
    }

    .task-activity-icon.completed,
    .task-activity-icon.done {
        background: #E3FCEF;
        color: #00875A;
    }

    .task-activity-icon.blocked {
        background: #FFEBE6;
        color: #DE350B;
    }

    .task-activity-icon.postponed {
        background: #EAE6FF;
        color: #8777D9;
    }

    .task-activity-icon.review {
        background: #FFFAE6;
        color: #FF991F;
    }

    .task-activity-icon.status_change {
        background: #FFF0B3;
        color: #FF991F;
    }

    .task-activity-icon.subtask_completed {
        background: #E3FCEF;
        color: #00875A;
    }

    /* Image Viewer Modal */
    .task-image-viewer {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .task-viewer-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        border: none;
        background: rgba(255,255,255,0.2);
        color: #FFFFFF;
        font-size: 32px;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .task-viewer-close:hover {
        background: rgba(255,255,255,0.3);
    }

    .task-image-viewer img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .task-activity-timeline::before {
            left: 18px;
        }

        .task-activity-icon {
            display: none;
        }

        .task-attachments-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .task-comment-box {
            flex-direction: column;
        }
    }
</style>

<script>
function submitComment() {
    const input = document.getElementById('taskCommentInput');
    const comment = input.value.trim();
    
    if (!comment) {
        window.showToast('Please enter a comment', 'error');
        return;
    }

    const taskId = {{ $task->id }};
    const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/comment`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ comment: comment })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast('✅ Comment added', 'success');
            input.value = '';
            
            // Reload page to show new comment
            setTimeout(() => location.reload(), 500);
        } else {
            throw new Error(data.message || 'Failed to add comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast(error.message || 'Failed to add comment', 'error');
    });
}

function downloadAttachment(attachmentId) {
    const taskId = {{ $task->id }};
    window.location.href = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/attachments/${attachmentId}/download`;
}

function openImageViewer(imageSrc) {
    const viewer = document.getElementById('taskImageViewer');
    const img = document.getElementById('taskViewerImage');
    img.src = imageSrc;
    viewer.style.display = 'flex';
}

function closeImageViewer() {
    document.getElementById('taskImageViewer').style.display = 'none';
}









// Store selected comment files
let commentSelectedFiles = [];

// Handle file selection for comments
function handleCommentFiles(event) {
    const files = Array.from(event.target.files);
    
    files.forEach(file => {
        if (file.size > 10 * 1024 * 1024) {
            window.showToast(`"${file.name}" is too large (max 10MB)`, 'error');
            return;
        }
        
        commentSelectedFiles.push(file);
    });
    
    displayCommentFiles();
    event.target.value = ''; // Reset input
}

// Display selected files as pills
function displayCommentFiles() {
    const preview = document.getElementById('commentFilesPreview');
    
    if (commentSelectedFiles.length === 0) {
        preview.style.display = 'none';
        return;
    }
    
    preview.style.display = 'flex';
    preview.innerHTML = '';
    
    commentSelectedFiles.forEach((file, index) => {
        const pill = document.createElement('div');
        pill.className = 'comment-file-pill';
        
        // Determine file type and icon
        const ext = file.name.split('.').pop().toLowerCase();
        let iconClass = 'file-default';
        let iconSvg = '<path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><path d="M13 2v7h7"/>';
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
            iconClass = 'file-image';
            iconSvg = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>';
        } else if (ext === 'pdf') {
            iconClass = 'file-pdf';
            iconSvg = '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M10 13h4M10 17h4"/>';
        } else if (['doc', 'docx'].includes(ext)) {
            iconClass = 'file-doc';
        } else if (['xls', 'xlsx'].includes(ext)) {
            iconClass = 'file-excel';
        }
        
        pill.innerHTML = `
            <div class="comment-file-icon ${iconClass}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    ${iconSvg}
                </svg>
            </div>
            <span class="comment-file-name" title="${file.name}">${file.name}</span>
            <button type="button" class="comment-file-remove" onclick="removeCommentFile(${index})">
                <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 2l8 8M10 2l-8 8" stroke-linecap="round"/>
                </svg>
            </button>
        `;
        
        preview.appendChild(pill);
    });
}

// Remove file from selection
function removeCommentFile(index) {
    commentSelectedFiles.splice(index, 1);
    displayCommentFiles();
}

// Submit comment with files
function submitComment() {
    const input = document.getElementById('taskCommentInput');
    const comment = input.value.trim();
    
    if (!comment) {
        window.showToast('Please enter a comment', 'error');
        return;
    }

    const taskId = {{ $task->id }};
    const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/comment`;
    
    // Create FormData to include files
    const formData = new FormData();
    formData.append('comment', comment);
    
    // Append all selected files
    commentSelectedFiles.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file);
    });
    
    // Disable input during upload
    input.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast('✅ Comment added', 'success');
            input.value = '';
            input.disabled = false;
            
            // Clear files
            commentSelectedFiles = [];
            displayCommentFiles();
            
            // Reload page to show new comment
            setTimeout(() => location.reload(), 500);
        } else {
            throw new Error(data.message || 'Failed to add comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast(error.message || 'Failed to add comment', 'error');
        input.disabled = false;
    });
}
</script>
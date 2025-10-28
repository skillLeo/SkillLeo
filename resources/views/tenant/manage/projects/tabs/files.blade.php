{{-- resources/views/tenant/manage/projects/tabs/files.blade.php --}}

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$clientMedia = $project->media()->where('visibility', 'client')->orderBy('sort_order')->orderBy('id')->get();
$internalMedia = $project->media()->where('visibility', 'internal')->orderBy('sort_order')->orderBy('id')->get();
$totalFiles = $project->media->count();
@endphp

<div class="pro-files">
    <!-- Files Header -->
    <div class="pro-files-header">
        <div class="pro-files-controls">
            <div class="pro-view-switcher">
                <button class="pro-view-btn active" onclick="switchFileView('grid')">
                    <i class="fas fa-th"></i>
                </button>
                <button class="pro-view-btn" onclick="switchFileView('list')">
                    <i class="fas fa-list"></i>
                </button>
            </div>
            <div class="pro-files-filter">
                <button class="pro-filter-btn active" data-filter="all">
                    All Files <span>{{ $totalFiles }}</span>
                </button>
                <button class="pro-filter-btn" data-filter="client">
                    Client <span>{{ $clientMedia->count() }}</span>
                </button>
                <button class="pro-filter-btn" data-filter="internal">
                    Internal <span>{{ $internalMedia->count() }}</span>
                </button>
            </div>
        </div>
        <button class="pro-btn pro-btn-primary" onclick="openUploadModal()">
            <i class="fas fa-upload"></i> Upload Files
        </button>
    </div>

    @if($totalFiles > 0)
        <!-- Grid View -->
        <div id="gridView" class="pro-files-grid">
            @foreach($project->media as $media)
                <div class="pro-file-card" data-visibility="{{ $media->visibility }}">
                    <div class="pro-file-preview">
                        @if(Str::startsWith($media->mime_type, 'image/'))
                            <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->original_name }}" />
                        @else
                            <div class="pro-file-icon">
                                @php
                                    $ext = pathinfo($media->original_name, PATHINFO_EXTENSION);
                                    $iconMap = [
                                        'pdf' => 'file-pdf',
                                        'doc' => 'file-word', 'docx' => 'file-word',
                                        'xls' => 'file-excel', 'xlsx' => 'file-excel',
                                        'ppt' => 'file-powerpoint', 'pptx' => 'file-powerpoint',
                                        'zip' => 'file-archive', 'rar' => 'file-archive',
                                        'mp4' => 'file-video', 'mov' => 'file-video',
                                        'mp3' => 'file-audio', 'wav' => 'file-audio',
                                    ];
                                    $icon = $iconMap[$ext] ?? 'file';
                                @endphp
                                <i class="fas fa-{{ $icon }}"></i>
                                <span class="pro-file-ext">{{ strtoupper($ext) }}</span>
                            </div>
                        @endif
                        
                        <div class="pro-file-overlay">
                            <button class="pro-file-action" onclick="viewFile({{ $media->id }})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="pro-file-action" onclick="downloadFile({{ $media->id }})" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="pro-file-action delete" onclick="deleteFile({{ $media->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="pro-file-badge pro-badge-{{ $media->visibility }}">
                            <i class="fas fa-{{ $media->visibility === 'client' ? 'eye' : 'lock' }}"></i>
                            {{ ucfirst($media->visibility) }}
                        </div>
                    </div>

                    <div class="pro-file-info">
                        <h4 class="pro-file-name" title="{{ $media->original_name }}">
                            {{ Str::limit($media->original_name, 30) }}
                        </h4>
                        <div class="pro-file-meta">
                            <span class="pro-file-size">{{ number_format($media->size_bytes / 1024, 1) }} KB</span>
                            <span class="pro-file-date">{{ $media->created_at->format('M d') }}</span>
                        </div>
                        @if($media->note)
                            <div class="pro-file-note">
                                <i class="fas fa-comment-alt"></i>
                                <span>{{ Str::limit($media->note, 50) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- List View -->
        <div id="listView" class="pro-files-list" style="display: none;">
            <div class="pro-files-list-header">
                <div class="pro-file-list-name">Name</div>
                <div class="pro-file-list-type">Type</div>
                <div class="pro-file-list-size">Size</div>
                <div class="pro-file-list-visibility">Visibility</div>
                <div class="pro-file-list-date">Uploaded</div>
                <div class="pro-file-list-actions"></div>
            </div>

            @foreach($project->media as $media)
                <div class="pro-files-list-row" data-visibility="{{ $media->visibility }}">
                    <div class="pro-file-list-name">
                        <div class="pro-file-list-icon">
                            @php
                                $ext = pathinfo($media->original_name, PATHINFO_EXTENSION);
                            @endphp
                            @if(Str::startsWith($media->mime_type, 'image/'))
                                <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->original_name }}" />
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>
                        <div class="pro-file-list-info">
                            <h4>{{ $media->original_name }}</h4>
                            @if($media->note)
                                <p>{{ Str::limit($media->note, 60) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="pro-file-list-type">
                        <span class="pro-type-badge">{{ strtoupper($ext) }}</span>
                    </div>
                    <div class="pro-file-list-size">
                        {{ number_format($media->size_bytes / 1024, 1) }} KB
                    </div>
                    <div class="pro-file-list-visibility">
                        <span class="pro-badge-{{ $media->visibility }}">
                            <i class="fas fa-{{ $media->visibility === 'client' ? 'eye' : 'lock' }}"></i>
                            {{ ucfirst($media->visibility) }}
                        </span>
                    </div>
                    <div class="pro-file-list-date">
                        {{ $media->created_at->format('M d, Y') }}
                    </div>
                    <div class="pro-file-list-actions">
                        <button class="pro-btn-icon" onclick="viewFile({{ $media->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="pro-btn-icon" onclick="downloadFile({{ $media->id }})">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="pro-btn-icon" onclick="deleteFile({{ $media->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="pro-empty">
            <i class="fas fa-folder-open"></i>
            <h3>No Files Yet</h3>
            <p>Upload files to share with your team and clients</p>
            <button class="pro-btn pro-btn-primary" onclick="openUploadModal()">
                <i class="fas fa-upload"></i> Upload Files
            </button>
        </div>
    @endif
</div>

<style>
/* Files Styles */
.pro-files {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.pro-files-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.pro-files-controls {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.pro-view-switcher {
    display: flex;
    gap: 2px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 2px;
}

.pro-view-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
}

.pro-view-btn:hover {
    background: var(--bg);
    color: var(--text-body);
}

.pro-view-btn.active {
    background: var(--accent);
    color: #fff;
}

.pro-files-filter {
    display: flex;
    gap: 4px;
    background: var(--bg);
    padding: 3px;
    border-radius: 6px;
}

.pro-filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
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

.pro-filter-btn span {
    padding: 1px 6px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
}

.pro-filter-btn.active span {
    background: rgba(255, 255, 255, 0.25);
}

/* Grid View */
.pro-files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
}

.pro-file-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s;
}

.pro-file-card:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.pro-file-preview {
    position: relative;
    aspect-ratio: 16 / 11;
    background: var(--bg);
    overflow: hidden;
}

.pro-file-preview img {
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
    font-size: 42px;
}

.pro-file-ext {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-heading);
    text-transform: uppercase;
}

.pro-file-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.75));
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 6px;
    padding: 12px;
    opacity: 0;
    transition: opacity 0.2s;
}

.pro-file-card:hover .pro-file-overlay {
    opacity: 1;
}

.pro-file-action {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 6px;
    color: var(--text-heading);
    cursor: pointer;
    transition: all 0.2s;
}

.pro-file-action:hover {
    background: #fff;
    transform: scale(1.08);
}

.pro-file-action.delete {
    background: rgba(239, 68, 68, 0.95);
    color: #fff;
}

.pro-file-action.delete:hover {
    background: #ef4444;
}

.pro-file-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

.pro-badge-client {
    background: rgba(16, 185, 129, 0.9);
    color: #fff;
}

.pro-badge-internal {
    background: rgba(107, 114, 128, 0.9);
    color: #fff;
}

.pro-file-info {
    padding: 12px;
}

.pro-file-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 6px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-file-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.pro-file-note {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    padding: 8px;
    background: var(--bg);
    border-radius: 4px;
    font-size: 11px;
    color: var(--text-muted);
    line-height: 1.4;
}

.pro-file-note i {
    color: var(--accent);
    font-size: 10px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* List View */
.pro-files-list {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.pro-files-list-header,
.pro-files-list-row {
    display: grid;
    grid-template-columns: 2fr 100px 120px 140px 140px 120px;
    gap: 12px;
    padding: 12px 16px;
    align-items: center;
}

.pro-files-list-header {
    background: var(--bg);
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border);
}

.pro-files-list-row {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}

.pro-files-list-row:hover {
    background: var(--bg);
}

.pro-file-list-name {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pro-file-list-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    overflow: hidden;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pro-file-list-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pro-file-list-icon i {
    font-size: 18px;
    color: var(--accent);
}

.pro-file-list-info {
    flex: 1;
    min-width: 0;
}

.pro-file-list-info h4 {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-heading);
    margin: 0 0 2px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-file-list-info p {
    font-size: 11px;
    color: var(--text-muted);
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pro-type-badge {
    display: inline-flex;
    padding: 3px 8px;
    background: var(--bg);
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    color: var(--text-body);
}

.pro-file-list-size,
.pro-file-list-date {
    font-size: 12px;
    color: var(--text-body);
}

.pro-file-list-visibility span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.pro-file-list-visibility .pro-badge-client {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.pro-file-list-visibility .pro-badge-internal {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.pro-file-list-actions {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 1024px) {
    .pro-files-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }

    .pro-files-list-header,
    .pro-files-list-row {
        grid-template-columns: 2fr 80px 100px 120px 120px 100px;
    }
}

@media (max-width: 768px) {
    .pro-files-header {
        flex-direction: column;
        align-items: stretch;
    }

    .pro-files-controls {
        width: 100%;
        justify-content: space-between;
    }

    .pro-files-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }

    .pro-files-list-header,
    .pro-files-list-row {
        grid-template-columns: 1fr;
    }

    .pro-file-list-type,
    .pro-file-list-size,
    .pro-file-list-visibility,
    .pro-file-list-date {
        display: none;
    }

    .pro-files-list-header {
        display: none;
    }
}
</style>

<script>
function switchFileView(view) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const buttons = document.querySelectorAll('.pro-view-btn');

    buttons.forEach(btn => {
        btn.classList.remove('active');
    });

    if (view === 'grid') {
        gridView.style.display = 'grid';
        listView.style.display = 'none';
        buttons[0].classList.add('active');
    } else {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        buttons[1].classList.add('active');
    }
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.pro-filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const cards = document.querySelectorAll('[data-visibility]');
            cards.forEach(card => {
                if (filter === 'all' || card.dataset.visibility === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function viewFile(fileId) {
    console.log('View file:', fileId);
}

function downloadFile(fileId) {
    window.location.href = `/{{ $username }}/manage/projects/media/${fileId}/download`;
}

function deleteFile(fileId) {
    if (confirm('Delete this file? This action cannot be undone.')) {
        fetch(`/{{ $username }}/manage/projects/media/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function openUploadModal() {
    console.log('Open upload modal');
}
</script>
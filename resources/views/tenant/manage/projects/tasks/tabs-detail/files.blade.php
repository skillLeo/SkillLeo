{{-- resources/views/tenant/manage/projects/tasks/tabs-detail/files.blade.php --}}
@php
    use Illuminate\Support\Facades\Storage;

@endphp
<div class="task-files-container">
    <div class="task-files-header">
        <div class="task-files-header-left">
            <h3>Attachments</h3>
            <p>{{ $task->attachments->count() }} files uploaded</p>
        </div>
        
        <button class="task-btn task-btn-primary" onclick="openFileUpload()">
            <i class="fas fa-upload"></i> Upload File
        </button>
    </div>

    <!-- File Upload Area (Hidden) -->
    <div class="task-upload-area" id="taskUploadArea" style="display: none;">
        <div class="task-upload-dropzone" id="taskDropzone">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag and drop files here or click to browse</p>
            <span class="task-upload-hint">Max file size: 10MB</span>
            <input type="file" 
                   id="taskFileInput" 
                   multiple 
                   accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                   style="display: none;">
        </div>
        <div class="task-upload-preview" id="taskUploadPreview"></div>
    </div>

    @if($task->attachments->count() > 0)
        <!-- Images Gallery -->
        @if($images->count() > 0)
            <div class="task-files-section">
                <h4 class="task-files-section-title">
                    <i class="fas fa-images"></i> Images ({{ $images->count() }})
                </h4>
                <div class="task-images-grid">
                    @foreach($images as $image)
                        <div class="task-image-item" data-attachment-id="{{ $image->id }}">
                            <div class="task-image-preview" onclick="openImageViewer('{{ Storage::url($image->path_or_url) }}')">
                                <img src="{{ Storage::url($image->path_or_url) }}" 
                                     alt="{{ $image->label }}"
                                     loading="lazy">
                                <div class="task-image-overlay">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                            <div class="task-image-info">
                                <div class="task-image-name" title="{{ $image->label }}">{{ $image->label }}</div>
                                <div class="task-image-meta">
                                    <span>{{ $image->created_at->format('M d, Y') }}</span>
                                    <button class="task-file-action" onclick="downloadFile({{ $image->id }})">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="task-file-action task-file-action-danger" onclick="deleteFile({{ $image->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Other Files -->
        @if($files->count() > 0)
            <div class="task-files-section">
                <h4 class="task-files-section-title">
                    <i class="fas fa-file"></i> Documents ({{ $files->count() }})
                </h4>
                <div class="task-files-list">
                    @foreach($files as $file)
                        <div class="task-file-item" data-attachment-id="{{ $file->id }}">
                            <div class="task-file-icon">
                                @php
                                    $ext = pathinfo($file->label, PATHINFO_EXTENSION);
                                    $iconClass = match($ext) {
                                        'pdf' => 'fa-file-pdf',
                                        'doc', 'docx' => 'fa-file-word',
                                        'xls', 'xlsx' => 'fa-file-excel',
                                        'zip', 'rar' => 'fa-file-archive',
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
                                <i class="fas {{ $iconClass }}" style="color: {{ $iconColor }};"></i>
                            </div>
                            <div class="task-file-info">
                                <div class="task-file-name">{{ $file->label }}</div>
                                <div class="task-file-meta">
                                    <span>Uploaded by {{ $file->uploader->name }}</span>
                                    <span>•</span>
                                    <span>{{ $file->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="task-file-actions">
                                <button class="task-btn task-btn-secondary task-btn-sm" onclick="downloadFile({{ $file->id }})">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                <button class="task-file-action task-file-action-danger" onclick="deleteFile({{ $file->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="task-empty-state">
            <i class="fas fa-paperclip"></i>
            <h4>No files attached yet</h4>
            <p>Upload images, documents, and other files to this task</p>
            <button class="task-btn task-btn-primary" onclick="openFileUpload()">
                <i class="fas fa-upload"></i> Upload Your First File
            </button>
        </div>
    @endif
</div>

<!-- Image Viewer Modal -->
<div class="task-image-viewer" id="taskImageViewer" style="display: none;" onclick="closeImageViewer()">
    <button class="task-viewer-close">&times;</button>
    <img src="" alt="" id="taskViewerImage">
</div>

<style>
    .task-files-container {
        max-width: 100%;
    }

    .task-files-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .task-files-header-left h3 {
        font-size: 18px;
        font-weight: 700;
        color: #172B4D;
        margin: 0 0 4px 0;
    }

    .task-files-header-left p {
        font-size: 13px;
        color: #6B778C;
        margin: 0;
    }

    /* Upload Area */
    .task-upload-area {
        margin-bottom: 24px;
    }

    .task-upload-dropzone {
        padding: 40px;
        border: 2px dashed #DFE1E6;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #FAFBFC;
    }

    .task-upload-dropzone:hover,
    .task-upload-dropzone.drag-over {
        border-color: #0052CC;
        background: #DEEBFF;
    }

    .task-upload-dropzone i {
        font-size: 48px;
        color: #6B778C;
        margin-bottom: 16px;
    }

    .task-upload-dropzone p {
        font-size: 14px;
        color: #172B4D;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .task-upload-hint {
        font-size: 12px;
        color: #6B778C;
    }

    .task-upload-preview {
        margin-top: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
    }

    /* Files Section */
    .task-files-section {
        margin-bottom: 32px;
    }

    .task-files-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #172B4D;
        margin: 0 0 16px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #DFE1E6;
    }

    .task-files-section-title i {
        color: #6B778C;
    }

    /* Images Grid */
    .task-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .task-image-item {
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        overflow: hidden;
        background: #FFFFFF;
        transition: all 0.2s;
    }

    .task-image-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .task-image-preview {
        position: relative;
        aspect-ratio: 4/3;
        cursor: pointer;
        overflow: hidden;
        background: #F4F5F7;
    }

    .task-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .task-image-preview:hover img {
        transform: scale(1.05);
    }

    .task-image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .task-image-preview:hover .task-image-overlay {
        opacity: 1;
    }

    .task-image-overlay i {
        font-size: 32px;
        color: #FFFFFF;
    }

    .task-image-info {
        padding: 12px;
    }

    .task-image-name {
        font-size: 13px;
        font-weight: 600;
        color: #172B4D;
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .task-image-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 11px;
        color: #6B778C;
    }

    /* Files List */
    .task-files-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .task-file-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #FAFBFC;
        border: 1px solid #DFE1E6;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .task-file-item:hover {
        background: #FFFFFF;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .task-file-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        border: 1px solid #DFE1E6;
    }

    .task-file-info {
        flex: 1;
        min-width: 0;
    }

    .task-file-name {
        font-size: 14px;
        font-weight: 600;
        color: #172B4D;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .task-file-meta {
        font-size: 12px;
        color: #6B778C;
        display: flex;
        gap: 6px;
    }

    .task-file-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .task-btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .task-file-action {
        width: 32px;
        height: 32px;
        border: 1px solid #DFE1E6;
        border-radius: 4px;
        background: #FFFFFF;
        color: #6B778C;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .task-file-action:hover {
        background: #F4F5F7;
        border-color: #0052CC;
        color: #0052CC;
    }

    .task-file-action-danger:hover {
        background: #FFEBE6;
        border-color: #DE350B;
        color: #DE350B;
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
        .task-images-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .task-file-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .task-file-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>

<script>
const taskId = {{ $task->id }};

// Open file upload
function openFileUpload() {
    const uploadArea = document.getElementById('taskUploadArea');
    uploadArea.style.display = 'block';
    
    const dropzone = document.getElementById('taskDropzone');
    const fileInput = document.getElementById('taskFileInput');
    
    dropzone.onclick = () => fileInput.click();
    
    fileInput.onchange = (e) => handleFiles(e.target.files);
    
    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('drag-over'), false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('drag-over'), false);
    });
    
    dropzone.addEventListener('drop', (e) => handleFiles(e.dataTransfer.files), false);
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (file.size > 10 * 1024 * 1024) {
            window.showToast(`${file.name} is too large (max 10MB)`, 'error');
            return;
        }
        uploadFile(file);
    });
}

function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('label', file.name);
    
    const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/attachments`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast(`✅ ${file.name} uploaded`, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Upload failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast(error.message || 'Upload failed', 'error');
    });
}

function downloadFile(attachmentId) {
    window.location.href = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/attachments/${attachmentId}/download`;
}

function deleteFile(attachmentId) {
    if (!confirm('Are you sure you want to delete this file?')) return;
    
    const url = `/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/attachments/${attachmentId}`;
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast('✅ File deleted', 'success');
            document.querySelector(`[data-attachment-id="${attachmentId}"]`).remove();
        } else {
            throw new Error(data.message || 'Delete failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast(error.message || 'Delete failed', 'error');
    });
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
</script>
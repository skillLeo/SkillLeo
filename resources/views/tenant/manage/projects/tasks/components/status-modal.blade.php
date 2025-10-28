{{-- resources/views/tenant/manage/projects/tasks/components/status-modal.blade.php --}}

<!-- Status Update Modal -->
<div id="taskStatusModal" class="task-modal-overlay" style="display: none;" onclick="closeStatusModal(event)">
    <div class="task-modal-container" onclick="event.stopPropagation()">
        <div class="task-modal-header">
            <h2 class="task-modal-title" id="modalTitle">Update Task</h2>
            <button class="task-modal-close" onclick="closeStatusModal()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 5l10 10M15 5l-10 10" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form id="taskStatusForm" onsubmit="submitTaskStatus(event)">
            <input type="hidden" id="modalTaskId" name="task_id">
            <input type="hidden" id="modalSubtaskId" name="subtask_id">
            <input type="hidden" id="modalAction" name="action">

            <div class="task-modal-body">
                <!-- Status Selection (shown for status updates) -->
                <div id="statusSelection" style="display: none;">
                    <label class="modal-label">Change Status To</label>
                    <div class="status-options">
                        <label class="status-option">
                            <input type="radio" name="status" value="done">
                            <div class="status-option-card status-done">
                                <div class="status-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="status-info">
                                    <div class="status-name">Mark as Done</div>
                                    <div class="status-desc">Task completed successfully</div>
                                </div>
                            </div>
                        </label>

                        <label class="status-option">
                            <input type="radio" name="status" value="postponed">
                            <div class="status-option-card status-postponed">
                                <div class="status-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="status-info">
                                    <div class="status-name">Postpone Task</div>
                                    <div class="status-desc">Delay to a later date</div>
                                </div>
                            </div>
                        </label>

                        <label class="status-option">
                            <input type="radio" name="status" value="blocked">
                            <div class="status-option-card status-blocked">
                                <div class="status-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M4.5 4.5l15 15"/>
                                    </svg>
                                </div>
                                <div class="status-info">
                                    <div class="status-name">Mark as Blocked</div>
                                    <div class="status-desc">Cannot proceed currently</div>
                                </div>
                            </div>
                        </label>

                        <label class="status-option">
                            <input type="radio" name="status" value="cancelled">
                            <div class="status-option-card status-cancelled">
                                <div class="status-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="status-info">
                                    <div class="status-name">Unable to Do</div>
                                    <div class="status-desc">Task cannot be completed</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Postpone Date (shown when postponed is selected) -->
                <div id="postponeDateField" style="display: none; margin-top: 20px;">
                    <label class="modal-label" for="postponed_until">
                        New Target Date *
                    </label>
                    <input 
                        type="date" 
                        id="postponed_until" 
                        name="postponed_until" 
                        class="modal-input"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    >
                </div>

                <!-- Remark Field -->
                <div style="margin-top: 20px;">
                    <label class="modal-label" for="remark">
                        <span id="remarkLabel">Add Remark</span>
                        <span class="required-badge">Required</span>
                    </label>
                    <textarea 
                        id="remark" 
                        name="remark" 
                        class="modal-textarea" 
                        rows="4" 
                        placeholder="Describe what you did, what challenges you faced, or any important notes..."
                        required
                        minlength="5"
                        maxlength="2000"
                    ></textarea>
                    <div class="textarea-hint">
                        <span id="charCount">0</span>/2000 characters
                    </div>
                </div>

                <!-- File Upload -->
                <div style="margin-top: 20px;">
                    <label class="modal-label">
                        Attachments
                        <span class="optional-badge">Optional</span>
                    </label>
                    
                    <div class="upload-area" id="uploadArea">
                        <input 
                            type="file" 
                            id="attachments" 
                            name="attachments[]" 
                            multiple 
                            accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                            style="display: none;"
                            onchange="handleFileSelect(event)"
                        >
                        <label for="attachments" class="upload-label">
                            <div class="upload-icon">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M24 16v16m-8-8h16" stroke-linecap="round"/>
                                    <rect x="8" y="8" width="32" height="32" rx="4"/>
                                </svg>
                            </div>
                            <div class="upload-text">
                                <div class="upload-primary">Click to upload or drag and drop</div>
                                <div class="upload-secondary">PNG, JPG, PDF, DOC up to 10MB each</div>
                            </div>
                        </label>
                    </div>

                    <!-- Preview Area -->
                    <div id="filePreview" class="file-preview-grid" style="display: none;"></div>
                </div>
            </div>

            <div class="task-modal-footer">
                <button type="button" class="modal-btn modal-btn-secondary" onclick="closeStatusModal()">
                    Cancel
                </button>
                <button type="submit" class="modal-btn modal-btn-primary" id="submitBtn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 8l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span id="submitBtnText">Submit</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal Overlay */
.task-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(9, 30, 66, 0.54);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.task-modal-container {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal Header */
.task-modal-header {
    padding: 24px 24px 20px;
    border-bottom: 1px solid #DFE1E6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.task-modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #172B4D;
    margin: 0;
}

.task-modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #6B778C;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.task-modal-close:hover {
    background: #F4F5F7;
    color: #172B4D;
}

/* Modal Body */
.task-modal-body {
    padding: 24px;
}

.modal-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #172B4D;
    margin-bottom: 8px;
}

.required-badge {
    font-size: 11px;
    font-weight: 700;
    color: #DE350B;
    background: rgba(222, 53, 11, 0.08);
    padding: 2px 6px;
    border-radius: 3px;
}

.optional-badge {
    font-size: 11px;
    font-weight: 600;
    color: #6B778C;
    background: #F4F5F7;
    padding: 2px 6px;
    border-radius: 3px;
}

/* Status Options */
.status-options {
    display: grid;
    gap: 12px;
}

.status-option {
    cursor: pointer;
}

.status-option input[type="radio"] {
    display: none;
}

.status-option-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 2px solid #DFE1E6;
    border-radius: 8px;
    transition: all 0.2s;
    background: white;
}

.status-option-card:hover {
    border-color: #0052CC;
    background: #F4F5F7;
}

.status-option input[type="radio"]:checked + .status-option-card {
    border-color: #0052CC;
    background: #DEEBFF;
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.status-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.status-done .status-icon {
    background: rgba(0, 135, 90, 0.1);
    color: #00875A;
}

.status-postponed .status-icon {
    background: rgba(135, 119, 217, 0.1);
    color: #8777D9;
}

.status-blocked .status-icon {
    background: rgba(222, 53, 11, 0.1);
    color: #DE350B;
}

.status-cancelled .status-icon {
    background: rgba(107, 119, 140, 0.1);
    color: #6B778C;
}

.status-info {
    flex: 1;
}

.status-name {
    font-size: 15px;
    font-weight: 600;
    color: #172B4D;
    margin-bottom: 2px;
}

.status-desc {
    font-size: 13px;
    color: #5E6C84;
}

/* Input Fields */
.modal-input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #DFE1E6;
    border-radius: 6px;
    font-size: 14px;
    color: #172B4D;
    transition: all 0.2s;
}

.modal-input:focus {
    outline: none;
    border-color: #0052CC;
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.modal-textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #DFE1E6;
    border-radius: 6px;
    font-size: 14px;
    color: #172B4D;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    resize: vertical;
    transition: all 0.2s;
}

.modal-textarea:focus {
    outline: none;
    border-color: #0052CC;
    box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
}

.textarea-hint {
    font-size: 12px;
    color: #6B778C;
    margin-top: 6px;
    text-align: right;
}

/* Upload Area */
.upload-area {
    border: 2px dashed #DFE1E6;
    border-radius: 8px;
    transition: all 0.2s;
}

.upload-area.drag-over {
    border-color: #0052CC;
    background: #DEEBFF;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 32px 20px;
    cursor: pointer;
}

.upload-icon {
    color: #6B778C;
    margin-bottom: 12px;
}

.upload-text {
    text-align: center;
}

.upload-primary {
    font-size: 14px;
    font-weight: 600;
    color: #0052CC;
    margin-bottom: 4px;
}

.upload-secondary {
    font-size: 12px;
    color: #6B778C;
}

/* File Preview */
.file-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.file-preview-item {
    position: relative;
    border: 1px solid #DFE1E6;
    border-radius: 6px;
    overflow: hidden;
    background: #F4F5F7;
}

.file-preview-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.file-preview-file {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 120px;
    padding: 12px;
}

.file-preview-icon {
    width: 40px;
    height: 40px;
    color: #6B778C;
    margin-bottom: 8px;
}

.file-preview-name {
    font-size: 11px;
    color: #172B4D;
    text-align: center;
    word-break: break-all;
    line-height: 1.3;
}

.file-preview-remove {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 24px;
    height: 24px;
    border: none;
    background: rgba(222, 53, 11, 0.95);
    color: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.file-preview-remove:hover {
    background: #BF2600;
    transform: scale(1.1);
}

/* Modal Footer */
.task-modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #DFE1E6;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    background: #FAFBFC;
}

.modal-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.modal-btn-secondary {
    background: white;
    color: #172B4D;
    border: 1px solid #DFE1E6;
}

.modal-btn-secondary:hover {
    background: #F4F5F7;
}

.modal-btn-primary {
    background: linear-gradient(135deg, #0052CC 0%, #0065FF 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(0, 82, 204, 0.3);
}

.modal-btn-primary:hover {
    background: linear-gradient(135deg, #0747A6 0%, #0052CC 100%);
    box-shadow: 0 4px 12px rgba(0, 82, 204, 0.4);
    transform: translateY(-1px);
}

.modal-btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 640px) {
    .task-modal-container {
        max-width: 100%;
        max-height: 100vh;
        border-radius: 0;
    }

    .task-modal-header,
    .task-modal-body,
    .task-modal-footer {
        padding: 16px;
    }

    .status-options {
        grid-template-columns: 1fr;
    }

    .file-preview-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }
}
</style>

<script>
let selectedFiles = [];

// Open modal
function openStatusModal(taskId, action, subtaskId = null) {
    const modal = document.getElementById('taskStatusModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalAction = document.getElementById('modalAction');
    const modalTaskId = document.getElementById('modalTaskId');
    const modalSubtaskId = document.getElementById('modalSubtaskId');
    const statusSelection = document.getElementById('statusSelection');
    const remarkLabel = document.getElementById('remarkLabel');
    const submitBtnText = document.getElementById('submitBtnText');
    
    // Reset form
    document.getElementById('taskStatusForm').reset();
    selectedFiles = [];
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('filePreview').innerHTML = '';
    
    // Set values
    modalTaskId.value = taskId;
    modalSubtaskId.value = subtaskId || '';
    modalAction.value = action;
    
    // Configure modal based on action
    if (action === 'remark') {
        modalTitle.textContent = 'Add Remark';
        statusSelection.style.display = 'none';
        remarkLabel.textContent = 'Your Remark';
        submitBtnText.textContent = 'Add Remark';
    } else {
        modalTitle.textContent = 'Update Task Status';
        statusSelection.style.display = 'block';
        remarkLabel.textContent = 'Describe your update';
        submitBtnText.textContent = 'Update Status';
        
        // Pre-select status if action is specific
        if (action !== 'status') {
            const radio = document.querySelector(`input[name="status"][value="${action}"]`);
            if (radio) radio.checked = true;
            updatePostponeDateVisibility();
        }
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeStatusModal(event) {
    if (event && event.target !== event.currentTarget) return;
    
    const modal = document.getElementById('taskStatusModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Handle status radio change
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', updatePostponeDateVisibility);
});

function updatePostponeDateVisibility() {
    const status = document.querySelector('input[name="status"]:checked')?.value;
    const postponeDateField = document.getElementById('postponeDateField');
    const postponeInput = document.getElementById('postponed_until');
    
    if (status === 'postponed') {
        postponeDateField.style.display = 'block';
        postponeInput.required = true;
    } else {
        postponeDateField.style.display = 'none';
        postponeInput.required = false;
    }
}

// Character counter
document.getElementById('remark').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('charCount').textContent = count;
    
    if (count > 1900) {
        document.getElementById('charCount').style.color = '#DE350B';
    } else {
        document.getElementById('charCount').style.color = '#6B778C';
    }
});

// File upload handling
const uploadArea = document.getElementById('uploadArea');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.classList.add('drag-over');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, () => {
        uploadArea.classList.remove('drag-over');
    }, false);
});

uploadArea.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
}, false);

function handleFileSelect(event) {
    const files = event.target.files;
    handleFiles(files);
}

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (file.size > 10 * 1024 * 1024) {
            alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
            return;
        }
        
        selectedFiles.push(file);
        displayFilePreview(file);
    });
}

function displayFilePreview(file) {
    const previewContainer = document.getElementById('filePreview');
    previewContainer.style.display = 'grid';
    
    const fileItem = document.createElement('div');
    fileItem.className = 'file-preview-item';
    fileItem.dataset.fileName = file.name;
    
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            fileItem.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}" class="file-preview-image">
                <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                    </svg>
                </button>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        fileItem.innerHTML = `
            <div class="file-preview-file">
                <svg class="file-preview-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                    <path d="M13 2v7h7"/>
                </svg>
                <div class="file-preview-name">${file.name}</div>
            </div>
            <button type="button" class="file-preview-remove" onclick="removeFile('${file.name}')">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3l8 8M11 3l-8 8" stroke-linecap="round"/>
                </svg>
            </button>
        `;
    }
    
    previewContainer.appendChild(fileItem);
}

function removeFile(fileName) {
    selectedFiles = selectedFiles.filter(f => f.name !== fileName);
    const fileItem = document.querySelector(`.file-preview-item[data-file-name="${fileName}"]`);
    if (fileItem) fileItem.remove();
    
    if (selectedFiles.length === 0) {
        document.getElementById('filePreview').style.display = 'none';
    }
}

// Submit form
function submitTaskStatus(event) {
    event.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" class="spinning">
            <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" fill="none" opacity="0.25"/>
            <path d="M8 1a7 7 0 017 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
        <span>Processing...</span>
    `;
    
    const formData = new FormData(event.target);
    
    // Add files to FormData
    selectedFiles.forEach(file => {
        formData.append('attachments[]', file);
    });
    
    const taskId = document.getElementById('modalTaskId').value;
    const action = document.getElementById('modalAction').value;
    const subtaskId = document.getElementById('modalSubtaskId').value;
    
    let url;
    if (action === 'remark') {
        url = `/{{ $username }}/manage/projects/tasks/${taskId}/remark`;
    } else if (subtaskId) {
        url = `/{{ $username }}/manage/projects/tasks/${taskId}/subtasks/${subtaskId}/complete`;
    } else {
        url = `/{{ $username }}/manage/projects/tasks/${taskId}/update-status`;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeStatusModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to update task');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Add spinning animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spinning {
        animation: spin 1s linear infinite;
    }
`;
document.head.appendChild(style);

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeStatusModal();
    }
});
</script>
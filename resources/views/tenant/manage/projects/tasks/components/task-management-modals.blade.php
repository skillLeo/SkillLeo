{{-- resources/views/tenant/manage/projects/tasks/components/task-management-modals.blade.php --}}

<!-- Reassign Task Modal -->
<div id="reassignTaskModal" class="jira-modal" style="display: none;">
    <div class="jira-modal-overlay" onclick="closeReassignModal()"></div>
    <div class="jira-modal-content jira-modal-sm">
        <div class="jira-modal-header">
            <h3><i class="fas fa-user-plus"></i> Reassign Task</h3>
            <button class="jira-modal-close" onclick="closeReassignModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="reassignTaskForm" onsubmit="submitReassignTask(event)">
            <input type="hidden" id="reassignTaskId" name="task_id">

            <div class="jira-modal-body">
                <div class="jira-form-group">
                    <label class="jira-label">Assign to</label>
                    <div class="jira-radio-group">
                        <label class="jira-radio-option">
                            <input type="radio" name="assign_to" value="me" checked onchange="toggleTeammateSelect()">
                            <span class="jira-radio-custom"></span>
                            <span class="jira-radio-label">
                                <i class="fas fa-user"></i> Assign to myself
                            </span>
                        </label>
                        <label class="jira-radio-option">
                            <input type="radio" name="assign_to" value="teammate" onchange="toggleTeammateSelect()">
                            <span class="jira-radio-custom"></span>
                            <span class="jira-radio-label">
                                <i class="fas fa-users"></i> Assign to team member
                            </span>
                        </label>
                    </div>
                </div>

                <div id="teammateSelectWrapper" class="jira-form-group" style="display: none;">
                    <label class="jira-label">Select Team Member</label>
                    <div class="jira-search-wrapper">
                        <input type="text" 
                               class="jira-input" 
                               id="teammateSearch" 
                               placeholder="Search team members..."
                               autocomplete="off"
                               oninput="searchTeammates(this.value)">
                        <i class="fas fa-search jira-search-icon"></i>
                    </div>
                    <input type="hidden" id="selectedUserId" name="user_id">
                    <div id="teammateResults" class="jira-search-results"></div>
                    <div id="selectedTeammate" class="jira-selected-user"></div>
                </div>

                <div class="jira-form-group">
                    <label class="jira-label">Note (optional)</label>
                    <textarea class="jira-textarea" 
                              name="note" 
                              rows="3" 
                              placeholder="Add a note about this reassignment..."></textarea>
                </div>
            </div>

            <div class="jira-modal-footer">
                <button type="button" class="jira-btn jira-btn-secondary" onclick="closeReassignModal()">
                    Cancel
                </button>
                <button type="submit" class="jira-btn jira-btn-primary" id="reassignSubmitBtn">
                    <i class="fas fa-user-check"></i> Reassign Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Task Modal -->
<div id="editTaskModal" class="jira-modal" style="display: none;">
    <div class="jira-modal-overlay" onclick="closeEditModal()"></div>
    <div class="jira-modal-content jira-modal-lg">
        <div class="jira-modal-header">
            <h3><i class="fas fa-edit"></i> Edit Task</h3>
            <button class="jira-modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editTaskForm" onsubmit="submitEditTask(event)">
            <input type="hidden" id="editTaskId" name="task_id">

            <div class="jira-modal-body">
                <div class="jira-form-group">
                    <label class="jira-label">Task Title *</label>
                    <input type="text" 
                           class="jira-input" 
                           name="title" 
                           id="editTaskTitle"
                           required
                           maxlength="255"
                           placeholder="Enter task title...">
                </div>

                <div class="jira-form-group">
                    <label class="jira-label">Description</label>
                    <textarea class="jira-textarea" 
                              name="notes" 
                              id="editTaskNotes"
                              rows="4" 
                              maxlength="5000"
                              placeholder="Add detailed description..."></textarea>
                    <div class="jira-char-count">
                        <span id="editNotesCount">0</span> / 5000
                    </div>
                </div>

                <div class="jira-form-row">
                    <div class="jira-form-group">
                        <label class="jira-label">Priority *</label>
                        <select class="jira-select" name="priority" id="editTaskPriority" required>
                            <option value="low">🟢 Low</option>
                            <option value="medium">🟡 Medium</option>
                            <option value="high">🟠 High</option>
                            <option value="urgent">🔴 Urgent</option>
                        </select>
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Due Date</label>
                        <input type="date" class="jira-input" name="due_date" id="editTaskDueDate">
                    </div>
                </div>

                <div class="jira-form-row">
                    <div class="jira-form-group">
                        <label class="jira-label">Estimated Hours</label>
                        <input type="number" 
                               class="jira-input" 
                               name="estimated_hours" 
                               id="editTaskHours"
                               min="0" 
                               step="0.5"
                               placeholder="0.0">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Story Points</label>
                        <input type="number" 
                               class="jira-input" 
                               name="story_points" 
                               id="editTaskPoints"
                               min="0" 
                               step="1"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <div class="jira-modal-footer">
                <button type="button" class="jira-btn jira-btn-secondary" onclick="closeEditModal()">
                    Cancel
                </button>
                <button type="submit" class="jira-btn jira-btn-primary" id="editSubmitBtn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* ===== JIRA-STYLE MODAL SYSTEM ===== */

.jira-modal {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.jira-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(9, 30, 66, 0.54);
    backdrop-filter: blur(2px);
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.jira-modal-content {
    position: relative;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    max-height: 90vh;
    overflow: hidden;
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
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

.jira-modal-sm {
    width: 100%;
    max-width: 480px;
}

.jira-modal-lg {
    width: 100%;
    max-width: 680px;
}

.jira-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #DFE1E6;
}

.jira-modal-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #172B4D;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.jira-modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #6B778C;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}

.jira-modal-close:hover {
    background: #F4F5F7;
    color: #172B4D;
}

.jira-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.jira-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 16px 24px;
    border-top: 1px solid #DFE1E6;
}

/* Form Elements */
.jira-form-group {
    margin-bottom: 20px;
}

.jira-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.jira-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #172B4D;
    margin-bottom: 6px;
}

.jira-input,
.jira-textarea,
.jira-select {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    font-size: 14px;
    color: #172B4D;
    background: #FAFBFC;
    transition: all 0.15s;
}

.jira-input:focus,
.jira-textarea:focus,
.jira-select:focus {
    outline: none;
    border-color: #0052CC;
    background: #FFFFFF;
    box-shadow: 0 0 0 1px #0052CC;
}

.jira-textarea {
    resize: vertical;
    font-family: inherit;
    line-height: 1.5;
}

.jira-char-count {
    font-size: 11px;
    color: #6B778C;
    text-align: right;
    margin-top: 4px;
}

/* Radio Group */
.jira-radio-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.jira-radio-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 2px solid #DFE1E6;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
}

.jira-radio-option:hover {
    border-color: #0052CC;
    background: #F4F5F7;
}

.jira-radio-option input[type="radio"] {
    display: none;
}

.jira-radio-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #DFE1E6;
    border-radius: 50%;
    position: relative;
    transition: all 0.15s;
}

.jira-radio-option input[type="radio"]:checked + .jira-radio-custom {
    border-color: #0052CC;
}

.jira-radio-option input[type="radio"]:checked + .jira-radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: #0052CC;
    border-radius: 50%;
}

.jira-radio-label {
    font-size: 14px;
    font-weight: 500;
    color: #172B4D;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Search */
.jira-search-wrapper {
    position: relative;
}

.jira-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6B778C;
    pointer-events: none;
}

.jira-search-wrapper .jira-input {
    padding-left: 38px;
}

.jira-search-results {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #DFE1E6;
    border-radius: 4px;
    margin-top: 8px;
    background: #FFFFFF;
}

.jira-user-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    cursor: pointer;
    transition: background 0.15s;
}

.jira-user-item:hover {
    background: #F4F5F7;
}

.jira-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.jira-user-info {
    flex: 1;
}

.jira-user-name {
    font-size: 14px;
    font-weight: 600;
    color: #172B4D;
}

.jira-user-email {
    font-size: 12px;
    color: #6B778C;
}

.jira-selected-user {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #DEEBFF;
    border: 1px solid #0052CC;
    border-radius: 6px;
    margin-top: 8px;
}

.jira-selected-user.active {
    display: flex;
}

.jira-remove-user {
    margin-left: auto;
    width: 24px;
    height: 24px;
    border: none;
    background: transparent;
    color: #0052CC;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.15s;
}

.jira-remove-user:hover {
    background: rgba(0, 82, 204, 0.2);
}

/* Buttons */
.jira-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    border: none;
}

.jira-btn-primary {
    background: #0052CC;
    color: #FFFFFF;
}

.jira-btn-primary:hover {
    background: #0747A6;
}

.jira-btn-secondary {
    background: #F4F5F7;
    color: #172B4D;
    border: 1px solid #DFE1E6;
}

.jira-btn-secondary:hover {
    background: #EBECF0;
}

.jira-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .jira-modal {
        padding: 0;
        align-items: flex-end;
    }

    .jira-modal-content {
        max-height: 95vh;
        border-radius: 8px 8px 0 0;
    }

    .jira-form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// ===== REASSIGN TASK =====
let searchTimeout;

function openReassignModal(taskId) {
    document.getElementById('reassignTaskId').value = taskId;
    document.getElementById('reassignTaskModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Reset form
    document.getElementById('reassignTaskForm').reset();
    document.getElementById('teammateSelectWrapper').style.display = 'none';
    document.getElementById('teammateResults').innerHTML = '';
    document.getElementById('selectedTeammate').classList.remove('active');
}

function closeReassignModal() {
    document.getElementById('reassignTaskModal').style.display = 'none';
    document.body.style.overflow = '';
}

function toggleTeammateSelect() {
    const assignTo = document.querySelector('input[name="assign_to"]:checked').value;
    const wrapper = document.getElementById('teammateSelectWrapper');
    
    if (assignTo === 'teammate') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('selectedUserId').value = '';
    }
}

function searchTeammates(query) {
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        document.getElementById('teammateResults').innerHTML = '';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/${window.TENANT_USERNAME}/manage/projects/search-users?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                const resultsDiv = document.getElementById('teammateResults');
                
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<div style="padding: 12px; text-align: center; color: #6B778C; font-size: 13px;">No users found</div>';
                    return;
                }
                
                resultsDiv.innerHTML = users.map(user => `
                    <div class="jira-user-item" onclick="selectTeammate(${user.id}, '${user.name}', '${user.avatar}')">
                        <img src="${user.avatar}" alt="${user.name}" class="jira-user-avatar">
                        <div class="jira-user-info">
                            <div class="jira-user-name">${user.name}</div>
                            <div class="jira-user-email">${user.email}</div>
                        </div>
                    </div>
                `).join('');
            })
            .catch(err => {
                console.error('Search error:', err);
            });
    }, 300);
}

function selectTeammate(userId, userName, avatar) {
    document.getElementById('selectedUserId').value = userId;
    document.getElementById('teammateSearch').value = '';
    document.getElementById('teammateResults').innerHTML = '';
    
    const selectedDiv = document.getElementById('selectedTeammate');
    selectedDiv.innerHTML = `
        <img src="${avatar}" alt="${userName}" class="jira-user-avatar">
        <div class="jira-user-info">
            <div class="jira-user-name">${userName}</div>
        </div>
        <button type="button" class="jira-remove-user" onclick="clearTeammateSelection()">
            <i class="fas fa-times"></i>
        </button>
    `;
    selectedDiv.classList.add('active');
}

function clearTeammateSelection() {
    document.getElementById('selectedUserId').value = '';
    document.getElementById('selectedTeammate').classList.remove('active');
}

function submitReassignTask(e) {
    e.preventDefault();
    
    const form = e.target;
    const taskId = document.getElementById('reassignTaskId').value;
    const submitBtn = document.getElementById('reassignSubmitBtn');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    const formData = new FormData(form);
    
    fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.showToast(data.message, 'success');
            closeReassignModal();
            
            // Update card UI
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            if (card) {
                const assigneeDiv = card.querySelector('.jira-assignee');
                if (assigneeDiv) {
                    assigneeDiv.innerHTML = `
                        <img src="${data.task.assignee.avatar_url}" 
                             alt="${data.task.assignee.name}" 
                             class="jira-avatar">
                    `;
                    assigneeDiv.title = data.task.assignee.name;
                }
            }
        } else {
            window.showToast(data.message, 'error');
        }
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    })
    .catch(err => {
        console.error(err);
        window.showToast('Error reassigning task', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    });
}

// ===== EDIT TASK =====
function openEditTaskModal(taskId) {
    const card = document.querySelector(`[data-task-id="${taskId}"]`);
    if (!card) return;
    
    // Fetch task details
    fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`)
        .then(res => res.json())
        .then(task => {
            document.getElementById('editTaskId').value = taskId;
            document.getElementById('editTaskTitle').value = task.title || '';
            document.getElementById('editTaskNotes').value = task.notes || '';
            document.getElementById('editTaskPriority').value = task.priority || 'medium';
            document.getElementById('editTaskDueDate').value = task.due_date || '';
            document.getElementById('editTaskHours').value = task.estimated_hours || '';
            document.getElementById('editTaskPoints').value = task.story_points || '';
            
            updateEditCharCount();
            
            document.getElementById('editTaskModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Error loading task details', 'error');
        });
}

function closeEditModal() {
    document.getElementById('editTaskModal').style.display = 'none';
    document.body.style.overflow = '';
}

function updateEditCharCount() {
    const textarea = document.getElementById('editTaskNotes');
    const counter = document.getElementById('editNotesCount');
    counter.textContent = textarea.value.length;
}

// Add event listener for character count
document.addEventListener('DOMContentLoaded', function() {
    const editNotes = document.getElementById('editTaskNotes');
    if (editNotes) {
        editNotes.addEventListener('input', updateEditCharCount);
    }
});

function submitEditTask(e) {
    e.preventDefault();
    
    const form = e.target;
    const taskId = document.getElementById('editTaskId').value;
    const submitBtn = document.getElementById('editSubmitBtn');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(form);
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });
    
    fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.showToast(data.message, 'success');
            closeEditModal();
            
            // Refresh page or update card
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            window.showToast(data.message, 'error');
        }
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    })
    .catch(err => {
        console.error(err);
        window.showToast('Error updating task', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    });
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReassignModal();
        closeEditModal();
    }
});
</script>
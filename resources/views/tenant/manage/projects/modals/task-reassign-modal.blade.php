{{-- resources/views/tenant/manage/projects/modals/task-reassign-modal.blade.php --}}

<style>
    :root {
        --primary: #0052CC;
        --primary-dark: #0747A6;
        --success: #00875A;
        --danger: #DE350B;
        --warning: #FF991F;
        --bg: #F4F5F7;
        --card: #FFFFFF;
        --border: #DFE1E6;
        --text: #172B4D;
        --text-subtle: #5E6C84;
        --hover: #EBECF0;
    }
    
    .reassign-overlay {
        position: fixed;
        inset: 0;
        background: rgba(9, 30, 66, 0.54);
        backdrop-filter: blur(2px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.2s ease;
    }
    
    .reassign-overlay.active {
        display: flex;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .reassign-modal {
        width: 540px;
        max-width: 95vw;
        max-height: 90vh;
        background: var(--card);
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(9, 30, 66, 0.2);
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Header */
    .reassign-header {
        padding: 20px 24px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .reassign-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .reassign-title i {
        color: var(--primary);
    }
    
    .reassign-close {
        width: 32px;
        height: 32px;
        border: none;
        background: none;
        color: var(--text-subtle);
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    
    .reassign-close:hover {
        background: #FFEBE6;
        color: var(--danger);
    }
    
    /* Body */
    .reassign-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background: var(--bg);
    }
    
    .reassign-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .reassign-body::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }
    
    /* Current Assignment */
    .current-section {
        background: var(--card);
        border: 2px solid var(--border);
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .section-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 12px 0;
    }
    
    .current-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .current-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
    }
    
    .current-info {
        flex: 1;
    }
    
    .current-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 2px 0;
    }
    
    .current-email {
        font-size: 13px;
        color: var(--text-subtle);
        margin: 0;
    }
    
    /* Search Section */
    .search-section {
        background: var(--card);
        border: 2px solid var(--border);
        border-radius: 6px;
        padding: 16px;
    }
    
    .search-wrapper {
        position: relative;
        margin-bottom: 12px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 44px 12px 44px;
        background: var(--bg);
        border: 2px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        color: var(--text);
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--card);
        box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-subtle);
        pointer-events: none;
    }
    
    .search-loading {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    
    .search-loading.active {
        display: block;
    }
    
    .search-loading i {
        color: var(--primary);
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Results */
    .search-results {
        max-height: 240px;
        overflow-y: auto;
        border: 2px solid var(--border);
        border-radius: 6px;
        background: var(--card);
        display: none;
    }
    
    .search-results.active {
        display: block;
    }
    
    .search-results::-webkit-scrollbar {
        width: 6px;
    }
    
    .search-results::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 3px;
    }
    
    .result-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.15s;
        border-bottom: 1px solid var(--border);
    }
    
    .result-item:last-child {
        border-bottom: none;
    }
    
    .result-item:hover {
        background: var(--hover);
    }
    
    .result-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
    
    .result-info {
        flex: 1;
    }
    
    .result-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 2px 0;
    }
    
    .result-email {
        font-size: 12px;
        color: var(--text-subtle);
        margin: 0;
    }
    
    /* Empty State */
    .empty-state {
        padding: 32px 16px;
        text-align: center;
    }
    
    .empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        background: var(--bg);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--text-subtle);
    }
    
    .empty-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 4px 0;
    }
    
    .empty-text {
        font-size: 13px;
        color: var(--text-subtle);
        margin: 0;
    }
    
    /* Selected User */
    .selected-wrap {
        margin-top: 16px;
        display: none;
    }
    
    .selected-wrap.active {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .selected-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: rgba(0, 82, 204, 0.08);
        border: 2px solid var(--primary);
        border-radius: 6px;
        position: relative;
    }
    
    .selected-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
        border: 2px solid white;
    }
    
    .selected-info {
        flex: 1;
    }
    
    .selected-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--primary);
        margin: 0 0 2px 0;
    }
    
    .selected-email {
        font-size: 13px;
        color: var(--text-subtle);
        margin: 0;
    }
    
    .selected-check {
        width: 28px;
        height: 28px;
        background: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    
    /* Note Field */
    .note-field {
        margin-top: 16px;
    }
    
    .note-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 8px 0;
    }
    
    .note-textarea {
        width: 100%;
        padding: 10px 12px;
        background: var(--bg);
        border: 2px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        color: var(--text);
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
        transition: all 0.2s;
    }
    
    .note-textarea:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--card);
    }
    
    .note-hint {
        font-size: 12px;
        color: var(--text-subtle);
        margin: 6px 0 0 0;
    }
    
    /* Footer */
    .reassign-footer {
        padding: 16px 24px;
        border-top: 2px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    
    .btn {
        height: 36px;
        padding: 0 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-cancel {
        background: var(--card);
        color: var(--text);
        border: 2px solid var(--border);
    }
    
    .btn-cancel:hover {
        background: var(--hover);
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Responsive */
    @media (max-width: 600px) {
        .reassign-modal {
            width: 100%;
            max-height: 100vh;
            border-radius: 0;
        }
    }
    </style>
    
    <!-- Reassign Modal -->
    <div class="reassign-overlay" id="reassignModal">
        <div class="reassign-modal">
            <div class="reassign-header">
                <h3 class="reassign-title">
                    <i class="fas fa-user-plus"></i>
                    Reassign Task
                </h3>
                <button type="button" class="reassign-close" onclick="closeReassignModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
    
            <form id="reassignForm" onsubmit="submitReassign(event)">
                @csrf
                <input type="hidden" id="reassignTaskId" name="task_id">
                <input type="hidden" id="selectedUserId" name="user_id">
    
                <div class="reassign-body">
                    <!-- Current Assignment -->
                    <div class="current-section">
                        <div class="section-label">Currently Assigned To</div>
                        <div id="currentAssignee" class="current-user">
                            <div class="current-avatar">?</div>
                            <div class="current-info">
                                <div class="current-name">Loading...</div>
                                <div class="current-email">—</div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Search & Select -->
                    <div class="search-section">
                        <div class="section-label">Assign To</div>
                        
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input
                                type="text"
                                class="search-input"
                                id="userSearchInput"
                                placeholder="Search by name or email..."
                                autocomplete="off"
                                oninput="searchTeamMembers(this.value)"
                            >
                            <div class="search-loading" id="searchLoading">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
    
                        <div class="search-results" id="searchResults"></div>
    
                        <div class="selected-wrap" id="selectedWrap">
                            <div class="selected-card" id="selectedCard"></div>
                        </div>
    
                        <div class="note-field">
                            <label class="note-label">Note (Optional)</label>
                            <textarea
                                class="note-textarea"
                                id="reassignNote"
                                name="note"
                                maxlength="500"
                                placeholder="Add context about this reassignment..."
                            ></textarea>
                            <p class="note-hint">This note will appear in the task activity log</p>
                        </div>
                    </div>
                </div>
    
                <div class="reassign-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeReassignModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="reassignSubmitBtn" disabled>
                        <i class="fas fa-check"></i>
                        Reassign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>

        
    // Global state
    let searchTimeout = null;
    let selectedUser = null;
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Open modal
    function openReassignModal(taskId) {
        const modal = document.getElementById('reassignModal');
        const form = document.getElementById('reassignForm');
        
        // Reset
        selectedUser = null;
        form.reset();
        document.getElementById('reassignTaskId').value = taskId;
        document.getElementById('selectedUserId').value = '';
        document.getElementById('userSearchInput').value = '';
        document.getElementById('searchResults').classList.remove('active');
        document.getElementById('selectedWrap').classList.remove('active');
        document.getElementById('reassignSubmitBtn').disabled = true;
        
        // Fetch task details
        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.task) {
                renderCurrentAssignee(data.task.assignee);
            }
        })
        .catch(() => {
            showToast('Failed to load task', 'error');
        });
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close modal
    function closeReassignModal() {
        const modal = document.getElementById('reassignModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Render current assignee
    function renderCurrentAssignee(assignee) {
        const el = document.getElementById('currentAssignee');
        
        if (!assignee) {
            el.innerHTML = `
                <div class="current-avatar">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="current-info">
                    <div class="current-name">Unassigned</div>
                    <div class="current-email">No one assigned</div>
                </div>
            `;
            return;
        }
        
        const initial = assignee.name.charAt(0).toUpperCase();
        const avatarHtml = assignee.avatar_url
            ? `<img src="${assignee.avatar_url}" alt="${assignee.name}" class="current-avatar">`
            : `<div class="current-avatar">${initial}</div>`;
        
        el.innerHTML = `
            ${avatarHtml}
            <div class="current-info">
                <div class="current-name">${escapeHtml(assignee.name)}</div>
                <div class="current-email">${escapeHtml(assignee.email || '')}</div>
            </div>
        `;
    }
    
    // Search team members
    function searchTeamMembers(query) {
        clearTimeout(searchTimeout);
        
        const loading = document.getElementById('searchLoading');
        const results = document.getElementById('searchResults');
        
        if (query.trim().length < 2) {
            results.classList.remove('active');
            results.innerHTML = '';
            return;
        }
        
        loading.classList.add('active');
        
        searchTimeout = setTimeout(() => {
            const projectId = document.querySelector('[data-project-id]')?.dataset.projectId;
            
            fetch(`/${window.TENANT_USERNAME}/manage/projects/search-users?query=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(users => {
                loading.classList.remove('active');
                
                if (!users || users.length === 0) {
                    results.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div class="empty-title">No users found</div>
                            <div class="empty-text">Try a different search term</div>
                        </div>
                    `;
                    results.classList.add('active');
                    return;
                }
                
                results.innerHTML = users.map(user => {
                    const initial = user.name.charAt(0).toUpperCase();
                    const avatarHtml = user.avatar
                        ? `<img src="${user.avatar}" alt="${user.name}" class="result-avatar">`
                        : `<div class="result-avatar">${initial}</div>`;
                    
                    return `
                        <div class="result-item" onclick='selectUser(${JSON.stringify(user)})'>
                            ${avatarHtml}
                            <div class="result-info">
                                <div class="result-name">${escapeHtml(user.name)}</div>
                                <div class="result-email">${escapeHtml(user.email)}</div>
                            </div>
                        </div>
                    `;
                }).join('');
                
                results.classList.add('active');
            })
            .catch(() => {
                loading.classList.remove('active');
                showToast('Search failed', 'error');
            });
        }, 300);
    }
    
    // Select user
    function selectUser(user) {
        selectedUser = user;
        
        const searchInput = document.getElementById('userSearchInput');
        const searchResults = document.getElementById('searchResults');
        const selectedWrap = document.getElementById('selectedWrap');
        const selectedCard = document.getElementById('selectedCard');
        const userIdInput = document.getElementById('selectedUserId');
        const submitBtn = document.getElementById('reassignSubmitBtn');
        
        searchInput.value = user.name;
        searchResults.classList.remove('active');
        userIdInput.value = user.id;
        submitBtn.disabled = false;
        
        const initial = user.name.charAt(0).toUpperCase();
        const avatarHtml = user.avatar
            ? `<img src="${user.avatar}" alt="${user.name}" class="selected-avatar">`
            : `<div class="selected-avatar">${initial}</div>`;
        
        selectedCard.innerHTML = `
            ${avatarHtml}
            <div class="selected-info">
                <div class="selected-name">${escapeHtml(user.name)}</div>
                <div class="selected-email">${escapeHtml(user.email)}</div>
            </div>
            <div class="selected-check">
                <i class="fas fa-check"></i>
            </div>
        `;
        
        selectedWrap.classList.add('active');
    }
    
// Submit reassignment
function submitReassign(e) {
        e.preventDefault();

        if (!selectedUser) {
            showToast('Please select a user', 'error');
            return;
        }

        const taskId   = document.getElementById('reassignTaskId').value;
        const note     = document.getElementById('reassignNote').value;
        const submitBtn = document.getElementById('reassignSubmitBtn');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reassigning...';

        // build form data body
        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);      // <-- important
        formData.append('user_id', selectedUser.id);
        formData.append('note', note);

        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}/reassign`, {
            method: 'POST',                         // <-- IMPORTANT TRICK
                                                     // we'll spoof PATCH using _method
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN          // <-- important
            },
            body: (() => {
                formData.append('_method', 'PATCH'); // spoof PATCH for Laravel
                return formData;
            })()
        })
        .then(async (r) => {
            // if 419 (CSRF fail) or 401 etc.
            if (!r.ok) {
                let txt = await r.text();
                throw new Error(txt || 'Request failed');
            }
            return r.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeReassignModal();

                // update assignee avatar live
                const card = document.querySelector(`[data-task-id="${taskId}"]`);
                if (card) {
                    const assigneeEl = card.querySelector('.jira-assignee');
                    if (assigneeEl && data.task.assignee) {
                        const initial = data.task.assignee.name.charAt(0).toUpperCase();
                        assigneeEl.innerHTML = data.task.assignee.avatar_url
                            ? `<img src="${data.task.assignee.avatar_url}" alt="${data.task.assignee.name}" class="jira-avatar">`
                            : `<div class="jira-avatar">${initial}</div>`;
                        assigneeEl.title = data.task.assignee.name;
                    }
                }

                // soft refresh UI if you want
                // setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(data.message || 'Failed', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Reassign Task';
            }
        })
        .catch((err) => {
            showToast('Failed to reassign task', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Reassign Task';
        });
    }
    // Utility: escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Utility: show toast
    function showToast(message, type = 'info') {
        const colors = {
            success: '#00875A',
            error: '#DE350B',
            warning: '#FF991F',
            info: '#0052CC'
        };
        
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10001;
            padding: 12px 20px;
            background: ${colors[type]};
            color: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-size: 14px;
            font-weight: 500;
            animation: slideInRight 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Close on escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeReassignModal();
        }
    });
    
    // Expose globally
    window.openReassignModal = openReassignModal;
    window.closeReassignModal = closeReassignModal;
    </script>
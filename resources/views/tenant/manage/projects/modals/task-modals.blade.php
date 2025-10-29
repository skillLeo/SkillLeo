{{-- resources/views/tenant/manage/projects/modals/task-modals.blade.php --}}
{{-- This partial should be @included once on pages that render task cards. --}}
{{-- Assumes you have $username, csrf token meta, etc. --}}

<style>
    :root {
        --jira-primary: #0052CC;
        --jira-primary-dark: #0747A6;
        --jira-success: #00875A;
        --jira-warning: #FF991F;
        --jira-danger: #DE350B;
        --jira-bg: #F4F5F7;
        --jira-card: #FFFFFF;
        --jira-border: #DFE1E6;
        --jira-text: #172B4D;
        --jira-text-subtle: #5E6C84;
        --jira-hover: #EBECF0;
        --jira-shadow-lg: 0 8px 16px rgba(9, 30, 66, 0.15), 0 0 1px rgba(9, 30, 66, 0.31);
    }

    /* Shared overlay */
    .jira-task-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(9, 30, 66, 0.54);
        backdrop-filter: blur(2px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Modal shell */
    .jira-task-modal-box {
        width: 600px;
        max-width: 95vw;
        max-height: 90vh;
        background: var(--jira-card);
        border-radius: 4px;
        box-shadow: var(--jira-shadow-lg);
        display: flex;
        flex-direction: column;
        animation: jiraTaskModalSlideUp 0.28s cubic-bezier(0.15, 1, 0.3, 1);
    }

    @keyframes jiraTaskModalSlideUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header */
    .jira-task-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 16px 20px;
        border-bottom: 2px solid var(--jira-border);
    }

    .jira-task-modal-heading {
        flex: 1;
    }

    .jira-task-modal-title {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--jira-text);
        line-height: 1.3;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .jira-task-modal-subtitle {
        margin: 0;
        font-size: 13px;
        color: var(--jira-text-subtle);
        line-height: 1.3;
    }

    .jira-task-close-btn {
        border: none;
        background: none;
        color: var(--jira-text-subtle);
        width: 32px;
        height: 32px;
        border-radius: 4px;
        cursor: pointer;
        transition: all .15s;
    }

    .jira-task-close-btn:hover {
        color: var(--jira-danger);
        background: #FFEBE6;
    }

    /* Body / scroll container */
    .jira-task-modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: var(--jira-bg);
    }

    .jira-task-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .jira-task-modal-body::-webkit-scrollbar-track {
        background: var(--jira-bg);
    }

    .jira-task-modal-body::-webkit-scrollbar-thumb {
        background: var(--jira-border);
        border-radius: 4px;
    }

    /* Inner card */
    .jira-task-form-section {
        background: var(--jira-card);
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(9, 30, 66, .25);
        border: 2px solid var(--jira-border);
        padding: 16px 20px;
    }

    .jira-task-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 0 0 16px 0;
    }

    .jira-task-section-title i {
        color: var(--jira-primary);
    }

    .jira-task-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .jira-task-grid-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    @media(max-width:600px) {
        .jira-task-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .jira-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .jira-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text);
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .jira-label.required::after {
        content: ' *';
        color: var(--jira-danger);
    }

    .jira-input,
    .jira-select,
    .jira-textarea {
        width: 100%;
        padding: 8px 10px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        font-size: 14px;
        color: var(--jira-text);
        font-family: inherit;
        transition: all .15s;
    }

    .jira-input:focus,
    .jira-select:focus,
    .jira-textarea:focus {
        outline: none;
        border-color: var(--jira-primary);
        background: var(--jira-card);
    }

    .jira-textarea {
        min-height: 80px;
        resize: vertical;
        line-height: 1.4;
        font-size: 13px;
    }

    .jira-hint {
        font-size: 11px;
        color: var(--jira-text-subtle);
    }

    /* Priority selector */
    .jira-priority-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .jira-priority-chip {
        flex: 1;
        min-width: 80px;
        border-radius: 4px;
        border: 2px solid var(--jira-border);
        background: var(--jira-bg);
        font-size: 13px;
        font-weight: 500;
        color: var(--jira-text);
        padding: 8px 10px;
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s;
    }

    .jira-priority-chip:hover {
        background: var(--jira-card);
        border-color: var(--jira-primary);
    }

    .jira-priority-chip.active {
        background: var(--jira-primary);
        border-color: var(--jira-primary);
        color: #fff;
    }

    .jira-priority-chip i {
        font-size: 12px;
    }

    /* Assignee row (add modal only) */
    .jira-assignee-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .jira-assignee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        overflow: hidden;
    }

    .jira-assignee-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Subtasks mini builder */
    .jira-subtasks-mini {
        background: var(--jira-bg);
        border-radius: 4px;
        border: 2px solid var(--jira-border);
        padding: 12px;
    }

    .jira-subtasks-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .jira-subtasks-header-row .left {
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        letter-spacing: .3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .jira-subtasks-header-row .count-badge {
        background: var(--jira-hover);
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 6px;
        color: var(--jira-text);
    }

    .jira-subtask-line {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--jira-card);
        border-radius: 3px;
        border: 2px solid var(--jira-border);
        padding: 8px;
        margin-bottom: 6px;
    }

    .jira-subtask-line input[type="text"] {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 13px;
        color: var(--jira-text);
    }

    .jira-subtask-line input[type="text"]:focus {
        outline: none;
        border-bottom: 2px solid var(--jira-primary);
    }

    .jira-subtask-remove-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: none;
        border-radius: 3px;
        color: var(--jira-text-subtle);
        cursor: pointer;
        transition: all .15s;
    }

    .jira-subtask-remove-btn:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    .jira-add-subtask-btn {
        border: none;
        background: none;
        color: var(--jira-primary);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        padding: 4px 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .jira-add-subtask-btn i {
        font-size: 12px;
    }

    /* Footer */
    .jira-task-modal-footer {
        border-top: 2px solid var(--jira-border);
        background: var(--jira-card);
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .jira-btn-primary,
    .jira-btn-secondary,
    .jira-btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 36px;
        padding: 0 16px;
        border-radius: 3px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        transition: all .15s;
        white-space: nowrap;
    }

    .jira-btn-primary {
        background: var(--jira-primary);
        color: #fff;
    }

    .jira-btn-primary:hover {
        background: var(--jira-primary-dark);
    }

    .jira-btn-secondary {
        background: var(--jira-card);
        color: var(--jira-text);
        border: 2px solid var(--jira-border);
    }

    .jira-btn-secondary:hover {
        background: var(--jira-hover);
    }

    .jira-btn-ghost {
        background: none;
        color: var(--jira-text-subtle);
    }

    .jira-btn-ghost:hover {
        background: var(--jira-hover);
        color: var(--jira-text);
    }

    /* tiny badge in header for task id */
    .jira-task-id-pill {
        background: var(--jira-bg);
        border: 1px solid var(--jira-border);
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        line-height: 1.2;
    }

    .jira-inline-note {
        font-size: 12px;
        color: var(--jira-text-subtle);
    }
</style>


{{-- ============================= --}}
{{-- ADD TASK MODAL (create)      --}}
{{-- ============================= --}}
<div class="jira-task-modal-overlay" id="addTaskModal">
    <div class="jira-task-modal-box">
        <div class="jira-task-modal-header">
            <div class="jira-task-modal-heading">
                <h2 class="jira-task-modal-title">
                    <span><i class="fas fa-plus-circle"></i> New Task</span>
                    <span class="jira-task-id-pill" id="addTaskProjectBadge">PRJ-###</span>
                </h2>
                <p class="jira-task-modal-subtitle">
                    Create a task in this project and assign it immediately.
                </p>
            </div>
            <button class="jira-task-close-btn" onclick="closeAddTaskModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="jira-task-modal-body">
            <div class="jira-task-form-section">
                <h3 class="jira-task-section-title">
                    <i class="fas fa-tasks"></i>
                    Task Details
                </h3>

                <div class="jira-task-grid-1">
                    <div class="jira-form-group">
                        <label class="jira-label required">Task Title</label>
                        <input type="text" class="jira-input" id="addTaskTitle"
                            placeholder="e.g. Implement homepage hero section">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Notes / Description</label>
                        <textarea class="jira-textarea" id="addTaskNotes" placeholder="Explain requirements, acceptance criteria, links, etc."></textarea>
                    </div>
                </div>

                <div class="jira-task-grid-2" style="margin-top:16px;">
                    <div class="jira-form-group">
                        <label class="jira-label">Due Date</label>
                        <input type="date" class="jira-input" id="addTaskDueDate">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Assign To</label>
                        <div class="jira-assignee-row">
                            <div class="jira-assignee-avatar" id="addTaskAssigneeAvatar">?</div>
                            <select class="jira-select" id="addTaskAssigneeSelect"
                                onchange="previewAssigneeAvatar(this)">
                                <option value="">Unassigned</option>
                                {{-- we inject team members via JS --}}
                            </select>
                        </div>
                        <small class="jira-hint">Assignee will see it under "My Tasks".</small>
                    </div>
                </div>

                <div class="jira-task-grid-2" style="margin-top:16px;">
                    <div class="jira-form-group">
                        <label class="jira-label">Estimated Hours</label>
                        <input type="number" class="jira-input" id="addTaskHours" min="0" step="0.5"
                            placeholder="0">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Story Points</label>
                        <select class="jira-select" id="addTaskPoints">
                            <option value="0">None</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="8">8</option>
                            <option value="13">13</option>
                        </select>
                    </div>
                </div>

                <div class="jira-form-group" style="margin-top:16px;">
                    <label class="jira-label">Priority</label>
                    <input type="hidden" id="addTaskPriority" value="medium">
                    <div class="jira-priority-row">
                        <button type="button" class="jira-priority-chip" data-value="low"
                            onclick="selectPriorityChip(this, 'addTaskPriority')"><i class="fas fa-arrow-down"></i>
                            Low</button>
                        <button type="button" class="jira-priority-chip active" data-value="medium"
                            onclick="selectPriorityChip(this, 'addTaskPriority')"><i class="fas fa-minus"></i>
                            Medium</button>
                        <button type="button" class="jira-priority-chip" data-value="high"
                            onclick="selectPriorityChip(this, 'addTaskPriority')"><i class="fas fa-arrow-up"></i>
                            High</button>
                        <button type="button" class="jira-priority-chip" data-value="urgent"
                            onclick="selectPriorityChip(this, 'addTaskPriority')"><i class="fas fa-exclamation"></i>
                            Urgent</button>
                    </div>
                </div>

                <div class="jira-form-group" style="margin-top:24px;">
                    <label class="jira-label">Subtasks</label>
                    <div class="jira-subtasks-mini" id="addTaskSubtasksWrapper">
                        <div class="jira-subtasks-header-row">
                            <div class="left">
                                <i class="fas fa-list-ul"></i>
                                <span>Checklist</span>
                                <span class="count-badge" id="addTaskSubtaskCount">0</span>
                            </div>
                            <button class="jira-add-subtask-btn" type="button"
                                onclick="addSubtaskRow('addTaskSubtasksList','addTaskSubtaskCount')">
                                <i class="fas fa-plus"></i> Add subtask
                            </button>
                        </div>

                        <div id="addTaskSubtasksList"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="jira-task-modal-footer">
            <button class="jira-btn-secondary" onclick="closeAddTaskModal()">Cancel</button>
            <button class="jira-btn-primary" onclick="submitNewTask()">
                <i class="fas fa-check"></i>
                Create Task
            </button>
        </div>
    </div>
</div>


{{-- ============================= --}}
{{-- EDIT TASK MODAL (update)     --}}
{{-- ============================= --}}
<div class="jira-task-modal-overlay" id="editTaskModal">
    <div class="jira-task-modal-box">
        <div class="jira-task-modal-header">
            <div class="jira-task-modal-heading">
                <h2 class="jira-task-modal-title">
                    <span><i class="fas fa-edit"></i> Edit Task</span>
                    <span class="jira-task-id-pill" id="editTaskIdPill">TASK-###</span>
                </h2>
                <p class="jira-task-modal-subtitle" id="editTaskSubtitle">
                    Update details, change priority, and re-estimate.
                </p>
            </div>
            <button class="jira-task-close-btn" onclick="closeEditTaskModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="jira-task-modal-body">
            <div class="jira-task-form-section">
                <h3 class="jira-task-section-title">
                    <i class="fas fa-sliders-h"></i>
                    Task Fields
                </h3>

                <input type="hidden" id="editTaskId">
                <input type="hidden" id="editTaskProjectId">

                <div class="jira-task-grid-1">
                    <div class="jira-form-group">
                        <label class="jira-label required">Task Title</label>
                        <input type="text" class="jira-input" id="editTaskTitle">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Notes / Description</label>
                        <textarea class="jira-textarea" id="editTaskNotes"></textarea>
                    </div>
                </div>

                <!-- due date full width -->
                <div class="jira-task-grid-1" style="margin-top:16px;">
                    <div class="jira-form-group">
                        <label class="jira-label">Due Date</label>
                        <input type="date" class="jira-input" id="editTaskDueDate">
                    </div>
                </div>

                <div class="jira-task-grid-2" style="margin-top:16px;">
                    <div class="jira-form-group">
                        <label class="jira-label">Estimated Hours</label>
                        <input type="number" class="jira-input" id="editTaskHours" min="0" step="0.5">
                    </div>

                    <div class="jira-form-group">
                        <label class="jira-label">Story Points</label>
                        <select class="jira-select" id="editTaskPoints">
                            <option value="0">None</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="8">8</option>
                            <option value="13">13</option>
                        </select>
                    </div>
                </div>

                <div class="jira-form-group" style="margin-top:16px;">
                    <label class="jira-label">Priority</label>
                    <input type="hidden" id="editTaskPriority" value="medium">
                    <div class="jira-priority-row" id="editPriorityChips">
                        <button type="button" class="jira-priority-chip" data-value="low"
                            onclick="selectPriorityChip(this, 'editTaskPriority')"><i class="fas fa-arrow-down"></i>
                            Low</button>
                        <button type="button" class="jira-priority-chip" data-value="medium"
                            onclick="selectPriorityChip(this, 'editTaskPriority')"><i class="fas fa-minus"></i>
                            Medium</button>
                        <button type="button" class="jira-priority-chip" data-value="high"
                            onclick="selectPriorityChip(this, 'editTaskPriority')"><i class="fas fa-arrow-up"></i>
                            High</button>
                        <button type="button" class="jira-priority-chip" data-value="urgent"
                            onclick="selectPriorityChip(this, 'editTaskPriority')"><i class="fas fa-exclamation"></i>
                            Urgent</button>
                    </div>
                </div>

                <div class="jira-form-group" style="margin-top:24px;">
                    <label class="jira-label">Subtasks</label>
                    <div class="jira-subtasks-mini" id="editTaskSubtasksWrapper">
                        <div class="jira-subtasks-header-row">
                            <div class="left">
                                <i class="fas fa-list-ul"></i>
                                <span>Checklist</span>
                                <span class="count-badge" id="editTaskSubtaskCount">0</span>
                            </div>
                            <button class="jira-add-subtask-btn" type="button"
                                onclick="addSubtaskRow('editTaskSubtasksList','editTaskSubtaskCount')">
                                <i class="fas fa-plus"></i> Add subtask
                            </button>
                        </div>

                        <div id="editTaskSubtasksList"></div>
                    </div>
                    <div class="jira-inline-note">
                        You can rename / add / remove subtasks here.
                        Completion is handled in the task view.
                    </div>
                </div>

            </div>
        </div>

        <div class="jira-task-modal-footer">
            <button class="jira-btn-secondary" onclick="closeEditTaskModal()">Cancel</button>
            <button class="jira-btn-primary" onclick="submitTaskUpdate()">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </div>
</div>


<script>
    // ---------------------------------
    // globals helpers for reuse
    // ---------------------------------
    window.TENANT_USERNAME = "{{ $username }}";
    window.AVATAR_FALLBACK_URL = "{{ asset('images/avatar-fallback.png') }}";

    /**
     * Toast helper
     */
    function modalToast(msg, type = 'info') {
        const colors = {
            success: '#00875A',
            error: '#DE350B',
            warning: '#FF991F',
            info: '#0052CC',
        };
        const el = document.createElement('div');
        el.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 100000;
            padding: 12px 16px;
            border-radius: 4px;
            color:#fff;
            font-size:13px;
            font-weight:600;
            background:${colors[type]||colors.info};
            box-shadow:0 4px 12px rgba(0,0,0,.15);
        `;
        el.textContent = msg;
        document.body.appendChild(el);
        setTimeout(() => {
            el.remove();
        }, 2600);
    }

    /**
     * Priority chip handler
     * hiddenInputId = 'addTaskPriority' or 'editTaskPriority'
     */
    function selectPriorityChip(btn, hiddenInputId) {
        const wrapper = btn.parentElement;
        const val = btn.getAttribute('data-value');

        // set hidden input
        const hidden = document.getElementById(hiddenInputId);
        if (hidden) hidden.value = val;

        // mark active
        wrapper.querySelectorAll('.jira-priority-chip').forEach(chip => {
            chip.classList.toggle('active', chip === btn);
        });
    }

    /**
     * Escape HTML (for innerHTML-safe string)
     */
    function escapeHtml(txt = '') {
        const div = document.createElement('div');
        div.textContent = txt;
        return div.innerHTML;
    }

    /**
     * Build + remove subtask rows (add modal vs edit modal)
     */
    function addSubtaskRow(
        listId,
        countId,
        titleVal = '',
        completedVal = false,
        subtaskId = null
    ) {
        const list = document.getElementById(listId);
        const countEl = document.getElementById(countId);
        if (!list || !countEl) return;

        const row = document.createElement('div');
        row.className = 'jira-subtask-line';
        if (subtaskId) {
            // Keep ID so backend knows it's existing
            row.dataset.subtaskId = subtaskId;
        }

        const isEditModal = (listId === 'editTaskSubtasksList');

        if (isEditModal) {
            // EDIT MODAL: no visible checkbox
            // but keep completion state in hidden input
            row.innerHTML = `
                <input type="text"
                    class="jira-subtask-title-input"
                    placeholder="Subtask..."
                    value="${escapeHtml(titleVal)}">

                <input type="hidden"
                    class="jira-subtask-completed-input"
                    value="${completedVal ? '1' : '0'}">

                <button class="jira-subtask-remove-btn"
                        type="button"
                        onclick="removeSubtaskRow(this,'${countId}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
        } else {
            // ADD TASK MODAL VERSION: show checkbox
            row.innerHTML = `
                <input type="text"
                    class="jira-subtask-title-input"
                    placeholder="Subtask..."
                    value="${escapeHtml(titleVal)}">

                <label style="font-size:11px;color:var(--jira-text-subtle);display:flex;align-items:center;gap:4px;">
                    <input type="checkbox"
                        class="jira-subtask-completed-input"
                        ${completedVal ? 'checked' : ''} />
                    <span>Done</span>
                </label>

                <button class="jira-subtask-remove-btn"
                        type="button"
                        onclick="removeSubtaskRow(this,'${countId}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }

        list.appendChild(row);

        countEl.textContent = list.querySelectorAll('.jira-subtask-line').length.toString();
    }

    function removeSubtaskRow(btn, countId) {
        const row = btn.closest('.jira-subtask-line');
        if (row) row.remove();
        const countEl = document.getElementById(countId);
        if (countEl) {
            const wrapper = btn.closest('.jira-subtasks-mini');
            const len = wrapper.querySelectorAll('.jira-subtask-line').length;
            countEl.textContent = len.toString();
        }
    }

    /**
     * Assignee avatar preview (used in Add Task modal only)
     */
    function previewAssigneeAvatar(selectEl, avatarDivId) {
        if (!avatarDivId) avatarDivId = 'addTaskAssigneeAvatar';

        const avatarDiv = document.getElementById(avatarDivId);
        if (!avatarDiv) return;

        const opt = selectEl.options[selectEl.selectedIndex];
        const avatar = opt.getAttribute('data-avatar');
        const name = opt.textContent.trim();

        if (!selectEl.value) {
            avatarDiv.innerHTML = '?';
            avatarDiv.style.background = 'var(--jira-bg)';
            avatarDiv.style.color = 'var(--jira-text-subtle)';
            return;
        }

        if (avatar) {
            avatarDiv.innerHTML = `<img src="${avatar}" alt="${name}">`;
        } else {
            avatarDiv.textContent = name.charAt(0).toUpperCase();
            avatarDiv.style.background = 'linear-gradient(135deg,#667eea,#764ba2)';
            avatarDiv.style.color = '#fff';
        }
    }

    // ------------------------------------------------
    // DOM PATCH after successful update (NO RELOAD)
    // ------------------------------------------------
    function updateTaskCardFromResponse(task) {
        console.log('Updating card for task:', task.id, task);

        const card = document.querySelector(`.jira-task-card[data-task-id="${task.id}"]`);
        if (!card) {
            console.warn('Card not found for task', task.id);
            return;
        }

        // 1. TITLE
        const titleEl = card.querySelector('.jira-card-title');
        if (titleEl) {
            titleEl.textContent = task.title || '';
        }

        // 2. PRIORITY BADGE
        const priorityMap = {
            urgent:  { color:'#DE350B', bg:'rgba(222,53,11,0.1)', label:'Urgent',  icon:'exclamation-circle' },
            high:    { color:'#FF991F', bg:'rgba(255,153,31,0.1)', label:'High',    icon:'arrow-up' },
            medium:  { color:'#0065FF', bg:'rgba(0,101,255,0.1)', label:'Medium',  icon:'minus' },
            low:     { color:'#00875A', bg:'rgba(0,135,90,0.1)',  label:'Low',     icon:'arrow-down' },
        };
        const pConf = priorityMap[task.priority || 'medium'];

        const prBadge = card.querySelector('.jira-priority-badge');
        if (prBadge && pConf) {
            prBadge.style.background = pConf.bg;
            prBadge.style.color = pConf.color;
            prBadge.style.borderColor = pConf.color;
            prBadge.innerHTML = `<i class="fas fa-${pConf.icon}"></i> ${pConf.label}`;
        }

        // 3. DUE DATE
        const dueWrap = card.querySelector('.jira-due-date');
        if (dueWrap) {
            if (task.due_date) {
                const d = new Date(task.due_date + 'T00:00:00');
                const month = d.toLocaleString('en-US', { month: 'short' });
                const day = d.getDate();

                dueWrap.style.display = '';
                dueWrap.classList.toggle('is-overdue', task.is_overdue || false);
                dueWrap.innerHTML = `<i class="fas fa-calendar"></i> ${month} ${String(day).padStart(2,'0')}`;
            } else {
                dueWrap.style.display = 'none';
            }
        }

        // 4. STORY POINTS
        const storyPointsEl = card.querySelector('.jira-story-points');
        if (storyPointsEl) {
            if (task.story_points && task.story_points > 0) {
                storyPointsEl.style.display = 'inline-flex';
                storyPointsEl.innerHTML = `<i class="fas fa-chart-line"></i> ${task.story_points}`;
            } else {
                storyPointsEl.style.display = 'none';
            }
        }

        // 5. ASSIGNEE AVATAR in card footer (still shown on cards, read-only here)
        const assigneeWrapper = card.querySelector('.jira-footer-right .jira-assignee');
        if (assigneeWrapper) {
            if (task.assignee) {
                const name = task.assignee.name || 'User';
                const avatarUrl = task.assignee.avatar_url;

                assigneeWrapper.setAttribute('title', name);

                if (avatarUrl) {
                    assigneeWrapper.innerHTML = `
                        <img src="${avatarUrl}"
                             alt="${name}"
                             class="jira-avatar"
                             referrerpolicy="no-referrer"
                             crossorigin="anonymous"
                             onerror="this.onerror=null; this.src='${window.AVATAR_FALLBACK_URL || '/images/avatar-fallback.png'}';">
                    `;
                } else {
                    assigneeWrapper.innerHTML = `
                        <div class="jira-avatar jira-avatar-placeholder">
                            ${name.charAt(0).toUpperCase()}
                        </div>
                    `;
                }
            } else {
                assigneeWrapper.innerHTML = `
                    <div class="jira-assignee jira-assignee-unassigned" title="Unassigned">
                        <i class="fas fa-user-slash"></i>
                    </div>
                `;
            }
        }

        // 6. SUBTASK PROGRESS
        const subtasks = Array.isArray(task.subtasks) ? task.subtasks : [];
        const total = subtasks.length;
        const completed = subtasks.filter(st => st.completed).length;
        const percent = total > 0 ? (completed * 100) / total : 0;

        // Count badge
        const subCountEl = card.querySelector('.jira-subtasks-count');
        if (subCountEl) {
            subCountEl.textContent = `${completed}/${total}`;
        }

        // Progress bar
        const bar = card.querySelector('.jira-progress-bar');
        if (bar) {
            bar.style.width = percent + '%';
        }

        // Expanded list (if visible)
        const listEl = card.querySelector('.jira-subtasks-list');
        if (listEl) {
            listEl.innerHTML = subtasks.map(st => {
                const checked = st.completed ? 'checked' : '';
                const completedClass = st.completed ? 'is-completed' : '';
                const disabled = 'disabled';

                return `
                    <div class="jira-subtask-item ${completedClass}"
                         data-task-id="${task.id}"
                         data-subtask-id="${st.id}">
                        <label class="jira-subtask-checkbox-wrapper">
                            <input type="checkbox"
                                class="jira-subtask-checkbox"
                                ${checked}
                                ${disabled}>
                            <span class="jira-checkbox-custom">
                                <i class="fas fa-check"></i>
                            </span>
                        </label>
                        <span class="jira-subtask-title">${escapeHtml(st.title)}</span>
                    </div>
                `;
            }).join('');
        }

        console.log('✅ Card updated successfully');
    }

    // --------------------------------
    // ADD TASK MODAL
    // --------------------------------
    let CURRENT_ADD_TASK_PROJECT_ID = null;

    function openAddTaskModal(projectId, projectKey) {
        CURRENT_ADD_TASK_PROJECT_ID = projectId;

        // reset form
        document.getElementById('addTaskTitle').value = '';
        document.getElementById('addTaskNotes').value = '';
        document.getElementById('addTaskDueDate').value = '';
        document.getElementById('addTaskHours').value = '';
        document.getElementById('addTaskPoints').value = '0';
        document.getElementById('addTaskPriority').value = 'medium';
        document.getElementById('addTaskSubtasksList').innerHTML = '';
        document.getElementById('addTaskSubtaskCount').textContent = '0';

        // reset chips state
        const addPriorityRow = document.querySelector('#addTaskModal .jira-priority-row');
        if (addPriorityRow) {
            addPriorityRow.querySelectorAll('.jira-priority-chip').forEach(chip => {
                chip.classList.toggle('active', chip.dataset.value === 'medium');
            });
        }

        // badge
        const badge = document.getElementById('addTaskProjectBadge');
        if (badge) {
            badge.textContent = (projectKey || 'PROJ') + ' Project';
        }

        // populate assignee dropdown
        populateAssigneeSelect('addTaskAssigneeSelect', 'addTaskAssigneeAvatar', projectId);

        // show modal
        const overlay = document.getElementById('addTaskModal');
        if (overlay) overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeAddTaskModal() {
        const overlay = document.getElementById('addTaskModal');
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    function gatherNewTaskPayload() {
        const subtasks = [];
        document.querySelectorAll('#addTaskSubtasksList .jira-subtask-line').forEach(line => {
            subtasks.push({
                title: line.querySelector('.jira-subtask-title-input')?.value.trim() || '',
                completed: !!line.querySelector('.jira-subtask-completed-input')?.checked
            });
        });

        return {
            project_id: CURRENT_ADD_TASK_PROJECT_ID,
            title: document.getElementById('addTaskTitle').value.trim(),
            notes: document.getElementById('addTaskNotes').value.trim(),
            priority: document.getElementById('addTaskPriority').value,
            assigned_to: document.getElementById('addTaskAssigneeSelect').value || null,
            due_date: document.getElementById('addTaskDueDate').value || null,
            estimated_hours: document.getElementById('addTaskHours').value || null,
            story_points: document.getElementById('addTaskPoints').value || 0,
            subtasks: subtasks
        };
    }

    function submitNewTask() {
        const payload = gatherNewTaskPayload();

        if (!payload.title) {
            modalToast('Task title is required', 'error');
            return;
        }

        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                modalToast(data.message || 'Failed to create task', 'error');
                return;
            }

            modalToast('Task created', 'success');
            closeAddTaskModal();

            // you can inject new card to DOM here if you want
            // location.reload();
        })
        .catch(err => {
            console.error(err);
            modalToast('Request failed', 'error');
        });
    }

    // --------------------------------
    // EDIT TASK MODAL
    // --------------------------------
    function openEditTaskModal(taskId) {
        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                modalToast(data.message || 'Unable to load task', 'error');
                return;
            }

            const t = data.task;

            // Fill fields
            document.getElementById('editTaskId').value = t.id;
            document.getElementById('editTaskProjectId').value = t.project_id;
            document.getElementById('editTaskTitle').value = t.title || '';
            document.getElementById('editTaskNotes').value = t.notes || '';
            document.getElementById('editTaskDueDate').value = t.due_date || '';
            document.getElementById('editTaskHours').value = (t.estimated_hours ?? '');
            document.getElementById('editTaskPoints').value = (t.story_points ?? '0');
            document.getElementById('editTaskPriority').value = t.priority || 'medium';

            // Header pill / subtitle
            const pill = document.getElementById('editTaskIdPill');
            if (pill) {
                pill.textContent = `#${t.id} • ${t.status || 'todo'}`;
            }

            const subtitle = document.getElementById('editTaskSubtitle');
            if (subtitle) {
                subtitle.textContent = `Project ID ${t.project_id} • Status: ${t.status}`;
            }

            // Priority chip active state
            const row = document.getElementById('editPriorityChips');
            if (row) {
                row.querySelectorAll('.jira-priority-chip').forEach(chip => {
                    chip.classList.toggle('active', chip.dataset.value === t.priority);
                });
            }

            // Subtasks
            const subtaskList = document.getElementById('editTaskSubtasksList');
            const subtaskCount = document.getElementById('editTaskSubtaskCount');
            if (subtaskList && subtaskCount) {
                subtaskList.innerHTML = '';
                if (Array.isArray(t.subtasks)) {
                    t.subtasks
                        .sort((a, b) => (a.order || 0) - (b.order || 0))
                        .forEach(st => {
                            addSubtaskRow(
                                'editTaskSubtasksList',
                                'editTaskSubtaskCount',
                                st.title,
                                !!st.completed,
                                st.id
                            );
                        });
                    subtaskCount.textContent = t.subtasks.length.toString();
                } else {
                    subtaskCount.textContent = '0';
                }
            }

            // show modal
            const overlay = document.getElementById('editTaskModal');
            if (overlay) overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            console.error(err);
            modalToast('Error loading task', 'error');
        });
    }

    function closeEditTaskModal() {
        const overlay = document.getElementById('editTaskModal');
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    function gatherEditTaskPayload() {
        const subtasks = [];
        document.querySelectorAll('#editTaskSubtasksList .jira-subtask-line').forEach(line => {
            const title = line.querySelector('.jira-subtask-title-input')?.value.trim() || '';

            // read completion state:
            const completedInput = line.querySelector('.jira-subtask-completed-input');
            let completedVal = false;
            if (completedInput) {
                if (completedInput.type === 'checkbox') {
                    completedVal = !!completedInput.checked;
                } else {
                    // hidden input version for edit modal
                    completedVal = completedInput.value === '1';
                }
            }

            subtasks.push({
                id: line.dataset.subtaskId || null,
                title: title,
                completed: completedVal,
            });
        });

        return {
            title: document.getElementById('editTaskTitle').value.trim(),
            notes: document.getElementById('editTaskNotes').value.trim(),
            priority: document.getElementById('editTaskPriority').value,
            due_date: document.getElementById('editTaskDueDate').value || null,
            estimated_hours: document.getElementById('editTaskHours').value || null,
            story_points: document.getElementById('editTaskPoints').value || 0,
            // we are NOT sending assigned_to here anymore (edit modal doesn't edit assignee)
            subtasks: subtasks,
        };
    }

    function submitTaskUpdate() {
        const taskId = document.getElementById('editTaskId').value;
        const payload = gatherEditTaskPayload();

        // Validation
        if (!payload.title || payload.title.trim().length === 0) {
            modalToast('Task title is required', 'error');
            return;
        }

        if (payload.title.trim().length < 3) {
            modalToast('Task title must be at least 3 characters', 'error');
            return;
        }

        // show loading UI
        const submitBtn = document.querySelector('#editTaskModal .jira-btn-primary');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Update failed');
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                modalToast(data.message || 'Failed to update task', 'error');
                return;
            }

            modalToast('✅ Task updated successfully', 'success');
            closeEditTaskModal();

            // patch card in DOM
            updateTaskCardFromResponse(data.task);
        })
        .catch(err => {
            console.error('Task update error:', err);
            modalToast(err.message || 'Network error. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    // ------------------------------------------------
    // Assignee dropdown population (Add Task modal ONLY)
    // ------------------------------------------------
    //
    // backend endpoint must return teammates like:
    // [
    //   { "id":5, "name":"Ayesha", "avatar_url":"..." },
    //   { "id":12, "name":"Daniyal", "avatar_url":null }
    // ]
    //
    // Route we call:
    // GET /{username}/manage/projects/{projectId}/team
    //
    function populateAssigneeSelect(selectId, avatarDivId, projectId, preselectUserId = null) {
        const sel = document.getElementById(selectId);
        const avatarDiv = document.getElementById(avatarDivId);

        if (!sel) return;

        sel.innerHTML = `<option value="">Unassigned</option>`;

        // default avatar bubble
        if (avatarDiv) {
            avatarDiv.innerHTML = '?';
            avatarDiv.style.background = 'var(--jira-bg)';
            avatarDiv.style.color = 'var(--jira-text-subtle)';
        }

        fetch(`/${window.TENANT_USERNAME}/manage/projects/${projectId}/team`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(list => {
            if (!Array.isArray(list)) return;

            list.forEach(member => {
                const opt = document.createElement('option');
                opt.value = member.id;
                opt.textContent = member.name;
                opt.setAttribute('data-avatar', member.avatar_url || '');
                sel.appendChild(opt);
            });

            // preselect if provided
            if (preselectUserId) {
                sel.value = preselectUserId;
            } else {
                sel.value = sel.value || '';
            }

            // update avatar preview bubble
            previewAssigneeAvatar(sel, avatarDivId);
        })
        .catch(err => {
            console.error(err);
        });
    }

    // ------------------------------------------------
    // Card dropdown menu etc
    // ------------------------------------------------
    function toggleTaskMenu(taskId) {
        const menu = document.getElementById(`task-menu-${taskId}`);
        const allMenus = document.querySelectorAll('.jira-dropdown-menu');

        allMenus.forEach(m => {
            if (m.id !== `task-menu-${taskId}`) {
                m.style.display = 'none';
            }
        });

        menu.style.display =
            (menu.style.display === 'none' || menu.style.display === '')
            ? 'block'
            : 'none';
    }

    // close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.jira-card-menu')) {
            document.querySelectorAll('.jira-dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    function toggleSubtasksExpand(taskId) {
        const list = document.getElementById(`subtasks-list-${taskId}`);
        const btn = document.getElementById(`expand-btn-${taskId}`);

        if (list && btn) {
            const isHidden = (list.style.display === 'none' || list.style.display === '');
            list.style.display = isHidden ? 'block' : 'none';
            btn.classList.toggle('is-expanded', isHidden);
        }
    }

    function openReassignModal(taskId) {
        // future reassign modal if you want to move assignee separately
        console.log('Open reassign modal for task:', taskId);
    }

    function deleteTask(taskId) {
        if (!confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
            return;
        }

        fetch(`/${window.TENANT_USERNAME}/manage/projects/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-task-id="${taskId}"]`);
                if (card) {
                    card.style.transition = 'all 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => card.remove(), 300);
                }
                modalToast('Task deleted successfully', 'success');
            } else {
                modalToast(data.message || 'Failed to delete task', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            modalToast('Error deleting task', 'error');
        });
    }
</script>

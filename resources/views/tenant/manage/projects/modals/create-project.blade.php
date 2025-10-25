{{-- resources/views/tenant/projects/modals/create-project.blade.php --}}

<div class="jira-modal-overlay" id="createProjectModal" style="display: none;">
    <div class="jira-modal-container">
        <div class="jira-modal">
            <!-- Modal Header -->
            <div class="jira-modal-header">
                <div class="jira-header-left">
                    <button class="jira-back-btn" id="modalBackBtn" style="display: none;" onclick="goToPreviousStep()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="jira-header-info">
                        <h2 class="jira-modal-title" id="modalTitle">Create Project</h2>
                        <p class="jira-modal-subtitle" id="modalSubtitle">Set up your project in a few steps</p>
                    </div>
                </div>
                <div class="jira-header-actions">
                    <button class="jira-icon-btn" onclick="saveAsDraft()" title="Save Draft">
                        <i class="fas fa-save"></i>
                    </button>
                    <button class="jira-icon-btn jira-close-btn" onclick="closeCreateProjectModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Progress Indicator -->
            <div class="jira-progress-bar">
                <div class="jira-progress-fill" id="progressFill"></div>
            </div>

            <!-- Step Indicators -->
            <div class="jira-steps-container">
                <div class="jira-step-item active" data-step="1">
                    <div class="jira-step-number">1</div>
                    <span class="jira-step-label">Project Details</span>
                </div>
                <div class="jira-step-divider"></div>
                <div class="jira-step-item" data-step="2">
                    <div class="jira-step-number">2</div>
                    <span class="jira-step-label">Tasks & Subtasks</span>
                </div>
                <div class="jira-step-divider"></div>
                <div class="jira-step-item" data-step="3">
                    <div class="jira-step-number">3</div>
                    <span class="jira-step-label">Team Assignment</span>
                </div>
                <div class="jira-step-divider"></div>
                <div class="jira-step-item" data-step="4">
                    <div class="jira-step-number">4</div>
                    <span class="jira-step-label">Client & Review</span>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="jira-modal-body">

                <!-- STEP 1: Project Details -->
                <div class="jira-step-content active" id="step-1">
                    <div class="jira-form-section">
                        <h3 class="jira-section-title">
                            <i class="fas fa-project-diagram"></i>
                            Project Information
                        </h3>

                        <div class="jira-form-grid">
                            <div class="jira-form-group full-width">
                                <label class="jira-label required">Project Name</label>
                                <input type="text" class="jira-input" id="projectName"
                                    placeholder="e.g., Website Redesign" required>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Project Key</label>
                                <input type="text" class="jira-input" id="projectKey" placeholder="PROJ"
                                    maxlength="10" style="text-transform: uppercase;" required>
                                <small class="jira-hint">Short identifier (2-10 characters)</small>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Project Type</label>
                                <select class="jira-select" id="projectType" required>
                                    <option value="">Select type...</option>
                                    <option value="scrum">🏃 Scrum</option>
                                    <option value="kanban">📋 Kanban</option>
                                    <option value="waterfall">💧 Waterfall</option>
                                    <option value="custom">⚙️ Custom</option>
                                </select>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Category</label>
                                <select class="jira-select" id="projectCategory">
                                    <option value="">Select category...</option>
                                    <option value="development">💻 Development</option>
                                    <option value="design">🎨 Design</option>
                                    <option value="marketing">📢 Marketing</option>
                                    <option value="research">🔬 Research</option>
                                </select>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Priority</label>
                                <div class="jira-priority-selector">
                                    <button type="button" class="jira-priority-btn" data-priority="low"
                                        onclick="selectPriority('low')">
                                        <i class="fas fa-arrow-down"></i> Low
                                    </button>
                                    <button type="button" class="jira-priority-btn active" data-priority="medium"
                                        onclick="selectPriority('medium')">
                                        <i class="fas fa-minus"></i> Medium
                                    </button>
                                    <button type="button" class="jira-priority-btn" data-priority="high"
                                        onclick="selectPriority('high')">
                                        <i class="fas fa-arrow-up"></i> High
                                    </button>
                                    <button type="button" class="jira-priority-btn" data-priority="urgent"
                                        onclick="selectPriority('urgent')">
                                        <i class="fas fa-exclamation"></i> Urgent
                                    </button>
                                </div>
                                <input type="hidden" id="projectPriority" value="medium">
                            </div>

                            <div class="jira-form-group full-width">
                                <label class="jira-label">Description</label>
                                <textarea class="jira-textarea" id="projectDescription" rows="4"
                                    placeholder="Describe what this project aims to achieve..."></textarea>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Start Date</label>
                                <input type="date" class="jira-input" id="projectStartDate" required>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Due Date</label>
                                <input type="date" class="jira-input" id="projectDueDate" required>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Budget</label>
                                <div class="jira-input-group">
                                    <select class="jira-input-addon" id="projectCurrency">
                                        <option value="PKR">PKR</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                    </select>
                                    <input type="number" class="jira-input" id="projectBudget" placeholder="0"
                                        min="0">
                                </div>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Estimated Hours</label>
                                <input type="number" class="jira-input" id="estimatedHours" placeholder="0"
                                    min="0" step="0.5">
                            </div>

                            <div class="jira-form-group full-width">
                                <label class="jira-label">Project Flags</label>
                                <div class="jira-flags-container">
                                    <button type="button" class="jira-flag-btn" data-flag="important"
                                        onclick="toggleFlag(this)">
                                        🚩 Important
                                    </button>
                                    <button type="button" class="jira-flag-btn" data-flag="blocked"
                                        onclick="toggleFlag(this)">
                                        🚫 Blocked
                                    </button>
                                    <button type="button" class="jira-flag-btn" data-flag="urgent"
                                        onclick="toggleFlag(this)">
                                        ⚠️ Urgent
                                    </button>
                                    <button type="button" class="jira-flag-btn" data-flag="review"
                                        onclick="toggleFlag(this)">
                                        👀 Needs Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Tasks & Subtasks -->
                <div class="jira-step-content" id="step-2" style="display: none;">
                    <div class="jira-form-section">
                        <div class="jira-section-header">
                            <div>
                                <h3 class="jira-section-title">
                                    <i class="fas fa-tasks"></i>
                                    Tasks & Subtasks
                                </h3>
                                <p class="jira-section-desc">Break down your project into tasks and subtasks</p>
                            </div>
                            <button class="jira-btn-primary" onclick="addTask()">
                                <i class="fas fa-plus"></i>
                                Add Task
                            </button>
                        </div>

                        <div class="jira-tasks-stats">
                            <div class="jira-stat-card">
                                <span class="jira-stat-icon">📋</span>
                                <div class="jira-stat-info">
                                    <span class="jira-stat-label">Total Tasks</span>
                                    <span class="jira-stat-value" id="totalTasks">0</span>
                                </div>
                            </div>
                            <div class="jira-stat-card">
                                <span class="jira-stat-icon">✅</span>
                                <div class="jira-stat-info">
                                    <span class="jira-stat-label">Subtasks</span>
                                    <span class="jira-stat-value" id="totalSubtasks">0</span>
                                </div>
                            </div>
                            <div class="jira-stat-card">
                                <span class="jira-stat-icon">⏱️</span>
                                <div class="jira-stat-info">
                                    <span class="jira-stat-label">Est. Hours</span>
                                    <span class="jira-stat-value" id="totalHours">0h</span>
                                </div>
                            </div>
                        </div>

                        <div id="tasksContainer" class="jira-tasks-list">
                            <div class="jira-empty-state">
                                <div class="jira-empty-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h4 class="jira-empty-title">No tasks yet</h4>
                                <p class="jira-empty-text">Add your first task to get started</p>
                                <button class="jira-btn-primary" onclick="addTask()">
                                    <i class="fas fa-plus"></i>
                                    Add First Task
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Team Assignment -->
                <!-- STEP 3: Team & Assignment (ENHANCED VERSION) -->
                <div class="jira-step-content" id="step-3" style="display: none;">
                    <div class="jira-form-section">
                        <div class="jira-section-header">
                            <div>
                                <h3 class="jira-section-title">
                                    <i class="fas fa-users"></i>
                                    Team & Task Assignment
                                </h3>
                                <p class="jira-section-desc">Add team members and assign tasks to them</p>
                            </div>
                            <button type="button" class="jira-btn-primary" onclick="showAddMemberModal()">
                                <i class="fas fa-user-plus"></i>
                                Add Member
                            </button>
                        </div>

                        <!-- Team & Assignment Combined View -->
                        <div id="teamAssignmentContainer">
                            <!-- Empty state when no team members -->
                            <div class="jira-empty-state" id="emptyTeamState">
                                <div class="jira-empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="jira-empty-title">No team members yet</h4>
                                <p class="jira-empty-text">Add team members to assign tasks</p>
                                <button type="button" class="jira-btn-primary" onclick="showAddMemberModal()">
                                    <i class="fas fa-user-plus"></i>
                                    Add First Member
                                </button>
                            </div>

                            <!-- Team members with task assignment cards -->
                            <div id="teamMembersWithTasks" class="jira-team-assignment-grid" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Add Member Modal with Live Search -->
                <div class="jira-modal-overlay" id="addMemberModal" style="display: none;">
                    <div class="jira-small-modal">
                        <div class="jira-modal-header">
                            <h3 class="jira-modal-title">Add Team Member</h3>
                            <button type="button" class="jira-icon-btn" onclick="closeAddMemberModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="jira-modal-body">
                            <div class="jira-form-group">
                                <label class="jira-label required">Search User</label>
                                <div class="jira-search-input-wrapper" style="position: relative;">
                                    <input type="text" class="jira-input" id="memberSearch"
                                        placeholder="Type name or email to search..." autocomplete="off"
                                        oninput="searchUsers(this.value)">
                                    <div class="jira-search-loading" id="memberSearchLoading"
                                        style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>

                                <!-- Search Results Dropdown -->
                                <div class="jira-search-results" id="memberSearchResults"
                                    style="display: none; margin-top: 8px; max-height: 200px; overflow-y: auto; border: 2px solid var(--jira-border); border-radius: 3px; background: var(--jira-card);">
                                </div>
                            </div>

                            <!-- Selected User Preview -->
                            <div id="selectedMemberPreview" style="display: none; margin-top: 16px;">
                                <label class="jira-label">Selected User</label>
                                <div class="jira-selected-member-card" id="selectedMemberCard"
                                    style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--jira-bg); border-radius: 3px; margin-top: 6px;">
                                    <!-- Populated by JavaScript -->
                                </div>
                            </div>

                            <div class="jira-form-group" style="margin-top: 16px;">
                                <label class="jira-label required">Role</label>
                                <select class="jira-select" id="memberRole">
                                    <option value="developer">💻 Developer</option>
                                    <option value="designer">🎨 Designer</option>
                                    <option value="manager">📊 Project Manager</option>
                                    <option value="qa">🧪 QA Engineer</option>
                                    <option value="lead">👨‍💼 Team Lead</option>
                                </select>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Tech Stack</label>
                                <select class="jira-select" id="memberTechStack">
                                    <option value="frontend">Frontend Development</option>
                                    <option value="backend">Backend Development</option>
                                    <option value="fullstack">Full Stack Development</option>
                                    <option value="ui-ux">UI/UX Design</option>
                                    <option value="graphic">Graphic Design</option>
                                    <option value="mobile">Mobile Development</option>
                                    <option value="devops">DevOps</option>
                                    <option value="qa">Quality Assurance</option>
                                </select>
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Position</label>
                                <select class="jira-select" id="memberPosition">
                                    <option value="member">Team Member</option>
                                    <option value="lead">Team Lead</option>
                                    <option value="manager">Manager</option>
                                    <option value="senior">Senior</option>
                                    <option value="junior">Junior</option>
                                </select>
                            </div>
                        </div>
                        <div class="jira-modal-footer">
                            <button class="jira-btn-ghost" type="button"
                                onclick="closeAddMemberModal()">Cancel</button>
                            <button class="jira-btn-primary" type="button" onclick="confirmAddMember()"
                                id="confirmAddMemberBtn" disabled>
                                <i class="fas fa-check"></i>
                                Add Member
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Client & Review -->
          {{-- resources/views/tenant/projects/modals/create-project.blade.php --}}

<!-- STEP 4: Client & Review -->
<div class="jira-step-content" id="step-4" style="display: none;">
    <div class="jira-form-section">
        <!-- Client Toggle -->
        <div class="jira-client-card">
            <div class="jira-client-header">
                <div class="jira-client-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="jira-client-info">
                    <h3 class="jira-client-title">Convert to Client Order</h3>
                    <p class="jira-client-desc">Add a client to track this as a paid order with progress visibility</p>
                </div>
                <label class="jira-toggle">
                    <input type="checkbox" id="hasClientToggle" onchange="toggleClientSection()">
                    <span class="jira-toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Client Form -->
        <div id="clientFormSection" class="jira-client-form" style="display: none;">
            <h4 class="jira-subsection-title">Client Details</h4>
            
            <!-- Client Search -->
            <div class="jira-form-group" style="margin-bottom: 20px;">
                <label class="jira-label required">Search Client</label>
                <div style="position: relative;">
                    <input type="text" 
                           class="jira-input" 
                           id="clientSearch" 
                           placeholder="Type name or email to search existing client..." 
                           autocomplete="off"
                           oninput="searchClients(this.value)">
                    <div id="clientSearchLoading" style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
                
                <!-- Search Results -->
                <div id="clientSearchResults" style="display: none; margin-top: 8px; max-height: 200px; overflow-y: auto; border: 2px solid var(--jira-border); border-radius: 3px; background: var(--jira-card);"></div>
                
                <!-- Invite New Client Button -->
                <div id="inviteClientButton" style="display: none; margin-top: 12px;">
                    <button type="button" class="jira-btn-secondary" onclick="showInviteClientModal()">
                        <i class="fas fa-envelope"></i>
                        Invite as New Client
                    </button>
                </div>
            </div>
            
            <!-- Selected Client Preview -->
            <div id="selectedClientPreview" style="display: none; margin-bottom: 20px;">
                <label class="jira-label">Selected Client</label>
                <div id="selectedClientCard" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--jira-bg); border-radius: 3px; margin-top: 6px;">
                    <!-- Populated by JavaScript -->
                </div>
                <input type="hidden" id="selectedClientUserId" name="client_user_id">
            </div>

            <div class="jira-form-grid">
                <div class="jira-form-group">
                    <label class="jira-label">Company</label>
                    <input type="text" class="jira-input" id="clientCompany" name="client_company" placeholder="Acme Corp">
                </div>
                
                <div class="jira-form-group">
                    <label class="jira-label">Phone</label>
                    <input type="tel" class="jira-input" id="clientPhone" name="client_phone" placeholder="+92 300 1234567">
                </div>
                
                <div class="jira-form-group">
                    <label class="jira-label">Order Value</label>
                    <div class="jira-input-group">
                        <span class="jira-input-addon" id="orderCurrency">PKR</span>
                        <input type="number" class="jira-input" id="orderValue" name="order_value" placeholder="0" min="0">
                    </div>
                </div>
                
                <div class="jira-form-group">
                    <label class="jira-label">Payment Terms</label>
                    <select class="jira-select" id="paymentTerms" name="payment_terms">
                        <option value="milestone">Per Milestone</option>
                        <option value="upfront50">50% Upfront, 50% Completion</option>
                        <option value="monthly">Monthly</option>
                        <option value="completion">On Completion</option>
                    </select>
                </div>
                
                <div class="jira-form-group full-width">
                    <label class="jira-label">Special Requirements</label>
                    <textarea class="jira-textarea" id="specialRequirements" name="special_requirements" 
                        rows="3" placeholder="Any special requirements or notes..."></textarea>
                </div>
                
                <div class="jira-form-group full-width">
                    <label class="jira-label">Client Portal Access</label>
                    <div class="jira-checkbox-group">
                        <label class="jira-checkbox">
                            <input type="checkbox" id="clientPortalAccess" name="portal_access" checked>
                            <span>Grant portal access to view progress</span>
                        </label>
                        <label class="jira-checkbox">
                            <input type="checkbox" id="clientCanComment" name="can_comment">
                            <span>Allow commenting on tasks</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Summary -->
        <div class="jira-summary-section">
            <h4 class="jira-subsection-title">Project Summary</h4>
            <div class="jira-summary-grid" id="projectSummary"></div>
        </div>
    </div>
</div>

<!-- Invite Client Modal -->
<div class="jira-modal-overlay" id="inviteClientModal" style="display: none;">
    <div class="jira-small-modal">
        <div class="jira-modal-header">
            <h3 class="jira-modal-title">Invite New Client</h3>
            <button type="button" class="jira-icon-btn" onclick="closeInviteClientModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="jira-modal-body">
            <p style="margin-bottom: 16px; color: var(--jira-text-subtle); font-size: 13px;">
                Send an invitation to create an account. They'll receive an email with a registration link.
            </p>
            
            <div class="jira-form-group">
                <label class="jira-label required">Client Email</label>
                <input type="email" class="jira-input" id="inviteClientEmail" placeholder="client@example.com">
            </div>
            
            <div class="jira-form-group">
                <label class="jira-label required">Client Name</label>
                <input type="text" class="jira-input" id="inviteClientName" placeholder="John Doe">
            </div>
            
            <div class="jira-form-group">
                <label class="jira-label">Personal Message (Optional)</label>
                <textarea class="jira-textarea" id="inviteClientMessage" rows="3" 
                    placeholder="Add a personal message to the invitation email..."></textarea>
            </div>
        </div>
        <div class="jira-modal-footer">
            <button class="jira-btn-ghost" type="button" onclick="closeInviteClientModal()">Cancel</button>
            <button class="jira-btn-primary" type="button" onclick="sendClientInvitation()">
                <i class="fas fa-paper-plane"></i>
                Send Invitation
            </button>
        </div>
    </div>
</div>

<script>
// ======================================================
// CLIENT SEARCH & MANAGEMENT
// ======================================================

let selectedClient = null;
let clientSearchTimeout = null;

function toggleClientSection() {
    const isChecked = document.getElementById('hasClientToggle').checked;
    const clientForm = document.getElementById('clientFormSection');
    clientForm.style.display = isChecked ? 'block' : 'none';
    
    if (!isChecked) {
        // Reset client selection
        selectedClient = null;
        document.getElementById('clientSearch').value = '';
        document.getElementById('selectedClientPreview').style.display = 'none';
        document.getElementById('selectedClientUserId').value = '';
    }
}

function searchClients(query) {
    clearTimeout(clientSearchTimeout);
    
    const resultsContainer = document.getElementById('clientSearchResults');
    const loadingIndicator = document.getElementById('clientSearchLoading');
    const inviteButton = document.getElementById('inviteClientButton');
    
    if (query.trim().length < 2) {
        resultsContainer.style.display = 'none';
        resultsContainer.innerHTML = '';
        inviteButton.style.display = 'none';
        return;
    }
    
    loadingIndicator.style.display = 'block';
    
    clientSearchTimeout = setTimeout(() => {
        const url = `/{{ $username }}/manage/projects/search-clients?query=${encodeURIComponent(query)}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(users => {
            loadingIndicator.style.display = 'none';
            displayClientSearchResults(users, query);
        })
        .catch(error => {
            loadingIndicator.style.display = 'none';
            console.error('Client search error:', error);
            showNotification('Failed to search clients', 'error');
        });
    }, 300);
}

function displayClientSearchResults(users, searchQuery) {
    const resultsContainer = document.getElementById('clientSearchResults');
    const inviteButton = document.getElementById('inviteClientButton');
    
    if (users.length === 0) {
        resultsContainer.innerHTML = `
            <div style="padding: 16px; text-align: center; color: var(--jira-text-subtle);">
                <i class="fas fa-user-slash" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 13px;">No existing clients found</p>
            </div>
        `;
        resultsContainer.style.display = 'block';
        inviteButton.style.display = 'block';
        
        // Pre-fill email if it looks like an email
        if (searchQuery.includes('@')) {
            document.getElementById('inviteClientEmail').value = searchQuery;
        }
        return;
    }
    
    resultsContainer.innerHTML = users.map(user => `
        <div class="jira-search-result-item" 
             onclick="selectClient(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', '${escapeHtml(user.avatar)}', '${escapeHtml(user.company || '')}', '${escapeHtml(user.phone || '')}')"
             style="display: flex; align-items: center; gap: 12px; padding: 12px; cursor: pointer; transition: all 0.15s; border-bottom: 1px solid var(--jira-border);"
             onmouseover="this.style.background='var(--jira-hover)'"
             onmouseout="this.style.background='transparent'">
            <img src="${user.avatar}" 
                 alt="${escapeHtml(user.name)}" 
                 style="width: 36px; height: 36px; border-radius: 50%;">
            <div style="flex: 1;">
                <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${escapeHtml(user.name)}</div>
                <div style="font-size: 12px; color: var(--jira-text-subtle);">${escapeHtml(user.email)}</div>
                ${user.company ? `<div style="font-size: 11px; color: var(--jira-text-subtle); margin-top: 2px;">${escapeHtml(user.company)}</div>` : ''}
            </div>
            ${user.is_client ? '<span style="padding: 2px 8px; background: var(--jira-primary); color: white; border-radius: 3px; font-size: 10px; font-weight: 600;">CLIENT</span>' : ''}
        </div>
    `).join('');
    
    resultsContainer.style.display = 'block';
    inviteButton.style.display = 'block';
}

function selectClient(id, name, email, avatar, company, phone) {
    selectedClient = { id, name, email, avatar, company, phone };
    
    document.getElementById('clientSearchResults').style.display = 'none';
    document.getElementById('inviteClientButton').style.display = 'none';
    document.getElementById('clientSearch').value = name;
    
    const previewContainer = document.getElementById('selectedClientPreview');
    const cardContainer = document.getElementById('selectedClientCard');
    
    cardContainer.innerHTML = `
        <img src="${avatar}" alt="${name}" style="width: 40px; height: 40px; border-radius: 50%;">
        <div style="flex: 1;">
            <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${name}</div>
            <div style="font-size: 12px; color: var(--jira-text-subtle);">${email}</div>
            ${company ? `<div style="font-size: 11px; color: var(--jira-text-subtle); margin-top: 2px;">${company}</div>` : ''}
        </div>
        <i class="fas fa-check-circle" style="color: var(--jira-success); font-size: 20px;"></i>
    `;
    
    previewContainer.style.display = 'block';
    document.getElementById('selectedClientUserId').value = id;
    
    // Pre-fill company and phone if available
    if (company) document.getElementById('clientCompany').value = company;
    if (phone) document.getElementById('clientPhone').value = phone;
}

function showInviteClientModal() {
    document.getElementById('inviteClientModal').style.display = 'flex';
    document.getElementById('inviteClientEmail').focus();
}

function closeInviteClientModal() {
    document.getElementById('inviteClientModal').style.display = 'none';
    document.getElementById('inviteClientEmail').value = '';
    document.getElementById('inviteClientName').value = '';
    document.getElementById('inviteClientMessage').value = '';
}

function sendClientInvitation() {
    const email = document.getElementById('inviteClientEmail').value.trim();
    const name = document.getElementById('inviteClientName').value.trim();
    const message = document.getElementById('inviteClientMessage').value.trim();
    
    if (!email || !name) {
        showNotification('Please enter both email and name', 'error');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showNotification('Please enter a valid email address', 'error');
        return;
    }
    
    // Disable button during request
    const sendButton = event.target;
    sendButton.disabled = true;
    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    const url = `/{{ $username }}/manage/projects/invite-client`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, name, message })
    })
    .then(async response => {
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ ' + data.message, 'success');
            closeInviteClientModal();
            
            // Update UI to show pending invitation
            const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0052CC&color=fff`;
            selectedClient = {
                id: null,
                name: name,
                email: email,
                avatar: avatarUrl,
                company: '',
                phone: '',
                pending: true
            };
            
            document.getElementById('clientSearch').value = `${name} (Invitation Sent)`;
            document.getElementById('selectedClientPreview').style.display = 'block';
            document.getElementById('selectedClientCard').innerHTML = `
                <img src="${avatarUrl}" alt="${escapeHtml(name)}" style="width: 40px; height: 40px; border-radius: 50%;">
                <div style="flex: 1;">
                    <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${escapeHtml(name)}</div>
                    <div style="font-size: 12px; color: var(--jira-text-subtle);">${escapeHtml(email)}</div>
                    <div style="font-size: 11px; color: var(--jira-warning); margin-top: 2px;">⏳ Invitation Pending</div>
                </div>
                <i class="fas fa-clock" style="color: var(--jira-warning); font-size: 20px;"></i>
            `;
        } else {
            showNotification(data.message || 'Failed to send invitation', 'error');
        }
        
        // Re-enable button
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i> Send Invitation';
    })
    .catch(error => {
        console.error('Invitation error:', error);
        showNotification('Failed to send invitation. Please try again.', 'error');
        
        // Re-enable button
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i> Send Invitation';
    });
}
// Expose functions globally
window.toggleClientSection = toggleClientSection;
window.searchClients = searchClients;
window.selectClient = selectClient;
window.showInviteClientModal = showInviteClientModal;
window.closeInviteClientModal = closeInviteClientModal;
window.sendClientInvitation = sendClientInvitation;
</script>

            </div>

            <!-- Modal Footer -->
            <div class="jira-modal-footer">
                <div class="jira-footer-left">
                    <button class="jira-btn-ghost" onclick="closeCreateProjectModal()">Cancel</button>
                </div>
                <div class="jira-footer-right">
                    <button class="jira-btn-secondary" id="prevBtn" onclick="previousStep()"
                        style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                        Previous
                    </button>
                    <button class="jira-btn-primary" id="nextBtn" onclick="nextStep()">
                        <span id="nextBtnText">Continue</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="jira-modal-overlay" id="addMemberModal" style="display: none;">
    <div class="jira-small-modal">
        <div class="jira-modal-header">
            <h3 class="jira-modal-title">Add Team Member</h3>
            <button class="jira-icon-btn" onclick="closeAddMemberModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="jira-modal-body">
            <div class="jira-form-group">
                <label class="jira-label required">Search Member</label>
                <input type="text" class="jira-input" id="memberSearch" placeholder="Enter name or email...">
            </div>
            <div class="jira-form-group">
                <label class="jira-label required">Role</label>
                <select class="jira-select" id="memberRole">
                    <option value="developer">💻 Developer</option>
                    <option value="designer">🎨 Designer</option>
                    <option value="manager">📊 Project Manager</option>
                    <option value="qa">🧪 QA Engineer</option>
                    <option value="lead">👨‍💼 Team Lead</option>
                </select>
            </div>
            <div class="jira-form-group">
                <label class="jira-label required">Tech Stack</label>
                <select class="jira-select" id="memberTechStack">
                    <option value="frontend">Frontend Development</option>
                    <option value="backend">Backend Development</option>
                    <option value="fullstack">Full Stack Development</option>
                    <option value="ui-ux">UI/UX Design</option>
                    <option value="graphic">Graphic Design</option>
                    <option value="mobile">Mobile Development</option>
                    <option value="devops">DevOps</option>
                    <option value="qa">Quality Assurance</option>
                </select>
            </div>
            <div class="jira-form-group">
                <label class="jira-label">Position</label>
                <select class="jira-select" id="memberPosition">
                    <option value="member">Team Member</option>
                    <option value="lead">Team Lead</option>
                    <option value="manager">Manager</option>
                    <option value="senior">Senior</option>
                    <option value="junior">Junior</option>
                </select>
            </div>
        </div>
        <div class="jira-modal-footer">
            <button class="jira-btn-ghost" onclick="closeAddMemberModal()">Cancel</button>
            <button class="jira-btn-primary" onclick="confirmAddMember()">
                <i class="fas fa-check"></i>
                Add Member
            </button>
        </div>
    </div>
</div>
<!-- Dependencies Selection Modal -->
<div class="jira-dependencies-modal" id="dependenciesModal">
    <div class="jira-dependencies-content">
        <div class="jira-dependencies-header">
            <h3 class="jira-dependencies-title">Select Task Dependencies</h3>
            <button type="button" class="jira-icon-btn" onclick="closeDependenciesModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="jira-dependencies-body">
            <p style="font-size: 13px; color: var(--jira-text-subtle); margin: 0 0 16px 0;">
                Select tasks that must be completed before this task can start:
            </p>
            <div class="jira-dependencies-list" id="dependenciesListModal"></div>
        </div>
        <div class="jira-dependencies-footer">
            <button type="button" class="jira-btn-ghost" onclick="closeDependenciesModal()">Cancel</button>
            <button type="button" class="jira-btn-primary" onclick="confirmDependencies()">
                <i class="fas fa-check"></i>
                Confirm
            </button>
        </div>
    </div>
</div>

 
{{-- Include all your CSS here --}}

<script>
// ======================================================
// CORE VARIABLES
// ======================================================
let currentStep = 1;
const totalSteps = 4;
let projectData = { basic: {}, tasks: [], team: [], client: null, flags: [] };
let taskCounter = 0;
let teamMembers = [];
let searchTimeout = null;
let selectedUser = null;

// ======================================================
// USER SEARCH WITH LIVE API
// ======================================================
function searchUsers(query) {
    clearTimeout(searchTimeout);
    
    const resultsContainer = document.getElementById('memberSearchResults');
    const loadingIndicator = document.getElementById('memberSearchLoading');
    const confirmBtn = document.getElementById('confirmAddMemberBtn');
    
    if (query.trim().length < 2) {
        resultsContainer.style.display = 'none';
        resultsContainer.innerHTML = '';
        confirmBtn.disabled = true;
        return;
    }
    
    loadingIndicator.style.display = 'block';
    
    searchTimeout = setTimeout(() => {
        const url = `/{{ $username }}/manage/projects/search-users?query=${encodeURIComponent(query)}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(users => {
            loadingIndicator.style.display = 'none';
            displaySearchResults(users);
        })
        .catch(error => {
            loadingIndicator.style.display = 'none';
            console.error('Search error:', error);
            showNotification('Failed to search users', 'error');
        });
    }, 300);
}

function displaySearchResults(users) {
    const resultsContainer = document.getElementById('memberSearchResults');
    
    if (users.length === 0) {
        resultsContainer.innerHTML = `
            <div style="padding: 16px; text-align: center; color: var(--jira-text-subtle);">
                <i class="fas fa-user-slash" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 13px;">No users found</p>
            </div>
        `;
        resultsContainer.style.display = 'block';
        return;
    }
    
    resultsContainer.innerHTML = users.map(user => `
        <div class="jira-search-result-item" 
             onclick="selectUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', '${escapeHtml(user.avatar)}')"
             style="display: flex; align-items: center; gap: 12px; padding: 12px; cursor: pointer; transition: all 0.15s; border-bottom: 1px solid var(--jira-border);"
             onmouseover="this.style.background='var(--jira-hover)'"
             onmouseout="this.style.background='transparent'">
            <img src="${user.avatar}" 
                 alt="${escapeHtml(user.name)}" 
                 style="width: 36px; height: 36px; border-radius: 50%;">
            <div style="flex: 1;">
                <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${escapeHtml(user.name)}</div>
                <div style="font-size: 12px; color: var(--jira-text-subtle);">${escapeHtml(user.email)}</div>
            </div>
        </div>
    `).join('');
    
    resultsContainer.style.display = 'block';
}

function selectUser(id, name, email, avatar) {
    selectedUser = { id, name, email, avatar };
    
    document.getElementById('memberSearchResults').style.display = 'none';
    document.getElementById('memberSearch').value = name;
    
    const previewContainer = document.getElementById('selectedMemberPreview');
    const cardContainer = document.getElementById('selectedMemberCard');
    
    cardContainer.innerHTML = `
        <img src="${avatar}" alt="${name}" style="width: 40px; height: 40px; border-radius: 50%;">
        <div style="flex: 1;">
            <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${name}</div>
            <div style="font-size: 12px; color: var(--jira-text-subtle);">${email}</div>
        </div>
        <i class="fas fa-check-circle" style="color: var(--jira-success); font-size: 20px;"></i>
    `;
    
    previewContainer.style.display = 'block';
    document.getElementById('confirmAddMemberBtn').disabled = false;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function confirmAddMember() {
    if (!selectedUser) {
        showNotification('Please select a user', 'error');
        return;
    }
    
    const role = document.getElementById('memberRole').value;
    const techStack = document.getElementById('memberTechStack').value;
    const position = document.getElementById('memberPosition').value;
    
    if (teamMembers.some(m => m.user_id === selectedUser.id)) {
        showNotification('This user is already in the team', 'error');
        return;
    }
    
    const member = {
        id: Date.now(),
        user_id: selectedUser.id,
        name: selectedUser.name,
        email: selectedUser.email,
        avatar: selectedUser.avatar,
        role: role,
        techStack: techStack,
        position: position,
        assignedTasks: []
    };
    
    teamMembers.push(member);
    renderTeamMembersWithTasks();
    closeAddMemberModal();
    showNotification('✅ Team member added successfully!', 'success');
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').style.display = 'none';
    document.getElementById('memberSearch').value = '';
    document.getElementById('memberSearchResults').style.display = 'none';
    document.getElementById('selectedMemberPreview').style.display = 'none';
    document.getElementById('confirmAddMemberBtn').disabled = true;
    selectedUser = null;
}

function showAddMemberModal() {
    document.getElementById('addMemberModal').style.display = 'flex';
    document.getElementById('memberSearch').focus();
}

function renderTeamMembersWithTasks() {
    const emptyState = document.getElementById('emptyTeamState');
    const container = document.getElementById('teamMembersWithTasks');
    
    if (teamMembers.length === 0) {
        emptyState.style.display = 'flex';
        container.style.display = 'none';
        return;
    }
    
    emptyState.style.display = 'none';
    container.style.display = 'grid';
    
    if (projectData.tasks.length === 0) {
        container.innerHTML = `
            <div class="jira-empty-state">
                <div class="jira-empty-icon"><i class="fas fa-tasks"></i></div>
                <h4 class="jira-empty-title">No tasks to assign</h4>
                <p class="jira-empty-text">Please add tasks in Step 2 first</p>
            </div>
        `;
        return;
    }
    
    const roleIcons = {
        developer: '💻',
        designer: '🎨',
        manager: '📊',
        qa: '🧪',
        lead: '👨‍💼'
    };
    
    const priorityIcons = {
        low: 'fa-arrow-down',
        medium: 'fa-minus',
        high: 'fa-arrow-up',
        urgent: 'fa-exclamation'
    };
    
    container.innerHTML = teamMembers.map(member => {
        const assignedTasks = member.assignedTasks || [];
        
        return `
            <div class="jira-team-member-assignment-card" style="background: var(--jira-card); border: 2px solid var(--jira-border); border-radius: 3px; padding: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid var(--jira-border);">
                    <img src="${member.avatar}" alt="${member.name}" style="width: 48px; height: 48px; border-radius: 50%;">
                    <div style="flex: 1;">
                        <div style="font-size: 16px; font-weight: 600; color: var(--jira-text); margin-bottom: 4px;">
                            ${member.name}
                        </div>
                        <div style="font-size: 12px; color: var(--jira-text-subtle);">
                            ${roleIcons[member.role] || '👤'} ${member.role} · ${member.techStack}
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="padding: 6px 12px; background: ${assignedTasks.length > 0 ? 'var(--jira-primary)' : 'var(--jira-hover)'}; color: ${assignedTasks.length > 0 ? 'white' : 'var(--jira-text-subtle)'}; border-radius: 3px; font-size: 12px; font-weight: 600;">
                            ${assignedTasks.length} ${assignedTasks.length === 1 ? 'task' : 'tasks'}
                        </div>
                        <button type="button" 
                                onclick="removeMember(${member.id})" 
                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: none; border: none; color: var(--jira-text-subtle); cursor: pointer; border-radius: 3px; transition: all 0.15s;"
                                onmouseover="this.style.background='#FFEBE6'; this.style.color='var(--jira-danger)'"
                                onmouseout="this.style.background='none'; this.style.color='var(--jira-text-subtle)'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <div style="font-size: 12px; font-weight: 600; color: var(--jira-text-subtle); text-transform: uppercase; margin-bottom: 12px;">
                        Available Tasks
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        ${projectData.tasks.map((task, taskIndex) => {
                            const isAssigned = assignedTasks.includes(taskIndex);
                            const priorityClass = task.priority || 'medium';
                            
                            return `
                                <div onclick="toggleTaskAssignmentForMember(${member.id}, ${taskIndex})"
                                     style="display: flex; align-items: center; gap: 12px; padding: 12px; background: ${isAssigned ? 'rgba(0, 82, 204, 0.1)' : 'var(--jira-bg)'}; border: 2px solid ${isAssigned ? 'var(--jira-primary)' : 'var(--jira-border)'}; border-radius: 3px; cursor: pointer; transition: all 0.15s;"
                                     onmouseover="if(!${isAssigned}) this.style.borderColor='var(--jira-primary)'"
                                     onmouseout="if(!${isAssigned}) this.style.borderColor='var(--jira-border)'">
                                    
                                    <div class="jira-task-priority-icon ${priorityClass}" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 3px; flex-shrink: 0;">
                                        <i class="fas ${priorityIcons[priorityClass]}"></i>
                                    </div>
                                    
                                    <div style="flex: 1; font-size: 14px; font-weight: 500; color: var(--jira-text);">
                                        
${task.name}
                                    </div>
                                    
                                    <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: ${isAssigned ? 'var(--jira-success)' : 'var(--jira-card)'}; border: 2px solid ${isAssigned ? 'var(--jira-success)' : 'var(--jira-border)'}; border-radius: 3px; flex-shrink: 0;">
                                        ${isAssigned ? '<i class="fas fa-check" style="color: white; font-size: 12px;"></i>' : ''}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
                
                ${assignedTasks.length === 0 ? `
                    <div style="text-align: center; padding: 20px; color: var(--jira-text-subtle); font-size: 13px; margin-top: 16px; background: var(--jira-bg); border-radius: 3px;">
                        <i class="fas fa-hand-point-up" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        Click on tasks above to assign them to ${member.name.split(' ')[0]}
                    </div>
                ` : ''}
            </div>
        `;
    }).join('');
}

function toggleTaskAssignmentForMember(memberId, taskIndex) {
    const member = teamMembers.find(m => m.id === memberId);
    if (!member) return;
    
    if (!member.assignedTasks) member.assignedTasks = [];
    
    const index = member.assignedTasks.indexOf(taskIndex);
    if (index > -1) {
        member.assignedTasks.splice(index, 1);
        showNotification('Task unassigned', 'info');
    } else {
        member.assignedTasks.push(taskIndex);
        showNotification('Task assigned!', 'success');
    }
    
    renderTeamMembersWithTasks();
}

function removeMember(memberId) {
    if (!confirm('Remove this team member? All task assignments will be lost.')) return;
    
    teamMembers = teamMembers.filter(m => m.id !== memberId);
    renderTeamMembersWithTasks();
    showNotification('Member removed', 'info');
}

function prepareTeamAssignmentStep() {
    saveCurrentStepData();
    renderTeamMembersWithTasks();
}

// ======================================================
// ALL YOUR OTHER EXISTING FUNCTIONS (Keep them all!)
// ======================================================

function selectPriority(priority, ev) {
    document.querySelectorAll('.jira-priority-btn').forEach(btn => btn.classList.remove('active'));
    ev.currentTarget.classList.add('active');
    document.getElementById('projectPriority').value = priority;
}

function toggleFlag(button) {
    const flag = button.dataset.flag;
    button.classList.toggle('active');
    
    if (button.classList.contains('active')) {
        if (!projectData.flags.includes(flag)) {
            projectData.flags.push(flag);
        }
    } else {
        projectData.flags = projectData.flags.filter(f => f !== flag);
    }
}

function addTask() {
    taskCounter++;
    const container = document.getElementById('tasksContainer');
    const emptyState = container.querySelector('.jira-empty-state');
    if (emptyState) emptyState.remove();
    
    const taskCard = document.createElement('div');
    taskCard.className = 'jira-task-card';
    taskCard.dataset.taskId = taskCounter;
    taskCard.innerHTML = `
        <div class="jira-task-header">
            <div class="jira-task-drag" title="Drag to reorder">
                <i class="fas fa-grip-vertical"></i>
            </div>
            <div class="jira-task-priority-icon medium" onclick="cycleTaskPriority(this)">
                <i class="fas fa-minus"></i>
            </div>
            <input type="text" class="jira-task-title-input" name="tasks[${taskCounter}][title]" 
                placeholder="Task name..." value="Task ${taskCounter}" required>
            <div class="jira-task-actions">
                <button type="button" class="jira-task-action-btn" onclick="addTaskFlag(this)" title="Add flag">
                    <i class="fas fa-flag"></i>
                </button>
                <button type="button" class="jira-task-action-btn" onclick="duplicateTask(this)" title="Duplicate">
                    <i class="fas fa-copy"></i>
                </button>
                <button type="button" class="jira-task-action-btn delete" onclick="deleteTask(this)" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <div class="jira-task-notes">
            <label class="jira-task-notes-label">Notes (Optional)</label>
            <textarea class="jira-task-notes-textarea" name="tasks[${taskCounter}][notes]" 
                placeholder="Add detailed notes, requirements, or context for this task..." rows="3"></textarea>
        </div>
        
        <div class="jira-task-meta">
            <div class="jira-task-meta-item">
                <label class="jira-task-meta-label">Due Date</label>
                <input type="date" class="jira-input" name="tasks[${taskCounter}][due_date]">
            </div>
            <div class="jira-task-meta-item">
                <label class="jira-task-meta-label">Est. Hours</label>
                <input type="number" class="jira-input" name="tasks[${taskCounter}][estimated_hours]" 
                    placeholder="0" min="0" step="0.5" onchange="updateStats()">
            </div>
            <div class="jira-task-meta-item">
                <label class="jira-task-meta-label">Story Points</label>
                <select class="jira-select" name="tasks[${taskCounter}][story_points]">
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
        
        <input type="hidden" name="tasks[${taskCounter}][priority]" value="medium" class="task-priority-input">
        
        <div class="jira-task-flags" style="display: none;"></div>
        
        <div class="jira-subtasks-section">
            <div class="jira-subtasks-header">
                <span class="jira-subtasks-title">
                    Subtasks
                    <span class="jira-subtask-count">0</span>
                </span>
                <button type="button" class="jira-btn-ghost" style="height: 28px; padding: 0 12px; font-size: 13px;" 
                    onclick="addSubtask(this)">
                    <i class="fas fa-plus"></i> Add Subtask
                </button>
            </div>
            <div class="jira-subtasks-list"></div>
        </div>
    `;
    
    container.appendChild(taskCard);
    updateStats();
}

function cycleTaskPriority(icon) {
    const priorities = ['low', 'medium', 'high', 'urgent'];
    const icons = {
        low: 'fa-arrow-down',
        medium: 'fa-minus',
        high: 'fa-arrow-up',
        urgent: 'fa-exclamation'
    };
    
    let current = 'medium';
    priorities.forEach(p => {
        if (icon.classList.contains(p)) current = p;
    });
    
    const next = priorities[(priorities.indexOf(current) + 1) % priorities.length];
    
    priorities.forEach(p => icon.classList.remove(p));
    icon.classList.add(next);
    icon.querySelector('i').className = `fas ${icons[next]}`;
    
    const taskCard = icon.closest('.jira-task-card');
    const priorityInput = taskCard.querySelector('.task-priority-input');
    if (priorityInput) {
        priorityInput.value = next;
    }
}

function deleteTask(button) {
    if (!confirm('Delete this task and all subtasks?')) return;
    
    button.closest('.jira-task-card').remove();
    
    const container = document.getElementById('tasksContainer');
    if (!container.querySelector('.jira-task-card')) {
        container.innerHTML = `
            <div class="jira-empty-state">
                <div class="jira-empty-icon"><i class="fas fa-tasks"></i></div>
                <h4 class="jira-empty-title">No tasks yet</h4>
                <p class="jira-empty-text">Add your first task to get started</p>
                <button type="button" class="jira-btn-primary" onclick="addTask()">
                    <i class="fas fa-plus"></i> Add First Task
                </button>
            </div>
        `;
    }
    
    updateStats();
}

function addSubtask(button) {
    const taskCard = button.closest('.jira-task-card');
    const taskId = taskCard.dataset.taskId;
    const subtasksList = taskCard.querySelector('.jira-subtasks-list');
    const subtaskCount = subtasksList.querySelectorAll('.jira-subtask-item').length;
    
    const subtaskEl = document.createElement('div');
    subtaskEl.className = 'jira-subtask-item';
    subtaskEl.innerHTML = `
        <div class="jira-subtask-checkbox" onclick="toggleSubtaskCheck(this)"></div>
        <input type="text" class="jira-subtask-input" 
            name="tasks[${taskId}][subtasks][${subtaskCount}][title]" 
            placeholder="Subtask name..." value="Subtask ${subtaskCount + 1}" required>
        <input type="hidden" class="subtask-completed-input" 
            name="tasks[${taskId}][subtasks][${subtaskCount}][completed]" value="0">
        <button type="button" class="jira-subtask-remove" onclick="deleteSubtask(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    subtasksList.appendChild(subtaskEl);
    updateSubtaskCount(taskCard);
    updateStats();
}

function toggleSubtaskCheck(box) {
    box.classList.toggle('checked');
    const subtaskItem = box.closest('.jira-subtask-item');
    const completedInput = subtaskItem.querySelector('.subtask-completed-input');
    if (completedInput) {
        completedInput.value = box.classList.contains('checked') ? '1' : '0';
    }
}

function deleteSubtask(button) {
    const taskCard = button.closest('.jira-task-card');
    button.closest('.jira-subtask-item').remove();
    updateSubtaskCount(taskCard);
    updateStats();
}

function updateSubtaskCount(taskCard) {
    const total = taskCard.querySelectorAll('.jira-subtask-item').length;
    const countEl = taskCard.querySelector('.jira-subtask-count');
    if (countEl) countEl.textContent = total;
}

function updateStats() {
    const totalTasks = document.querySelectorAll('.jira-task-card').length;
    const totalSubtasks = document.querySelectorAll('.jira-subtask-item').length;
    
    let totalHours = 0;
    document.querySelectorAll('input[name*="[estimated_hours]"]').forEach(input => {
        totalHours += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalTasks').textContent = totalTasks;
    document.getElementById('totalSubtasks').textContent = totalSubtasks;
    document.getElementById('totalHours').textContent = totalHours + 'h';
}

function collectTasksData() {
    const tasks = [];
    
    document.querySelectorAll('.jira-task-card').forEach((card) => {
        const priorityInput = card.querySelector('.task-priority-input');
        const priority = priorityInput ? priorityInput.value : 'medium';
        
        const titleInput = card.querySelector('.jira-task-title-input');
        const notesTextarea = card.querySelector('.jira-task-notes-textarea');
        const dueDateInput = card.querySelector('input[name*="[due_date]"]');
        const estimatedHoursInput = card.querySelector('input[name*="[estimated_hours]"]');
        const storyPointsSelect = card.querySelector('select[name*="[story_points]"]');
        
        const task = {
            name: titleInput ? titleInput.value.trim() : '',
            notes: notesTextarea ? notesTextarea.value.trim() : '',
            priority: priority,
            dueDate: dueDateInput ? dueDateInput.value : '',
            estimatedHours: estimatedHoursInput ? estimatedHoursInput.value : '',
            storyPoints: storyPointsSelect ? storyPointsSelect.value : '0',
            subtasks: []
        };
        
        // Collect subtasks
        card.querySelectorAll('.jira-subtask-item').forEach(subtask => {
            const stTitle = subtask.querySelector('.jira-subtask-input');
            const completed = subtask.querySelector('.subtask-completed-input');
            task.subtasks.push({
                name: stTitle ? stTitle.value.trim() : '',
                completed: completed ? completed.value === '1' : false
            });
        });
        
        tasks.push(task);
    });
    
    return tasks;
}
function saveCurrentStepData() {
    projectData.basic = {
        name: document.getElementById('projectName').value.trim(),
        key: document.getElementById('projectKey').value.trim().toUpperCase(),
        type: document.getElementById('projectType').value,
        category: document.getElementById('projectCategory').value,
        priority: document.getElementById('projectPriority').value,
        description: document.getElementById('projectDescription').value.trim(),
        startDate: document.getElementById('projectStartDate').value,
        dueDate: document.getElementById('projectDueDate').value,
        budget: document.getElementById('projectBudget').value,
        currency: document.getElementById('projectCurrency').value,
        estimatedHours: document.getElementById('estimatedHours').value
    };
    
    projectData.tasks = collectTasksData();
}

function nextStep() {
    if (!validateCurrentStep()) return;
    
    saveCurrentStepData();
    
    if (currentStep < totalSteps) {
        currentStep++;
        
        if (currentStep === 3) {
            prepareTeamAssignmentStep();
        } else if (currentStep === 4) {
            prepareReviewStep();
        }
        
        updateStepDisplay();
        scrollToTop();
    } else {
        submitProject();
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
        scrollToTop();
    }
}

function goToPreviousStep() {
    previousStep();
}

function scrollToTop() {
    const modalBody = document.querySelector('.jira-modal-body');
    if (modalBody) modalBody.scrollTop = 0;
}

function updateStepDisplay() {
    document.querySelectorAll('.jira-step-content').forEach(step => {
        step.style.display = 'none';
    });
    
    const curEl = document.getElementById(`step-${currentStep}`);
    if (curEl) curEl.style.display = 'block';
    
    document.querySelectorAll('.jira-step-item').forEach((item, index) => {
        const stepNum = index + 1;
        item.classList.remove('active', 'completed');
        
        if (stepNum < currentStep) {
            item.classList.add('completed');
        } else if (stepNum === currentStep) {
            item.classList.add('active');
        }
    });
    
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progressFill').style.width = progress + '%';
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const nextBtnTxt = document.getElementById('nextBtnText');
    const backBtn = document.getElementById('modalBackBtn');
    
    if (currentStep === 1) {
        prevBtn.style.display = 'none';
        backBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'flex';
        backBtn.style.display = 'flex';
    }
    
    if (currentStep === totalSteps) {
        nextBtnTxt.textContent = 'Create Project';
        nextBtn.querySelector('i').className = 'fas fa-check';
    } else {
        nextBtnTxt.textContent = 'Continue';
        nextBtn.querySelector('i').className = 'fas fa-arrow-right';
    }
    
    const titles = [
        { title: 'Create Project', subtitle: 'Set up your project in a few steps' },
        { title: 'Add Tasks & Subtasks', subtitle: 'Break down your project into manageable pieces' },
        { title: 'Assign Team Members', subtitle: 'Add collaborators and assign tasks' },
        { title: 'Client & Review', subtitle: 'Finalize and review your project' }
    ];
    
    document.getElementById('modalTitle').textContent = titles[currentStep - 1].title;
    document.getElementById('modalSubtitle').textContent = titles[currentStep - 1].subtitle;
}

function validateCurrentStep() {
    if (currentStep === 1) {
        const name = document.getElementById('projectName').value.trim();
        const key = document.getElementById('projectKey').value.trim();
        const type = document.getElementById('projectType').value;
        const startDate = document.getElementById('projectStartDate').value;
        const dueDate = document.getElementById('projectDueDate').value;
        
        if (!name) {
            showNotification('Please enter project name', 'error');
            document.getElementById('projectName').focus();
            return false;
        }
        if (!key || key.length < 2 || key.length > 10) {
            showNotification('Project key must be between 2-10 characters', 'error');
            document.getElementById('projectKey').focus();
            return false;
        }
        if (!type) {
            showNotification('Please select project type', 'error');
            return false;
        }
        if (!startDate || !dueDate) {
            showNotification('Please select start and due dates', 'error');
            return false;
        }
        if (new Date(startDate) > new Date(dueDate)) {
            showNotification('Start date must be before due date', 'error');
            return false;
        }
    }
    
    if (currentStep === 2) {
        const taskCards = document.querySelectorAll('.jira-task-card');
        if (taskCards.length === 0) {
            if (!confirm('No tasks added. Continue anyway?')) {
                return false;
            }
        } else {
            for (let card of taskCards) {
                const titleInput = card.querySelector('.jira-task-title-input');
                if (!titleInput.value.trim()) {
                    showNotification('All tasks must have a title', 'error');
                    titleInput.focus();
                    return false;
                }
            }
        }
    }
    
    return true;
}

function prepareReviewStep() {
    const summary = document.getElementById('projectSummary');
    
    const summaryData = [
        { label: 'Project Name', value: projectData.basic.name },
        { label: 'Project Key', value: projectData.basic.key },
        { label: 'Type', value: projectData.basic.type },
        { label: 'Priority', value: projectData.basic.priority },
        { label: 'Start Date', value: projectData.basic.startDate },
        { label: 'Due Date', value: projectData.basic.dueDate },
        { label: 'Total Tasks', value: projectData.tasks.length },
        { label: 'Team Members', value: teamMembers.length },
        {
            label: 'Budget',
            value: projectData.basic.budget
                ? `${projectData.basic.currency} ${projectData.basic.budget}`
                : 'Not set'
        },
        {
            label: 'Estimated Hours',
            value: projectData.basic.estimatedHours || 'Not set'
        }
    ];
    
    summary.innerHTML = summaryData.map(item => `
        <div class="jira-summary-item">
            <div class="jira-summary-label">${item.label}</div>
            <div class="jira-summary-value">${item.value}</div>
        </div>
    `).join('');
}

function submitProject() {
    if (!validateCurrentStep()) return;
    
    saveCurrentStepData(); // ✅ THIS IS CRITICAL!
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('tenant.manage.projects.store', $username) }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    function appendField(name, value) {
        if (value === undefined || value === null) return;
        if (typeof value === 'string' && value.trim() === '') return;
        const i = document.createElement('input');
        i.type = 'hidden';
        i.name = name;
        i.value = value;
        form.appendChild(i);
    }
    
    // ✅ 1. ADD BASIC PROJECT FIELDS FIRST
    appendField('name', projectData.basic.name);
    appendField('key', projectData.basic.key);
    appendField('type', projectData.basic.type);
    appendField('category', projectData.basic.category);
    appendField('priority', projectData.basic.priority);
    appendField('description', projectData.basic.description);
    appendField('start_date', projectData.basic.startDate);
    appendField('due_date', projectData.basic.dueDate);
    appendField('budget', projectData.basic.budget);
    appendField('currency', projectData.basic.currency);
    appendField('estimated_hours', projectData.basic.estimatedHours);
    
    // ✅ 2. ADD FLAGS
    if (projectData.flags && projectData.flags.length > 0) {
        projectData.flags.forEach((flag, index) => {
            appendField(`flags[${index}]`, flag);
        });
    }
    
    // ✅ 3. ADD TASKS WITH PROPER STRUCTURE
    projectData.tasks.forEach((task, taskIndex) => {
        appendField(`tasks[${taskIndex}][title]`, task.name);
        appendField(`tasks[${taskIndex}][notes]`, task.notes);
        appendField(`tasks[${taskIndex}][priority]`, task.priority);
        appendField(`tasks[${taskIndex}][due_date]`, task.dueDate);
        appendField(`tasks[${taskIndex}][estimated_hours]`, task.estimatedHours);
        appendField(`tasks[${taskIndex}][story_points]`, task.storyPoints);
        
        // Add subtasks
        if (task.subtasks && task.subtasks.length > 0) {
            task.subtasks.forEach((subtask, subIndex) => {
                appendField(`tasks[${taskIndex}][subtasks][${subIndex}][title]`, subtask.name);
                appendField(`tasks[${taskIndex}][subtasks][${subIndex}][completed]`, subtask.completed ? '1' : '0');
            });
        }
    });
    
    // ✅ 4. ADD TEAM MEMBERS WITH TASK ASSIGNMENTS
    teamMembers.forEach((member, index) => {
        if (!member.user_id) return;
        
        appendField(`team[${index}][user_id]`, member.user_id);
        appendField(`team[${index}][role]`, member.role);
        appendField(`team[${index}][tech_stack]`, member.techStack);
        appendField(`team[${index}][position]`, member.position);
        
        // Assign tasks to this member
        if (member.assignedTasks && member.assignedTasks.length > 0) {
            member.assignedTasks.forEach(taskIndex => {
                appendField(`tasks[${taskIndex}][assigned_to]`, member.user_id);
            });
        }
    });
    
    // ✅ 5. ADD CLIENT DATA (if toggle is ON)
    const hasClient = document.getElementById('hasClientToggle')?.checked;
    if (hasClient) {
        const clientUserId = document.getElementById('selectedClientUserId')?.value;
        if (clientUserId) {
            appendField('client_user_id', clientUserId);
            appendField('client_company', document.getElementById('clientCompany')?.value);
            appendField('client_phone', document.getElementById('clientPhone')?.value);
            appendField('order_value', document.getElementById('orderValue')?.value);
            appendField('payment_terms', document.getElementById('paymentTerms')?.value);
            appendField('special_requirements', document.getElementById('specialRequirements')?.value);
            appendField('portal_access', document.getElementById('clientPortalAccess')?.checked ? '1' : '0');
            appendField('can_comment', document.getElementById('clientCanComment')?.checked ? '1' : '0');
        }
    }
    
    console.log('📦 Form Data Being Submitted:', Array.from(new FormData(form)).reduce((obj, [key, val]) => {
        obj[key] = val;
        return obj;
    }, {}));
    
    localStorage.removeItem('projectDraft');
    
    document.body.appendChild(form);
    form.submit();
}

function openCreateProjectModal() {
    document.getElementById('createProjectModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    initializeModal();
}

function closeCreateProjectModal() {
    if (confirm('Are you sure? All unsaved data will be lost.')) {
        document.getElementById('createProjectModal').style.display = 'none';
        document.body.style.overflow = '';
    }
}

function initializeModal() {
    currentStep = 1;
    taskCounter = 0;
    updateStepDisplay();
    setDefaultDates();
}

function setDefaultDates() {
    const today = new Date().toISOString().split('T')[0];
    const startDateEl = document.getElementById('projectStartDate');
    const dueDateEl = document.getElementById('projectDueDate');
    
    if (startDateEl) {
        startDateEl.min = today;
        startDateEl.value = today;
    }
    if (dueDateEl) {
        dueDateEl.min = today;
    }
}

function showNotification(message, type = 'info') {
    const colors = {
        success: '#00875A',
        error: '#DE350B',
        warning: '#FF991F',
        info: '#0052CC'
    };
    
    const el = document.createElement('div');
    el.style.cssText = `
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        padding: 12px 20px;
        background: ${colors[type] || colors.info};
        color: white;
        border-radius: 3px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
    `;
    
    el.textContent = message;
    document.body.appendChild(el);
    
    setTimeout(() => {
        el.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => el.remove(), 300);
    }, 3000);
}

// Expose all functions globally
window.openCreateProjectModal = openCreateProjectModal;
window.closeCreateProjectModal = closeCreateProjectModal;
window.nextStep = nextStep;
window.previousStep = previousStep;
window.goToPreviousStep = goToPreviousStep;
window.selectPriority = selectPriority;
window.toggleFlag = toggleFlag;
window.addTask = addTask;
window.cycleTaskPriority = cycleTaskPriority;
window.deleteTask = deleteTask;
window.addSubtask = addSubtask;
window.toggleSubtaskCheck = toggleSubtaskCheck;
window.deleteSubtask = deleteSubtask;
window.showAddMemberModal = showAddMemberModal;
window.closeAddMemberModal = closeAddMemberModal;
window.searchUsers = searchUsers;
window.selectUser = selectUser;
window.confirmAddMember = confirmAddMember;
window.toggleTaskAssignmentForMember = toggleTaskAssignmentForMember;
window.removeMember = removeMember;
window.renderTeamMembersWithTasks = renderTeamMembersWithTasks;

console.log('✅ Complete Project Modal System Loaded Successfully');
</script>

<style>
.jira-team-assignment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

@media (max-width: 768px) {
    .jira-team-assignment-grid {
        grid-template-columns: 1fr;
    }
}




/* Add to your existing styles */

.jira-client-form {
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

.jira-search-result-item {
    position: relative;
}

.jira-search-result-item:last-child {
    border-bottom: none !important;
}

#inviteClientButton {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>








<style>
    /* ================================================
   JIRA-STYLE PROFESSIONAL MODAL - PREMIUM DESIGN
   ================================================ */

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
        --jira-shadow-sm: 0 1px 1px rgba(9, 30, 66, 0.25);
        --jira-shadow-md: 0 4px 8px rgba(9, 30, 66, 0.15);
        --jira-shadow-lg: 0 8px 16px rgba(9, 30, 66, 0.15), 0 0 1px rgba(9, 30, 66, 0.31);
    }

    /* Modal Overlay */
    .jira-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(9, 30, 66, 0.54);
        backdrop-filter: blur(2px);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        align-content: center;
        justify-content: center
    }

    .jira-section-title i {
        color: var(--jira-primary);
        font-size: 18px;
    }

    .jira-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .jira-section-desc {
        font-size: 14px;
        color: var(--jira-text-subtle);
        margin: 4px 0 0 0;
    }

    .jira-subsection-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 24px 0 12px 0;
    }

    /* Form Grid */
    .jira-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .jira-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .jira-form-group.full-width {
        grid-column: 1 / -1;
    }

    .jira-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .jira-label.required::after {
        content: ' *';
        color: var(--jira-danger);
    }

    .jira-input,
    .jira-select,
    .jira-textarea {
        width: 100%;
        padding: 8px 12px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        font-size: 14px;
        color: var(--jira-text);
        font-family: inherit;
        transition: all 0.15s;
    }

    .jira-input:hover,
    .jira-select:hover,
    .jira-textarea:hover {
        background: var(--jira-card);
    }

    .jira-input:focus,
    .jira-select:focus,
    .jira-textarea:focus {
        outline: none;
        border-color: var(--jira-primary);
        background: var(--jira-card);
    }

    .jira-textarea {
        resize: vertical;
        min-height: 80px;
        line-height: 1.5;
    }

    .jira-hint {
        font-size: 11px;
        color: var(--jira-text-subtle);
    }

    .jira-input-group {
        display: flex;
        width: 100%;
    }

    .jira-input-addon {
        padding: 8px 12px;
        background: var(--jira-hover);
        border: 2px solid var(--jira-border);
        border-right: none;
        border-radius: 3px 0 0 3px;
        font-size: 14px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        white-space: nowrap;
    }

    .jira-input-group .jira-input {
        border-radius: 0 3px 3px 0;
    }

    /* Priority Selector */
    .jira-priority-selector {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    .jira-priority-btn {
        padding: 10px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        font-size: 13px;
        font-weight: 500;
        color: var(--jira-text);
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .jira-priority-btn:hover {
        background: var(--jira-card);
        border-color: var(--jira-primary);
    }

    .jira-priority-btn.active {
        background: var(--jira-primary);
        border-color: var(--jira-primary);
        color: white;
    }

    .jira-priority-btn i {
        font-size: 12px;
    }

    /* Flags Container */
    .jira-flags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .jira-flag-btn {
        padding: 8px 16px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        font-size: 13px;
        font-weight: 500;
        color: var(--jira-text);
        cursor: pointer;
        transition: all 0.15s;
    }

    .jira-flag-btn:hover {
        background: var(--jira-card);
        border-color: var(--jira-primary);
    }

    .jira-flag-btn.active {
        background: #FFF0B3;
        border-color: #FFD700;
        color: #7A5C00;
    }

    /* Stats Cards */
    .jira-tasks-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .jira-stat-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: var(--jira-bg);
        border: 1px solid var(--jira-border);
        border-radius: 3px;
    }

    .jira-stat-icon {
        font-size: 24px;
    }

    .jira-stat-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .jira-stat-label {
        font-size: 11px;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .jira-stat-value {
        font-size: 20px;
        font-weight: 600;
        color: var(--jira-primary);
    }

    /* Tasks List */
    .jira-tasks-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .jira-task-card {
        background: var(--jira-card);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        padding: 16px;
        transition: all 0.15s;
    }

    .jira-task-card:hover {
        border-color: var(--jira-primary);
        box-shadow: var(--jira-shadow-sm);
    }

    .jira-task-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .jira-task-drag {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--jira-text-subtle);
        cursor: grab;
        flex-shrink: 0;
    }

    .jira-task-drag:active {
        cursor: grabbing;
    }

    .jira-task-priority-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.15s;
    }

    .jira-task-priority-icon.low {
        background: #E3FCEF;
        color: #00875A;
    }

    .jira-task-priority-icon.medium {
        background: #FFF4E5;
        color: #FF991F;
    }

    .jira-task-priority-icon.high {
        background: #FFEBE6;
        color: #DE350B;
    }

    .jira-task-priority-icon.urgent {
        background: #FFEBE6;
        color: #BF2600;
    }

    .jira-task-title-input {
        flex: 1;
        font-size: 15px;
        font-weight: 500;
        color: var(--jira-text);
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 4px 0;
        transition: all 0.15s;
    }

    .jira-task-title-input:focus {
        outline: none;
        border-bottom-color: var(--jira-primary);
    }

    .jira-task-actions {
        display: flex;
        gap: 4px;
    }

    .jira-task-action-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-task-action-btn:hover {
        background: var(--jira-hover);
        color: var(--jira-text);
    }

    .jira-task-action-btn.delete:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    .jira-task-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }

    .jira-task-meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .jira-task-meta-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .jira-task-meta-item .jira-input,
    .jira-task-meta-item .jira-select {
        height: 32px;
        font-size: 13px;
    }

    .jira-task-flags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }

    .jira-task-flag {
        padding: 4px 10px;
        background: #FFF0B3;
        border: 1px solid #FFD700;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        color: #7A5C00;
    }

    .jira-task-dependencies {
        margin-bottom: 12px;
    }

    .jira-task-dep-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .jira-task-dep-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
    }

    .jira-add-dep-btn {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--jira-primary);
        border: none;
        border-radius: 3px;
        color: white;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.15s;
    }

    .jira-add-dep-btn:hover {
        background: var(--jira-primary-dark);
    }

    .jira-dependencies-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .jira-dep-tag {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: var(--jira-bg);
        border: 1px solid var(--jira-border);
        border-radius: 3px;
        font-size: 12px;
        color: var(--jira-text);
    }

    .jira-dep-remove {
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 2px;
        font-size: 10px;
        transition: all 0.15s;
    }

    .jira-dep-remove:hover {
        background: var(--jira-danger);
        color: white;
    }

    /* Subtasks */
    .jira-subtasks-section {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--jira-border);
    }

    .jira-subtasks-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .jira-subtasks-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jira-subtask-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 18px;
        padding: 0 6px;
        background: var(--jira-hover);
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        color: var(--jira-text);
    }

    .jira-subtasks-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .jira-subtask-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background: var(--jira-bg);
        border-radius: 3px;
    }

    .jira-subtask-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s;
    }

    .jira-subtask-checkbox:hover {
        border-color: var(--jira-primary);
    }

    .jira-subtask-checkbox.checked {
        background: var(--jira-success);
        border-color: var(--jira-success);
    }

    .jira-subtask-checkbox.checked::before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: white;
        font-size: 10px;
    }

    .jira-subtask-input {
        flex: 1;
        font-size: 13px;
        color: var(--jira-text);
        background: none;
        border: none;
        padding: 4px 0;
    }

    .jira-subtask-input:focus {
        outline: none;
    }

    .jira-subtask-remove {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-subtask-remove:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    /* Team Section */
    .jira-team-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .jira-members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
    }

    .jira-member-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: var(--jira-card);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-member-card:hover {
        border-color: var(--jira-primary);
        box-shadow: var(--jira-shadow-sm);
    }

    .jira-member-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .jira-member-info {
        flex: 1;
        min-width: 0;
    }

    .jira-member-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--jira-text);
        margin-bottom: 2px;
    }

    .jira-member-role {
        font-size: 12px;
        color: var(--jira-text-subtle);
        margin-bottom: 4px;
    }

    .jira-member-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 4px;
    }

    .jira-member-badge {
        padding: 2px 8px;
        background: var(--jira-hover);
        border-radius: 3px;
        font-size: 10px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
    }

    .jira-member-badge.lead {
        background: #E3FCEF;
        color: #00875A;
    }

    .jira-member-remove {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
        flex-shrink: 0;
    }

    .jira-member-remove:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    /* Task Assignments */
    .jira-assignments-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .jira-assignment-card {
        background: var(--jira-card);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        padding: 16px;
    }

    .jira-assignment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .jira-assignment-member {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .jira-assignment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .jira-assignment-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--jira-text);
    }

    .jira-assignment-count {
        padding: 4px 10px;
        background: var(--jira-primary);
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
        color: white;
    }

    .jira-assigned-tasks {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .jira-assigned-task {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--jira-bg);
        border-radius: 3px;
    }

    .jira-assigned-task-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        font-size: 12px;
    }

    .jira-assigned-task-name {
        flex: 1;
        font-size: 13px;
        color: var(--jira-text);
    }

    .jira-unassign-btn {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-unassign-btn:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    /* Client Section */
    .jira-client-card {
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .jira-client-header {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .jira-client-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--jira-primary);
        color: white;
        border-radius: 3px;
        font-size: 24px;
        flex-shrink: 0;
    }

    .jira-client-info {
        flex: 1;
    }

    .jira-client-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 0 0 4px 0;
    }

    .jira-client-desc {
        font-size: 13px;
        color: var(--jira-text-subtle);
        margin: 0;
        line-height: 1.4;
    }

    /* Toggle Switch */
    .jira-toggle {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
        flex-shrink: 0;
    }

    .jira-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .jira-toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: var(--jira-border);
        transition: 0.3s;
        border-radius: 12px;
    }

    .jira-toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .jira-toggle input:checked+.jira-toggle-slider {
        background: var(--jira-primary);
    }

    .jira-toggle input:checked+.jira-toggle-slider:before {
        transform: translateX(24px);
    }

    .jira-client-form {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--jira-border);
    }

    .jira-checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .jira-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }

    .jira-checkbox input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--jira-primary);
    }

    .jira-checkbox span {
        font-size: 13px;
        color: var(--jira-text);
    }

    /* Summary Section */
    .jira-summary-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid var(--jira-border);
    }

    .jira-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .jira-summary-item {
        padding: 12px;
        background: var(--jira-bg);
        border-radius: 3px;
    }

    .jira-summary-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .jira-summary-value {
        font-size: 14px;
        font-weight: 500;
        color: var(--jira-text);
    }

    /* Empty State */
    .jira-empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .jira-empty-state.small {
        padding: 40px 20px;
    }

    .jira-empty-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        background: var(--jira-bg);
        border-radius: 50%;
        font-size: 32px;
        color: var(--jira-text-subtle);
    }

    .jira-empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 0 0 8px 0;
    }

    .jira-empty-text {
        font-size: 14px;
        color: var(--jira-text-subtle);
        margin: 0 0 20px 0;
    }

    /* Modal Footer */
    .jira-modal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-top: 2px solid var(--jira-border);
        background: var(--jira-card);
        flex-shrink: 0;
    }

    .jira-footer-left,
    .jira-footer-right {
        display: flex;
        gap: 8px;
    }

    /* Buttons */
    .jira-btn-primary,
    .jira-btn-secondary,
    .jira-btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 36px;
        padding: 0 16px;
        border: none;
        border-radius: 3px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
        font-family: inherit;
    }

    .jira-btn-primary {
        background: var(--jira-primary);
        color: white;
    }

    .jira-btn-primary:hover {
        background: var(--jira-primary-dark);
    }

    .jira-btn-primary:active {
        background: #0747A6;
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
        color: var(--jira-text);
        background: var(--jira-hover);
    }

    /* Small Modal */
    .jira-small-modal {
        width: 500px;
        max-width: 90vw;
        background: var(--jira-card);
        border-radius: 3px;
        box-shadow: var(--jira-shadow-lg);
        animation: jiraSlideUp 0.3s cubic-bezier(0.15, 1, 0.3, 1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .jira-modal-container {
            height: 100vh;
            margin: 0;
            max-width: 100%;
        }

        .jira-modal {
            border-radius: 0;
        }

        .jira-steps-container {
            overflow-x: auto;
            padding: 12px 16px;
        }

        .jira-step-label {
            display: none;
        }

        .jira-form-grid {
            grid-template-columns: 1fr;
        }

        .jira-priority-selector {
            grid-template-columns: repeat(2, 1fr);
        }

        .jira-tasks-stats {
            grid-template-columns: 1fr;
        }

        .jira-task-meta {
            grid-template-columns: 1fr;
        }

        .jira-members-grid {
            grid-template-columns: 1fr;
        }

        .jira-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .jira-modal-header {
            padding: 16px;
        }

        .jira-modal-body {
            padding: 16px;
        }

        .jira-form-section {
            padding: 16px;
        }

        .jira-priority-selector {
            grid-template-columns: 1fr;
        }
    }












    @keyframes jiraFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Modal Container - Full Height */
    .jira-modal-container {
        width: 100%;
        max-width: 1000px;
        height: 95vh;
        margin: 20px;
        display: flex;
        flex-direction: column;
    }

    .jira-modal {
        background: var(--jira-card);
        border-radius: 3px;
        box-shadow: var(--jira-shadow-lg);
        display: flex;
        flex-direction: column;
        height: 100%;
        animation: jiraSlideUp 0.3s cubic-bezier(0.15, 1, 0.3, 1);
    }

    @keyframes jiraSlideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Header */
    .jira-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 2px solid var(--jira-border);
        flex-shrink: 0;
        background: var(--jira-card);
    }

    .jira-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .jira-back-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-back-btn:hover {
        background: var(--jira-hover);
        color: var(--jira-primary);
    }

    .jira-header-info {
        flex: 1;
    }

    .jira-modal-title {
        font-size: 20px;
        font-weight: 500;
        color: var(--jira-text);
        margin: 0 0 4px 0;
        line-height: 1.2;
    }

    .jira-modal-subtitle {
        font-size: 14px;
        color: var(--jira-text-subtle);
        margin: 0;
    }

    .jira-header-actions {
        display: flex;
        gap: 8px;
    }

    .jira-icon-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--jira-text-subtle);
        cursor: pointer;
        border-radius: 3px;
        transition: all 0.15s;
    }

    .jira-icon-btn:hover {
        background: var(--jira-hover);
        color: var(--jira-text);
    }

    .jira-close-btn:hover {
        background: #FFEBE6;
        color: var(--jira-danger);
    }

    /* Progress Bar */
    .jira-progress-bar {
        height: 4px;
        background: var(--jira-border);
        position: relative;
        overflow: hidden;
    }

    .jira-progress-fill {
        height: 100%;
        background: var(--jira-primary);
        transition: width 0.3s ease;
        width: 25%;
    }

    /* Step Indicators */
    .jira-steps-container {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        background: var(--jira-bg);
        border-bottom: 1px solid var(--jira-border);
        flex-shrink: 0;
    }

    .jira-step-item {
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .jira-step-number {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--jira-card);
        border: 2px solid var(--jira-border);
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        transition: all 0.2s;
    }

    .jira-step-item.active .jira-step-number {
        background: var(--jira-primary);
        border-color: var(--jira-primary);
        color: white;
        transform: scale(1.1);
    }

    .jira-step-item.completed .jira-step-number {
        background: var(--jira-success);
        border-color: var(--jira-success);
        color: white;
    }

    .jira-step-item.completed .jira-step-number::before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
    }

    .jira-step-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--jira-text-subtle);
        white-space: nowrap;
    }

    .jira-step-item.active .jira-step-label {
        color: var(--jira-primary);
        font-weight: 600;
    }

    .jira-step-divider {
        flex: 1;
        height: 2px;
        background: var(--jira-border);
        margin: 0 12px;
        min-width: 30px;
    }

    /* Modal Body */
    .jira-modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background: var(--jira-bg);
    }

    .jira-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .jira-modal-body::-webkit-scrollbar-track {
        background: var(--jira-bg);
    }

    .jira-modal-body::-webkit-scrollbar-thumb {
        background: var(--jira-border);
        border-radius: 4px;
    }

    .jira-modal-body::-webkit-scrollbar-thumb:hover {
        background: #B3BAC5;
    }

    /* Step Content */
    .jira-step-content {
        animation: jiraSlideIn 0.3s ease;
    }

    @keyframes jiraSlideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Form Section */
    .jira-form-section {
        background: var(--jira-card);
        border-radius: 3px;
        padding: 24px;
        box-shadow: var(--jira-shadow-sm);
    }

    .jira-section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 0 0 16px 0;
        display: flex;
    }




    /* Dependencies Modal */
    .jira-dependencies-modal {
        position: fixed;
        inset: 0;
        background: rgba(9, 30, 66, 0.54);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .jira-dependencies-modal.active {
        display: flex;
    }

    .jira-dependencies-content {
        width: 500px;
        max-width: 90vw;
        max-height: 70vh;
        background: var(--jira-card);
        border-radius: 3px;
        box-shadow: var(--jira-shadow-lg);
        display: flex;
        flex-direction: column;
    }

    .jira-dependencies-header {
        padding: 20px;
        border-bottom: 2px solid var(--jira-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .jira-dependencies-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--jira-text);
        margin: 0;
    }

    .jira-dependencies-body {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
    }

    .jira-dependencies-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .jira-dependency-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.15s;
    }

    .jira-dependency-option:hover {
        border-color: var(--jira-primary);
        background: var(--jira-card);
    }

    .jira-dependency-option.selected {
        border-color: var(--jira-primary);
        background: rgba(0, 82, 204, 0.1);
    }

    .jira-dependency-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .jira-dependency-option.selected .jira-dependency-checkbox {
        background: var(--jira-primary);
        border-color: var(--jira-primary);
    }

    .jira-dependency-option.selected .jira-dependency-checkbox::before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: white;
        font-size: 12px;
    }

    .jira-dependency-info {
        flex: 1;
    }

    .jira-dependency-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--jira-text);
        margin-bottom: 4px;
    }

    .jira-dependency-meta {
        font-size: 12px;
        color: var(--jira-text-subtle);
    }

    .jira-dependencies-footer {
        padding: 16px 20px;
        border-top: 2px solid var(--jira-border);
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* Task Notes */
    .jira-task-notes {
        margin-bottom: 12px;
    }

    .jira-task-notes-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--jira-text-subtle);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .jira-task-notes-textarea {
        width: 100%;
        min-height: 80px;
        padding: 10px 12px;
        background: var(--jira-bg);
        border: 2px solid var(--jira-border);
        border-radius: 3px;
        font-size: 13px;
        color: var(--jira-text);
        font-family: inherit;
        resize: vertical;
        transition: all 0.15s;
    }

    .jira-task-notes-textarea:hover {
        background: var(--jira-card);
    }

    .jira-task-notes-textarea:focus {
        outline: none;
        border-color: var(--jira-primary);
        background: var(--jira-card);
    }

    .jira-task-notes-textarea::placeholder {
        color: var(--jira-text-subtle);
    }
</style>


<script>
    // ======================================================
    // ENHANCED TEAM MANAGEMENT WITH LIVE SEARCH
    // ======================================================

    let searchTimeout = null;
    let selectedUser = null;

    // Search users with debounce
    function searchUsers(query) {
        clearTimeout(searchTimeout);

        const resultsContainer = document.getElementById('memberSearchResults');
        const loadingIndicator = document.getElementById('memberSearchLoading');
        const confirmBtn = document.getElementById('confirmAddMemberBtn');

        if (query.trim().length < 2) {
            resultsContainer.style.display = 'none';
            resultsContainer.innerHTML = '';
            confirmBtn.disabled = true;
            return;
        }

        loadingIndicator.style.display = 'block';

        searchTimeout = setTimeout(() => {
            const username = '{{ $username }}';
            const url = `/{{ $username }}/manage/projects/search-users?query=${encodeURIComponent(query)}`;

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin' // <-- THIS is the important part
                })
                .then(response => {
                    if (!response.ok) {
                        // helpful for debugging if you're still getting redirected or 500s
                        console.error('Bad response status:', response.status);
                    }
                    return response.json();
                })
                .then(users => {
                    loadingIndicator.style.display = 'none';
                    displaySearchResults(users);
                })
                .catch(error => {
                    loadingIndicator.style.display = 'none';
                    console.error('Search error:', error);
                    showNotification('Failed to search users', 'error');
                });

            .then(response => response.json())
                .then(users => {
                    loadingIndicator.style.display = 'none';
                    displaySearchResults(users);
                })
                .catch(error => {
                    loadingIndicator.style.display = 'none';
                    console.error('Search error:', error);
                    showNotification('Failed to search users', 'error');
                });
        }, 300);
    }

    // Display search results
    function displaySearchResults(users) {
        const resultsContainer = document.getElementById('memberSearchResults');

        if (users.length === 0) {
            resultsContainer.innerHTML = `
            <div style="padding: 16px; text-align: center; color: var(--jira-text-subtle);">
                <i class="fas fa-user-slash" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 13px;">No users found</p>
            </div>
        `;
            resultsContainer.style.display = 'block';
            return;
        }

        resultsContainer.innerHTML = users.map(user => `
        <div class="jira-search-result-item" 
             onclick="selectUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', '${escapeHtml(user.avatar)}')"
             style="display: flex; align-items: center; gap: 12px; padding: 12px; cursor: pointer; transition: all 0.15s; border-bottom: 1px solid var(--jira-border);"
             onmouseover="this.style.background='var(--jira-hover)'"
             onmouseout="this.style.background='transparent'">
            <img src="${user.avatar}" 
                 alt="${escapeHtml(user.name)}" 
                 style="width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;">
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${escapeHtml(user.name)}</div>
                <div style="font-size: 12px; color: var(--jira-text-subtle); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${escapeHtml(user.email)}</div>
            </div>
            <i class="fas fa-check-circle" style="color: var(--jira-success); display: none;"></i>
        </div>
    `).join('');

        resultsContainer.style.display = 'block';
    }

    // Select a user from search results
    function selectUser(id, name, email, avatar) {
        selectedUser = {
            id,
            name,
            email,
            avatar
        };

        // Hide search results
        document.getElementById('memberSearchResults').style.display = 'none';
        document.getElementById('memberSearch').value = name;

        // Show selected user preview
        const previewContainer = document.getElementById('selectedMemberPreview');
        const cardContainer = document.getElementById('selectedMemberCard');

        cardContainer.innerHTML = `
        <img src="${avatar}" alt="${name}" style="width: 40px; height: 40px; border-radius: 50%;">
        <div style="flex: 1;">
            <div style="font-size: 14px; font-weight: 600; color: var(--jira-text);">${name}</div>
            <div style="font-size: 12px; color: var(--jira-text-subtle);">${email}</div>
        </div>
        <i class="fas fa-check-circle" style="color: var(--jira-success); font-size: 20px;"></i>
    `;

        previewContainer.style.display = 'block';

        // Enable confirm button
        document.getElementById('confirmAddMemberBtn').disabled = false;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Enhanced confirmAddMember with real user_id
    function confirmAddMember() {
        if (!selectedUser) {
            showNotification('Please select a user', 'error');
            return;
        }

        const role = document.getElementById('memberRole').value;
        const techStack = document.getElementById('memberTechStack').value;
        const position = document.getElementById('memberPosition').value;

        // Check if already added
        if (teamMembers.some(m => m.user_id === selectedUser.id)) {
            showNotification('This user is already in the team', 'error');
            return;
        }

        const member = {
            id: Date.now(), // local temp id for UI
            user_id: selectedUser.id, // REAL database user ID
            name: selectedUser.name,
            email: selectedUser.email,
            avatar: selectedUser.avatar,
            role: role,
            techStack: techStack,
            position: position,
            assignedTasks: []
        };

        teamMembers.push(member);
        renderTeamMembersWithTasks();
        closeAddMemberModal();
        showNotification('✅ Team member added successfully!', 'success');
    }

    // Close add member modal and reset
    function closeAddMemberModal() {
        document.getElementById('addMemberModal').style.display = 'none';
        document.getElementById('memberSearch').value = '';
        document.getElementById('memberSearchResults').style.display = 'none';
        document.getElementById('memberSearchResults').innerHTML = '';
        document.getElementById('selectedMemberPreview').style.display = 'none';
        document.getElementById('confirmAddMemberBtn').disabled = true;
        selectedUser = null;
    }

    // ======================================================
    // RENDER TEAM WITH TASK ASSIGNMENT
    // ======================================================

    function prepareTeamAssignmentStep() {
        saveCurrentStepData(); // Update projectData.tasks
        renderTeamMembersWithTasks();
    }

    function renderTeamMembersWithTasks() {
        const emptyState = document.getElementById('emptyTeamState');
        const container = document.getElementById('teamMembersWithTasks');

        if (teamMembers.length === 0) {
            emptyState.style.display = 'flex';
            container.style.display = 'none';
            return;
        }

        emptyState.style.display = 'none';
        container.style.display = 'grid';

        if (projectData.tasks.length === 0) {
            container.innerHTML = `
            <div class="jira-empty-state">
                <div class="jira-empty-icon"><i class="fas fa-tasks"></i></div>
                <h4 class="jira-empty-title">No tasks to assign</h4>
                <p class="jira-empty-text">Please add tasks in Step 2 first</p>
            </div>
        `;
            return;
        }

        const roleIcons = {
            developer: '💻',
            designer: '🎨',
            manager: '📊',
            qa: '🧪',
            lead: '👨‍💼'
        };

        const priorityIcons = {
            low: 'fa-arrow-down',
            medium: 'fa-minus',
            high: 'fa-arrow-up',
            urgent: 'fa-exclamation'
        };

        container.innerHTML = teamMembers.map(member => {
            const assignedTasks = member.assignedTasks || [];

            return `
            <div class="jira-team-member-assignment-card" style="background: var(--jira-card); border: 2px solid var(--jira-border); border-radius: 3px; padding: 20px;">
                <!-- Member Header -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid var(--jira-border);">
                    <img src="${member.avatar}" alt="${member.name}" style="width: 48px; height: 48px; border-radius: 50%;">
                    <div style="flex: 1;">
                        <div style="font-size: 16px; font-weight: 600; color: var(--jira-text); margin-bottom: 4px;">
                            ${member.name}
                        </div>
                        <div style="font-size: 12px; color: var(--jira-text-subtle);">
                            ${roleIcons[member.role] || '👤'} ${member.role} · ${member.techStack}
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="padding: 6px 12px; background: ${assignedTasks.length > 0 ? 'var(--jira-primary)' : 'var(--jira-hover)'}; color: ${assignedTasks.length > 0 ? 'white' : 'var(--jira-text-subtle)'}; border-radius: 3px; font-size: 12px; font-weight: 600;">
                            ${assignedTasks.length} ${assignedTasks.length === 1 ? 'task' : 'tasks'}
                        </div>
                        <button type="button" 
                                onclick="removeMember(${member.id})" 
                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: none; border: none; color: var(--jira-text-subtle); cursor: pointer; border-radius: 3px; transition: all 0.15s;"
                                onmouseover="this.style.background='#FFEBE6'; this.style.color='var(--jira-danger)'"
                                onmouseout="this.style.background='none'; this.style.color='var(--jira-text-subtle)'">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Task Assignment Section -->
                <div>
                    <div style="font-size: 12px; font-weight: 600; color: var(--jira-text-subtle); text-transform: uppercase; margin-bottom: 12px;">
                        Available Tasks
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        ${projectData.tasks.map((task, taskIndex) => {
                            const isAssigned = assignedTasks.includes(taskIndex);
                            const priorityClass = task.priority || 'medium';
                            
                            return `
                                <div onclick="toggleTaskAssignmentForMember(${member.id}, ${taskIndex})"
                                     style="display: flex; align-items: center; gap: 12px; padding: 12px; background: ${isAssigned ? 'rgba(0, 82, 204, 0.1)' : 'var(--jira-bg)'}; border: 2px solid ${isAssigned ? 'var(--jira-primary)' : 'var(--jira-border)'}; border-radius: 3px; cursor: pointer; transition: all 0.15s;"
                                     onmouseover="if(!${isAssigned}) this.style.borderColor='var(--jira-primary)'"
                                     onmouseout="if(!${isAssigned}) this.style.borderColor='var(--jira-border)'">
                                    
                                    <!-- Priority Icon -->
                                    <div class="jira-task-priority-icon ${priorityClass}" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 3px; flex-shrink: 0;">
                                        <i class="fas ${priorityIcons[priorityClass]}"></i>
                                    </div>
                                    
                                    <!-- Task Name -->
                                    <div style="flex: 1; font-size: 14px; font-weight: 500; color: var(--jira-text);">
                                        ${task.name}
                                    </div>
                                    
                                    <!-- Assignment Checkbox -->
                                    <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: ${isAssigned ? 'var(--jira-success)' : 'var(--jira-card)'}; border: 2px solid ${isAssigned ? 'var(--jira-success)' : 'var(--jira-border)'}; border-radius: 3px; flex-shrink: 0;">
                                        ${isAssigned ? '<i class="fas fa-check" style="color: white; font-size: 12px;"></i>' : ''}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
                
                ${assignedTasks.length === 0 ? `
                    <div style="text-align: center; padding: 20px; color: var(--jira-text-subtle); font-size: 13px; margin-top: 16px; background: var(--jira-bg); border-radius: 3px;">
                        <i class="fas fa-hand-point-up" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        Click on tasks above to assign them to ${member.name.split(' ')[0]}
                    </div>
                ` : ''}
            </div>
        `;
        }).join('');
    }

    // Toggle task assignment
    function toggleTaskAssignmentForMember(memberId, taskIndex) {
        const member = teamMembers.find(m => m.id === memberId);
        if (!member) return;

        if (!member.assignedTasks) member.assignedTasks = [];

        const index = member.assignedTasks.indexOf(taskIndex);
        if (index > -1) {
            member.assignedTasks.splice(index, 1);
            showNotification('Task unassigned', 'info');
        } else {
            member.assignedTasks.push(taskIndex);
            showNotification('Task assigned!', 'success');
        }

        renderTeamMembersWithTasks();
    }

    // Remove member
    function removeMember(memberId) {
        if (!confirm('Remove this team member? All task assignments will be lost.')) return;

        teamMembers = teamMembers.filter(m => m.id !== memberId);
        renderTeamMembersWithTasks();
        showNotification('Member removed', 'info');
    }

    // Update window functions
    window.searchUsers = searchUsers;
    window.selectUser = selectUser;
    window.confirmAddMember = confirmAddMember;
    window.closeAddMemberModal = closeAddMemberModal;
    window.toggleTaskAssignmentForMember = toggleTaskAssignmentForMember;
    window.removeMember = removeMember;
    window.renderTeamMembersWithTasks = renderTeamMembersWithTasks;
</script>

<style>
    .jira-team-assignment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 20px;
    }

    @media (max-width: 768px) {
        .jira-team-assignment-grid {
            grid-template-columns: 1fr;
        }
    }

    .jira-search-input-wrapper {
        position: relative;
    }
</style>

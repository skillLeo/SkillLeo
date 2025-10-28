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
</style>{{-- resources/views/tenant/projects/modals/create-project.blade.php --}}

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
                <div class="jira-step-divider"></div>
                <div class="jira-step-item" data-step="5">
                    <div class="jira-step-number">5</div>
                    <span class="jira-step-label">Files & Notes</span>
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
                                    <p class="jira-client-desc">Add a client to track this as a paid order with
                                        progress visibility</p>
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
                                    <input type="text" class="jira-input" id="clientSearch"
                                        placeholder="Type name or email to search existing client..."
                                        autocomplete="off" oninput="searchClients(this.value)">
                                    <div id="clientSearchLoading"
                                        style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>

                                <!-- Search Results -->
                                <div id="clientSearchResults"
                                    style="display: none; margin-top: 8px; max-height: 200px; overflow-y: auto; border: 2px solid var(--jira-border); border-radius: 3px; background: var(--jira-card);">
                                </div>

                                <!-- Invite New Client Button -->
                                <div id="inviteClientButton" style="display: none; margin-top: 12px;">
                                    <button type="button" class="jira-btn-secondary"
                                        onclick="showInviteClientModal()">
                                        <i class="fas fa-envelope"></i>
                                        Invite as New Client
                                    </button>
                                </div>
                            </div>

                            <!-- Selected Client Preview -->
                            <div id="selectedClientPreview" style="display: none; margin-bottom: 20px;">
                                <label class="jira-label">Selected Client</label>
                                <div id="selectedClientCard"
                                    style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--jira-bg); border-radius: 3px; margin-top: 6px;">
                                    <!-- Populated by JavaScript -->
                                </div>
                                <input type="hidden" id="selectedClientUserId" name="client_user_id">
                            </div>

                            <div class="jira-form-grid">
                                <div class="jira-form-group">
                                    <label class="jira-label">Company</label>
                                    <input type="text" class="jira-input" id="clientCompany"
                                        name="client_company" placeholder="Acme Corp">
                                </div>

                                <div class="jira-form-group">
                                    <label class="jira-label">Phone</label>
                                    <input type="tel" class="jira-input" id="clientPhone" name="client_phone"
                                        placeholder="+92 300 1234567">
                                </div>

                                <div class="jira-form-group">
                                    <label class="jira-label">Order Value</label>
                                    <div class="jira-input-group">
                                        <span class="jira-input-addon" id="orderCurrency">PKR</span>
                                        <input type="number" class="jira-input" id="orderValue" name="order_value"
                                            placeholder="0" min="0">
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
                                    <textarea class="jira-textarea" id="specialRequirements" name="special_requirements" rows="3"
                                        placeholder="Any special requirements or notes..."></textarea>
                                </div>

                                <div class="jira-form-group full-width">
                                    <label class="jira-label">Client Portal Access</label>
                                    <div class="jira-checkbox-group">
                                        <label class="jira-checkbox">
                                            <input type="checkbox" id="clientPortalAccess" name="portal_access"
                                                checked>
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
                                Send an invitation to create an account. They'll receive an email with a registration
                                link.
                            </p>

                            <div class="jira-form-group">
                                <label class="jira-label required">Client Email</label>
                                <input type="email" class="jira-input" id="inviteClientEmail"
                                    placeholder="client@example.com">
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label required">Client Name</label>
                                <input type="text" class="jira-input" id="inviteClientName"
                                    placeholder="John Doe">
                            </div>

                            <div class="jira-form-group">
                                <label class="jira-label">Personal Message (Optional)</label>
                                <textarea class="jira-textarea" id="inviteClientMessage" rows="3"
                                    placeholder="Add a personal message to the invitation email..."></textarea>
                            </div>
                        </div>
                        <div class="jira-modal-footer">
                            <button class="jira-btn-ghost" type="button"
                                onclick="closeInviteClientModal()">Cancel</button>
                            <button class="jira-btn-primary" type="button" onclick="sendClientInvitation()">
                                <i class="fas fa-paper-plane"></i>
                                Send Invitation
                            </button>
                        </div>
                    </div>
                </div>
                <!-- STEP 5: Files & Notes -->
                <div class="jira-step-content" id="step-5" style="display: none;">
                    <div class="jira-form-section">
                        <div class="jira-section-header">
                            <div>
                                <h3 class="jira-section-title">
                                    <i class="fas fa-paperclip"></i>
                                    Project Files & Notes
                                </h3>
                                <p class="jira-section-desc">
                                    Upload any reference files and leave kickoff notes / next steps
                                </p>
                            </div>
                        </div>

                        <!-- FILE UPLOAD AREA -->
                        <div class="jira-form-group full-width">
                            <label class="jira-label">Upload Media / Assets</label>
                            <p class="jira-hint">Images, videos, PDFs, docs… (we'll attach them to this project)</p>

                            <input type="file" id="projectFilesInput" multiple class="jira-input"
                                style="padding:12px;background:var(--jira-card);border-style:dashed;cursor:pointer;"
                                onchange="handleProjectFilesSelected(this.files)">

                            <div id="selectedFilesList"
                                style="margin-top:16px; display:flex; flex-direction:column; gap:12px;"></div>
                        </div>

                        <!-- PROJECT NOTE -->
                        <div class="jira-form-group full-width" style="margin-top:24px;">
                            <label class="jira-label">Project Notes / Kickoff Context</label>
                            <textarea class="jira-textarea" id="projectInitialNoteBody" rows="4"
                                placeholder="Example: Scope confirmed with client. Waiting on brand assets. First milestone is homepage UI by Friday."></textarea>

                            <div style="margin-top:8px; display:flex; align-items:center; gap:12px;">
                                <label class="jira-checkbox" style="font-size:13px;">
                                    <input type="checkbox" id="projectInitialNoteIsInternal" checked>
                                    <span>Internal only (don’t show client)</span>
                                </label>

                                <label class="jira-checkbox" style="font-size:13px;">
                                    <input type="checkbox" id="projectInitialNotePinned">
                                    <span>Pin this note</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>




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
    // ============================================================================
    // Project Creation Modal - Full Working JS (no missing refs)
    // This script expects to run inside your Blade view where $username and
    // csrf_token() are available.
    // ============================================================================
    
    /* ============================================================================
       GLOBAL STATE
       ============================================================================ */
    let currentStep = 1;
    const totalSteps = 5;
    
    let taskCounter = 0; // used to generate unique task cards
    let teamMembers = []; // [{id,user_id,name,email,avatar,role,techStack,position,assignedTasks:number[]}]
    let selectedUser = null; // temp selection when adding team member
    let userSearchTimeout = null; // debounce for user search
    
    let selectedClient = null; // {user_id,name,email,avatar}
    let clientSearchTimeout = null; // debounce for client search
    
    let projectData = {
        basic: {},     // {name,key,type,...}
        tasks: [],     // [{...}]
        flags: [],     // ['urgent', ...]
        notes: [],     // [{body,is_internal,pinned}]
        media: [],     // [{file,visibility,note}]
        client: null   // {client_user_id,...}
    };
    
    // ============================================================================
    // UTILS / HELPERS
    // ============================================================================
    
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
            color: #fff;
            border-radius: 3px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 14px;
            font-weight: 500;
        `;
        el.textContent = message;
        document.body.appendChild(el);
    
        setTimeout(() => {
            el.remove();
        }, 2800);
    }
    
    function escapeHtml(txt) {
        const div = document.createElement('div');
        div.textContent = txt ?? '';
        return div.innerHTML;
    }
    
    function scrollToTop() {
        const modalBody = document.querySelector('.jira-modal-body');
        if (modalBody) modalBody.scrollTop = 0;
    }
    
    function setDefaultDates() {
        const today = new Date().toISOString().split('T')[0];
        const start = document.getElementById('projectStartDate');
        const due = document.getElementById('projectDueDate');
    
        if (start) {
            start.min = today;
            if (!start.value) start.value = today;
        }
        if (due) {
            due.min = today;
        }
    }
    
    // ============================================================================
    // MODAL OPEN / CLOSE
    // ============================================================================
    
    function openCreateProjectModal() {
        const modal = document.getElementById('createProjectModal');
        if (!modal) return;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        initializeModal();
    }
    
    function closeCreateProjectModal() {
        if (!confirm('Are you sure? All unsaved data will be lost.')) return;
        const modal = document.getElementById('createProjectModal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    function initializeModal() {
        currentStep = 1;
        taskCounter = 0;
        teamMembers = [];
        selectedUser = null;
        selectedClient = null;
        projectData = {
            basic: {},
            tasks: [],
            flags: [],
            notes: [],
            media: [],
            client: null
        };
    
        // wipe UI areas that depend on state
        const tasksContainer = document.getElementById('tasksContainer');
        if (tasksContainer) {
            tasksContainer.innerHTML = `
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
            `;
        }
    
        const teamContainer = document.getElementById('teamMembersWithTasks');
        if (teamContainer) teamContainer.innerHTML = '';
    
        const emptyTeamState = document.getElementById('emptyTeamState');
        if (emptyTeamState) emptyTeamState.style.display = 'flex';
    
        const filesList = document.getElementById('selectedFilesList');
        if (filesList) filesList.innerHTML = '';
    
        const clientFormSection = document.getElementById('clientFormSection');
        if (clientFormSection) clientFormSection.style.display = 'none';
        const hasClientToggle = document.getElementById('hasClientToggle');
        if (hasClientToggle) hasClientToggle.checked = false;
        clearSelectedClientUI();
    
        updateStats();
        setDefaultDates();
        updateStepDisplay();
    }
    
    // ============================================================================
    // STEP / NAVIGATION
    // ============================================================================
    
    function updateStepDisplay() {
        // show/hide step panels
        document.querySelectorAll('.jira-step-content').forEach(step => {
            step.style.display = 'none';
        });
        const active = document.getElementById(`step-${currentStep}`);
        if (active) active.style.display = 'block';
    
        // step bar state
        document.querySelectorAll('.jira-step-item').forEach((item, index) => {
            const stepNum = index + 1;
            item.classList.remove('active', 'completed');
            if (stepNum < currentStep) {
                item.classList.add('completed');
            } else if (stepNum === currentStep) {
                item.classList.add('active');
            }
        });
    
        // progress bar
        const progress = (currentStep / totalSteps) * 100;
        const fill = document.getElementById('progressFill');
        if (fill) fill.style.width = progress + '%';
    
        // nav buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const nextBtnText = document.getElementById('nextBtnText');
        const backBtn = document.getElementById('modalBackBtn');
    
        if (currentStep === 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (backBtn) backBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (backBtn) backBtn.style.display = 'flex';
        }
    
        if (currentStep === totalSteps) {
            if (nextBtnText) nextBtnText.textContent = 'Create Project';
            if (nextBtn) {
                const icon = nextBtn.querySelector('i');
                if (icon) icon.className = 'fas fa-check';
            }
        } else {
            if (nextBtnText) nextBtnText.textContent = 'Continue';
            if (nextBtn) {
                const icon = nextBtn.querySelector('i');
                if (icon) icon.className = 'fas fa-arrow-right';
            }
        }
    
        // header titles
        const titles = [
            { title: 'Create Project',          subtitle: 'Set up your project in a few steps' },
            { title: 'Add Tasks & Subtasks',    subtitle: 'Break down your project into manageable pieces' },
            { title: 'Assign Team Members',     subtitle: 'Add collaborators and assign tasks' },
            { title: 'Client & Review',         subtitle: 'Attach a client and confirm scope' },
            { title: 'Files & Notes',           subtitle: 'Upload assets and write kickoff notes' }
        ];
    
        const hdrTitle = document.getElementById('modalTitle');
        const hdrSub   = document.getElementById('modalSubtitle');
    
        if (hdrTitle) hdrTitle.textContent = titles[currentStep - 1].title;
        if (hdrSub)   hdrSub.textContent   = titles[currentStep - 1].subtitle;
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
    
    function validateCurrentStep() {
        if (currentStep === 1) {
            const name = document.getElementById('projectName')?.value.trim() || '';
            const key = document.getElementById('projectKey')?.value.trim() || '';
            const type = document.getElementById('projectType')?.value || '';
            const startDate = document.getElementById('projectStartDate')?.value || '';
            const dueDate   = document.getElementById('projectDueDate')?.value || '';
    
            if (!name) {
                showNotification('Please enter project name', 'error');
                return false;
            }
            if (key.length < 2 || key.length > 10) {
                showNotification('Project key must be 2–10 characters', 'error');
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
            const cards = document.querySelectorAll('.jira-task-card');
            for (const card of cards) {
                const titleInput = card.querySelector('.jira-task-title-input');
                if (!titleInput || !titleInput.value.trim()) {
                    showNotification('All tasks must have a title', 'error');
                    return false;
                }
            }
        }
    
        return true;
    }
    
    function nextStep() {
        if (!validateCurrentStep()) return;
    
        saveCurrentStepData();
    
        if (currentStep < totalSteps) {
            currentStep++;
    
            // Step-specific prep
            if (currentStep === 3) {
                // Team assignment view needs projectData.tasks
                renderTeamMembersWithTasks();
            }
    
            if (currentStep === 4) {
                // Review summary
                prepareReviewStep();
            }
    
            if (currentStep === 5) {
                // Ensure notes + files are represented in state if step5 opens directly
                saveCurrentStepData();
            }
    
            updateStepDisplay();
            scrollToTop();
        } else {
            // last step -> submit
            submitProject();
        }
    }
    
    // ============================================================================
    // CAPTURE DATA FROM FORM INTO projectData
    // ============================================================================
    
    function collectTasksData() {
        const tasks = [];
    
        document.querySelectorAll('.jira-task-card').forEach(card => {
            const tTitleInput   = card.querySelector('.jira-task-title-input');
            const tNotesArea    = card.querySelector('.jira-task-notes-textarea');
            const tDueDateInput = card.querySelector('.jira-task-meta input[type="date"]');
            const tHoursInput   = card.querySelector('.jira-task-meta input[type="number"]');
            const tStorySelect  = card.querySelector('.jira-task-meta select');
            const tPriority     = card.querySelector('.task-priority-input');
    
            const taskObj = {
                name:            tTitleInput ? tTitleInput.value.trim() : '',
                notes:           tNotesArea  ? tNotesArea.value.trim()  : '',
                priority:        tPriority   ? tPriority.value          : 'medium',
                dueDate:         tDueDateInput ? tDueDateInput.value    : '',
                estimatedHours:  tHoursInput   ? tHoursInput.value      : '',
                storyPoints:     tStorySelect  ? tStorySelect.value     : '0',
                subtasks: []
            };
    
            // subtasks
            card.querySelectorAll('.jira-subtask-item').forEach(st => {
                const stTitleInput = st.querySelector('.jira-subtask-input');
                const stCompleted  = st.querySelector('.subtask-completed-input');
                taskObj.subtasks.push({
                    name: stTitleInput ? stTitleInput.value.trim() : '',
                    completed: stCompleted ? (stCompleted.value === '1') : false
                });
            });
    
            tasks.push(taskObj);
        });
    
        return tasks;
    }
    
    function saveCurrentStepData() {
        // BASIC INFO
        projectData.basic = {
            name:           document.getElementById('projectName')?.value.trim() || '',
            key:            (document.getElementById('projectKey')?.value.trim() || '').toUpperCase(),
            type:           document.getElementById('projectType')?.value || '',
            category:       document.getElementById('projectCategory')?.value || '',
            priority:       document.getElementById('projectPriority')?.value || 'medium',
            description:    document.getElementById('projectDescription')?.value.trim() || '',
            startDate:      document.getElementById('projectStartDate')?.value || '',
            dueDate:        document.getElementById('projectDueDate')?.value || '',
            budget:         document.getElementById('projectBudget')?.value || '',
            currency:       document.getElementById('projectCurrency')?.value || 'PKR',
            estimatedHours: document.getElementById('estimatedHours')?.value || ''
        };
    
        // TASKS
        projectData.tasks = collectTasksData();
    
        // NOTES (kickoff note in step5)
        const initialNoteBody = document.getElementById('projectInitialNoteBody')?.value.trim() || '';
        if (initialNoteBody !== '') {
            projectData.notes = [{
                body:        initialNoteBody,
                is_internal: document.getElementById('projectInitialNoteIsInternal')?.checked ?? true,
                pinned:      document.getElementById('projectInitialNotePinned')?.checked ?? false
            }];
        } else {
            projectData.notes = [];
        }
    
        // CLIENT
        const hasClient = document.getElementById('hasClientToggle')?.checked || false;
        if (hasClient && selectedClient && selectedClient.user_id) {
            projectData.client = {
                client_user_id:      selectedClient.user_id,
                client_company:      document.getElementById('clientCompany')?.value || '',
                client_phone:        document.getElementById('clientPhone')?.value || '',
                order_value:         document.getElementById('orderValue')?.value || '',
                currency:            projectData.basic.currency || 'PKR',
                payment_terms:       document.getElementById('paymentTerms')?.value || '',
                special_requirements:document.getElementById('specialRequirements')?.value || '',
                portal_access:       document.getElementById('clientPortalAccess')?.checked ? '1' : '0',
                can_comment:         document.getElementById('clientCanComment')?.checked ? '1' : '0'
            };
        } else {
            projectData.client = null;
        }
    
        // MEDIA stays in projectData.media (updated in handleProjectFilesSelected / updateMediaMeta)
        // FLAGS already tracked live in projectData.flags
    }
    
    // ============================================================================
    // PRIORITY + FLAGS
    // ============================================================================
    
    function selectPriority(priority) {
        document.querySelectorAll('.jira-priority-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.priority === priority);
        });
        const input = document.getElementById('projectPriority');
        if (input) input.value = priority;
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
    
    // ============================================================================
    // TASKS / SUBTASKS UI
    // ============================================================================
    
    function addTask() {
        taskCounter++;
    
        const container = document.getElementById('tasksContainer');
        if (!container) return;
    
        // remove "empty" state if present
        const emptyState = container.querySelector('.jira-empty-state');
        if (emptyState) emptyState.remove();
    
        const card = document.createElement('div');
        card.className = 'jira-task-card';
        card.dataset.taskId = taskCounter;
    
        card.innerHTML = `
            <div class="jira-task-header">
                <div class="jira-task-drag" title="Drag to reorder">
                    <i class="fas fa-grip-vertical"></i>
                </div>
    
                <div class="jira-task-priority-icon medium" onclick="cycleTaskPriority(this)">
                    <i class="fas fa-minus"></i>
                </div>
    
                <input type="text"
                    class="jira-task-title-input"
                    placeholder="Task name..."
                    value="Task ${taskCounter}"
                    required>
    
                <div class="jira-task-actions">
                    <button type="button"
                        class="jira-task-action-btn delete"
                        onclick="deleteTask(this)"
                        title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
    
            <div class="jira-task-notes">
                <label class="jira-task-notes-label">Notes (Optional)</label>
                <textarea class="jira-task-notes-textarea"
                    placeholder="Add detailed notes, requirements, or context for this task..."
                    rows="3"></textarea>
            </div>
    
            <div class="jira-task-meta">
                <div class="jira-task-meta-item">
                    <label class="jira-task-meta-label">Due Date</label>
                    <input type="date" class="jira-input">
                </div>
                <div class="jira-task-meta-item">
                    <label class="jira-task-meta-label">Est. Hours</label>
                    <input type="number"
                        class="jira-input"
                        placeholder="0"
                        min="0"
                        step="0.5"
                        onchange="updateStats()">
                </div>
                <div class="jira-task-meta-item">
                    <label class="jira-task-meta-label">Story Points</label>
                    <select class="jira-select">
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
    
            <input type="hidden"
                class="task-priority-input"
                value="medium">
    
            <div class="jira-subtasks-section">
                <div class="jira-subtasks-header">
                    <span class="jira-subtasks-title">
                        Subtasks
                        <span class="jira-subtask-count">0</span>
                    </span>
                    <button type="button"
                        class="jira-btn-ghost"
                        style="height:28px;padding:0 12px;font-size:13px;"
                        onclick="addSubtask(this)">
                        <i class="fas fa-plus"></i>
                        Add Subtask
                    </button>
                </div>
                <div class="jira-subtasks-list"></div>
            </div>
        `;
    
        container.appendChild(card);
        updateStats();
    }
    
    function cycleTaskPriority(iconEl) {
        const states = ['low', 'medium', 'high', 'urgent'];
        const icons = {
            low: 'fa-arrow-down',
            medium: 'fa-minus',
            high: 'fa-arrow-up',
            urgent: 'fa-exclamation'
        };
    
        let current = 'medium';
        for (const s of states) {
            if (iconEl.classList.contains(s)) {
                current = s;
                break;
            }
        }
    
        const next = states[(states.indexOf(current) + 1) % states.length];
    
        states.forEach(s => iconEl.classList.remove(s));
        iconEl.classList.add(next);
    
        const iTag = iconEl.querySelector('i');
        if (iTag) iTag.className = `fas ${icons[next]}`;
    
        const taskCard = iconEl.closest('.jira-task-card');
        if (!taskCard) return;
        const hiddenInput = taskCard.querySelector('.task-priority-input');
        if (hiddenInput) hiddenInput.value = next;
    }
    
    function deleteTask(btn) {
        if (!confirm('Delete this task and all subtasks?')) return;
    
        const card = btn.closest('.jira-task-card');
        if (card) card.remove();
    
        const container = document.getElementById('tasksContainer');
        if (container && !container.querySelector('.jira-task-card')) {
            container.innerHTML = `
                <div class="jira-empty-state">
                    <div class="jira-empty-icon"><i class="fas fa-tasks"></i></div>
                    <h4 class="jira-empty-title">No tasks yet</h4>
                    <p class="jira-empty-text">Add your first task to get started</p>
                    <button class="jira-btn-primary" onclick="addTask()">
                        <i class="fas fa-plus"></i>
                        Add First Task
                    </button>
                </div>
            `;
        }
    
        updateStats();
    }
    
    function addSubtask(btn) {
        const taskCard = btn.closest('.jira-task-card');
        if (!taskCard) return;
    
        const list = taskCard.querySelector('.jira-subtasks-list');
        if (!list) return;
    
        const idx = list.querySelectorAll('.jira-subtask-item').length;
    
        const subEl = document.createElement('div');
        subEl.className = 'jira-subtask-item';
    
        subEl.innerHTML = `
            <div class="jira-subtask-checkbox" onclick="toggleSubtaskCheck(this)"></div>
            <input type="text"
                class="jira-subtask-input"
                placeholder="Subtask name..."
                value="Subtask ${idx + 1}"
                required>
            <input type="hidden"
                class="subtask-completed-input"
                value="0">
            <button type="button"
                class="jira-subtask-remove"
                onclick="deleteSubtask(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
    
        list.appendChild(subEl);
        updateSubtaskCount(taskCard);
        updateStats();
    }
    
    function toggleSubtaskCheck(box) {
        box.classList.toggle('checked');
        const item = box.closest('.jira-subtask-item');
        if (!item) return;
        const hiddenInput = item.querySelector('.subtask-completed-input');
        if (hiddenInput) hiddenInput.value = box.classList.contains('checked') ? '1' : '0';
    }
    
    function deleteSubtask(btn) {
        const taskCard = btn.closest('.jira-task-card');
        const item = btn.closest('.jira-subtask-item');
        if (item) item.remove();
        if (taskCard) updateSubtaskCount(taskCard);
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
        document.querySelectorAll('.jira-task-meta input[type="number"]').forEach(inp => {
            totalHours += parseFloat(inp.value) || 0;
        });
    
        const totalTasksEl = document.getElementById('totalTasks');
        const totalSubtasksEl = document.getElementById('totalSubtasks');
        const totalHoursEl = document.getElementById('totalHours');
    
        if (totalTasksEl) totalTasksEl.textContent = totalTasks.toString();
        if (totalSubtasksEl) totalSubtasksEl.textContent = totalSubtasks.toString();
        if (totalHoursEl) totalHoursEl.textContent = totalHours + 'h';
    }
    
    // ============================================================================
    // TEAM MANAGEMENT + TASK ASSIGNMENT
    // ============================================================================
    
    function showAddMemberModal() {
        const modal = document.getElementById('addMemberModal');
        if (!modal) return;
    
        // reset modal fields
        const searchInput = document.getElementById('memberSearch');
        const resultsDiv  = document.getElementById('memberSearchResults');
        const previewWrap = document.getElementById('selectedMemberPreview');
        const previewCard = document.getElementById('selectedMemberCard');
        const loadingIcon = document.getElementById('memberSearchLoading');
        const confirmBtn  = document.getElementById('confirmAddMemberBtn');
    
        selectedUser = null;
    
        if (searchInput) searchInput.value = '';
        if (resultsDiv) {
            resultsDiv.innerHTML = '';
            resultsDiv.style.display = 'none';
        }
        if (previewWrap) previewWrap.style.display = 'none';
        if (previewCard) previewCard.innerHTML = '';
        if (loadingIcon) loadingIcon.style.display = 'none';
        if (confirmBtn) confirmBtn.disabled = true;
    
        modal.style.display = 'flex';
        if (searchInput) searchInput.focus();
    }
    
    function closeAddMemberModal() {
        const modal = document.getElementById('addMemberModal');
        if (modal) modal.style.display = 'none';
    }
    
    function searchUsers(query) {
        clearTimeout(userSearchTimeout);
    
        const resultsDiv  = document.getElementById('memberSearchResults');
        const loadingIcon = document.getElementById('memberSearchLoading');
        const confirmBtn  = document.getElementById('confirmAddMemberBtn');
    
        if (!resultsDiv || !loadingIcon || !confirmBtn) return;
    
        if (query.trim().length < 2) {
            resultsDiv.style.display = 'none';
            resultsDiv.innerHTML = '';
            confirmBtn.disabled = true;
            return;
        }
    
        loadingIcon.style.display = 'block';
    
        userSearchTimeout = setTimeout(() => {
            fetch(`/{{ $username }}/manage/projects/search-users?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(res => res.json())
                .then(users => {
                    loadingIcon.style.display = 'none';
    
                    if (!Array.isArray(users) || users.length === 0) {
                        resultsDiv.innerHTML = `
                            <div style="padding:16px;text-align:center;color:var(--jira-text-subtle);">
                                <i class="fas fa-user-slash" style="font-size:24px;margin-bottom:8px;"></i>
                                <p style="margin:0;font-size:13px;">No users found</p>
                            </div>
                        `;
                        resultsDiv.style.display = 'block';
                        return;
                    }
    
                    resultsDiv.innerHTML = users.map(user => `
                        <div class="jira-search-result-item"
                            onclick="selectUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', '${escapeHtml(user.avatar || '')}')"
                            style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;border-bottom:1px solid var(--jira-border);transition:all .15s;">
                            <img src="${escapeHtml(user.avatar || '')}"
                                 alt="${escapeHtml(user.name)}"
                                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div style="flex:1;">
                                <div style="font-size:14px;font-weight:600;color:var(--jira-text);">${escapeHtml(user.name)}</div>
                                <div style="font-size:12px;color:var(--jira-text-subtle);">${escapeHtml(user.email)}</div>
                            </div>
                        </div>
                    `).join('');
    
                    resultsDiv.style.display = 'block';
                })
                .catch(() => {
                    loadingIcon.style.display = 'none';
                    showNotification('Failed to search users', 'error');
                });
        }, 300);
    }
    
    function selectUser(id, name, email, avatar) {
        selectedUser = { id, name, email, avatar };
    
        const resultsDiv  = document.getElementById('memberSearchResults');
        const previewWrap = document.getElementById('selectedMemberPreview');
        const previewCard = document.getElementById('selectedMemberCard');
        const confirmBtn  = document.getElementById('confirmAddMemberBtn');
        const searchInput = document.getElementById('memberSearch');
    
        if (resultsDiv) resultsDiv.style.display = 'none';
        if (searchInput) searchInput.value = name;
    
        if (previewWrap && previewCard) {
            previewCard.innerHTML = `
                <img src="${escapeHtml(avatar || '')}"
                     alt="${escapeHtml(name)}"
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                <div style="flex:1;">
                    <div style="font-size:14px;font-weight:600;color:var(--jira-text);">${escapeHtml(name)}</div>
                    <div style="font-size:12px;color:var(--jira-text-subtle);">${escapeHtml(email)}</div>
                </div>
                <i class="fas fa-check-circle" style="color:var(--jira-success);font-size:20px;"></i>
            `;
            previewWrap.style.display = 'block';
        }
    
        if (confirmBtn) confirmBtn.disabled = false;
    }
    
    function confirmAddMember() {
        if (!selectedUser) {
            showNotification('Please select a user first', 'error');
            return;
        }
    
        const roleSel       = document.getElementById('memberRole');
        const techStackSel  = document.getElementById('memberTechStack');
        const positionSel   = document.getElementById('memberPosition');
    
        const role      = roleSel ? roleSel.value : '';
        const techStack = techStackSel ? techStackSel.value : '';
        const position  = positionSel ? positionSel.value : '';
    
        if (teamMembers.some(m => m.user_id === selectedUser.id)) {
            showNotification('This user is already in the team', 'error');
            return;
        }
    
        teamMembers.push({
            id: Date.now(),
            user_id: selectedUser.id,
            name: selectedUser.name,
            email: selectedUser.email,
            avatar: selectedUser.avatar || '',
            role,
            techStack,
            position,
            assignedTasks: []
        });
    
        closeAddMemberModal();
        renderTeamMembersWithTasks();
        showNotification('✅ Team member added', 'success');
    }
    
    function toggleTaskAssignmentForMember(memberId, taskIndex) {
        const member = teamMembers.find(m => m.id === memberId);
        if (!member) return;
    
        if (!Array.isArray(member.assignedTasks)) {
            member.assignedTasks = [];
        }
    
        const pos = member.assignedTasks.indexOf(taskIndex);
        if (pos >= 0) {
            member.assignedTasks.splice(pos, 1);
            showNotification('Task unassigned', 'info');
        } else {
            member.assignedTasks.push(taskIndex);
            showNotification('Task assigned!', 'success');
        }
    
        renderTeamMembersWithTasks();
    }
    
    function removeMember(memberId) {
        if (!confirm('Remove this team member? Task assignments for them will be lost.')) return;
        teamMembers = teamMembers.filter(m => m.id !== memberId);
        renderTeamMembersWithTasks();
        showNotification('Member removed', 'info');
    }
    
    function renderTeamMembersWithTasks() {
        const emptyTeamState = document.getElementById('emptyTeamState');
        const container      = document.getElementById('teamMembersWithTasks');
    
        // ensure tasks up to date
        saveCurrentStepData();
    
        if (!container || !emptyTeamState) return;
    
        if (teamMembers.length === 0) {
            emptyTeamState.style.display = 'flex';
            container.style.display = 'none';
            container.innerHTML = '';
            return;
        }
    
        emptyTeamState.style.display = 'none';
        container.style.display = 'grid';
    
        // if no tasks yet:
        if (!projectData.tasks.length) {
            container.innerHTML = `
                <div class="jira-empty-state">
                    <div class="jira-empty-icon"><i class="fas fa-tasks"></i></div>
                    <h4 class="jira-empty-title">No tasks to assign</h4>
                    <p class="jira-empty-text">Please add tasks in Step 2 first</p>
                </div>
            `;
            return;
        }
    
        const priorityIcons = {
            low: 'fa-arrow-down',
            medium: 'fa-minus',
            high: 'fa-arrow-up',
            urgent: 'fa-exclamation'
        };
    
        container.innerHTML = teamMembers.map(member => {
            const assignedTasks = member.assignedTasks || [];
    
            // build list of "available tasks" clickable
            const tasksHtml = projectData.tasks.map((task, taskIndex) => {
                const isAssigned = assignedTasks.includes(taskIndex);
                const prio = task.priority || 'medium';
                return `
                    <div
                        onclick="toggleTaskAssignmentForMember(${member.id}, ${taskIndex})"
                        style="
                            display:flex;
                            align-items:center;
                            gap:12px;
                            padding:12px;
                            background:${isAssigned ? 'rgba(0,82,204,0.1)' : 'var(--jira-bg)'};
                            border:2px solid ${isAssigned ? 'var(--jira-primary)' : 'var(--jira-border)'};
                            border-radius:3px;
                            cursor:pointer;
                            transition:all .15s;
                        "
                    >
                        <div class="jira-task-priority-icon ${escapeHtml(prio)}"
                            style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;border-radius:3px;">
                            <i class="fas ${priorityIcons[prio] || 'fa-minus'}"></i>
                        </div>
                        <div style="flex:1;font-size:14px;font-weight:500;color:var(--jira-text);">
                            ${escapeHtml(task.name || 'Untitled Task')}
                        </div>
                        <div style="
                            width:24px;
                            height:24px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            background:${isAssigned ? 'var(--jira-success)' : 'var(--jira-card)'};
                            border:2px solid ${isAssigned ? 'var(--jira-success)' : 'var(--jira-border)'};
                            border-radius:3px;
                            flex-shrink:0;
                            font-size:12px;
                            color:${isAssigned ? '#fff' : 'inherit'};
                            ">
                            ${isAssigned ? '<i class="fas fa-check" style="color:#fff;font-size:12px;"></i>' : ''}
                        </div>
                    </div>
                `;
            }).join('');
    
            return `
                <div class="jira-team-member-assignment-card"
                    style="background:var(--jira-card);border:2px solid var(--jira-border);border-radius:3px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:2px solid var(--jira-border);">
                        <img src="${escapeHtml(member.avatar || '')}"
                             alt="${escapeHtml(member.name)}"
                             style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:16px;font-weight:600;color:var(--jira-text);margin-bottom:4px;">
                                ${escapeHtml(member.name)}
                            </div>
                            <div style="font-size:12px;color:var(--jira-text-subtle);">
                                ${escapeHtml(member.role)} · ${escapeHtml(member.techStack)}
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="
                                padding:6px 12px;
                                background:${assignedTasks.length ? 'var(--jira-primary)' : 'var(--jira-hover)'};
                                color:${assignedTasks.length ? '#fff' : 'var(--jira-text-subtle)'};
                                border-radius:3px;
                                font-size:12px;
                                font-weight:600;
                            ">
                                ${assignedTasks.length} ${assignedTasks.length === 1 ? 'task' : 'tasks'}
                            </div>
                            <button type="button"
                                onclick="removeMember(${member.id})"
                                style="
                                    width:32px;
                                    height:32px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    background:none;
                                    border:none;
                                    color:var(--jira-text-subtle);
                                    cursor:pointer;
                                    border-radius:3px;
                                    transition:all .15s;
                                "
                                onmouseover="this.style.background='#FFEBE6';this.style.color='var(--jira-danger)'"
                                onmouseout="this.style.background='none';this.style.color='var(--jira-text-subtle)'">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
    
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--jira-text-subtle);text-transform:uppercase;margin-bottom:12px;">
                            Available Tasks
                        </div>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            ${tasksHtml}
                        </div>
                    </div>
    
                    ${assignedTasks.length === 0 ? `
                    <div style="
                            text-align:center;
                            padding:20px;
                            color:var(--jira-text-subtle);
                            font-size:13px;
                            margin-top:16px;
                            background:var(--jira-bg);
                            border-radius:3px;">
                        <i class="fas fa-hand-point-up"
                           style="font-size:24px;margin-bottom:8px;display:block;"></i>
                        Click on tasks above to assign them to ${escapeHtml(member.name.split(' ')[0] || 'member')}
                    </div>` : ''}
                </div>
            `;
        }).join('');
    }
    
    // ============================================================================
    // CLIENT / ORDER SECTION
    // ============================================================================
    
    function toggleClientSection() {
        const toggle = document.getElementById('hasClientToggle');
        const section = document.getElementById('clientFormSection');
        if (!toggle || !section) return;
    
        if (toggle.checked) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
            clearSelectedClientUI();
            selectedClient = null;
            projectData.client = null;
        }
    }
    
    function clearSelectedClientUI() {
        const selectedPreview = document.getElementById('selectedClientPreview');
        const selectedCard    = document.getElementById('selectedClientCard');
        const selectedField   = document.getElementById('selectedClientUserId');
        const inviteBtn       = document.getElementById('inviteClientButton');
        const searchResults   = document.getElementById('clientSearchResults');
    
        if (selectedPreview) selectedPreview.style.display = 'none';
        if (selectedCard)    selectedCard.innerHTML = '';
        if (selectedField)   selectedField.value = '';
        if (inviteBtn)       inviteBtn.style.display = 'none';
        if (searchResults)   searchResults.style.display = 'none';
    }
    
    function searchClients(query) {
        clearTimeout(clientSearchTimeout);
    
        const loadingEl      = document.getElementById('clientSearchLoading');
        const resultsEl      = document.getElementById('clientSearchResults');
        const inviteBtnWrap  = document.getElementById('inviteClientButton');
    
        if (!resultsEl || !loadingEl || !inviteBtnWrap) return;
    
        if (query.trim().length < 2) {
            resultsEl.style.display = 'none';
            resultsEl.innerHTML = '';
            inviteBtnWrap.style.display = 'none';
            return;
        }
    
        loadingEl.style.display = 'block';
    
        clientSearchTimeout = setTimeout(() => {
            fetch(`/{{ $username }}/manage/projects/search-clients?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(clients => {
                loadingEl.style.display = 'none';
    
                // if empty -> allow invite flow
                if (!Array.isArray(clients) || clients.length === 0) {
                    resultsEl.innerHTML = `
                        <div style="padding:16px;text-align:center;color:var(--jira-text-subtle);font-size:13px;">
                            <i class="fas fa-user-slash" style="font-size:24px;margin-bottom:8px;"></i>
                            <div>No existing client found</div>
                        </div>
                    `;
                    resultsEl.style.display = 'block';
                    inviteBtnWrap.style.display = 'block';
                    return;
                }
    
                inviteBtnWrap.style.display = 'none';
    
                resultsEl.innerHTML = clients.map(c => `
                    <div class="jira-search-result-item"
                        onclick="selectClient(${c.id}, '${escapeHtml(c.name)}', '${escapeHtml(c.email)}', '${escapeHtml(c.avatar || '')}')"
                        style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;border-bottom:1px solid var(--jira-border);transition:all .15s;">
                        <img src="${escapeHtml(c.avatar || '')}"
                             alt="${escapeHtml(c.name)}"
                             style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                        <div style="flex:1;">
                            <div style="font-size:14px;font-weight:600;color:var(--jira-text);">${escapeHtml(c.name)}</div>
                            <div style="font-size:12px;color:var(--jira-text-subtle);">${escapeHtml(c.email)}</div>
                        </div>
                    </div>
                `).join('');
    
                resultsEl.style.display = 'block';
            })
            .catch(() => {
                loadingEl.style.display = 'none';
                showNotification('Failed to search clients', 'error');
            });
        }, 300);
    }
    
    function selectClient(id, name, email, avatar) {
        selectedClient = {
            user_id: id,
            name,
            email,
            avatar: avatar || ''
        };
    
        const previewWrap  = document.getElementById('selectedClientPreview');
        const card         = document.getElementById('selectedClientCard');
        const idField      = document.getElementById('selectedClientUserId');
        const resultsEl    = document.getElementById('clientSearchResults');
        const inviteBtn    = document.getElementById('inviteClientButton');
    
        if (resultsEl) resultsEl.style.display = 'none';
        if (inviteBtn) inviteBtn.style.display = 'none';
    
        if (card) {
            card.innerHTML = `
                <img src="${escapeHtml(selectedClient.avatar)}"
                     alt="${escapeHtml(selectedClient.name)}"
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                <div style="flex:1;">
                    <div style="font-size:14px;font-weight:600;color:var(--jira-text);">${escapeHtml(selectedClient.name)}</div>
                    <div style="font-size:12px;color:var(--jira-text-subtle);">${escapeHtml(selectedClient.email)}</div>
                </div>
                <i class="fas fa-check-circle" style="color:var(--jira-success);font-size:20px;"></i>
            `;
        }
    
        if (idField) {
            idField.value = selectedClient.user_id;
        }
    
        if (previewWrap) previewWrap.style.display = 'block';
    }
    
    // Invite Client submodal
    function showInviteClientModal() {
        const modal = document.getElementById('inviteClientModal');
        if (!modal) return;
    
        document.getElementById('inviteClientEmail').value = '';
        document.getElementById('inviteClientName').value = '';
        document.getElementById('inviteClientMessage').value = '';
    
        modal.style.display = 'flex';
    }
    
    function closeInviteClientModal() {
        const modal = document.getElementById('inviteClientModal');
        if (modal) modal.style.display = 'none';
    }
    
    function sendClientInvitation() {
        const emailField = document.getElementById('inviteClientEmail');
        const nameField  = document.getElementById('inviteClientName');
        const msgField   = document.getElementById('inviteClientMessage');
    
        const emailVal = emailField?.value.trim() || '';
        const nameVal  = nameField?.value.trim() || '';
        const msgVal   = msgField?.value.trim() || '';
    
        if (!emailVal || !nameVal) {
            showNotification('Client email and name are required', 'error');
            return;
        }
    
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('email', emailVal);
        formData.append('name', nameVal);
        formData.append('message', msgVal);
    
        fetch(`/{{ $username }}/manage/projects/invite-client`, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(res => res.json().catch(() => ({})))
            .then(json => {
                showNotification('Invitation sent (if deliverable)', 'success');
                closeInviteClientModal();
            })
            .catch(() => {
                showNotification('Failed to send invite', 'error');
            });
    }
    
    // ============================================================================
    // STEP 4 SUMMARY
    // ============================================================================
    
    function prepareReviewStep() {
        saveCurrentStepData();
    
        const summaryEl = document.getElementById('projectSummary');
        if (!summaryEl) return;
    
        const basic = projectData.basic;
        const totalTasks = projectData.tasks.length;
        const teamCount = teamMembers.length;
    
        // sync order currency label with selected currency
        const orderCurrencySpan = document.getElementById('orderCurrency');
        if (orderCurrencySpan) {
            orderCurrencySpan.textContent = basic.currency || 'PKR';
        }
    
        const rows = [
            { label: 'Project Name',      value: basic.name || '-' },
            { label: 'Project Key',       value: basic.key || '-' },
            { label: 'Type',              value: basic.type || '-' },
            { label: 'Priority',          value: basic.priority || '-' },
            { label: 'Start Date',        value: basic.startDate || '-' },
            { label: 'Due Date',          value: basic.dueDate || '-' },
            { label: 'Total Tasks',       value: totalTasks.toString() },
            { label: 'Team Members',      value: teamCount.toString() },
            { label: 'Budget',            value: basic.budget ? `${basic.currency} ${basic.budget}` : 'Not set' },
            { label: 'Estimated Hours',   value: basic.estimatedHours || 'Not set' }
        ];
    
        summaryEl.innerHTML = rows.map(r => `
            <div class="jira-summary-item">
                <div class="jira-summary-label">${escapeHtml(r.label)}</div>
                <div class="jira-summary-value">${escapeHtml(r.value)}</div>
            </div>
        `).join('');
    }
    
    // ============================================================================
    // FILES / MEDIA (STEP 5)
    // ============================================================================
    
    function bytesToKB(sizeBytes) {
        // safe convert file size to KB int
        return Math.round((sizeBytes || 0) / 1024);
    }
    
    function handleProjectFilesSelected(fileList) {
        if (!fileList || !fileList.length) return;
    
        // push new files into projectData.media
        for (let i = 0; i < fileList.length; i++) {
            const f = fileList[i];
            projectData.media.push({
                file: f,
                visibility: 'internal',
                note: '',
            });
        }
    
        renderSelectedFilesList();
    }
    
    function updateMediaMeta(idx, field, value) {
        if (!projectData.media[idx]) return;
        projectData.media[idx][field] = value;
    }
    
    function removeMediaFile(idx) {
        if (!confirm('Remove this file from upload list?')) return;
        projectData.media.splice(idx, 1);
        renderSelectedFilesList();
    }
    
    function renderSelectedFilesList() {
        const listEl = document.getElementById('selectedFilesList');
        if (!listEl) return;
    
        if (!projectData.media.length) {
            listEl.innerHTML = `
                <div class="jira-empty-state small">
                    <div class="jira-empty-icon"><i class="fas fa-paperclip"></i></div>
                    <div class="jira-empty-title">No files selected</div>
                    <div class="jira-empty-text">You can attach assets (images, PDFs, docs...)</div>
                </div>
            `;
            return;
        }
    
        listEl.innerHTML = projectData.media.map((m, idx) => `
            <div style="
                border:2px solid var(--jira-border);
                border-radius:3px;
                background:var(--jira-card);
                padding:12px;
                display:flex;
                flex-direction:column;
                gap:8px;
                position:relative;
            ">
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="
                        width:40px;
                        height:40px;
                        border-radius:3px;
                        background:var(--jira-bg);
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:12px;
                        font-weight:600;
                        color:var(--jira-text-subtle);
                        text-align:center;
                        line-height:1.2;
                        flex-shrink:0;
                    ">
                        ${escapeHtml((m.file && m.file.name ? m.file.name.split('.').pop() : 'FILE').toUpperCase())}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:14px;font-weight:600;color:var(--jira-text);word-break:break-all;">
                            ${escapeHtml(m.file ? m.file.name : 'Unnamed')}
                        </div>
                        <div style="font-size:12px;color:var(--jira-text-subtle);">
                            ${bytesToKB(m.file?.size || 0)} KB
                        </div>
                    </div>
    
                    <button type="button"
                        onclick="removeMediaFile(${idx})"
                        style="
                            width:28px;
                            height:28px;
                            border:none;
                            background:none;
                            color:var(--jira-danger);
                            cursor:pointer;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            border-radius:3px;
                        "
                        title="Remove file">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
    
                <div style="display:flex;flex-wrap:wrap;gap:12px;">
                    <div style="flex:1;min-width:200px;">
                        <label class="jira-label" style="font-size:11px;">Visibility</label>
                        <select class="jira-select"
                            onchange="updateMediaMeta(${idx}, 'visibility', this.value)">
                            <option value="internal" ${m.visibility === 'internal' ? 'selected' : ''}>Internal Only</option>
                            <option value="client"   ${m.visibility === 'client'   ? 'selected' : ''}>Visible to Client</option>
                        </select>
                    </div>
                    <div style="flex:2;min-width:200px;">
                        <label class="jira-label" style="font-size:11px;">Note</label>
                        <input class="jira-input"
                            type="text"
                            placeholder="Optional note about this file"
                            value="${escapeHtml(m.note || '')}"
                            oninput="updateMediaMeta(${idx}, 'note', this.value)">
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // ============================================================================
    // SAVE DRAFT
    // ============================================================================
    
    function saveAsDraft() {
        saveCurrentStepData();
    
        // save locally for safety
        try {
            localStorage.setItem('projectDraft', JSON.stringify(projectData));
        } catch (e) { /* ignore quota errors */ }
    
        // Optionally also POST to backend draft route (best effort, not blocking)
        const draftUrl = "/{{ $username }}/manage/projects/draft";
    
        const payload = new FormData();
        payload.append('_token', '{{ csrf_token() }}');
        payload.append('draft', JSON.stringify(projectData));
    
        fetch(draftUrl, {
            method: 'POST',
            body: payload,
            credentials: 'same-origin'
        }).catch(() => { /* ignore network/draft errors */ });
    
        showNotification('Draft saved', 'success');
    }
    
    // ============================================================================
    // FINAL SUBMIT
    // ============================================================================
    
    function submitProject() {
        // validate last step also includes notes/media
        saveCurrentStepData();
    
        const submitUrl = "{{ route('tenant.manage.projects.store', $username) }}";
    
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
    
        // BASIC
        formData.append('name',            projectData.basic.name);
        formData.append('key',             projectData.basic.key);
        formData.append('type',            projectData.basic.type);
        formData.append('category',        projectData.basic.category || '');
        formData.append('priority',        projectData.basic.priority || 'medium');
        formData.append('description',     projectData.basic.description || '');
        formData.append('start_date',      projectData.basic.startDate);
        formData.append('due_date',        projectData.basic.dueDate);
    
        if (projectData.basic.budget !== '') {
            formData.append('budget', projectData.basic.budget);
        }
    
        formData.append('currency', projectData.basic.currency || 'PKR');
    
        if (projectData.basic.estimatedHours !== '') {
            formData.append('estimated_hours', projectData.basic.estimatedHours);
        }
    
        // FLAGS (array)
        if (Array.isArray(projectData.flags) && projectData.flags.length > 0) {
            projectData.flags.forEach((flag, i) => {
                formData.append(`flags[${i}]`, flag);
            });
        }
    
        // TASKS
        // Also attach assigned_to from team assignments
        // We'll compute mapping taskIndex -> user_id (last assignment wins)
        const taskAssignees = {}; // {taskIndex: user_id}
        teamMembers.forEach(member => {
            (member.assignedTasks || []).forEach(taskIdx => {
                taskAssignees[taskIdx] = member.user_id;
            });
        });
    
        projectData.tasks.forEach((task, taskIndex) => {
            formData.append(`tasks[${taskIndex}][title]`, task.name || '');
            formData.append(`tasks[${taskIndex}][notes]`, task.notes || '');
            formData.append(`tasks[${taskIndex}][priority]`, task.priority || 'medium');
    
            if (task.dueDate) {
                formData.append(`tasks[${taskIndex}][due_date]`, task.dueDate);
            }
    
            if (task.estimatedHours !== '') {
                formData.append(`tasks[${taskIndex}][estimated_hours]`, task.estimatedHours);
            }
    
            // story_points is integer|min:0 so always safe
            formData.append(`tasks[${taskIndex}][story_points]`, task.storyPoints || '0');
    
            if (taskAssignees[taskIndex]) {
                formData.append(`tasks[${taskIndex}][assigned_to]`, taskAssignees[taskIndex]);
            }
    
            // Subtasks
            if (Array.isArray(task.subtasks)) {
                task.subtasks.forEach((subtask, subIndex) => {
                    formData.append(
                        `tasks[${taskIndex}][subtasks][${subIndex}][title]`,
                        subtask.name || ''
                    );
                    formData.append(
                        `tasks[${taskIndex}][subtasks][${subIndex}][completed]`,
                        subtask.completed ? '1' : '0'
                    );
                });
            }
        });
    
        // TEAM
        teamMembers.forEach((member, idx) => {
            if (!member.user_id) return;
    
            formData.append(`team[${idx}][user_id]`, member.user_id);
            formData.append(`team[${idx}][role]`, member.role || '');
            formData.append(`team[${idx}][tech_stack]`, member.techStack || '');
            formData.append(`team[${idx}][position]`, member.position || '');
        });
    
        // CLIENT
        if (projectData.client && projectData.client.client_user_id) {
            formData.append('client_user_id', projectData.client.client_user_id);
            formData.append('client_company', projectData.client.client_company || '');
            formData.append('client_phone',   projectData.client.client_phone   || '');
    
            if (projectData.client.order_value !== '') {
                formData.append('order_value', projectData.client.order_value);
            }
    
            formData.append('payment_terms',        projectData.client.payment_terms || '');
            formData.append('special_requirements', projectData.client.special_requirements || '');
            formData.append('portal_access',        projectData.client.portal_access || '0');
            formData.append('can_comment',          projectData.client.can_comment   || '0');
        }
    
        // NOTES
        if (Array.isArray(projectData.notes)) {
            projectData.notes.forEach((note, idx) => {
                formData.append(`notes[${idx}][body]`, note.body || '');
                formData.append(`notes[${idx}][is_internal]`, note.is_internal ? '1' : '0');
                formData.append(`notes[${idx}][pinned]`,      note.pinned      ? '1' : '0');
            });
        }
    
        // MEDIA
        if (Array.isArray(projectData.media)) {
            projectData.media.forEach((item, idx) => {
                if (item.file) {
                    formData.append(`media[${idx}][file]`, item.file);
                }
formData.append(`media[${idx}][visibility]`, item.visibility || 'internal');
formData.append(`media[${idx}][note]`, item.note || '');
formData.append(`media[${idx}][sort_order]`, idx.toString());

            });
        }
    
        // SEND
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(async res => {
            let data = {};
            try {
                data = await res.json();
            } catch (err) {
                // ignore parse error
            }
    
            if (!res.ok || !data.success) {
                console.error('Create project failed', data);
                showNotification(data.message || 'Failed to create project', 'error');
                return;
            }
    
            // success
            try {
                localStorage.removeItem('projectDraft');
            } catch (e) { /* ignore */ }
    
            if (data.redirect_to) {
                window.location.href = data.redirect_to;
            } else {
                showNotification('✅ Project created successfully!', 'success');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Unexpected error creating project', 'error');
        });
    }
    
    // ============================================================================
    // EXPOSE GLOBALS FOR INLINE HTML HANDLERS
    // ============================================================================
    
    window.openCreateProjectModal          = openCreateProjectModal;
    window.closeCreateProjectModal         = closeCreateProjectModal;
    window.previousStep                    = previousStep;
    window.goToPreviousStep                = goToPreviousStep;
    window.nextStep                        = nextStep;
    window.selectPriority                  = selectPriority;
    window.toggleFlag                      = toggleFlag;
    
    window.addTask                         = addTask;
    window.cycleTaskPriority               = cycleTaskPriority;
    window.deleteTask                      = deleteTask;
    window.addSubtask                      = addSubtask;
    window.toggleSubtaskCheck              = toggleSubtaskCheck;
    window.deleteSubtask                   = deleteSubtask;
    
    window.showAddMemberModal              = showAddMemberModal;
    window.closeAddMemberModal             = closeAddMemberModal;
    window.searchUsers                     = searchUsers;
    window.selectUser                      = selectUser;
    window.confirmAddMember                = confirmAddMember;
    window.renderTeamMembersWithTasks      = renderTeamMembersWithTasks;
    window.toggleTaskAssignmentForMember   = toggleTaskAssignmentForMember;
    window.removeMember                    = removeMember;
    
    window.toggleClientSection             = toggleClientSection;
    window.searchClients                   = searchClients;
    window.selectClient                    = selectClient;
    window.showInviteClientModal           = showInviteClientModal;
    window.closeInviteClientModal          = closeInviteClientModal;
    window.sendClientInvitation            = sendClientInvitation;
    
    window.handleProjectFilesSelected      = handleProjectFilesSelected;
    window.updateMediaMeta                 = updateMediaMeta;
    window.removeMediaFile                 = removeMediaFile;
    
    window.saveAsDraft                     = saveAsDraft;
    
    // init log (optional)
    console.log('✅ Project Creation Script Loaded');
    </script>
    

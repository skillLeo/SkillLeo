@extends('tenant.settings.layout')

@section('settings-content')
<div class="settings-page-header">
  <h2 class="settings-page-title">Danger Zone</h2>
  <p class="settings-page-desc">Irreversible and destructive actions for your account.</p>
</div>

@php
  $status = $user->is_active;
  $requiresPassword = $requiresPassword ?? filled($user->password);
@endphp

{{-- Status Alerts --}}
@if($status === 'hibernated')
  <div class="alert alert-info">
    <div class="alert-icon">
      <i class="fas fa-info-circle"></i>
    </div>
    <div class="alert-content">
      <div class="alert-title">Account Hibernated</div>
      <div class="alert-message">Your account is currently hibernated. Sign in to reactivate it.</div>
    </div>
  </div>
@elseif($status === 'pending_delete')
  <div class="alert alert-warning">
    <div class="alert-icon">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="alert-content">
      <div class="alert-title">Deletion Scheduled</div>
      <div class="alert-message">Your account is scheduled for deletion. You can cancel this action below before the deadline.</div>
    </div>
  </div>
@endif

@if(session('status'))
  <div class="alert alert-success">
    <div class="alert-icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <div class="alert-content">
      <div class="alert-message">{{ session('status') }}</div>
    </div>
  </div>
@endif

{{-- Hibernate Account --}}
<div class="danger-card">
  <div class="danger-card-header">
    <div class="danger-card-icon hibernate">
      <i class="fas fa-pause-circle"></i>
    </div>
    <div class="danger-card-info">
      <h3 class="danger-card-title">Hibernate Account</h3>
      <p class="danger-card-desc">Temporarily disable your account without losing any data. You can reactivate anytime.</p>
    </div>
  </div>
  
  <div class="danger-card-body">
    <div class="info-box">
      <div class="info-box-title">What happens when you hibernate?</div>
      <ul class="info-list">
        <li>Your profile will be hidden from search and public view</li>
        <li>You won't be able to sign in until you reactivate</li>
        <li>All your data remains intact and secure</li>
        <li>Active subscriptions continue unless canceled separately</li>
      </ul>
    </div>

    <button 
      class="btn btn-warning btn-lg" 
      onclick="openHibernateModal()"
      {{ $status !== 'active' ? 'disabled' : '' }}>
      <i class="fas fa-pause-circle"></i>
      Hibernate Account
    </button>
  </div>
</div>

{{-- Delete Account --}}
<div class="danger-card danger-card-critical">
  <div class="danger-card-header">
    <div class="danger-card-icon delete">
      <i class="fas fa-trash-alt"></i>
    </div>
    <div class="danger-card-info">
      <h3 class="danger-card-title">Delete Account</h3>
      <p class="danger-card-desc">Permanently delete your account and all associated data. This action cannot be undone.</p>
    </div>
  </div>

  <div class="danger-card-body">
    @if($status === 'pending_delete')
      <div class="alert alert-warning mb-4">
        <div class="alert-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="alert-content">
          <div class="alert-title">Deletion Pending</div>
          <div class="alert-message">Your account is scheduled for deletion. Cancel below to keep your account.</div>
        </div>
      </div>

      <button class="btn btn-secondary btn-lg" onclick="openCancelDeleteModal()">
        <i class="fas fa-undo"></i>
        Cancel Deletion
      </button>
    @else
      <div class="warning-box">
        <div class="warning-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="warning-content">
          <div class="warning-title">This action is permanent and irreversible</div>
          <p>All your data will be permanently deleted. If you might return, consider hibernating your account instead.</p>
        </div>
      </div>

      <button 
        class="btn btn-danger btn-lg" 
        onclick="openDeleteModal()"
        {{ $status !== 'active' ? 'disabled' : '' }}>
        <i class="fas fa-trash-alt"></i>
        Delete Account
      </button>
    @endif
  </div>
</div>

{{-- Hibernate Modal --}}
<div id="hibernateModal" class="modal" style="display:none;">
  <div class="modal-overlay" onclick="closeHibernateModal()"></div>
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">Hibernate Account</h3>
      <button class="modal-close" onclick="closeHibernateModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('tenant.settings.danger.hibernate', $username) }}">
      @csrf
      <div class="modal-body">
        <p class="modal-text">Your account will be hidden and inaccessible until you sign in again to reactivate it.</p>

        @if($requiresPassword)
          <div class="form-group">
            <label class="form-label">Confirm your password</label>
            <input 
              name="password" 
              type="password" 
              class="form-input" 
              placeholder="Enter your password"
              required 
              autocomplete="current-password">
            @error('password')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        @else
          <div class="info-box info-box-sm">
            <i class="fas fa-info-circle"></i>
            You signed in with a social account. No password required.
          </div>
        @endif

        <label class="checkbox-label">
          <input type="checkbox" name="confirm" value="1" required>
          <span>I understand my account will be hidden until I reactivate it</span>
        </label>
        @error('confirm')
          <div class="form-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeHibernateModal()">
          Cancel
        </button>
        <button type="submit" class="btn btn-warning">
          <i class="fas fa-pause-circle"></i>
          Hibernate Account
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Delete Modal (Step 1) --}}
<div id="deleteModal" class="modal" style="display:none;">
  <div class="modal-overlay" onclick="closeDeleteModal()"></div>
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">Delete Account</h3>
      <button class="modal-close" onclick="closeDeleteModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    @if(!session('danger.challenge'))
      <form method="POST" action="{{ route('tenant.settings.danger.delete.start', $username) }}">
        @csrf
        <div class="modal-body">
          <div class="warning-box warning-box-modal">
            <div class="warning-icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="warning-content">
              <div class="warning-title">This action is permanent</div>
              <p>All your data will be permanently deleted and cannot be recovered.</p>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              Type <strong>DELETE</strong> to confirm
            </label>
            <input 
              name="phrase" 
              type="text" 
              class="form-input" 
              placeholder="DELETE" 
              required
              autocomplete="off">
            @error('phrase')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          @if($requiresPassword)
            <div class="form-group">
              <label class="form-label">Confirm your password</label>
              <input 
                name="password" 
                type="password" 
                class="form-input" 
                placeholder="Enter your password"
                required 
                autocomplete="current-password">
              @error('password')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>
          @else
            <div class="info-box info-box-sm">
              <i class="fas fa-info-circle"></i>
              You signed in with a social account. No password required.
            </div>
          @endif
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
            Cancel
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-paper-plane"></i>
            Send Verification Code
          </button>
        </div>
      </form>
    @else
      <form method="POST" action="{{ route('tenant.settings.danger.delete.confirm', $username) }}">
        @csrf
        <input type="hidden" name="challenge_id" value="{{ session('danger.challenge') }}">
        
        <div class="modal-body">
          <p class="modal-text">We've sent a 6-digit verification code to your email. Enter it below to confirm account deletion.</p>

          <div class="form-group">
            <label class="form-label">Verification Code</label>
            <input 
              name="code" 
              type="text" 
              inputmode="numeric" 
              pattern="[0-9]{6}" 
              maxlength="6"
              class="form-input form-input-code" 
              placeholder="000000" 
              required
              autocomplete="one-time-code">
            @error('code')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
            Cancel
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i>
            Confirm Deletion
          </button>
        </div>
      </form>
    @endif
  </div>
</div>

{{-- Cancel Deletion Modal --}}
<div id="cancelDeleteModal" class="modal" style="display:none;">
  <div class="modal-overlay" onclick="closeCancelDeleteModal()"></div>
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">Cancel Account Deletion</h3>
      <button class="modal-close" onclick="closeCancelDeleteModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('tenant.settings.danger.delete.cancel', $username) }}">
      @csrf
      <div class="modal-body">
        <p class="modal-text">Are you sure you want to cancel the scheduled deletion and keep your account?</p>

        @if($requiresPassword)
          <div class="form-group">
            <label class="form-label">Confirm your password</label>
            <input 
              name="password" 
              type="password" 
              class="form-input" 
              placeholder="Enter your password"
              required 
              autocomplete="current-password">
            @error('password')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        @else
          <label class="checkbox-label">
            <input type="checkbox" name="confirm" value="1" required>
            <span>I want to cancel the pending deletion and keep my account</span>
          </label>
          @error('confirm')
            <div class="form-error">{{ $message }}</div>
          @enderror
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeCancelDeleteModal()">
          Close
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-undo"></i>
          Cancel Deletion
        </button>
      </div>
    </form>
  </div>
</div>

<style>
/* Alert Styles */
.alert {
  display: flex;
  gap: 12px;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
  border: 1px solid;
}

.alert-info {
  background: #eff6ff;
  border-color: #bfdbfe;
  color: #1e40af;
}

.alert-warning {
  background: #fff7ed;
  border-color: #fed7aa;
  color: #9a3412;
}

.alert-success {
  background: #f0fdf4;
  border-color: #bbf7d0;
  color: #15803d;
}

.alert-icon {
  font-size: 20px;
  flex-shrink: 0;
}

.alert-content {
  flex: 1;
}

.alert-title {
  font-weight: 600;
  margin-bottom: 4px;
}

.alert-message {
  font-size: 14px;
  opacity: 0.9;
}

/* Danger Card Styles */
.danger-card {
  background: var(--card, #fff);
  border: 1px solid var(--border, #e5e7eb);
  border-radius: 12px;
  margin-bottom: 24px;
  overflow: hidden;
}

.danger-card-critical {
  border: 2px solid #fca5a5;
}

.danger-card-header {
  display: flex;
  gap: 16px;
  padding: 24px;
  border-bottom: 1px solid var(--border, #e5e7eb);
  background: var(--bg, #fafafa);
}

.danger-card-icon {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}

.danger-card-icon.hibernate {
  background: #fef3c7;
  color: #92400e;
}

.danger-card-icon.delete {
  background: #fee2e2;
  color: #991b1b;
}

.danger-card-info {
  flex: 1;
}

.danger-card-title {
  font-size: 18px;
  font-weight: 600;
  margin: 0 0 4px 0;
  color: var(--text-heading, #111827);
}

.danger-card-desc {
  font-size: 14px;
  margin: 0;
  color: var(--text-body, #6b7280);
  line-height: 1.5;
}

.danger-card-body {
  padding: 24px;
}

/* Info Box */
.info-box {
  background: var(--bg, #f9fafb);
  border: 1px solid var(--border, #e5e7eb);
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 20px;
}

.info-box-sm {
  padding: 12px;
  font-size: 14px;
  display: flex;
  gap: 8px;
  align-items: center;
}

.info-box-title {
  font-weight: 600;
  margin-bottom: 12px;
  color: var(--text-heading, #111827);
}

.info-list {
  margin: 0;
  padding-left: 20px;
  list-style: none;
}

.info-list li {
  position: relative;
  padding-left: 8px;
  margin-bottom: 8px;
  font-size: 14px;
  color: var(--text-body, #4b5563);
  line-height: 1.6;
}

.info-list li:before {
  content: "•";
  position: absolute;
  left: -12px;
  color: var(--text-muted, #9ca3af);
}

/* Warning Box */
.warning-box {
  display: flex;
  gap: 12px;
  background: #fef2f2;
  border: 1px solid #fca5a5;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 20px;
}

.warning-box-modal {
  margin-bottom: 24px;
}

.warning-icon {
  font-size: 20px;
  color: #dc2626;
  flex-shrink: 0;
}

.warning-content {
  flex: 1;
}

.warning-title {
  font-weight: 600;
  color: #991b1b;
  margin-bottom: 4px;
}

.warning-content p {
  margin: 0;
  font-size: 14px;
  color: #7f1d1d;
  line-height: 1.5;
}

/* Button Styles */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: inherit;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-lg {
  padding: 12px 24px;
  font-size: 15px;
}

.btn-primary {
  background: #2563eb;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.btn-secondary {
  background: var(--bg, #f3f4f6);
  color: var(--text-heading, #111827);
}

.btn-secondary:hover:not(:disabled) {
  background: #e5e7eb;
}

.btn-warning {
  background: #f59e0b;
  color: white;
}

.btn-warning:hover:not(:disabled) {
  background: #d97706;
}

.btn-danger {
  background: #dc2626;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background: #b91c1c;
}

/* Modal Styles */
.modal {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
}

.modal-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
}

.modal-container {
  position: relative;
  background: var(--card, #fff);
  border-radius: 16px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  max-width: 500px;
  width: 100%;
  max-height: 90vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border, #e5e7eb);
}

.modal-title {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: var(--text-heading, #111827);
}

.modal-close {
  width: 32px;
  height: 32px;
  border: none;
  background: transparent;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-muted, #9ca3af);
  transition: all 0.2s;
}

.modal-close:hover {
  background: var(--bg, #f3f4f6);
  color: var(--text-heading, #111827);
}

.modal-body {
  padding: 24px;
  overflow-y: auto;
}

.modal-text {
  margin: 0 0 20px 0;
  font-size: 14px;
  color: var(--text-body, #4b5563);
  line-height: 1.6;
}

.modal-footer {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding: 16px 24px;
  border-top: 1px solid var(--border, #e5e7eb);
}

/* Form Styles */
.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  font-weight: 500;
  color: var(--text-heading, #111827);
}

.form-input {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid var(--border, #d1d5db);
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  transition: all 0.2s;
  background: var(--card, #fff);
  color: var(--text-heading, #111827);
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input-code {
  font-size: 20px;
  letter-spacing: 8px;
  text-align: center;
  font-weight: 600;
}

.form-error {
  margin-top: 6px;
  font-size: 13px;
  color: #dc2626;
}

.checkbox-label {
  display: flex;
  gap: 10px;
  align-items: flex-start;
  cursor: pointer;
  font-size: 14px;
  color: var(--text-body, #4b5563);
  line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
  margin-top: 2px;
  cursor: pointer;
}

.mb-4 {
  margin-bottom: 16px;
}

/* Responsive */
@media (max-width: 640px) {
  .danger-card-header {
    flex-direction: column;
    gap: 12px;
  }

  .modal-footer {
    flex-direction: column-reverse;
  }

  .modal-footer .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<script>
// Modal functions
function openHibernateModal() {
  document.getElementById('hibernateModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeHibernateModal() {
  document.getElementById('hibernateModal').style.display = 'none';
  document.body.style.overflow = '';
}

function openDeleteModal() {
  document.getElementById('deleteModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
  document.body.style.overflow = '';
}

function openCancelDeleteModal() {
  document.getElementById('cancelDeleteModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeCancelDeleteModal() {
  document.getElementById('cancelDeleteModal').style.display = 'none';
  document.body.style.overflow = '';
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeHibernateModal();
    closeDeleteModal();
    closeCancelDeleteModal();
  }
});

// Auto-open delete modal if verification code was sent
@if(session('danger.challenge'))
  document.addEventListener('DOMContentLoaded', function() {
    openDeleteModal();
  });
@endif
</script>
@endsection
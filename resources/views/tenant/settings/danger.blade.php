
@extends('tenant.settings.layout')

@section('settings-content')
    <div class="settings-page-header">
        <h2 class="settings-page-title">Danger Zone</h2>
        <p class="settings-page-desc">Irreversible and destructive actions for your account.</p>
    </div>

    <!-- Warning Banner -->
    <div style="background: #fef2f2; border: 2px solid #fca5a5; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <div style="display: flex; gap: 16px;">
            <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 24px;"></i>
            <div>
                <div style="font-weight: var(--fw-bold); color: #991b1b; margin-bottom: 8px; font-size: 16px;">
                    Caution: Destructive Actions
                </div>
                <p style="margin: 0; font-size: var(--fs-body); color: #7f1d1d; line-height: 1.5;">
                    The actions in this section are permanent and cannot be undone. Please proceed with extreme caution.
                </p>
            </div>
        </div>
    </div>

    <!-- Hibernate Account -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Hibernate Account</h3>
            <p class="settings-card-desc">Temporarily disable your account without losing data</p>
        </div>
        <div class="settings-card-body">
            <div style="background: var(--bg); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 12px;">
                    What happens when you hibernate your account?
                </div>
                <ul
                    style="margin: 0; padding-left: 20px; font-size: var(--fs-body); color: var(--text-body); line-height: 1.8;">
                    <li>Your profile will be hidden from search and public view</li>
                    <li>You won't be able to log in until you reactivate</li>
                    <li>Your data will be preserved and can be restored</li>
                    <li>Active subscriptions will continue (you can cancel separately)</li>
                    <li>You can reactivate anytime by logging in</li>
                </ul>
            </div>

            <button class="settings-btn settings-btn-danger" onclick="openHibernateModal()">
                <i class="fas fa-pause-circle"></i> Hibernate Account
            </button>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="settings-card" style="border: 2px solid #fca5a5;">
        <div class="settings-card-header">
            <h3 class="settings-card-title" style="color: #dc2626;">Delete Account</h3>
            <p class="settings-card-desc">Permanently delete your account and all associated data</p>
        </div>
        <div class="settings-card-body">
            <div
                style="background: #fef2f2; padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #fca5a5;">
                <div style="font-weight: var(--fw-bold); color: #991b1b; margin-bottom: 12px;">
                    ⚠️ This action is permanent and cannot be undone!
                </div>
                <div style="font-weight: var(--fw-semibold); color: #991b1b; margin-bottom: 12px;">
                    What will be deleted:
                </div>
                <ul style="margin: 0; padding-left: 20px; font-size: var(--fs-body); color: #7f1d1d; line-height: 1.8;">
                    <li>Your profile and all personal information</li>
                    <li>All projects, portfolios, and work samples</li>
                    <li>Messages, conversations, and communications</li>
                    <li>Orders, invoices, and transaction history</li>
                    <li>Connected apps and integrations</li>
                    <li>All analytics and activity data</li>
                </ul>
            </div>

            <div style="background: var(--bg); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 12px;">
                    Before you delete:
                </div>
                <ul
                    style="margin: 0; padding-left: 20px; font-size: var(--fs-body); color: var(--text-body); line-height: 1.8;">
                    <li>Download your data if you want to keep a copy</li>
                    <li>Cancel any active subscriptions</li>
                    <li>Withdraw any remaining balance</li>
                    <li>Notify clients about ongoing projects</li>
                    <li>Consider hibernating instead if you might return</li>
                </ul>
            </div><button class="settings-btn settings-btn-danger" onclick="openDeleteModal()">
                <i class="fas fa-trash-alt"></i> Delete Account Permanently
            </button>
        </div>
    </div><!-- Hibernate Modal -->
    <div id="hibernateModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background: var(--card); border-radius: 16px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-2xl);">
            <!-- Modal Header -->
            <div style="padding: 24px; border-bottom: 1px solid var(--border);">
                <h3 style="margin: 0; font-size: 20px; font-weight: var(--fw-bold); color: var(--text-heading);">
                    Hibernate Your Account
                </h3>
            </div><!-- Modal Body -->
            <div style="padding: 24px;">
                <div
                    style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 12px;">
                        <i class="fas fa-info-circle" style="color: #f59e0b; font-size: 18px;"></i>
                        <div style="font-size: var(--fs-subtle); color: #78350f; line-height: 1.5;">
                            Your account will be temporarily disabled. You can reactivate it anytime by simply logging in.
                        </div>
                    </div>
                </div>
                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Enter your password to confirm
                    </label>
                    <input type="password" class="settings-form-input" placeholder="Your password"
                        id="hibernatePassword">
                </div>
                <div style="display: flex; align-items: start; gap: 12px; margin-top: 20px;">
                    <input type="checkbox" id="hibernateConfirm" style="margin-top: 4px;">
                    <label for="hibernateConfirm"
                        style="font-size: var(--fs-body); color: var(--text-body); cursor: pointer;">
                        I understand that my account will be hidden until I reactivate it
                    </label>
                </div>
            </div><!-- Modal Footer -->
            <div
                style="padding: 24px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: flex-end;">
                <button class="settings-btn settings-btn-secondary" onclick="closeHibernateModal()">
                    Cancel
                </button>
                <button class="settings-btn settings-btn-danger" onclick="confirmHibernate()">
                    <i class="fas fa-pause-circle"></i> Hibernate Account
                </button>
            </div>
        </div>
    </div><!-- Delete Modal -->
    <div id="deleteModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div
            style="background: var(--card); border-radius: 16px; max-width: 550px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-2xl);">
            <!-- Modal Header -->
            <div style="padding: 24px; border-bottom: 1px solid var(--border); background: #fef2f2;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 48px; height: 48px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 24px;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 4px 0; font-size: 20px; font-weight: var(--fw-bold); color: #dc2626;">
                            Delete Account Permanently
                        </h3>
                        <p style="margin: 0; font-size: var(--fs-subtle); color: #991b1b;">
                            This action cannot be undone
                        </p>
                    </div>
                </div>
            </div><!-- Modal Body -->
            <div style="padding: 24px;">
                <div
                    style="background: #fef2f2; border: 2px solid #fca5a5; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                    <div style="font-weight: var(--fw-bold); color: #991b1b; margin-bottom: 8px;">
                        ⚠️ Final Warning
                    </div>
                    <p style="margin: 0; font-size: var(--fs-body); color: #7f1d1d; line-height: 1.5;">
                        All your data will be permanently deleted within 30 days. This includes your profile, projects,
                        messages, and all associated content. This action is irreversible.
                    </p>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Type <strong style="color: var(--text-heading);">DELETE</strong> to confirm
                    </label>
                    <input type="text" class="settings-form-input" placeholder="Type DELETE" id="deleteConfirmText">
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Enter your password
                    </label>
                    <input type="password" class="settings-form-input" placeholder="Your password" id="deletePassword">
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">
                        Enter the 6-digit code sent to your email
                    </label>
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                        <div style="width: 16px;"></div>
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                        <input type="text" maxlength="1" class="delete-otp-input"
                            style="width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: var(--fw-bold); border: 2px solid var(--border); border-radius: 8px;">
                    </div>
                    <span class="settings-form-help">
                        Didn't receive the code? <a href="#" style="color: var(--accent);">Resend</a>
                    </span>
                </div>

                <div style="display: flex; align-items: start; gap: 12px; margin-top: 20px;">
                    <input type="checkbox" id="deleteConfirmCheck" style="margin-top: 4px;">
                    <label for="deleteConfirmCheck"
                        style="font-size: var(--fs-body); color: var(--text-body); cursor: pointer;">
                        I understand this action is permanent and all my data will be deleted
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div
                style="padding: 24px; border-top: 1px solid var(--border); background: #fef2f2; display: flex; gap: 12px; justify-content: flex-end;">
                <button class="settings-btn settings-btn-secondary" onclick="closeDeleteModal()">
                    Cancel
                </button>
                <button class="settings-btn settings-btn-danger" onclick="confirmDelete()" id="finalDeleteBtn" disabled>
                    <i class="fas fa-trash-alt"></i> Delete My Account Forever
                </button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Hibernate Modal
            function openHibernateModal() {
                document.getElementById('hibernateModal').style.display = 'flex';
            }

            function closeHibernateModal() {
                document.getElementById('hibernateModal').style.display = 'none';
            }

            function confirmHibernate() {
                const password = document.getElementById('hibernatePassword').value;
                const confirm = document.getElementById('hibernateConfirm').checked;

                if (!password) {
                    alert('Please enter your password');
                    return;
                }

                if (!confirm) {
                    alert('Please confirm that you understand the consequences');
                    return;
                }

                // Demo alert
                alert('Account hibernation requested (This is a demo)');
                closeHibernateModal();
            }

            // Delete Modal
            function openDeleteModal() {
                document.getElementById('deleteModal').style.display = 'flex';
                // Simulate sending email code
                setTimeout(() => {
                    alert('A verification code has been sent to your email (This is a demo)');
                }, 500);
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').style.display = 'none';
            }

            function confirmDelete() {
                const confirmText = document.getElementById('deleteConfirmText').value;
                const password = document.getElementById('deletePassword').value;
                const confirmed = document.getElementById('deleteConfirmCheck').checked;

                if (confirmText !== 'DELETE') {
                    alert('Please type DELETE to confirm');
                    return;
                }

                if (!password) {
                    alert('Please enter your password');
                    return;
                }

                if (!confirmed) {
                    alert('Please confirm that you understand this is permanent');
                    return;
                }

                // Get OTP code
                const otpInputs = document.querySelectorAll('.delete-otp-input');
                const otpCode = Array.from(otpInputs).map(input => input.value).join('');

                if (otpCode.length !== 6) {
                    alert('Please enter the complete 6-digit code');
                    return;
                }

                // Demo alert
                if (confirm('Are you ABSOLUTELY sure? This cannot be undone!')) {
                    alert('Account deletion requested. You will receive a confirmation email. (This is a demo)');
                    closeDeleteModal();
                }
            }

            // Enable/disable delete button based on form completion
            const deleteTextInput = document.getElementById('deleteConfirmText');
            const deletePasswordInput = document.getElementById('deletePassword');
            const deleteCheckbox = document.getElementById('deleteConfirmCheck');
            const deleteBtn = document.getElementById('finalDeleteBtn');

            function checkDeleteForm() {
                const textValid = deleteTextInput?.value === 'DELETE';
                const passwordValid = deletePasswordInput?.value.length > 0;
                const checkboxValid = deleteCheckbox?.checked;

                if (deleteBtn) {
                    deleteBtn.disabled = !(textValid && passwordValid && checkboxValid);
                }
            }

            deleteTextInput?.addEventListener('input', checkDeleteForm);
            deletePasswordInput?.addEventListener('input', checkDeleteForm);
            deleteCheckbox?.addEventListener('change', checkDeleteForm);

            // OTP auto-advance for delete
            document.querySelectorAll('.delete-otp-input').forEach((input, index, inputs) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Auto-paste support
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                    pastedData.split('').forEach((char, i) => {
                        if (inputs[index + i]) {
                            inputs[index + i].value = char;
                        }
                    });
                });
            });

            // Close modals on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeHibernateModal();
                    closeDeleteModal();
                }
            });

            // Close modals on overlay click
            document.getElementById('hibernateModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'hibernateModal') {
                    closeHibernateModal();
                }
            });

            document.getElementById('deleteModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'deleteModal') {
                    closeDeleteModal();
                }
            });
        </script>
    @endpush
@endsection

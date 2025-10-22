@extends('tenant.settings.layout')

@section('settings-content')
<div class="settings-page-header">
    <h2 class="settings-page-title">Notifications</h2>
    <p class="settings-page-desc">Choose how and when you want to be notified about activity.</p>
</div>

<!-- Quick Actions -->
<div class="settings-card" style="background: linear-gradient(135deg, rgba(19, 81, 216, 0.05) 0%, rgba(19, 81, 216, 0.02) 100%);">
    <div class="settings-card-body" style="margin: 0;">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button class="settings-btn settings-btn-secondary" onclick="enableAll()">
                <i class="fas fa-check-double"></i> Enable All
            </button>
            <button class="settings-btn settings-btn-secondary" onclick="disableAll()">
                <i class="fas fa-times"></i> Disable All
            </button>
            <button class="settings-btn settings-btn-secondary" onclick="resetDefaults()">
                <i class="fas fa-undo"></i> Reset to Defaults
            </button>
        </div>
    </div>
</div>

<!-- Notification Preferences Matrix -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Notification Preferences</h3>
        <p class="settings-card-desc">Choose which notifications you receive and how</p>
    </div>
    <div class="settings-card-body">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border);">
                        <th style="text-align: left; padding: 16px 8px; font-size: var(--fs-body); font-weight: var(--fw-semibold); color: var(--text-heading); min-width: 200px;">
                            Notification Type
                        </th>
                        <th style="text-align: center; padding: 16px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted); width: 100px;">
                            <i class="fas fa-envelope" style="display: block; font-size: 18px; margin-bottom: 4px;"></i>
                            Email
                        </th>
                        <th style="text-align: center; padding: 16px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted); width: 100px;">
                            <i class="fas fa-mobile-alt" style="display: block; font-size: 18px; margin-bottom: 4px;"></i>
                            Push
                        </th>
                        <th style="text-align: center; padding: 16px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted); width: 100px;">
                            <i class="fas fa-bell" style="display: block; font-size: 18px; margin-bottom: 4px;"></i>
                            In-App
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Messages Section -->
                    <tr style="background: var(--bg);">
                        <td colspan="4" style="padding: 12px 8px; font-size: var(--fs-body); font-weight: var(--fw-bold); color: var(--text-heading);">
                            Messages
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">New messages</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">When someone sends you a message</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="messages" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="messages" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="messages" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>

                    <!-- Orders Section -->
                    <tr style="background: var(--bg);">
                        <td colspan="4" style="padding: 12px 8px; font-size: var(--fs-body); font-weight: var(--fw-bold); color: var(--text-heading);">
                            Orders & Projects
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">New orders</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">When you receive a new order</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="orders" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="orders" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="orders" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Order updates</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Status changes, comments, and milestones</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="orders" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="orders" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="orders" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>

                    <!-- Projects Section -->
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Project mentions</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">When someone mentions you in a project</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="projects" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="projects" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="projects" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>

                    <!-- Invoices Section -->
                    <tr style="background: var(--bg);">
                        <td colspan="4" style="padding: 12px 8px; font-size: var(--fs-body); font-weight: var(--fw-bold); color: var(--text-heading);">
                            Billing & Invoices
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Payment received</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">When a client pays an invoice</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="invoices" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="invoices" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="invoices" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Invoices overdue</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Reminders for unpaid invoices</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="invoices" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="invoices" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="invoices" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>

                    <!-- Security Section -->
                    <tr style="background: var(--bg);">
                        <td colspan="4" style="padding: 12px 8px; font-size: var(--fs-body); font-weight: var(--fw-bold); color: var(--text-heading);">
                            Security & Account
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Security alerts</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Suspicious activity and login attempts</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="security" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="security" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" checked data-type="security" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>

                    <!-- Marketing Section -->
                    <tr style="background: var(--bg);">
                        <td colspan="4" style="padding: 12px 8px; font-size: var(--fs-body); font-weight: var(--fw-bold); color: var(--text-heading);">
                            Marketing & Updates
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Product updates</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">New features and improvements</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 8px;">
                            <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">Tips & tricks</div>
                            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Helpful guides and best practices</div>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="email">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="push">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: center; padding: 16px 8px;">
                            <label class="toggle-switch" style="margin: 0 auto;">
                                <input type="checkbox" data-type="marketing" data-channel="in_app">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="settings-card-footer">
        <span class="settings-card-meta">Changes are saved automatically</span>
        <button class="settings-btn settings-btn-primary">
            <i class="fas fa-save"></i> Save Preferences
        </button>
    </div>
</div>

<!-- Digest Settings -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Email Digest</h3>
        <p class="settings-card-desc">Get a summary of your notifications in one email</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-form-group">
            <label class="settings-form-label">Digest frequency</label>
            <select class="settings-form-input">
                <option value="off">Off - Send individual emails</option>
                <option value="daily">Daily digest</option>
                <option value="weekly" selected>Weekly digest</option>
                <option value="monthly">Monthly digest</option>
            </select>
            <span class="settings-form-help">
                Receive a single email with all your notifications instead of individual emails
            </span>
        </div>

        <div class="settings-form-group">
            <label class="settings-form-label">Send digest on</label>
            <select class="settings-form-input">
                <option value="monday" selected>Monday</option>
                <option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option>
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
                <option value="sunday">Sunday</option>
            </select>
        </div>

        <div class="settings-form-group">
            <label class="settings-form-label">Time</label>
            <select class="settings-form-input">
                <option value="06:00">6:00 AM</option>
                <option value="08:00" selected>8:00 AM</option>
                <option value="10:00">10:00 AM</option>
                <option value="12:00">12:00 PM</option>
                <option value="14:00">2:00 PM</option>
                <option value="16:00">4:00 PM</option>
                <option value="18:00">6:00 PM</option>
                <option value="20:00">8:00 PM</option>
            </select>
        </div>
    </div>
    <div class="settings-card-footer">
        <span class="settings-card-meta">Next digest: Monday, Jan 27 at 8:00 AM</span>
        <button class="settings-btn settings-btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</div>

<!-- Mute All -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Do Not Disturb</h3>
        <p class="settings-card-desc">Temporarily mute all notifications</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-toggle">
            <div class="settings-toggle-info">
                <div class="settings-toggle-label">Enable Do Not Disturb</div>
                <div class="settings-toggle-desc">Mute all notifications for a specific period</div>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" id="dndToggle">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div id="dndOptions" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
            <div class="settings-form-group">
                <label class="settings-form-label">Mute until</label>
                <select class="settings-form-input">
                    <option value="1hour">1 hour</option>
                    <option value="4hours">4 hours</option>
                    <option value="tomorrow">Tomorrow morning</option>
                    <option value="weekend">This weekend</option>
                    <option value="week">1 week</option>
                    <option value="custom">Custom date/time</option>
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable/Disable all notifications
    function enableAll() {
        document.querySelectorAll('.toggle-switch input').forEach(input => {
            input.checked = true;
        });
        showToast('All notifications enabled');
    }

    function disableAll() {
        document.querySelectorAll('.toggle-switch input').forEach(input => {
            // Don't disable security notifications
            if (input.dataset.type !== 'security') {
                input.checked = false;
            }
        });
        showToast('All non-security notifications disabled');
    }

    function resetDefaults() {
        if (confirm('Reset all notification preferences to default values?')) {
            location.reload();
        }
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--accent);
            color: white;
            padding: 16px 24px;
            border-radius: 10px;
            box-shadow: var(--shadow-xl);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Do Not Disturb toggle
    document.getElementById('dndToggle')?.addEventListener('change', (e) => {
        const options = document.getElementById('dndOptions');
        if (e.target.checked) {
            options.style.display = 'block';
        } else {
            options.style.display = 'none';
        }
    });

    // Auto-save on toggle change
    document.querySelectorAll('.toggle-switch input').forEach(input => {
        input.addEventListener('change', () => {
            showToast('Preference updated');
        });
    });
</script>
@endpush
@endsection
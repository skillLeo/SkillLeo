## 9. Billing Settings (resources/views/tenant/settings/billing.blade.php)
```blade
@extends('tenant.settings.layout')

@section('settings-content')
    <div class="settings-page-header">
        <h2 class="settings-page-title">Billing & Subscription</h2>
        <p class="settings-page-desc">Manage your subscription, payment methods, and invoices.</p>
    </div>

    <!-- Current Plan -->
    <div class="settings-card"
        style="background: linear-gradient(135deg, rgba(19, 81, 216, 0.05) 0%, rgba(19, 81, 216, 0.02) 100%);">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Current Plan</h3>
            <p class="settings-card-desc">You're on the {{ $subscription['plan'] }}</p>
        </div>
        <div class="settings-card-body">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 8px;">
                        <span
                            style="font-size: 36px; font-weight: var(--fw-bold); color: var(--text-heading);">${{ $subscription['price'] }}</span>
                        <span style="font-size: var(--fs-body); color: var(--text-muted);">/
                            {{ $subscription['interval'] }}</span>
                    </div>
                    <div style="font-size: var(--fs-body); color: var(--text-muted); margin-bottom: 4px;">
                        Next billing date: <strong
                            style="color: var(--text-heading);">{{ \Carbon\Carbon::parse($subscription['next_billing_date'])->format('F j, Y') }}</strong>
                    </div>
                    <div
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 6px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold);">
                        <i class="fas fa-check-circle"></i>
                        {{ ucfirst($subscription['status']) }}
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="settings-btn settings-btn-primary">
                        <i class="fas fa-arrow-up"></i> Upgrade Plan
                    </button>
                    <button class="settings-btn settings-btn-secondary">
                        <i class="fas fa-edit"></i> Change Plan
                    </button>
                </div>
            </div>

            <!-- Plan Features -->
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 16px;">
                    Your plan includes:
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Unlimited projects</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Advanced analytics</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Priority support</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">AI features</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Custom domain</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check" style="color: #10b981;"></i>
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Team collaboration</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-card-footer">
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View all plans →
            </a>
            <button class="settings-btn settings-btn-danger">
                Cancel Subscription
            </button>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Payment Methods</h3>
            <p class="settings-card-desc">Manage your saved payment methods</p>
        </div>
        <div class="settings-card-body">
            @if (count($paymentMethods) > 0)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach ($paymentMethods as $method)
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: var(--bg); border-radius: 10px; border: {{ $method['is_default'] ? '2px solid var(--accent)' : '1px solid var(--border)' }};">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div
                                    style="width: 56px; height: 40px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border);">
                                    @if ($method['brand'] === 'Visa')
                                        <i class="fab fa-cc-visa" style="font-size: 32px; color: #1434CB;"></i>
                                    @elseif($method['brand'] === 'Mastercard')
                                        <i class="fab fa-cc-mastercard" style="font-size: 32px; color: #EB001B;"></i>
                                    @else
                                        <i class="fas fa-credit-card"
                                            style="font-size: 24px; color: var(--text-muted);"></i>
                                    @endif
                                </div>
                                <div>
                                    <div
                                        style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">
                                        {{ $method['brand'] }} •••• {{ $method['last4'] }}
                                    </div>
                                    <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                                        Expires {{ $method['exp_month'] }}/{{ $method['exp_year'] }}
                                    </div>
                                </div>
                                @if ($method['is_default'])
                                    <span
                                        style="padding: 4px 10px; background: var(--accent-light); color: var(--accent); border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold);">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <div style="display: flex; gap: 8px;">
                                @if (!$method['is_default'])
                                    <button class="settings-btn settings-btn-secondary"
                                        style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                        Set as Default
                                    </button>
                                @endif
                                <button class="settings-btn settings-btn-danger"
                                    style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <button class="settings-btn settings-btn-primary" style="margin-top: 16px;">
                <i class="fas fa-plus-circle"></i> Add Payment Method
            </button>
        </div>
    </div>

    <!-- Billing History / Invoices -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Billing History</h3>
            <p class="settings-card-desc">View and download your invoices</p>
        </div>
        <div class="settings-card-body">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Date</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Invoice</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Amount</th>
                            <th
                                style="text-align: left; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Status</th>
                            <th
                                style="text-align: right; padding: 12px 8px; font-size: var(--fs-subtle); font-weight: var(--fw-semibold); color: var(--text-muted);">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 16px 8px; font-size: var(--fs-body); color: var(--text-body);">
                                    {{ \Carbon\Carbon::parse($invoice['date'])->format('M j, Y') }}
                                </td>
                                <td
                                    style="padding: 16px 8px; font-size: var(--fs-body); color: var(--text-body); font-family: monospace;">
                                    {{ $invoice['number'] }}
                                </td>
                                <td
                                    style="padding: 16px 8px; font-size: var(--fs-body); color: var(--text-heading); font-weight: var(--fw-semibold);">
                                    ${{ number_format($invoice['amount'], 2) }}
                                </td>
                                <td style="padding: 16px 8px;">
                                    <span
                                        style="padding: 4px 10px; background: {{ $invoice['status'] === 'paid' ? '#d1fae5' : '#fee2e2' }}; color: {{ $invoice['status'] === 'paid' ? '#065f46' : '#991b1b' }}; border-radius: 6px; font-size: var(--fs-micro); font-weight: var(--fw-semibold); text-transform: capitalize;">
                                        {{ $invoice['status'] }}
                                    </span>
                                </td>
                                <td style="padding: 16px 8px; text-align: right;">
                                    <button class="settings-btn settings-btn-secondary"
                                        style="padding: 6px 12px; font-size: var(--fs-subtle);">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">Showing last {{ count($invoices) }} invoices</span>
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View all invoices →
            </a>
        </div>
    </div>

    <!-- Billing Information -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Billing Information</h3>
            <p class="settings-card-desc">Update your billing details and tax information</p>
        </div>
        <div class="settings-card-body">
            <form>
                <div class="settings-form-group">
                    <label class="settings-form-label">Company Name (Optional)</label>
                    <input type="text" class="settings-form-input" placeholder="Your Company LLC">
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Billing Email</label>
                    <input type="email" class="settings-form-input" value="{{ $user->email }}"
                        placeholder="billing@example.com">
                    <span class="settings-form-help">Invoices will be sent to this email</span>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Tax ID / VAT Number (Optional)</label>
                    <input type="text" class="settings-form-input" placeholder="EU123456789">
                </div>

                <div class="settings-form-row">
                    <div class="settings-form-group">
                        <label class="settings-form-label">Country</label>
                        <select class="settings-form-input">
                            <option value="PK" selected>Pakistan</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="AE">United Arab Emirates</option>
                        </select>
                    </div>
                    <div class="settings-form-group">
                        <label class="settings-form-label">Postal Code</label>
                        <input type="text" class="settings-form-input" placeholder="40100">
                    </div>
                </div>

                <div class="settings-form-group">
                    <label class="settings-form-label">Billing Address</label>
                    <textarea class="settings-form-input" rows="3" placeholder="Street address, city, state"></textarea>
                </div>
            </form>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta"></span>
            <button class="settings-btn settings-btn-primary">
                <i class="fas fa-save"></i> Save Billing Info
            </button>
        </div>
    </div>

@endsection
```

## 10. Data & Apps Settings (resources/views/tenant/settings/data.blade.php)
```blade
@extends('tenant.settings.layout')

@section('settings-content')
    <div class="settings-page-header">
        <h2 class="settings-page-title">Data & Apps</h2>
        <p class="settings-page-desc">Manage your data, connected applications, and integrations.</p>
    </div>

    <!-- Export Data -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Export Your Data</h3>
            <p class="settings-card-desc">Download a copy of your SkillLeo data</p>
        </div>
        <div class="settings-card-body">
            <div style="background: var(--bg); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div
                        style="width: 48px; height: 48px; background: var(--accent-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-download" style="color: var(--accent); font-size: 20px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 8px;">
                            What's included in your export?
                        </div>
                        <ul
                            style="list-style: none; padding: 0; margin: 0; font-size: var(--fs-subtle); color: var(--text-body); line-height: 1.8;">
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Profile
                                information and settings</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Projects and
                                portfolio items</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Skills,
                                experience, and education</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Messages and
                                conversations</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Orders and
                                invoices</li>
                            <li><i class="fas fa-check" style="color: #10b981; margin-right: 8px;"></i> Activity logs and
                                analytics data</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div
                style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <div style="display: flex; gap: 12px;">
                    <i class="fas fa-info-circle" style="color: #f59e0b; font-size: 18px;"></i>
                    <div style="font-size: var(--fs-subtle); color: #78350f; line-height: 1.5;">
                        <strong>Note:</strong> Data export may take up to 24 hours to process. We'll email you a download
                        link when it's ready. The link will expire after 7 days.
                    </div>
                </div>
            </div><button class="settings-btn settings-btn-primary">
                <i class="fas fa-file-export"></i> Request Data Export
            </button>
        </div>
    </div><!-- Connected Apps -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Connected Applications</h3>
            <p class="settings-card-desc">Manage third-party apps connected to your account</p>
        </div>
        <div class="settings-card-body">
            @if (count($connectedApps) > 0)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach ($connectedApps as $app)
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: var(--bg); border-radius: 10px;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div
                                    style="width: 56px; height: 56px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); flex-shrink: 0;">
                                    <i class="{{ $app['icon'] }}" style="font-size: 28px;"></i>
                                </div>
                                <div>
                                    <div
                                        style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">
                                        {{ $app['name'] }}
                                    </div>
                                    <div
                                        style="font-size: var(--fs-subtle); color: var(--text-muted); margin-bottom: 6px;">
                                        Connected {{ \Carbon\Carbon::parse($app['connected_at'])->diffForHumans() }}
                                    </div>
                                    <div style="font-size: var(--fs-micro); color: var(--text-muted);">
                                        <i class="fas fa-lock"></i> {{ $app['permissions'] }}
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button class="settings-btn settings-btn-secondary"
                                    style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                    <i class="fas fa-cog"></i> Manage
                                </button>
                                <button class="settings-btn settings-btn-danger"
                                    style="padding: 8px 16px; font-size: var(--fs-subtle);">
                                    <i class="fas fa-unlink"></i> Disconnect
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px;">
                    <div
                        style="width: 64px; height: 64px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fas fa-plug" style="font-size: 28px; color: var(--text-muted);"></i>
                    </div>
                    <h4
                        style="font-size: 16px; font-weight: var(--fw-semibold); color: var(--text-heading); margin: 0 0 8px 0;">
                        No connected apps
                    </h4>
                    <p style="font-size: var(--fs-subtle); color: var(--text-muted); margin: 0;">
                        Connect third-party applications to enhance your workflow
                    </p>
                </div>
            @endif
        </div>
        <div class="settings-card-footer">
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                Browse available integrations →
            </a>
        </div>
    </div><!-- Activity Log -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Activity Log</h3>
            <p class="settings-card-desc">View your recent account activity</p>
        </div>
        <div class="settings-card-body">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- Activity Item -->
                <div style="display: flex; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 40px; height: 40px; background: var(--accent-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-shield-alt" style="color: var(--accent); font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">
                            Password changed
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            January 20, 2025 at 3:45 PM • Chrome on Windows
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 40px; height: 40px; background: rgba(0, 0, 0, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-sign-in-alt" style="color: var(--text-muted); font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">
                            Signed in
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            January 19, 2025 at 10:30 AM • Safari on iPhone
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 40px; height: 40px; background: rgba(0, 0, 0, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-file-export" style="color: var(--text-muted); font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">
                            Data export completed
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            January 15, 2025 at 2:15 PM
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 16px; padding: 16px; background: var(--bg); border-radius: 10px;">
                    <div
                        style="width: 40px; height: 40px; background: rgba(0, 0, 0, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-user-edit" style="color: var(--text-muted); font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: var(--fw-medium); color: var(--text-heading); margin-bottom: 4px;">
                            Profile updated
                        </div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">
                            January 12, 2025 at 5:20 PM • Chrome on Windows
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-card-footer">
            <span class="settings-card-meta">Showing last 10 activities</span>
            <button class="settings-btn settings-btn-secondary">
                <i class="fas fa-download"></i> Download Full Log
            </button>
        </div>
    </div><!-- API Keys (if applicable) -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">API Access</h3>
            <p class="settings-card-desc">Generate API keys for programmatic access</p>
        </div>
        <div class="settings-card-body">
            <div style="background: var(--bg); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="display: flex; gap: 16px;">
                    <div
                        style="width: 48px; height: 48px; background: var(--accent-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-code" style="color: var(--accent); font-size: 20px;"></i>
                    </div>
                    <div>
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 8px;">
                            Build with SkillLeo API
                        </div>
                        <p style="margin: 0; font-size: var(--fs-subtle); color: var(--text-muted); line-height: 1.5;">
                            Create API keys to access your SkillLeo data programmatically. Perfect for custom integrations
                            and automation.
                        </p>
                    </div>
                </div>
            </div>
            <div style="text-align: center; padding: 40px 20px;">
                <div
                    style="width: 64px; height: 64px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-key" style="font-size: 28px; color: var(--text-muted);"></i>
                </div>
                <h4
                    style="font-size: 16px; font-weight: var(--fw-semibold); color: var(--text-heading); margin: 0 0 8px 0;">
                    No API keys created
                </h4>
                <p style="font-size: var(--fs-subtle); color: var(--text-muted); margin: 0 0 20px 0;">
                    Generate your first API key to get started
                </p>
                <button class="settings-btn settings-btn-primary">
                    <i class="fas fa-plus-circle"></i> Generate API Key
                </button>
            </div>
        </div>
        <div class="settings-card-footer">
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View API documentation →
            </a>
        </div>
    </div>
@endsection

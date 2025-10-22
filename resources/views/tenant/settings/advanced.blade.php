
@extends('tenant.settings.layout')

@section('settings-content')
    <div class="settings-page-header">
        <h2 class="settings-page-title">Advanced Settings</h2>
        <p class="settings-page-desc">Configure advanced features and experimental options.</p>
    </div>

    <!-- Beta Features -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Beta Features</h3>
            <p class="settings-card-desc">Try out new features before they're released to everyone</p>
        </div>
        <div class="settings-card-body">
            <div
                style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                <div style="display: flex; gap: 12px;">
                    <i class="fas fa-flask" style="color: #3b82f6; font-size: 18px;"></i>
                    <div style="font-size: var(--fs-subtle); color: #1e40af; line-height: 1.5;">
                        <strong>About Beta Features:</strong> These features are still in development and may be unstable.
                        Your feedback helps us improve!
                    </div>
                </div>
            </div>

            <div class="settings-toggle">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Enable all beta features</div>
                    <div class="settings-toggle-desc">Get access to all experimental features</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" {{ $betaFeaturesEnabled ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 16px;">
                    Individual Beta Features:
                </div>

                <div class="settings-toggle">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">AI-powered search</div>
                        <div class="settings-toggle-desc">Semantic search across your projects and data</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle"
                    style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Advanced analytics dashboard</div>
                        <div class="settings-toggle-desc">Detailed insights and custom reports</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle"
                    style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Voice commands</div>
                        <div class="settings-toggle-desc">Control SkillLeo with voice (experimental)</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle"
                    style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Collaborative editing</div>
                        <div class="settings-toggle-desc">Real-time collaboration on projects</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Mode -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Developer Mode</h3>
            <p class="settings-card-desc">Advanced options for developers and power users</p>
        </div>
        <div class="settings-card-body">
            <div class="settings-toggle">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Enable developer mode</div>
                    <div class="settings-toggle-desc">Show technical information and debugging tools</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" {{ $developerModeEnabled ? 'checked' : '' }} id="devModeToggle">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div id="devModeOptions"
                style="display: {{ $developerModeEnabled ? 'block' : 'none' }}; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                <div class="settings-toggle">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Show request IDs</div>
                        <div class="settings-toggle-desc">Display request IDs in the console</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle"
                    style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Verbose logging</div>
                        <div class="settings-toggle-desc">Enable detailed console logs</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle"
                    style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                    <div class="settings-toggle-info">
                        <div class="settings-toggle-label">Performance metrics</div>
                        <div class="settings-toggle-desc">Show page load times and API response times</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Keyboard Shortcuts</h3>
            <p class="settings-card-desc">Customize keyboard shortcuts for faster navigation</p>
        </div>
        <div class="settings-card-body">
            <div class="settings-toggle">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Enable keyboard shortcuts</div>
                    <div class="settings-toggle-desc">Use keyboard commands to navigate SkillLeo</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 16px;">
                    Available Shortcuts:
                </div>

                <div style="display: grid; gap: 12px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--bg); border-radius: 8px;">
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Open search</span>
                        <kbd
                            style="padding: 4px 10px; background: var(--card); border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: var(--fw-semibold);">/</kbd>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--bg); border-radius: 8px;">
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Open keyboard shortcuts</span>
                        <kbd
                            style="padding: 4px 10px; background: var(--card); border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: var(--fw-semibold);">?</kbd>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--bg); border-radius: 8px;">
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Go to dashboard</span>
                        <kbd
                            style="padding: 4px 10px; background: var(--card); border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: var(--fw-semibold);">G
                            + D</kbd>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--bg); border-radius: 8px;">
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Go to projects</span>
                        <kbd
                            style="padding: 4px 10px; background: var(--card); border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: var(--fw-semibold);">G
                            + P</kbd>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--bg); border-radius: 8px;">
                        <span style="font-size: var(--fs-body); color: var(--text-body);">Go to settings</span>
                        <kbd
                            style="padding: 4px 10px; background: var(--card); border: 1px solid var(--border); border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: var(--fw-semibold);">G
                            + S</kbd>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-card-footer">
            <a href="#"
                style="color: var(--accent); font-size: var(--fs-body); font-weight: var(--fw-medium); text-decoration: none;">
                View all shortcuts →
            </a>
        </div>
    </div>

    <!-- Labs -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">SkillLeo Labs</h3>
            <p class="settings-card-desc">Experimental features from our research team</p>
        </div>
        <div class="settings-card-body">
            <div style="text-align: center; padding: 40px 20px;">
                <div
                    style="width: 64px; height: 64px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-microscope" style="font-size: 28px; color: var(--text-muted);"></i>
                </div>
                <h4
                    style="font-size: 16px; font-weight: var(--fw-semibold); color: var(--text-heading); margin: 0 0 8px 0;">
                    No lab features available
                </h4>
                <p style="font-size: var(--fs-subtle); color: var(--text-muted); margin: 0;">
                    Check back later for cutting-edge experimental features
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Developer mode toggle
            document.getElementById('devModeToggle')?.addEventListener('change', (e) => {
                const options = document.getElementById('devModeOptions');
                if (e.target.checked) {
                    options.style.display = 'block';
                } else {
                    options.style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection

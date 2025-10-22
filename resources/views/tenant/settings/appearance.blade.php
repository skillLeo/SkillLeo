@extends('tenant.settings.layout')

@section('settings-content')
<div class="settings-page-header">
    <h2 class="settings-page-title">Appearance & Accessibility</h2>
    <p class="settings-page-desc">Customize how SkillLeo looks and feels for you.</p>
</div>

<!-- Theme -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Theme</h3>
        <p class="settings-card-desc">Choose your preferred color scheme</p>
    </div>
    <div class="settings-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px;">
            <!-- System Theme -->
            <label class="theme-option {{ $currentTheme === 'system' ? 'active' : '' }}" style="position: relative; cursor: pointer;">
                <input type="radio" name="theme" value="system" {{ $currentTheme === 'system' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                <div style="border: 2px solid var(--border); border-radius: 12px; padding: 16px; transition: all 0.2s; background: var(--card);">
                    <div style="width: 100%; height: 80px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f3f4f6 50%, #ffffff 100%); margin-bottom: 12px;"></div>
                    <div style="text-align: center;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">System</div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Auto</div>
                    </div>
                </div>
            </label>

            <!-- Light Theme -->
            <label class="theme-option {{ $currentTheme === 'light' ? 'active' : '' }}" style="position: relative; cursor: pointer;">
                <input type="radio" name="theme" value="light" {{ $currentTheme === 'light' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                <div style="border: 2px solid var(--border); border-radius: 12px; padding: 16px; transition: all 0.2s; background: var(--card);">
                    <div style="width: 100%; height: 80px; border-radius: 8px; background: linear-gradient(to bottom, #ffffff 0%, #f3f4f6 100%); margin-bottom: 12px; border: 1px solid #e5e7eb;"></div>
                    <div style="text-align: center;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">Light</div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Default</div>
                    </div>
                </div>
            </label>

            <!-- Dark Theme -->
            <label class="theme-option {{ $currentTheme === 'dark' ? 'active' : '' }}" style="position: relative; cursor: pointer;">
                <input type="radio" name="theme" value="dark" {{ $currentTheme === 'dark' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                <div style="border: 2px solid var(--border); border-radius: 12px; padding: 16px; transition: all 0.2s; background: var(--card);">
                    <div style="width: 100%; height: 80px; border-radius: 8px; background: linear-gradient(to bottom, #1f2937 0%, #111827 100%); margin-bottom: 12px;"></div>
                    <div style="text-align: center;">
                        <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">Dark</div>
                        <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Eye-friendly</div>
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>

<!-- Density -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Density</h3>
        <p class="settings-card-desc">Adjust spacing and size of interface elements</p>
    </div>
    <div class="settings-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <!-- Cozy -->
            <label class="density-option {{ $currentDensity === 'cozy' ? 'active' : '' }}" style="position: relative; cursor: pointer;">
                <input type="radio" name="density" value="cozy" {{ $currentDensity === 'cozy' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                <div style="border: 2px solid var(--border); border-radius: 12px; padding: 20px; transition: all 0.2s; background: var(--card);">
                    <div style="margin-bottom: 12px;">
                        <div style="height: 12px; background: var(--bg); border-radius: 4px; margin-bottom: 10px;"></div>
                        <div style="height: 12px; background: var(--bg); border-radius: 4px; margin-bottom: 10px;"></div>
                        <div style="height: 12px; background: var(--bg); border-radius: 4px;"></div>
</div>
<div style="text-align: center;">
<div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">Cozy</div>
<div style="font-size: var(--fs-subtle); color: var(--text-muted);">More spacing (Recommended)</div>
</div>
</div>
</label><!-- Compact -->
<label class="density-option {{ $currentDensity === 'compact' ? 'active' : '' }}" style="position: relative; cursor: pointer;">
    <input type="radio" name="density" value="compact" {{ $currentDensity === 'compact' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
    <div style="border: 2px solid var(--border); border-radius: 12px; padding: 20px; transition: all 0.2s; background: var(--card);">
        <div style="margin-bottom: 12px;">
            <div style="height: 8px; background: var(--bg); border-radius: 4px; margin-bottom: 6px;"></div>
            <div style="height: 8px; background: var(--bg); border-radius: 4px; margin-bottom: 6px;"></div>
            <div style="height: 8px; background: var(--bg); border-radius: 4px; margin-bottom: 6px;"></div>
            <div style="height: 8px; background: var(--bg); border-radius: 4px;"></div>
        </div>
        <div style="text-align: center;">
            <div style="font-weight: var(--fw-semibold); color: var(--text-heading); margin-bottom: 4px;">Compact</div>
            <div style="font-size: var(--fs-subtle); color: var(--text-muted);">Less spacing</div>
        </div>
    </div>
</label>
</div>
</div></div><!-- Font Size -->
<div class="settings-card">
    <div class="settings-card-header">
        <h3 class="settings-card-title">Font Size</h3>
        <p class="settings-card-desc">Adjust text size for better readability</p>
    </div>
    <div class="settings-card-body">
        <div class="settings-form-group">
            <label class="settings-form-label">Text size</label>
            <div style="display: flex; gap: 16px; align-items: center;">
                <span style="font-size: 12px; color: var(--text-muted);">Aa</span>
                <input type="range" 
                       min="1" 
                       max="5" 
                       value="3" 
                       id="fontSizeSlider"
                       style="flex: 1; height: 6px; border-radius: 3px; background: var(--bg); outline: none; -webkit-appearance: none;">
                <span style="font-size: 18px; color: var(--text-muted);">Aa</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: var(--fs-subtle); color: var(--text-muted);">
                <span>Small</span>
                <span>Medium</span>
                <span>Large</span>
            </div>
        </div><!-- Preview -->
        <div style="margin-top: 24px; padding: 20px; background: var(--bg); border-radius: 10px; border: 1px solid var(--border);">
            <div style="font-size: var(--fs-subtle); color: var(--text-muted); margin-bottom: 8px;">Preview:</div>
            <p id="fontPreview" style="margin: 0; line-height: 1.6; color: var(--text-body);">
                The quick brown fox jumps over the lazy dog. This is how your text will appear across SkillLeo with the selected font size.
            </p>
        </div>
    </div></div><!-- Accessibility -->
    <div class="settings-card">
        <div class="settings-card-header">
            <h3 class="settings-card-title">Accessibility</h3>
            <p class="settings-card-desc">Options to improve your experience</p>
        </div>
        <div class="settings-card-body">
            <div class="settings-toggle">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Reduce motion</div>
                    <div class="settings-toggle-desc">Minimize animations and transitions</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" {{ $reduceMotion ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div><div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">High contrast</div>
                    <div class="settings-toggle-desc">Increase contrast for better visibility</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="toggle-slider"></span>
                </label>
            </div><div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Focus indicators</div>
                    <div class="settings-toggle-desc">Show enhanced keyboard navigation indicators</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div><div class="settings-toggle" style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <div class="settings-toggle-info">
                    <div class="settings-toggle-label">Screen reader optimizations</div>
                    <div class="settings-toggle-desc">Improve compatibility with screen readers</div>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div></div><!-- Localization -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">Localization</h3>
                <p class="settings-card-desc">Set your region, language, and formats</p>
            </div>
            <div class="settings-card-body">
                <div class="settings-form-group">
                    <label class="settings-form-label">Language</label>
                    <select class="settings-form-input">
                        <option value="en" selected>English (United States)</option>
                        <option value="en-gb">English (United Kingdom)</option>
                        <option value="ur">اردو (Urdu)</option>
                        <option value="es">Español (Spanish)</option>
                        <option value="fr">Français (French)</option>
                        <option value="de">Deutsch (German)</option>
                        <option value="ar">العربية (Arabic)</option>
                    </select>
                </div><div class="settings-form-group">
                    <label class="settings-form-label">Timezone</label>
                    <select class="settings-form-input">
                        <option value="Asia/Karachi" selected>Pakistan Standard Time (PKT)</option>
                        <option value="UTC">Coordinated Universal Time (UTC)</option>
                        <option value="America/New_York">Eastern Time (ET)</option>
                        <option value="America/Los_Angeles">Pacific Time (PT)</option>
                        <option value="Europe/London">British Time (GMT/BST)</option>
                        <option value="Asia/Dubai">Gulf Standard Time (GST)</option>
                    </select>
                    <span class="settings-form-help">Current time: {{ now()->format('h:i A, F j, Y') }}</span>
                </div><div class="settings-form-group">
                    <label class="settings-form-label">Date format</label>
                    <select class="settings-form-input">
                        <option value="mdy" selected>MM/DD/YYYY (01/21/2025)</option>
                        <option value="dmy">DD/MM/YYYY (21/01/2025)</option>
                        <option value="ymd">YYYY-MM-DD (2025-01-21)</option>
                        <option value="long">January 21, 2025</option>
                    </select>
                </div><div class="settings-form-group">
                    <label class="settings-form-label">Time format</label>
                    <select class="settings-form-input">
                        <option value="12" selected>12-hour (3:30 PM)</option>
                        <option value="24">24-hour (15:30)</option>
                    </select>
                </div><div class="settings-form-group">
                    <label class="settings-form-label">First day of week</label>
                    <select class="settings-form-input">
                        <option value="sunday" selected>Sunday</option>
                        <option value="monday">Monday</option>
                        <option value="saturday">Saturday</option>
                    </select>
                </div><div class="settings-form-group">
                    <label class="settings-form-label">Number format</label>
                    <select class="settings-form-input">
                        <option value="1234.56" selected>1,234.56 (US format)</option>
                        <option value="1234,56">1.234,56 (European format)</option>
                        <option value="1 234.56">1 234.56 (Space separator)</option>
                    </select>
                </div>
            </div>
            <div class="settings-card-footer">
                <span class="settings-card-meta"></span>
                <button class="settings-btn settings-btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div></div> 
@push('scripts')
<script>
    // Theme selection
    document.querySelectorAll('input[name="theme"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
            e.target.closest('.theme-option').classList.add('active');
            
            // Apply theme (demo)
            const theme = e.target.value;
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
            }
            
            showToast(`Theme changed to ${theme}`);
        });
    });

    // Density selection
    document.querySelectorAll('input[name="density"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.querySelectorAll('.density-option').forEach(opt => opt.classList.remove('active'));
            e.target.closest('.density-option').classList.add('active');
            showToast(`Density changed to ${e.target.value}`);
        });
    });

    // Font size slider
    const fontSlider = document.getElementById('fontSizeSlider');
    const fontPreview = document.getElementById('fontPreview');
    const fontSizes = ['12px', '14px', '16px', '18px', '20px'];

    fontSlider?.addEventListener('input', (e) => {
        const size = fontSizes[e.target.value - 1];
        fontPreview.style.fontSize = size;
    });

    // Active state styling
    const style = document.createElement('style');
    style.textContent = `
        .theme-option.active > div,
        .density-option.active > div {
            border-color: var(--accent) !important;
            background: var(--accent-light) !important;
        }
        .theme-option:hover > div,
        .density-option:hover > div {
            border-color: rgba(19, 81, 216, 0.5) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(19, 81, 216, 0.4);
        }
        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 6px rgba(19, 81, 216, 0.4);
        }
    `;
    document.head.appendChild(style);

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
</script>
@endpush
@endsection
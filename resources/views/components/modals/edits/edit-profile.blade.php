@props(['user' => null])

<x-modals.edits.base-modal id="editProfileModal" title="Edit Profile" size="lg">
    <form id="profileForm" method="POST" action="{{ route('tenant.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Profile Photo --}}
        <div class="modal-section photo-section">
            <div class="photo-upload-wrap">
                <div class="photo-preview-large" id="photoPreviewLarge">
                    @if(($user->avatar ?? false) || ($user->avatar_url ?? false))
                        <img src="{{ $user->avatar ?? $user->avatar_url }}" alt="{{ $user->name ?? 'User' }}">
                    @else
                        <i class="fa-solid fa-user" style="font-size: 40px; color: var(--text-muted);"></i>
                    @endif
                </div>
                <div class="photo-actions">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" hidden>
                    <button type="button" class="btn-photo-upload" onclick="document.getElementById('avatarInput').click()">
                        <i class="fa-solid fa-camera"></i>
                        Upload Photo
                    </button>
                    @if(($user->avatar ?? false) || ($user->avatar_url ?? false))
                        <button type="button" class="btn-photo-remove" onclick="removeAvatar()">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif
                </div>
                <p class="photo-hint">JPG or PNG. Max 5MB.</p>
            </div>
        </div>

        {{-- Basic Info --}}
        <div class="modal-section">
            <h3 class="section-title">Basic Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" class="form-input" value="{{ $user->first_name ?? '' }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-input" value="{{ $user->last_name ?? '' }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Headline</label>
                <textarea name="headline" class="form-textarea" rows="2" maxlength="120" placeholder="e.g., Full Stack Developer | Laravel Expert">{{ $user->headline ?? '' }}</textarea>
                <div class="char-count"><span id="headlineCount">0</span> / 120</div>
            </div>

            <div class="form-group">
                <label class="form-label">About</label>
                <textarea name="about" class="form-textarea" rows="6" maxlength="2000" placeholder="Tell us about yourself...">{{ $user->about ?? $user->bio ?? '' }}</textarea>
                <div class="char-count"><span id="aboutCount">0</span> / 2000</div>
            </div>
        </div>

        {{-- Location & Contact --}}
        <div class="modal-section">
            <h3 class="section-title">Location & Contact</h3>
            
            <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-input" value="{{ $user->location ?? '' }}" placeholder="City, Country">
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-input" value="{{ $user->email ?? '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-input" value="{{ $user->phone ?? '' }}" placeholder="+1 (555) 000-0000">
            </div>
        </div>

        {{-- Social Links --}}
        <div class="modal-section">
            <h3 class="section-title">Social Links</h3>
            
            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-linkedin"></i> LinkedIn</label>
                <input type="url" name="linkedin" class="form-input" value="{{ $user->linkedin ?? '' }}" placeholder="https://linkedin.com/in/yourprofile">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-x-twitter"></i> Twitter</label>
                <input type="url" name="twitter" class="form-input" value="{{ $user->twitter ?? '' }}" placeholder="https://twitter.com/yourhandle">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-facebook"></i> Facebook</label>
                <input type="url" name="facebook" class="form-input" value="{{ $user->facebook ?? '' }}" placeholder="https://facebook.com/yourprofile">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-brands fa-instagram"></i> Instagram</label>
                <input type="url" name="instagram" class="form-input" value="{{ $user->instagram ?? '' }}" placeholder="https://instagram.com/yourhandle">
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" class="btn-modal btn-cancel" onclick="closeModal('editProfileModal')">Cancel</button>
        <button type="submit" form="profileForm" class="btn-modal btn-save">Save Changes</button>
    </x-slot:footer>
</x-modals.edits.base-modal>

<style>
.modal-section {
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--border);
}

.modal-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-heading);
    margin-bottom: 16px;
}

.photo-section {
    background: var(--apc-bg);
    padding: 20px;
    border-radius: 8px;
    border-bottom: none;
    margin-bottom: 24px;
}

.photo-upload-wrap {
    display: flex;
    align-items: center;
    gap: 20px;
}

.photo-preview-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--card);
    border: 3px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.photo-preview-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-photo-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-photo-upload:hover {
    background: var(--accent-dark);
    transform: translateY(-1px);
}

.btn-photo-remove {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: #dc2626;
    border: 1px solid #dc2626;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-photo-remove:hover {
    background: #dc2626;
    color: white;
}

.photo-hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-body);
    margin-bottom: 6px;
}

.form-label i {
    margin-right: 6px;
    color: var(--text-muted);
}

.required {
    color: #dc2626;
    margin-left: 2px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--input-border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    background: var(--input-bg);
    color: var(--input-text);
    transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
    line-height: 1.6;
}

.char-count {
    text-align: right;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

@media (max-width: 640px) {
    .photo-upload-wrap {
        flex-direction: column;
        text-align: center;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Avatar preview
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photoPreviewLarge');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});

function removeAvatar() {
    const preview = document.getElementById('photoPreviewLarge');
    preview.innerHTML = '<i class="fa-solid fa-user" style="font-size: 40px; color: var(--text-muted);"></i>';
    document.getElementById('avatarInput').value = '';
}

// Character counters
document.querySelector('[name="headline"]')?.addEventListener('input', function(e) {
    document.getElementById('headlineCount').textContent = e.target.value.length;
});

document.querySelector('[name="about"]')?.addEventListener('input', function(e) {
    document.getElementById('aboutCount').textContent = e.target.value.length;
});

// Initialize counters
document.addEventListener('DOMContentLoaded', function() {
    const headline = document.querySelector('[name="headline"]');
    const about = document.querySelector('[name="about"]');
    
    if (headline) document.getElementById('headlineCount').textContent = headline.value.length;
    if (about) document.getElementById('aboutCount').textContent = about.value.length;
});
</script> 
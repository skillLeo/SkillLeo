@extends('layouts.onboarding')

@section('title', 'Welcome to ProMatch')

@section('card-content')
<div class="welcome-wrapper">
    <div class="welcome-content" id="mainContent">
        <x-onboarding.badge variant="primary">
            AI-Powered Setup
        </x-onboarding.badge>

        <h1 class="welcome-title">Build your <span class="gradient-text">professional profile</span></h1>
        <p class="welcome-subtitle">
            Upload your CV for instant AI setup, or build from scratch. Takes under 5 minutes.
        </p>

        <div id="errorAlert" class="error-alert hidden"></div>

        <div class="welcome-actions">
            <button type="button" class="btn btn-primary" id="uploadBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" 
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Upload CV
            </button>

            <form method="POST" action="{{ route('tenant.onboarding.scratch') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    Start from scratch
                </button>
            </form>
        </div>

        <div class="upload-drop" id="dropzone">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" 
                      stroke="currentColor" stroke-width="1.5"/>
                <path d="M14 2v6h6M12 18v-6M9 15l3 3 3-3" 
                      stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <div>
                <strong>Drop your CV here</strong>
                <span>PDF, DOC, DOCX • up to 8MB</span>
            </div>
        </div>

        <a href="{{ route('tenant.onboarding.personal') }}" class="link-skip">
            Skip for now
        </a>
    </div>

    {{-- Minimal Professional Loader --}}
    <div class="loader-overlay hidden" id="loaderOverlay">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <h3 class="loader-title">Processing your CV</h3>
            <p class="loader-text">This may take 30-60 seconds</p>
            <div class="loader-progress">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form --}}
<form id="uploadForm" action="{{ route('tenant.onboarding.cv.upload.json') }}" method="POST" enctype="multipart/form-data" hidden>
    @csrf
    <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx">
</form>
@endsection

@push('styles')
<style>
.welcome-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
}

.welcome-content {
    max-width: 520px;
    width: 100%;
    text-align: center;
    transition: opacity 0.3s ease;
}

.welcome-content.loading {
    opacity: 0;
    pointer-events: none;
}

.welcome-title {
    font-size: 2.75rem;
    font-weight: var(--fw-extrabold);
    color: var(--text-heading);
    line-height: 1.15;
    margin-bottom: var(--space-md);
    letter-spacing: -0.04em;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-subtitle {
    font-size: 1.0625rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: var(--space-2xl);
}

.error-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 0.9375rem;
    font-weight: 600;
    margin-bottom: var(--space-lg);
    animation: slideDown 0.3s ease;
}

.error-alert.hidden {
    display: none;
}

.welcome-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    margin-bottom: var(--space-xl);
}

.welcome-actions .btn {
    padding: 15px 24px;
    min-height: 54px;
    font-size: 1rem;
    font-weight: var(--fw-semibold);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--text-body);
}

.btn-secondary:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.upload-drop {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-md);
    padding: var(--space-xl);
    border: 2px dashed var(--border);
    border-radius: 14px;
    background: var(--card);
    cursor: pointer;
    transition: all 0.25s ease;
    margin-bottom: var(--space-xl);
}

.upload-drop:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: translateY(-2px);
}

.upload-drop.dragover {
    border-color: #667eea;
    border-style: solid;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    transform: scale(1.02);
}

.upload-drop svg {
    color: var(--text-muted);
    flex-shrink: 0;
}

.upload-drop strong {
    display: block;
    font-size: 1.0625rem;
    font-weight: var(--fw-semibold);
    color: var(--text-body);
    margin-bottom: 4px;
}

.upload-drop span {
    display: block;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.link-skip {
    display: inline-block;
    color: var(--text-muted);
    font-size: 0.9375rem;
    text-decoration: none;
    transition: color 0.2s ease;
    font-weight: 500;
}

.link-skip:hover {
    color: #667eea;
}

/* Minimal Professional Loader */
.loader-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(8px);
    z-index: 100;
    animation: fadeIn 0.3s ease;
}

.loader-overlay.hidden {
    display: none;
}

.loader-content {
    text-align: center;
    max-width: 320px;
}

.loader-spinner {
    width: 56px;
    height: 56px;
    margin: 0 auto 24px;
    border: 3px solid #f3f4f6;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

.loader-title {
    font-size: 1.375rem;
    font-weight: var(--fw-bold);
    color: var(--text-heading);
    margin: 0 0 8px;
}

.loader-text {
    font-size: 0.9375rem;
    color: var(--text-muted);
    margin: 0 0 24px;
}

.loader-progress {
    width: 100%;
    height: 4px;
    background: #f3f4f6;
    border-radius: 999px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    border-radius: 999px;
    width: 0%;
    animation: progress 45s ease-out forwards;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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

@keyframes progress {
    0% { width: 0%; }
    50% { width: 60%; }
    90% { width: 85%; }
    100% { width: 95%; }
}

@media (prefers-color-scheme: dark) {
    .loader-overlay {
        background: rgba(15, 22, 46, 0.98);
    }
    .loader-spinner {
        border-color: #1e293b;
        border-top-color: #667eea;
    }
    .loader-progress {
        background: #1e293b;
    }
}

@media (max-width: 640px) {
    .welcome-title {
        font-size: 2rem;
    }

    .welcome-actions {
        grid-template-columns: 1fr;
    }

    .upload-drop {
        flex-direction: column;
        text-align: center;
        padding: var(--space-lg);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const uploadBtn = document.getElementById('uploadBtn');
    const dropzone = document.getElementById('dropzone');
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('fileInput');
    const mainContent = document.getElementById('mainContent');
    const loaderOverlay = document.getElementById('loaderOverlay');
    const progressBar = document.getElementById('progressBar');
    const errorAlert = document.getElementById('errorAlert');
    const UPLOAD_URL = form.action;

    const showError = (msg) => {
        errorAlert.textContent = msg;
        errorAlert.classList.remove('hidden');
        setTimeout(() => errorAlert.classList.add('hidden'), 5000);
    };

    const showLoader = (show) => {
        if (show) {
            mainContent.classList.add('loading');
            loaderOverlay.classList.remove('hidden');
            progressBar.style.animation = 'progress 45s ease-out forwards';
        } else {
            mainContent.classList.remove('loading');
            loaderOverlay.classList.add('hidden');
        }
    };

    const isValidFile = (file) => {
        const validTypes = ['application/pdf', 'application/msword', 
                           'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const validExt = /\.(pdf|docx?|PDF|DOCX?)$/;
        return (validTypes.includes(file.type) || validExt.test(file.name)) && 
               file.size <= 8 * 1024 * 1024;
    };

    const uploadFile = async (file) => {
        if (!isValidFile(file)) {
            showError('Please upload a PDF, DOC, or DOCX file under 8MB');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', form.querySelector('input[name=_token]').value);

        showLoader(true);

        try {
            const response = await fetch(UPLOAD_URL, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const contentType = response.headers.get('content-type');
            let data;
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                try { data = JSON.parse(text); } catch { data = { raw: text }; }
            }

            if (response.ok && data?.ok && data?.redirect) {
                window.location.href = data.redirect;
                return;
            }

            // Handle errors
            let errorMsg = 'Failed to process CV. Please try again.';
            
            if (response.status === 419) {
                errorMsg = 'Session expired. Please refresh and try again.';
            } else if (response.status === 422) {
                errorMsg = data?.error || data?.errors?.file?.[0] || 'Invalid file format or size.';
            } else if (data?.error) {
                errorMsg = data.error;
            }

            throw new Error(errorMsg);

        } catch (error) {
            console.error('Upload error:', error);
            showLoader(false);
            showError(error.message || 'Upload failed. Please try again.');
        }
    };

    // Upload button click
    uploadBtn.addEventListener('click', () => fileInput.click());

    // File input change
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            uploadFile(fileInput.files[0]);
        }
    });

    // Drag & drop
    const preventDefaults = (e) => {
        e.preventDefault();
        e.stopPropagation();
    };

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
        dropzone.addEventListener(event, preventDefaults);
    });

    ['dragenter', 'dragover'].forEach(event => {
        dropzone.addEventListener(event, () => dropzone.classList.add('dragover'));
    });

    ['dragleave', 'drop'].forEach(event => {
        dropzone.addEventListener(event, () => dropzone.classList.remove('dragover'));
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer?.files?.[0];
        if (file) {
            uploadFile(file);
        }
    });
});
</script>
@endpush
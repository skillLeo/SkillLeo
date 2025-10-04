@extends('layouts.onboarding')

@section('title', 'Welcome to ProMatch')

@section('card-content')
{{-- <div class="skeleton"></div> --}}
<div class="welcome-wrapper">
    <div class="welcome-content">
        <x-onboarding.badge variant="primary">
            AI-Powered Setup
        </x-onboarding.badge>

        <h1 class="welcome-title">Build your professional profile</h1>
        <p class="welcome-subtitle">
            Upload your CV for instant AI setup, or build from scratch. Takes under 5 minutes.
        </p>

        <div class="welcome-actions">
            <button type="button" class="btn btn-primary" id="uploadBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" 
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Upload CV
            </button>

            <button type="button" class="btn btn-secondary" id="manualBtn">
                Start from scratch
            </button>
        </div>

        <div class="upload-drop" id="dropzone">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
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

        <input type="file" id="fileInput" accept=".pdf,.doc,.docx" hidden>

        <a href="{{ route('tenant.onboarding.personal') }}" class="link-skip">
            Skip for now
        </a>
    </div>
</div>

@endsection

@push('styles')
<style>
.welcome-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
}

.welcome-content {
    max-width: 480px;
    width: 100%;
    text-align: center;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: var(--fw-extrabold);
    color: var(--text-heading);
    line-height: 1.2;
    margin-bottom: var(--space-md);
    letter-spacing: -0.03em;
}

.welcome-subtitle {
    font-size: var(--fs-body);
    color: var(--text-muted);
    line-height: var(--lh-relaxed);
    margin-bottom: var(--space-2xl);
}

.welcome-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-md);
    margin-bottom: var(--space-lg);
}

.welcome-actions .btn {
    padding: 14px 24px;
    min-height: 50px;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
}

.upload-drop {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-md);
    padding: var(--space-lg);
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    background: var(--card);
    cursor: pointer;
    transition: all var(--transition-base);
    margin-bottom: var(--space-xl);
}

.upload-drop:hover {
    border-color: var(--accent);
    background: var(--accent-light);
}

.upload-drop.dragover {
    border-color: var(--accent);
    background: var(--accent-light);
    border-style: solid;
}

.upload-drop svg {
    color: var(--text-muted);
    flex-shrink: 0;
}

.upload-drop strong {
    display: block;
    font-size: var(--fs-body);
    font-weight: var(--fw-semibold);
    color: var(--text-body);
    margin-bottom: 2px;
}

.upload-drop span {
    display: block;
    font-size: var(--fs-subtle);
    color: var(--text-muted);
}

.link-skip {
    display: inline-block;
    color: var(--text-muted);
    font-size: var(--fs-subtle);
    text-decoration: none;
    transition: color var(--transition-base);
}

.link-skip:hover {
    color: var(--accent);
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
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const dropzone = document.getElementById('dropzone');
    const manualBtn = document.getElementById('manualBtn');

    uploadBtn.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('click', () => fileInput.click());
    
    manualBtn.addEventListener('click', () => {
        window.location.href = '{{ route("tenant.onboarding.personal") }}';
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (file) processFile(file);
    });

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer?.files?.[0];
        if (file) processFile(file);
    });

    function processFile(file) {
        const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!validTypes.includes(file.type)) {
            alert('Please upload PDF or DOC file');
            return;
        }

        if (file.size > 8 * 1024 * 1024) {
            alert('File must be under 8MB');
            return;
        }

        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';

        setTimeout(() => {
            window.location.href = '{{ route("tenant.onboarding.personal") }}';
        }, 1000);
    }
});
</script>
@endpush
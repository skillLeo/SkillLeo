@extends('layouts.onboarding')

@section('title', 'Complete Your Profile - ProMatch')

@php
    $currentStep = 0;
    $totalSteps = 8;
@endphp

@section('card-content')
    <div class="hero-layout">
        <div class="hero__content">
            <x-ui.step-badge label="AI-Powered Setup" />

            <h1 class="hero__title" id="title">Complete your professional profile</h1>
            <p class="hero__subtitle">
                Upload your CV or start fresh. Our AI drafts your profile, highlights, and portfolio —
                while you keep full control of your story.
            </p>

            <div class="cta-buttons">
                <x-ui.button variant="primary" id="getStartedBtn">
                    Get started
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-ui.button>

                <x-ui.button variant="secondary" id="uploadBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Upload CV
                </x-ui.button>
            </div>

            <!-- Dropzone -->
            <div class="dropzone" id="dropzone" role="button" aria-label="Upload your CV by clicking or dragging a file here">
                <div class="dropzone__inner">
                    <div class="dz-icon">CV</div>
                    <div>
                        <div class="dz-text"><strong>Drag & drop</strong> your CV here, or <strong>click to browse</strong></div>
                        <div class="dz-meta">PDF, DOC, or DOCX • up to 8 MB</div>
                    </div>
                </div>
            </div>

            <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.txt" hidden />

            <a class="link-secondary" href="javascript:void(0)" id="resetLink">Or Skip It?</a>
        </div>

        <div class="hero__visual" aria-hidden="true">
            <div class="visual-container">
                <div class="profile-mockup">
                    <div class="mockup-header">
                        <div class="avatar-placeholder" id="avatarInitials">JD</div>
                        <div class="mockup-info">
                            <h3 id="mockName">John Developer</h3>
                            <p id="mockRole">Senior Full-Stack Engineer</p>
                        </div>
                    </div>
                    <div class="mockup-stats">
                        <div class="stat">
                            <div class="stat-number" id="mockConnections">150+</div>
                            <div class="stat-label">Connections</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" id="mockProjects">12</div>
                            <div class="stat-label">Projects</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" id="mockRating">5★</div>
                            <div class="stat-label">Rating</div>
                        </div>
                    </div>
                </div>

                <div class="floating-elements">
                    <div class="floating-card">✨ Skills auto-detected</div>
                    <div class="floating-card">🏆 Top 5% match</div>
                    <div class="floating-card">📈 Profile boosted</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Steps -->
    <nav class="steps-nav" aria-label="Onboarding steps">
        <div class="steps-header">Your journey ahead</div>
        <div class="steps-list">
            <span class="step-item active">Personal Info</span>
            <span class="step-separator">•</span>
            <span class="step-item">Location</span>
            <span class="step-separator">•</span>
            <span class="step-item">Skills</span>
            <span class="step-separator">•</span>
            <span class="step-item">Experience</span>
            <span class="step-separator">•</span>
            <span class="step-item">Portfolio</span>
            <span class="step-separator">•</span>
            <span class="step-item">Education</span>
            <span class="step-separator">•</span>
            <span class="step-item">Preferences</span>
            <span class="step-separator">•</span>
            <span class="step-item">Launch</span>
        </div>
    </nav>
@endsection

@push('styles')
<style>
    .container {
        max-width: 1040px !important;
        margin: 0 auto;
    }
    .hero-layout {
        display: grid;
        grid-template-columns: 1fr 480px;
        min-height: 520px;
        gap: 40px;
    }

    .hero__content {
        padding: 24px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hero__title {
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -.02em;
        line-height: 1.25;
        margin-bottom: 12px;
    }

    .hero__subtitle {
        font-size: 16px;
        color: var(--gray-500);
        max-width: 480px;
        margin-bottom: 24px;
    }

    .cta-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-bottom: 18px;
    }

    .dropzone {
        margin-top: 16px;
        border: 2px dashed var(--gray-300);
        border-radius: 14px;
        background: var(--gray-100);
        padding: 16px;
        transition: all .2s ease;
        cursor: pointer;
    }

    .dropzone:hover {
        border-color: var(--primary);
        background: rgba(0, 102, 204, .06);
    }

    .dropzone.dragover {
        border-color: var(--primary);
        background: rgba(0, 102, 204, .08);
    }

    .dropzone__inner {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dz-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0066CC 0%, #00A0DC 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .dz-text {
        font-size: 13px;
        color: var(--gray-700);
    }

    .dz-text strong {
        color: var(--gray-900);
    }

    .dz-meta {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .link-secondary {
        color: var(--gray-500);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        width: fit-content;
        border-bottom: 1px dotted var(--gray-500);
        margin-top: 16px;
    }

    .link-secondary:hover {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    /* Visual mockup */
    .hero__visual {
        background: linear-gradient(135deg, #F8FAFF 0%, #EEF4FF 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        border-radius: 12px;
    }

    .visual-container {
        position: relative;
        width: 100%;
        max-width: 360px;
    }

    .profile-mockup {
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        border: 1px solid var(--gray-300);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .mockup-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
    }

    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0066CC 0%, #00A0DC 100%);
        color: #fff;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .mockup-info h3 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .mockup-info p {
        font-size: 13px;
        color: var(--gray-500);
    }

    .mockup-stats {
        display: flex;
        gap: 18px;
        padding-top: 12px;
        border-top: 1px solid var(--gray-300);
    }

    .stat {
        text-align: center;
        flex: 1;
    }

    .stat-number {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
    }

    .stat-label {
        font-size: 11px;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .floating-elements {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .floating-card {
        position: absolute;
        background: #fff;
        border: 1px solid var(--gray-300);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-900);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        animation: bob 6s ease-in-out infinite;
    }

    .floating-card:nth-child(1) {
        top: 18%;
        right: -10%;
        animation-delay: 0s;
    }

    .floating-card:nth-child(2) {
        bottom: 26%;
        left: -14%;
        animation-delay: 1.6s;
    }

    .floating-card:nth-child(3) {
        top: 58%;
        right: -6%;
        animation-delay: 3.2s;
    }

    @keyframes bob {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Steps nav */
    .steps-nav {
        background: #fff;
        border-top: 1px solid var(--gray-300);
        padding: 22px 0;
        margin: 0 -40px -40px;
        padding-left: 40px;
        padding-right: 40px;
    }

    .steps-header {
        font-size: 12px;
        font-weight: 800;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 12px;
    }

    .steps-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .step-item {
        padding: 8px 12px;
        background: var(--gray-100);
        border-radius: 20px;
        border: 1px solid var(--gray-300);
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
    }

    .step-item.active {
        background: #F3F6FF;
        color: var(--primary);
        border-color: rgba(0, 102, 204, .25);
    }

    .step-separator {
        color: var(--gray-500);
        font-size: 12px;
    }

    @media (max-width: 968px) {
        .hero-layout {
            grid-template-columns: 1fr;
        }

        .hero__visual {
            order: -1;
            padding: 28px 22px;
        }

        .hero__content {
            padding: 28px 0;
        }

        .steps-nav {
            padding: 20px 22px;
            margin: 0 -24px -24px;
        }
    }

    @media (max-width: 640px) {
        .cta-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .steps-list {
            justify-content: center;
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
        const getStartedBtn = document.getElementById('getStartedBtn');

        uploadBtn.addEventListener('click', () => fileInput.click());
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag and drop
        ['dragenter', 'dragover'].forEach(ev => {
            dropzone.addEventListener(ev, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(ev => {
            dropzone.addEventListener(ev, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const file = e.dataTransfer?.files?.[0];
            if (file) handleFile(file);
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (file) handleFile(file);
        });

        getStartedBtn.addEventListener('click', () => {
            window.location.href = '{{ route("tenant.onboarding.personal") }}';
        });

        function handleFile(file) {
            console.log('File uploaded:', file.name);
            // TODO: Handle file upload via AJAX
            setTimeout(() => {
                window.location.href = '{{ route("tenant.onboarding.personal") }}';
            }, 500);
        }
    });
</script>
@endpush
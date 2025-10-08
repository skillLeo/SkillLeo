@extends('layouts.onboarding')

@section('title', 'Review & Publish - ProMatch')

@section('card-content')

    <x-onboarding.form-header step="8" title="Your profile is ready to shine"
        subtitle="Give it a quick look, toggle visibility, and publish." />

    <!-- Profile summary -->
    <div class="summary-card" id="profileSummary">
        <div class="profile-header">
            <div class="avatar" id="profileAvatar">U</div>
            <div>
                <div class="ph-name" id="profileName">Your Name</div>
                <div class="ph-sub">
                    <span id="profileLocation">Your Location</span> ·
                    <span id="profileUrl">promatch.com/username</span>
                </div>
            </div>
        </div>

        <div class="chips" id="skillsChips"></div>

        <div class="stats">
            <div class="stat">
                <div class="n" id="skillsCount">0</div>
                <div class="l">Skills</div>
            </div>
            <div class="stat">
                <div class="n" id="experienceCount">0</div>
                <div class="l">Experience</div>
            </div>
            <div class="stat">
                <div class="n" id="projectsCount">0</div>
                <div class="l">Projects</div>
            </div>
        </div>
    </div>

    <!-- Experience preview -->
    <div class="summary-card" id="experiencePreview" style="display:none;">
        <div class="ph-name" style="font-size:var(--fs-body);margin-bottom:10px;">Recent experience</div>
        <div id="experienceRows"></div>
    </div>

    <!-- Portfolio preview -->
    <div class="summary-card" id="portfolioPreview" style="display:none;">
        <div class="ph-name" style="font-size:var(--fs-body);margin-bottom:10px;">Featured projects</div>
        <div id="projectRows"></div>
    </div>

    <!-- Visibility -->
    <div class="summary-card">
        <div class="toggle-row">
            <div class="toggle-info">
                <strong>Make profile public</strong><br />
                <span class="ph-sub">Your profile will be visible to clients and searchable.</span>
            </div>
            <label class="switch">
                <input type="checkbox" id="makePublic" name="is_public" />
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <form id="reviewForm" action="{{ route('tenant.onboarding.review.store') }}" method="POST">
        @csrf
        <input type="hidden" name="is_public" id="isPublicInput" value="1">

        <x-onboarding.form-footer backUrl="{{ route('tenant.onboarding.preferences') }}" nextLabel="Publish profile"
            id="publishBtn" />
    </form>

@endsection

@push('styles')
    <style>
        .summary-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            background: var(--apc-bg);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .03);
            margin-bottom: 16px
        }

        .profile-header {
            display: grid;
            grid-template-columns: 64px 1fr;
            gap: 14px;
            align-items: center;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px
        }

        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-weight: var(--fw-bold);
            color: var(--btn-text-primary);
            font-size: var(--fs-h2);
            background: var(--accent)
        }

        .ph-name {
            font-weight: var(--fw-bold);
            color: var(--text-heading);
            font-size: var(--fs-title)
        }

        .ph-sub {
            font-size: var(--fs-subtle);
            color: var(--text-muted)
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px
        }

        .chip {
            font-size: var(--fs-micro);
            color: var(--text-body);
            background: var(--card);
            border: 1px solid var(--border);
            padding: 6px 10px;
            border-radius: 999px
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px
        }

        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 12px;
            text-align: center
        }

        .stat .n {
            font-weight: var(--fw-extrabold);
            color: var(--text-heading);
            font-size: var(--fs-h3)
        }

        .stat .l {
            font-size: var(--fs-micro);
            color: var(--text-muted);
            margin-top: 2px
        }

        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            transition: border-color .2s ease, background .2s ease
        }

        .toggle-row:hover {
            border-color: var(--accent);
            background: var(--accent-light)
        }

        .toggle-info {
            flex: 1
        }

        .switch {
            position: relative;
            width: 48px;
            height: 26px;
            flex-shrink: 0
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0
        }

        .slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: var(--border);
            transition: .25s;
            border-radius: 999px
        }

        .slider:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            left: 3px;
            top: 3px;
            background: var(--card);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .15);
            transition: .25s
        }

        .switch input:checked+.slider {
            background: var(--accent)
        }

        .switch input:checked+.slider:before {
            transform: translateX(22px)
        }

        @media (max-width:640px) {
            .stats {
                grid-template-columns: 1fr
            }
        }

        /* small rows */
        .row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border)
        }

        .row:last-child {
            border-bottom: 0
        }

        .row .t {
            font-weight: 600;
            color: var(--text-heading)
        }

        .row .s {
            font-size: var(--fs-subtle);
            color: var(--text-muted)
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---- Server snapshot (DB-backed) ----
            const server = @json($profile ?? []);

            // ---- Local storage (client-edited steps) ----
            const personal = safeJSON(localStorage.getItem('onboarding_personal'), {});
            const location = safeJSON(localStorage.getItem('onboarding_location'), {});
            const skills = safeJSON(localStorage.getItem('onboarding_skills'), []);
            const expLs = safeJSON(localStorage.getItem('onboarding_experience'), []);
            const projects = safeJSON(localStorage.getItem('onboarding_portfolio'), []);

            // ---- Merge logic (local first if present, else server) ----
            const name = fullName(personal) || (server.name || 'Your Name');
            const initial = (personal.firstName?.[0] || server.initial || 'U').toUpperCase();
            const username = (personal.username || server.username || 'username');
            const loc = locationLine(location) || (server.location || '');
            const sk = skills.length ? skills.map(s => ({
                name: s.name
            })) : (server.skills || []);
            const exps = expLs.length ? expLs : (server.experiences || []);
            const projs = projects.length ? projects : (server.projects || []);

            // ---- Fill summary ----
            setText('#profileName', name);
            setText('#profileAvatar', initial);
            setText('#profileUrl', `promatch.com/${escapeHtml(username)}`);
            setText('#profileLocation', loc || '—');

            const chips = (sk || []).slice(0, 8).map(s => `<span class="chip">${escapeHtml(s.name || '')}</span>`)
                .join('');
            document.querySelector('#skillsChips').innerHTML = chips;

            setText('#skillsCount', (sk || []).length);
            setText('#experienceCount', (exps || []).length);
            setText('#projectsCount', (projs || []).length);

            // ---- Experience preview (2 rows) ----
            if (exps.length) {
                const rows = exps.slice(0, 2).map(e => {
                    const range = dateRange(e);
                    const title = [e.title, e.company].filter(Boolean).join(' • ');
                    return `<div class="row">
                <div class="t">${escapeHtml(title || '')}</div>
                <div class="s">${escapeHtml(range || '')}</div>
            </div>`;
                }).join('');
                document.querySelector('#experienceRows').innerHTML = rows;
                document.querySelector('#experiencePreview').style.display = 'block';
            }

            // ---- Portfolio preview (2 rows) ----
            if (projs.length) {
                const rows = projs.slice(0, 2).map(p => {
                    const host = hostname(p.link || p.url || '');
                    return `<div class="row">
                <div class="t">${escapeHtml(p.title || 'Untitled project')}</div>
                <div class="s">${host ? escapeHtml(host) : '—'}</div>
            </div>`;
                }).join('');
                document.querySelector('#projectRows').innerHTML = rows;
                document.querySelector('#portfolioPreview').style.display = 'block';
            }

            // ---- Visibility toggle (DB value wins initially if provided) ----
            const initialPublic = typeof server.is_public === 'boolean' ? server.is_public : true;
            const makePublic = document.getElementById('makePublic');
            const isPublicInput = document.getElementById('isPublicInput');
            makePublic.checked = initialPublic;
            isPublicInput.value = initialPublic ? '1' : '0';

            makePublic.addEventListener('change', () => {
                isPublicInput.value = makePublic.checked ? '1' : '0';
            });

            // Utilities
            function safeJSON(s, fallback) {
                try {
                    const v = JSON.parse(s || '');
                    return v ?? fallback;
                } catch {
                    return fallback;
                }
            }

            function setText(sel, v) {
                const el = document.querySelector(sel);
                if (el) el.textContent = String(v ?? '');
            }

            function escapeHtml(text) {
                const d = document.createElement('div');
                d.textContent = text || '';
                return d.innerHTML;
            }

            function fullName(p) {
                const fn = (p.firstName || '').trim();
                const ln = (p.lastName || '').trim();
                return (fn || ln) ? `${fn} ${ln}`.trim() : '';
            }

            function locationLine(l) {
                const c = (l.cityName || '').trim();
                const k = (l.countryName || '').trim();
                return [c, k].filter(Boolean).join(', ');
            }

            function hostname(url) {
                try {
                    return new URL(normalizeUrl(url)).hostname.replace(/^www\./, '');
                } catch {
                    return '';
                }
            }

            function normalizeUrl(u) {
                if (!u) return '';
                const v = String(u).trim();
                if (/^https?:\/\//i.test(v)) return v;
                return 'https://' + v.replace(/^\/+/, '');
            }

            function dateRange(e) {
                if (e.current || e.is_current) {
                    if (!e.startMonth && !e.start_month && !e.startYear && !e.start_year) return 'Present';
                }
                const sm = e.startMonth || e.start_month,
                    sy = e.startYear || e.start_year;
                const em = e.endMonth || e.end_month,
                    ey = e.endYear || e.end_year;
                const M = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const from = (sy ? (sm ? `${M[Number(sm)-1]||''} ${sy}` : sy) : '');
                const to = (e.current || e.is_current) ? 'Present' : (ey ? (em ? `${M[Number(em)-1]||''} ${ey}` :
                    ey) : '');
                return [from, to].filter(Boolean).join(' — ');
            }
        });
    </script>
@endpush

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
            <form id="uploadForm" action="{{ route('tenant.onboarding.cv.upload') }}" method="POST" enctype="multipart/form-data" hidden>
              @csrf
              <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx">
          </form>



          @push('scripts')
          <script>
          document.addEventListener('DOMContentLoaded', () => {
              const uploadBtn = document.getElementById('uploadBtn');
              const dropzone  = document.getElementById('dropzone');
              const form      = document.getElementById('uploadForm');
              const input     = document.getElementById('fileInput');
          
              // Click "Upload CV" => open picker
              uploadBtn?.addEventListener('click', () => input.click());
          
              // Auto-submit when a file is chosen
              input?.addEventListener('change', () => {
                  if (input.files.length) form.submit();
              });
          
              // Drag & drop support
              const stop = e => { e.preventDefault(); e.stopPropagation(); };
              ['dragenter','dragover','dragleave','drop'].forEach(ev => {
                  dropzone.addEventListener(ev, stop, false);
              });
          
              ['dragenter','dragover'].forEach(ev => {
                  dropzone.addEventListener(ev, () => dropzone.classList.add('dragover'), false);
              });
              ['dragleave','drop'].forEach(ev => {
                  dropzone.addEventListener(ev, () => dropzone.classList.remove('dragover'), false);
              });
          
              dropzone.addEventListener('drop', e => {
                  const dt   = e.dataTransfer;
                  const file = dt.files && dt.files[0];
                  if (!file) return;
          
                  const ok = ['application/pdf',
                              'application/msword',
                              'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
                             .includes(file.type) || /\.(pdf|docx?|PDF)$/.test(file.name);
          
                  if (!ok) { alert('Please drop a PDF, DOC or DOCX (max 8 MB)'); return; }
                  if (file.size > (8 * 1024 * 1024)) { alert('File is larger than 8 MB.'); return; }
          
                  // Put the dropped file into the real input and submit
                  const dt2 = new DataTransfer();
                  dt2.items.add(file);
                  input.files = dt2.files;
                  form.submit();
              });
          });
          </script>
          @endpush
          





            <form method="POST" action="{{ route('tenant.onboarding.scratch') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    Start from scratch
                </button>
            </form>
            
            
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
    document.addEventListener('DOMContentLoaded', () => {
      const form       = document.getElementById('personalForm');
      const firstName  = document.getElementById('firstName');
      const lastName   = document.getElementById('lastName');
      const username   = document.getElementById('username');   // ensure your input has this id
      const submitBtn  = document.getElementById('continueBtn') || form.querySelector('button[type="submit"]');
    
      const statusEl   = ensureStatusEl(username); // small helper to show messages
      const suggestEl  = ensureSuggestEl(statusEl); // clickable “Use suggestion”
      let userEdited   = false;
      let reqStamp     = 0;
      let debounceId;
    
      function slug(s) {
        return (s || '')
          .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
          .toLowerCase().replace(/[^a-z0-9_-]+/g, '')
          .replace(/^[-_]+|[-_]+$/g, '').slice(0, 50);
      }
    
      function baseFromNames() {
        const f = slug(firstName.value), l = slug(lastName.value);
        return (f && l) ? slug(`${f}-${l}`) : '';
      }
    
      function setState(type, text) {
        statusEl.textContent = text || '';
        statusEl.dataset.type = type || '';
        username.classList.toggle('is-valid', type === 'ok');
        username.classList.toggle('is-invalid', type === 'error');
        submitBtn.disabled = (type !== 'ok');
        if (type !== 'taken') suggestEl.classList.add('hidden');
      }
    
      function checkNow(u) {
        const stamp = ++reqStamp;
        setState('loading', 'Checking…');
    
        fetch(`{{ route('api.username.check') }}?username=${encodeURIComponent(u)}`, {
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(j => {
          if (stamp !== reqStamp) return; // ignore stale responses
    
          if (j.status === 'available') {
            setState('ok', 'Username is available');
          } else if (j.status === 'self') {
            setState('ok', 'This is already your username ✓');
          } else if (j.status === 'taken') {
            setState('error', 'That username is taken');
            if (j.suggestion) {
              suggestEl.textContent = `Use “${j.suggestion}”`;
              suggestEl.onclick = (e) => {
                e.preventDefault();
                username.value = j.suggestion;
                userEdited = true;
                trigger();
              };
              suggestEl.classList.remove('hidden');
            }
          } else {
            setState('error', j.error || 'Invalid username');
          }
        })
        .catch(() => setState('error', 'Couldn’t check right now. Try again.'));
      }
    
      function trigger() {
        clearTimeout(debounceId);
        const val = slug(username.value);
        username.value = val;
        if (val.length < 3) {
          setState('error', 'At least 3 characters');
          return;
        }
        debounceId = setTimeout(() => checkNow(val), 250);
      }
    
      function maybeGenerate() {
        if (userEdited) return;
        const base = baseFromNames();
        if (base && username.value.trim() === '') {
          username.value = base;
          trigger();
        }
      }
    
      firstName.addEventListener('input', maybeGenerate);
      lastName.addEventListener('input',  maybeGenerate);
    
      username.addEventListener('input', () => { userEdited = true; trigger(); });
      username.addEventListener('blur',  trigger);
    
      // initial state
      if (username.value.trim() === '') maybeGenerate(); else trigger();
    
      // helpers
      function ensureStatusEl(afterEl) {
        let el = document.getElementById('usernameStatus');
        if (!el) {
          el = document.createElement('small');
          el.id = 'usernameStatus';
          el.style.display = 'block';
          el.style.marginTop = '6px';
          el.style.fontSize = '12px';
          el.style.color = 'var(--text-muted,#666)';
          afterEl.insertAdjacentElement('afterend', el);
        }
        return el;
      }
      function ensureSuggestEl(statusAfterEl) {
        let a = document.getElementById('usernameSuggest');
        if (!a) {
          a = document.createElement('a');
          a.id = 'usernameSuggest';
          a.href = '#';
          a.className = 'hidden';
          a.style.display = 'inline-block';
          a.style.marginLeft = '8px';
          a.style.fontSize = '12px';
          statusAfterEl.insertAdjacentElement('afterend', a);
        }
        return a;
      }
    });
    </script>
    
@endpush
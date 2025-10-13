<script>
    (function() {
        'use strict';
        
        try {
            // Detect user's timezone
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            
            // Store in session storage for persistence
            if (typeof Storage !== 'undefined') {
                sessionStorage.setItem('user_timezone', userTimezone);
            }
            
            // Add timezone to all forms automatically
            function addTimezoneToForms() {
                document.querySelectorAll('form').forEach(form => {
                    // Skip if already has timezone field
                    if (form.querySelector('input[name="timezone"]')) {
                        return;
                    }
                    
                    // Skip if form has data-no-timezone attribute
                    if (form.hasAttribute('data-no-timezone')) {
                        return;
                    }
                    
                    // Add hidden timezone field
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'timezone';
                    input.value = userTimezone;
                    form.appendChild(input);
                });
            }
    
            // Run on DOM load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', addTimezoneToForms);
            } else {
                addTimezoneToForms();
            }
    
            // Also observe for dynamically added forms
            if (typeof MutationObserver !== 'undefined') {
                const observer = new MutationObserver(addTimezoneToForms);
                observer.observe(document.body, { childList: true, subtree: true });
            }
    
            // Send timezone to server for session storage (non-blocking)
            if (typeof fetch !== 'undefined') {
                fetch('{{ route("timezone.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ timezone: userTimezone }),
                    keepalive: true
                }).catch(function() {
                    // Silent fail - non-critical
                });
            }
        } catch(e) {
            console.warn('Timezone detection failed:', e);
        }
    })();
    </script>
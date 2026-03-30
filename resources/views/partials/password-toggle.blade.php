{{--
    ═══════════════════════════════════════════════════════════
    PASSWORD VISIBILITY TOGGLE — Global Reusable Component
    Include once per layout. Auto-initializes on all pages.
    ═══════════════════════════════════════════════════════════
--}}
<style>
.pw-wrap { position: relative; }
.pw-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s;
    z-index: 2;
}
.pw-toggle:hover { color: #6366f1; }
.pw-toggle svg { width: 18px; height: 18px; pointer-events: none; }
/* Ensure password inputs leave room for the eye icon */
.pw-wrap input[type="password"],
.pw-wrap input[type="text"] { padding-right: 40px !important; }
</style>
<script>
(function () {
    var EYE_ICON = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
    var EYE_OFF  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';

    function initPasswordToggles() {
        document.querySelectorAll('input[type="password"]').forEach(function (input) {
            // Skip already-processed inputs
            if (input.dataset.pwToggleInit === '1') return;
            input.dataset.pwToggleInit = '1';

            // Wrap input in a relative container if not already wrapped
            var parent = input.parentElement;
            if (!parent.classList.contains('pw-wrap')) {
                var wrapper = document.createElement('div');
                wrapper.className = 'pw-wrap';
                // Copy inline styles for width
                var cs = window.getComputedStyle(input);
                if (cs.display === 'block' || input.style.width === '100%' || input.classList.contains('w-full')) {
                    wrapper.style.width = '100%';
                }
                parent.insertBefore(wrapper, input);
                wrapper.appendChild(input);
            }

            // Create toggle button
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pw-toggle';
            btn.setAttribute('aria-label', 'Toggle password visibility');
            btn.innerHTML = EYE_ICON;
            btn.title = 'Show password';

            btn.addEventListener('click', function () {
                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.innerHTML = isPassword ? EYE_OFF : EYE_ICON;
                btn.title = isPassword ? 'Hide password' : 'Show password';
            });

            input.parentElement.appendChild(btn);
        });
    }

    // Run on DOM ready and after any dynamic content changes (for Alpine-rendered content)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPasswordToggles);
    } else {
        initPasswordToggles();
    }
    // Re-run after Livewire/Alpine updates (catches dynamically rendered inputs)
    document.addEventListener('alpine:initialized', initPasswordToggles);
})();
</script>

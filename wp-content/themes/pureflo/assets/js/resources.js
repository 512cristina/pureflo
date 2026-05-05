document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('rsFilterForm');
    if (!form) return;

    function submitWithAnchor() {
        const params = new URLSearchParams(new FormData(form)).toString();
        const action = window.location.pathname;

        window.location.href = action + '?' + params + '#rsFilterForm';
    }

    // Auto-submit on select change
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', submitWithAnchor);
    });

    // Debounced search input
    const searchInput = form.querySelector('input[name="search"]');
    let timeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(submitWithAnchor, 500);
        });

        // Handle Enter key
        searchInput.addEventListener('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                submitWithAnchor();
            }
        });
    }

    // Scroll to results if filters are applied
    if (window.location.search.length > 0) {
        const target = document.getElementById('rsList');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
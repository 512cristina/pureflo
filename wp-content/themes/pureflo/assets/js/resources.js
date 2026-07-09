document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('rsFilterForm');
    if (filterForm) {
        filterForm.querySelectorAll('select').forEach(function (select) {
            select.addEventListener('change', function () {
                filterForm.submit();
            });
        });
    }

    // If this page was loaded with a search or filter, scroll to the resources section
    if (window.location.search.length > 0) {
        const resources = document.getElementById('resources');
        if (resources) { resources.scrollIntoView(); }
    }
});
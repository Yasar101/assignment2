// public.js - simple client-side form improvements
document.addEventListener('DOMContentLoaded', function () {
    // Basic HTML5 validation is enough for UX. You can add enhancements if needed.
    const forms = document.querySelectorAll('form[novalidate]');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
            }
        }, false);
    });
});


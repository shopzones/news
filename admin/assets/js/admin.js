// ========================================
// NEWS PORTAL - ADMIN JAVASCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    initAdminSidebar();
    initFormValidation();
    initTableSearch();
    initConfirmDelete();
});

// Highlight active sidebar link
function initAdminSidebar() {
    const currentPage = location.pathname.split('/').pop();
    document.querySelectorAll('.admin-sidebar .nav-link').forEach(link => {
        if (link.getAttribute('href') && link.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
        }
    });
}

// Basic form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });
}

// Table search filter
function initTableSearch() {
    const searchInput = document.getElementById('tableSearch');
    if (!searchInput) return;

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

// Confirm before delete
function initConfirmDelete() {
    document.querySelectorAll('a[href*="delete"], button[data-action="delete"]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
}

// Show toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    setTimeout(() => toast.remove(), 4000);
}
// News Portal Main JavaScript

// Dark Mode Toggle
function initDarkMode() {
    const toggle = document.getElementById('darkModeToggle');
    if (!toggle) return;

    const currentTheme = localStorage.getItem('theme') || (document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        toggle.innerHTML = '<i class="fas fa-sun"></i>';
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        toggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    });
}

// Newsletter Subscription
function initNewsletter() {
    const forms = document.querySelectorAll('#newsletterForm, #homeNewsletter, #sidebarNewsletter');
    
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const emailInput = form.querySelector('input[type="email"]');
            if (!emailInput) return;
            
            const email = emailInput.value;
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btn.disabled = true;

            try {
                const response = await fetch('<?= BASE_URL ?>/api/subscribe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const msg = form.parentElement.querySelector('#newsletterMsg') || form;
                    msg.innerHTML = `<div class="alert alert-success small py-1 mt-2">Thank you! Check your inbox.</div>`;
                    emailInput.value = '';
                } else {
                    alert(data.message || 'Something went wrong');
                }
            } catch (err) {
                alert('Network error. Please try again.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    });
}

// Live Search (AJAX)
function initLiveSearch() {
    const searchInput = document.querySelector('input[name="q"]');
    if (!searchInput) return;

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(async () => {
            const query = searchInput.value.trim();
            if (query.length < 2) return;
            
            const resultsContainer = document.getElementById('searchResults');
            if (!resultsContainer) return;

            try {
                const res = await fetch(`<?= BASE_URL ?>/api/search.php?q=${encodeURIComponent(query)}`);
                const data = await res.json();
                
                resultsContainer.innerHTML = '';
                if (data.news && data.news.length) {
                    data.news.forEach(item => {
                        const el = document.createElement('a');
                        el.href = `<?= BASE_URL ?>/news/${item.slug}`;
                        el.className = 'list-group-item list-group-item-action';
                        el.innerHTML = `<strong>${item.title}</strong><br><small class="text-muted">${item.category_name}</small>`;
                        resultsContainer.appendChild(el);
                    });
                    resultsContainer.style.display = 'block';
                }
            } catch (e) {}
        }, 300);
    });
}

// Copy Link Button
function initCopyLink() {
    document.addEventListener('click', (e) => {
        if (e.target.closest('.copy-link-btn')) {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = e.target.closest('.copy-link-btn');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => btn.innerHTML = original, 2000);
            });
        }
    });
}

// Initialize everything
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    initNewsletter();
    initLiveSearch();
    initCopyLink();
    
    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => a.style.transition = 'opacity .5s', a.style.opacity = '0');
    }, 4000);
});

// Print Article
function printArticle() {
    window.print();
}
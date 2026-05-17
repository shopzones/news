// ========================================
// NEWS PORTAL - FRONTEND JAVASCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    initDarkMode();
    initNewsletter();
    initLiveSearch();
    initCopyLink();
    initLikeSystem();
});

// Dark Mode Toggle
function initDarkMode() {
    const toggle = document.getElementById('darkModeToggle');
    if (!toggle) return;

    const currentTheme = localStorage.getItem('theme');
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
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const emailInput = form.querySelector('input[type="email"]');
            if (!emailInput) return;

            const btn = form.querySelector('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btn.disabled = true;

            try {
                const response = await fetch('/api/subscribe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: emailInput.value })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    form.innerHTML = `<div class="alert alert-success py-2">Thank you! You are now subscribed.</div>`;
                } else {
                    alert(data.message || 'Something went wrong');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    });
}

// Live Search Suggestions
function initLiveSearch() {
    const searchInput = document.querySelector('input[name="q"]');
    if (!searchInput) return;

    let timeout;
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'list-group position-absolute shadow-sm';
    resultsContainer.style.cssText = 'top: 100%; left: 0; right: 0; z-index: 1000; display: none;';
    searchInput.parentNode.style.position = 'relative';
    searchInput.parentNode.appendChild(resultsContainer);

    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(async () => {
            const query = searchInput.value.trim();
            if (query.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            try {
                const res = await fetch(`/api/search.php?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                resultsContainer.innerHTML = '';
                if (data.news && data.news.length > 0) {
                    data.news.forEach(item => {
                        const a = document.createElement('a');
                        a.href = `/news/${item.slug}`;
                        a.className = 'list-group-item list-group-item-action';
                        a.innerHTML = `<strong>${item.title}</strong><br><small class="text-muted">${item.category_name}</small>`;
                        resultsContainer.appendChild(a);
                    });
                    resultsContainer.style.display = 'block';
                } else {
                    resultsContainer.style.display = 'none';
                }
            } catch (e) {}
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
}

// Copy Link Button
function initCopyLink() {
    document.addEventListener('click', async (e) => {
        if (e.target.closest('.copy-link-btn')) {
            try {
                await navigator.clipboard.writeText(window.location.href);
                const btn = e.target.closest('.copy-link-btn');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => btn.innerHTML = original, 2000);
            } catch (err) {
                alert('Failed to copy link');
            }
        }
    });
}

// Like / Dislike System
function initLikeSystem() {
    const likeBtn = document.getElementById('likeCount');
    if (!likeBtn) return;

    let likes = parseInt(localStorage.getItem('likes') || '124');
    likeBtn.textContent = likes;

    likeBtn.parentElement.addEventListener('click', () => {
        likes++;
        likeBtn.textContent = likes;
        localStorage.setItem('likes', likes);
        likeBtn.parentElement.style.transform = 'scale(1.2)';
        setTimeout(() => likeBtn.parentElement.style.transform = 'scale(1)', 200);
    });
}

// Print Article
function printArticle() {
    window.print();
}
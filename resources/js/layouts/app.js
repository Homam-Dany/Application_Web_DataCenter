// resources/js/layouts/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Dark Mode Toggle Logic
    const toggleBtn = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // Initialize Theme check (redundant but safe if theme-init didn't run or dynamic updates needed)
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.setAttribute('data-theme', 'dark');
    } else {
        html.setAttribute('data-theme', 'light');
    }

    // Sync icon on load
    const updateIcon = () => {
        if (!toggleBtn) return;
        const icon = toggleBtn.querySelector('i');
        if (!icon) return;
        if (html.getAttribute('data-theme') === 'dark') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    };

    updateIcon();

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.theme = newTheme;
            updateIcon();

            console.log('Theme changed to:', newTheme);
        });
    }
});

// resources/js/layouts/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Dark Mode Toggle Logic
    const toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;

    const html = document.documentElement;

    // Sync icon on load
    const updateIcon = () => {
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

    toggleBtn.addEventListener('click', function () {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', newTheme);
        localStorage.theme = newTheme;
        updateIcon();

        console.log('Theme changed to:', newTheme);
    });
});

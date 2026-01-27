// resources/js/auth/login.js

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    if (registerBtn && loginBtn && container) {
        registerBtn.addEventListener('click', () => { container.classList.add("active"); });
        loginBtn.addEventListener('click', () => { container.classList.remove("active"); });

        // Détection du mode (Login ou Register) via l'URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('panel') === 'register') {
            container.classList.add("active");
        }
    }
});

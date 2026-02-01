// resources/js/auth/login.js

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.login-content');
    const signUpBtn = document.getElementById('sign-up-btn');
    const signInBtn = document.getElementById('sign-in-btn');

    if (signUpBtn && signInBtn && container) {
        signUpBtn.addEventListener('click', () => {
            container.classList.add("sign-up-mode");
        });

        signInBtn.addEventListener('click', () => {
            container.classList.remove("sign-up-mode");
        });

        // Détection du mode (Login ou Register) via l'URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('panel') === 'register') {
            container.classList.add("sign-up-mode");
        }
    }
});

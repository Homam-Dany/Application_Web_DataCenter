/* resources/js/theme-init.js */

/**
 * Initialisation immédiate du thème pour éviter le FOUC (Flash of Unstyled Content).
 * Ce script doit être exécuté le plus tôt possible dans la balise <head>.
 */
(function () {
    const theme = localStorage.getItem('theme') ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
    }
})();

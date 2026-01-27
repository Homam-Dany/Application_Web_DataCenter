// resources/js/about.js

document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('teamGrid');
    if (grid) {
        // Duplicate content to create seamless infinite scroll
        grid.innerHTML += grid.innerHTML;
    }
});

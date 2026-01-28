// resources/js/incidents/manager.js

document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.incident-row');
    rows.forEach(row => {
        row.addEventListener('mouseover', () => {
            row.style.backgroundColor = '#f9fafb';
        });
        row.addEventListener('mouseout', () => {
            row.style.backgroundColor = 'white';
        });
    });
});

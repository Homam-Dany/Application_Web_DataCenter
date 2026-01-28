/* resources/js/incidents/manager.js */

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-history-btn');
    const historySection = document.getElementById('history-section');

    if (toggleBtn && historySection) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = historySection.style.display === 'none' || historySection.style.display === '';

            if (isHidden) {
                historySection.style.display = 'block';
                toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer l\'historique';
            } else {
                historySection.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-history"></i> Voir l\'historique';
            }
        });
    }
});

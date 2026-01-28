/* resources/js/profile.js */

document.addEventListener('DOMContentLoaded', () => {

    // Toggle Delete Confirmation
    const deleteRevealBtn = document.getElementById('btn-delete-reveal');
    const deleteConfirmSection = document.getElementById('delete-confirmation-section');
    const deleteCancelBtn = document.getElementById('btn-cancel-delete');

    if (deleteRevealBtn && deleteConfirmSection) {
        deleteRevealBtn.addEventListener('click', () => {
            deleteConfirmSection.style.display = 'block';
            deleteRevealBtn.style.display = 'none';
        });
    }

    if (deleteCancelBtn && deleteConfirmSection && deleteRevealBtn) {
        deleteCancelBtn.addEventListener('click', () => {
            deleteConfirmSection.style.display = 'none';
            deleteRevealBtn.style.display = 'inline-block';
        });
    }

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.status-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

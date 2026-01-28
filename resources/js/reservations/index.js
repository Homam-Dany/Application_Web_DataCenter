// resources/js/reservations/index.js

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('incidentModal');
    const closeBtn = document.getElementById('closeIncidentModal');
    const cancelBtn = document.getElementById('cancelIncidentBtn');
    const resourceInput = document.getElementById('modal_resource_id');
    const reportBtns = document.querySelectorAll('.btn-report-problem');

    // Open modal and set resource ID
    reportBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const resourceId = this.dataset.resourceId;
            resourceInput.value = resourceId;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scroll
        });
    });

    const closeModal = () => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    };

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close on click outside
    window.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });
});

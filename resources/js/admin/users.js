// resources/js/admin/users.js

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('rejectionModal');
    const form = document.getElementById('rejectionForm');
    const userNameSpan = document.getElementById('modalUserName');
    const baseRoute = modal ? modal.dataset.route : '';

    window.openRejectionModal = function (userId, userName) {
        if (!modal || !form || !userNameSpan) return;

        modal.style.display = 'flex';
        userNameSpan.innerText = userName;
        form.action = baseRoute.replace(':id', userId);
    };

    window.closeRejectionModal = function () {
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            closeRejectionModal();
        }
    });
});

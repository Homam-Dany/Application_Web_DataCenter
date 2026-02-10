document.addEventListener('DOMContentLoaded', function () {
    if (!window.flashData) return;

    const { success, error, errors } = window.flashData;

    // Success Toast
    if (success) {
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        }).fire({
            icon: 'success',
            title: success,
            background: '#f0fdf4', // Green-50
            color: '#166534'      // Green-800
        });
    }

    // Error Toast
    if (error) {
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        }).fire({
            icon: 'error',
            title: error,
            background: '#fef2f2', // Red-50
            color: '#991b1b'      // Red-800
        });
    }

    // Validation Errors Toast
    if (errors && errors.length > 0) {
        let htmlList = '<ul style="text-align: left; margin-left: 10px;">';
        errors.forEach(err => {
            htmlList += `<li>${err}</li>`;
        });
        htmlList += '</ul>';

        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        }).fire({
            icon: 'warning',
            title: "Plusieurs erreurs détectées",
            html: htmlList,
            background: '#fffbeb', // Amber-50
            color: '#92400e'      // Amber-800
        });
    }
});

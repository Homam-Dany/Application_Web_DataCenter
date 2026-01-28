// resources/js/reservations/create.js

document.addEventListener('DOMContentLoaded', () => {
    const resourceSelect = document.getElementById('resource_id');
    if (resourceSelect) {
        resourceSelect.addEventListener('change', function () {
            window.updateDetails(this);
        });
    }
});

window.updateDetails = function (select) {
    const preview = document.getElementById('preview-card');
    if (!preview) return;

    const option = select.options[select.selectedIndex];

    if (select.value) {
        preview.style.display = 'block';

        const cpu = document.getElementById('p-cpu');
        const ram = document.getElementById('p-ram');
        const storage = document.getElementById('p-storage');
        const os = document.getElementById('p-os');

        if (cpu) cpu.innerText = option.getAttribute('data-cpu');
        if (ram) ram.innerText = option.getAttribute('data-ram');
        if (storage) storage.innerText = option.getAttribute('data-storage');
        if (os) os.innerText = option.getAttribute('data-os');
    } else {
        preview.style.display = 'none';
    }
};

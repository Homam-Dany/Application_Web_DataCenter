// resources/js/admin/logs.js

document.addEventListener('DOMContentLoaded', () => {
    const actionSelect = document.getElementById('logActionSelect');
    if (actionSelect) {
        actionSelect.addEventListener('change', () => {
            actionSelect.closest('form').submit();
        });
    }
});

window.filterLogs = function () {
    const input = document.getElementById('logSearch');
    if (!input) return;

    const filter = input.value.toUpperCase();
    const table = document.getElementById('logTable');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const text = tr[i].textContent || tr[i].innerText;
        if (text.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
};

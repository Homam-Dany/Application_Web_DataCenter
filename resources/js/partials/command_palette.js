document.addEventListener('DOMContentLoaded', function () {
    const backdrop = document.getElementById('command-palette-backdrop');
    if (!backdrop) return; // Guard clause

    const input = document.getElementById('cmd-input');
    const results = document.getElementById('cmd-results');
    const items = results.querySelectorAll('.cmd-item');
    let selectedIndex = 0;

    function openPalette() {
        backdrop.style.display = 'flex';
        input.value = '';
        input.focus();
        filterItems('');
        selectItem(0);
    }

    function closePalette() {
        backdrop.style.display = 'none';
    }

    function selectItem(index) {
        // Re-query items in case of dynamic changes (unlikely here but safer)
        const currentItems = results.querySelectorAll('.cmd-item');
        currentItems.forEach(i => i.classList.remove('selected'));

        const visible = Array.from(currentItems).filter(item => item.style.display !== 'none');

        if (visible.length > 0) {
            if (index < 0) index = visible.length - 1;
            if (index >= visible.length) index = 0;

            visible[index].classList.add('selected');
            visible[index].scrollIntoView({ block: 'nearest' });
            selectedIndex = index;
        }
    }

    function filterItems(query) {
        const lowerQuery = query.toLowerCase();
        let hasVisible = false;

        const currentItems = results.querySelectorAll('.cmd-item');
        currentItems.forEach(item => {
            const text = item.textContent.trim().toLowerCase();
            const meta = item.querySelector('.cmd-item-meta') ? item.querySelector('.cmd-item-meta').textContent.toLowerCase() : '';

            if (text.includes(lowerQuery) || meta.includes(lowerQuery)) {
                item.style.display = 'flex';
                hasVisible = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (hasVisible) selectItem(0);
    }

    // Toggle Shortkey (Ctrl+K or Cmd+K)
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (backdrop.style.display === 'none') {
                openPalette();
            } else {
                closePalette();
            }
        }

        if (e.key === 'Escape' && backdrop.style.display === 'flex') {
            closePalette();
        }
    });

    // Navigation inside palette
    input.addEventListener('keydown', function (e) {
        const currentItems = results.querySelectorAll('.cmd-item');
        const visible = Array.from(currentItems).filter(item => item.style.display !== 'none');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            // Find current selected index in visible array
            let currentVisibleIndex = -1;
            visible.forEach((item, idx) => {
                if (item.classList.contains('selected')) currentVisibleIndex = idx;
            });

            let nextIndex = currentVisibleIndex + 1;
            if (nextIndex >= visible.length) nextIndex = 0;

            if (visible.length > 0) {
                currentItems.forEach(i => i.classList.remove('selected'));
                visible[nextIndex].classList.add('selected');
                visible[nextIndex].scrollIntoView({ block: 'nearest' });
            }

        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            let currentVisibleIndex = -1;
            visible.forEach((item, idx) => {
                if (item.classList.contains('selected')) currentVisibleIndex = idx;
            });

            let prevIndex = currentVisibleIndex - 1;
            if (prevIndex < 0) prevIndex = visible.length - 1;

            if (visible.length > 0) {
                currentItems.forEach(i => i.classList.remove('selected'));
                visible[prevIndex].classList.add('selected');
                visible[prevIndex].scrollIntoView({ block: 'nearest' });
            }

        } else if (e.key === 'Enter') {
            e.preventDefault();
            const selected = results.querySelector('.cmd-item.selected');
            if (selected) {
                selected.click();
                closePalette();
            }
        }
    });

    input.addEventListener('input', function (e) {
        filterItems(e.target.value);
    });

    // Close on click outside
    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closePalette();
    });

    // Hover effects
    const currentItems = results.querySelectorAll('.cmd-item');
    currentItems.forEach(item => {
        item.addEventListener('mouseenter', function () {
            currentItems.forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});

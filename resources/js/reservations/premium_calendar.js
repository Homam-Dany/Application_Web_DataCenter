/* resources/js/reservations/premium_calendar.js */

class PremiumCalendar {
    constructor() {
        this.currentDate = new Date();
        this.selectedStart = null;
        this.selectedEnd = null;
        this.reservedDates = [];

        this.elements = {
            calendarGrid: document.getElementById('calendar-grid'),
            monthTitle: document.getElementById('month-title'),
            startDateDisplay: document.getElementById('start-date-display'),
            endDateDisplay: document.getElementById('end-date-display'),
            startDateInput: document.getElementById('start_date'),
            endDateInput: document.getElementById('end_date'),
            resourceSelect: document.getElementById('resource_id'),
            prevBtn: document.getElementById('prev-month'),
            nextBtn: document.getElementById('next-month')
        };

        this.init();
    }

    init() {
        if (!this.elements.calendarGrid) return;

        this.elements.prevBtn.addEventListener('click', () => this.changeMonth(-1));
        this.elements.nextBtn.addEventListener('click', () => this.changeMonth(1));

        this.elements.resourceSelect.addEventListener('change', () => this.fetchAvailability());

        // Initial fetch if a resource is already selected
        if (this.elements.resourceSelect.value) {
            this.fetchAvailability();
        } else {
            this.render();
        }
    }

    async fetchAvailability() {
        const resourceId = this.elements.resourceSelect.value;
        const urlBase = this.elements.resourceSelect.dataset.availabilityUrlBase;

        if (!resourceId || !urlBase) {
            this.reservedDates = [];
            this.render();
            return;
        }

        const url = urlBase.replace('RESOURCE_ID', resourceId);

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('API Error: ' + response.status);
            this.reservedDates = await response.json();
            this.render();
        } catch (error) {
            console.error('Error fetching availability:', error);
            this.reservedDates = [];
            this.render();
        }
    }

    changeMonth(delta) {
        this.currentDate.setMonth(this.currentDate.getMonth() + delta);
        this.render();
    }

    formatDateLocal(date) {
        if (!date) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    render() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        // Title
        const monthName = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(this.currentDate);
        this.elements.monthTitle.innerText = monthName.charAt(0).toUpperCase() + monthName.slice(1);

        // Header days
        let html = '';
        ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'].forEach(day => {
            html += `<div class="res-calendar-weekday">${day}</div>`;
        });

        // Days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Padding
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="res-calendar-day empty"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateString = this.formatDateLocal(date);

            let classes = ['res-calendar-day'];
            const isPast = date < today;
            const isReserved = this.isDateReserved(date);
            const isSelected = this.isDateSelected(date);

            if (isPast) classes.push('unavailable');
            if (isReserved) classes.push('reserved');
            if (isSelected) classes.push('selected');

            if (this.selectedStart && dateString === this.formatDateLocal(this.selectedStart)) classes.push('range-start');
            if (this.selectedEnd && dateString === this.formatDateLocal(this.selectedEnd)) classes.push('range-end');

            html += `<div class="${classes.join(' ')}" data-date="${dateString}">${day}</div>`;
        }

        this.elements.calendarGrid.innerHTML = html;

        // Click Events
        this.elements.calendarGrid.querySelectorAll('.res-calendar-day:not(.empty):not(.unavailable):not(.reserved)').forEach(el => {
            el.addEventListener('click', () => {
                const parts = el.dataset.date.split('-');
                const clickedDate = new Date(parts[0], parseInt(parts[1]) - 1, parseInt(parts[2]));
                this.handleDateClick(clickedDate);
            });
        });
    }

    isDateReserved(date) {
        const checkStr = this.formatDateLocal(date);

        return this.reservedDates.some(range => {
            // Our standard format is YYYY-MM-DD
            const startStr = range.start_date.substring(0, 10);
            const endStr = range.end_date.substring(0, 10);
            return checkStr >= startStr && checkStr <= endStr;
        });
    }

    isDateSelected(date) {
        if (!this.selectedStart) return false;

        const checkTime = new Date(date).setHours(0, 0, 0, 0);
        const startTime = new Date(this.selectedStart).setHours(0, 0, 0, 0);

        if (!this.selectedEnd) return checkTime === startTime;

        const endTime = new Date(this.selectedEnd).setHours(0, 0, 0, 0);
        return checkTime >= startTime && checkTime <= endTime;
    }

    handleDateClick(date) {
        if (!this.selectedStart || (this.selectedStart && this.selectedEnd)) {
            this.selectedStart = date;
            this.selectedEnd = null;
        } else if (date < this.selectedStart) {
            this.selectedStart = date;
        } else {
            // Check for reserved dates in between
            if (this.hasReservedInBetween(this.selectedStart, date)) {
                this.selectedStart = date;
                this.selectedEnd = null;
            } else {
                this.selectedEnd = date;
            }
        }

        this.updateInputs();
        this.render();
    }

    hasReservedInBetween(start, end) {
        let current = new Date(start);
        while (current <= end) {
            if (this.isDateReserved(current)) return true;
            current.setDate(current.getDate() + 1);
        }
        return false;
    }

    updateInputs() {
        if (this.selectedStart) {
            this.elements.startDateInput.value = this.formatDateLocal(this.selectedStart);
            this.elements.startDateDisplay.innerText = this.formatDateFr(this.selectedStart);
        } else {
            this.elements.startDateInput.value = '';
            this.elements.startDateDisplay.innerText = '---';
        }

        if (this.selectedEnd) {
            this.elements.endDateInput.value = this.formatDateLocal(this.selectedEnd);
            this.elements.endDateDisplay.innerText = this.formatDateFr(this.selectedEnd);
        } else {
            this.elements.endDateInput.value = '';
            this.elements.endDateDisplay.innerText = '---';
        }
    }

    formatDateFr(date) {
        return new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }).format(date);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new PremiumCalendar();
});

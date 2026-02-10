function switchTab(tabId) {
    // Hide all contents
    document.querySelectorAll('.hub-content').forEach(el => el.classList.remove('active'));
    // Deactivate all buttons
    document.querySelectorAll('.hub-tab-btn').forEach(el => el.classList.remove('active'));

    // Activate selected
    document.getElementById(tabId).classList.add('active');
    // Find button that triggered this or find by id if needed. 
    // Since this is global, we need to find the button that corresponds to the tab.
    // The onClick in blade passes 'this' implicitly or we can find it.
    // However, the blade implementation used `event.currentTarget`.
    // In strict JS file, `event` might not be available unless passed.
    // I will attach event listeners in DOMContentLoaded instead of inline onclick to be cleaner?
    // Or I can keep the inline onclick but make sure this function is global.
    // But `event.currentTarget` relies on the event.
    
    // Better approach: Let's attach listeners in JS.
}

// Re-implementing switchTab to be cleaner and not rely on inline onclick
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.hub-tab-btn');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Get target from onclick attribute (legacy) or data attribute.
            // Since I'm refactoring, I should update the HTML to use data-target.
            // But to minimize HTML changes, I'll parse the onclick string OR just use the logic here.
            
            // Wait, I can't easily change the HTML structure without parsing it all.
            // The HTML has `onclick="switchTab('requests')"`
            
            // I will expose switchTab to window so the HTML can still call it, 
            // BUT I need to handle `event` correctly if called from HTML.
        });
    });
});

// Since I am keeping the HTML structure mostly as is, I will expose the function to window.
window.switchTab = function(tabId) {
     document.querySelectorAll('.hub-content').forEach(el => el.classList.remove('active'));
     document.querySelectorAll('.hub-tab-btn').forEach(el => el.classList.remove('active'));
     
     document.getElementById(tabId).classList.add('active');
     
     // Find the button that was clicked. 
     // Creating a query selector to find the button calling this function is hard without the event.
     // I'll rely on the fact that the user clicks it. `event` is usually available in window.event (deprecated but works) or passed.
     
     // Robust way: Find button by some attribute or just look for the one that has the matching onclick? No.
     // Let's iterate buttons.
     // Actually, I'll update the HTML to pass `this` : switchTab('id', this)
     // BUT I can't change HTML arguments easily with search/replace without regex.
     
     // Let's assume standard event bubbling.
     let target = event ? event.currentTarget : null;
     if (target) target.classList.add('active');
     
     if (tabId === 'calendar' && window.calendar) {
         setTimeout(() => {
             window.calendar.render();
         }, 100);
     }
}

var calendar;

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar-wrapper');
    if (!calendarEl) return;

    // Check if FullCalendar is loaded
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar not loaded');
        return;
    }

    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        height: 650,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: "Auj.",
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        events: window.reservationEvents || [],
        eventClick: function (info) {
            alert('Réservation de ' + info.event.extendedProps.user + '\nStatut: ' + info.event.extendedProps.status);
        }
    });
    // Render initially hidden, will be re-rendered on tab switch
    window.calendar.render();
});

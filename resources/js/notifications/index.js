// resources/js/notifications/index.js

document.addEventListener('DOMContentLoaded', function () {
    // Attach listeners to buttons with specific classes
    document.querySelectorAll('.btn-decision-accept').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            window.submitDecision(id, 'accepter');
        });
    });

    document.querySelectorAll('.btn-decision-refuse').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            window.handleRefusal(id);
        });
    });
    const toggleBtn = document.getElementById('toggle-history-btn');
    const historySection = document.getElementById('history-section');
    const toggleText = document.getElementById('toggle-text');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (historySection.style.display === 'none') {
                historySection.style.display = 'block';
                setTimeout(() => {
                    historySection.style.opacity = '1';
                }, 10);
                if (toggleText) toggleText.textContent = "Masquer l'historique";
                toggleBtn.style.background = "var(--primary)";
                toggleBtn.style.color = "white";
            } else {
                historySection.style.opacity = '0';
                setTimeout(() => {
                    historySection.style.display = 'none';
                }, 400);
                const count = toggleBtn.dataset.count;
                if (toggleText) toggleText.textContent = `Voir l'historique (${count})`;
                toggleBtn.style.background = "var(--bg-card)";
                toggleBtn.style.color = "var(--primary)";
            }
        });
    }
});

window.submitDecision = function (id, action) {
    const form = document.getElementById('decision-form-' + id);
    if (!form) return;

    const baseUrl = form.dataset.url;
    form.action = baseUrl + "/" + id + "/" + action;
    form.submit();
};

window.handleRefusal = function (id) {
    const area = document.getElementById('rejection-area-' + id);
    const input = document.getElementById('reason-input-' + id);
    const btn = document.getElementById('refuse-btn-' + id);

    if (!area || !input || !btn) return;

    if (area.style.display === 'none') {
        area.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Confirmer le refus';
        btn.style.backgroundColor = 'white';
        btn.style.color = '#ef4444';
        btn.style.border = '2px solid #ef4444';

        input.focus();
    } else {
        if (input.value.trim().length < 5) {
            alert("Veuillez saisir un motif de refus valide (5 caractères min).");
            return;
        }
        submitDecision(id, 'refuser');
    }
};

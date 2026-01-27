document.addEventListener('DOMContentLoaded', () => {
    
    // 1. ANIMATION DES JAUGES (DASHBOARD)
    const usageBar = document.getElementById('usageFill'); // 
    if (usageBar) {
        const percent = usageBar.getAttribute('data-percent');
        setTimeout(() => {
            usageBar.style.width = percent + '%';
        }, 400);
    }

    // 2. ANIMATION DES CHIFFRES (Effet compteur)
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(num => {
        const target = parseInt(num.innerText);
        if(!isNaN(target)) {
            let count = 0;
            const speed = 2000 / target; // Animation de 2 secondes
            const updateCount = () => {
                if(count < target) {
                    count++;
                    num.innerText = count + (num.innerText.includes('%') ? '%' : '');
                    setTimeout(updateCount, speed);
                }
            };
            num.innerText = "0";
            updateCount();
        }
    });

    // 3. AUTO-FERMETURE DES ALERTES
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 600);
        }, 4000);
    });

    // 4. CONFIRMATION DE SÉCURITÉ
    const deleteButtons = document.querySelectorAll('.btn-delete, .confirm-action');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if(!confirm("Êtes-vous sûr de vouloir effectuer cette action ?")) {
                e.preventDefault();
            }
        });
    });
    // 5. AFFICHAGE DYNAMIQUE DES DÉTAILS DE LA RESSOURCE
    const resourceSelect = document.getElementById('resource_id');
    const detailsContainer = document.getElementById('resource-details-display');

    if (resourceSelect && detailsContainer) {
        resourceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            // On récupère les données stockées dans les attributs data-
            const cpu = selectedOption.getAttribute('data-cpu') || 'N/A';
            const ram = selectedOption.getAttribute('data-ram') || 'N/A';
            const storage = selectedOption.getAttribute('data-storage') || 'N/A';
            const os = selectedOption.getAttribute('data-os') || 'N/A';

            if (this.value !== "") {
                detailsContainer.innerHTML = `
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 10px; color: var(--accent);">Spécifications techniques :</h4>
                        <ul style="list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.9rem;">
                            <li><strong>CPU:</strong> ${cpu} Cores</li>
                            <li><strong>RAM:</strong> ${ram} Go</li>
                            <li><strong>Stockage:</strong> ${storage}</li>
                            <li><strong>Système:</strong> ${os}</li>
                        </ul>
                    </div>
                `;
                detailsContainer.style.display = 'block';
            } else {
                detailsContainer.style.display = 'none';
            }
        });
    }
});
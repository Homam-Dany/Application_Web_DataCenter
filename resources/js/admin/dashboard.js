// resources/js/admin/dashboard.js

document.addEventListener('DOMContentLoaded', () => {
    // Occupancy Chart
    const occupancyCanvas = document.getElementById('occupancyChart');
    if (occupancyCanvas) {
        const active = occupancyCanvas.dataset.active;
        const total = occupancyCanvas.dataset.total;

        new Chart(occupancyCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Alloué', 'Disponible'],
                datasets: [{
                    data: [active, total - active],
                    backgroundColor: ['#6366f1', '#f1f5f9'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#64748b',
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#1e293b',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        displayColors: false
                    }
                }
            }
        });
    }

    // Inventory Chart
    const inventoryCanvas = document.getElementById('inventoryChart');
    if (inventoryCanvas) {
        const labels = JSON.parse(inventoryCanvas.dataset.labels);
        const data = JSON.parse(inventoryCanvas.dataset.values);

        new Chart(inventoryCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 11 } },
                        beginAtZero: true
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 12, weight: '500' } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#1e293b'
                    }
                }
            }
        });
    }

    // [NEW] Incidents Chart
    const incidentsCanvas = document.getElementById('incidentsChart');
    if (incidentsCanvas) {
        const labels = JSON.parse(incidentsCanvas.dataset.labels);
        const data = JSON.parse(incidentsCanvas.dataset.values);

        new Chart(incidentsCanvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right', // Légende à droite pour changer un peu
                        labels: {
                            color: '#64748b',
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b'
                    }
                }
            }
        });
    }

    // [NEW] Live Dashboard Polling (Every 30s)
    setInterval(() => {
        fetch('/admin/api/stats')
            .then(response => response.json())
            .then(data => {
                // Update Text Stats
                if (data.stats) {
                    const occupancyEl = document.getElementById('stat-occupancy');
                    const totalEl = document.getElementById('stat-total');
                    const maintenanceEl = document.getElementById('stat-maintenance');
                    const pendingEl = document.getElementById('stat-pending');
                    const progressBar = document.querySelector('.stat-progress-fill');

                    if (occupancyEl) occupancyEl.innerText = data.stats.occupancy_rate + '%';
                    if (progressBar) progressBar.style.width = data.stats.occupancy_rate + '%';
                    if (totalEl) totalEl.innerText = data.stats.total_resources;
                    if (maintenanceEl) maintenanceEl.innerText = data.stats.maintenance_count;
                    if (pendingEl) pendingEl.innerText = data.stats.pending_accounts;
                }
            })
            .catch(error => console.error('LiveStats Error:', error));
    }, 30000); // 30 seconds
});

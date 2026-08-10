document.addEventListener('DOMContentLoaded', () => {
    const userStatusCanvas = document.getElementById('userStatusChart');
    if (userStatusCanvas) {
        const labels = JSON.parse(userStatusCanvas.dataset.labels);
        const data = JSON.parse(userStatusCanvas.dataset.values);

        const statusColors = {
            'approuvée': '#10b981', // green
            'rejetée': '#ef4444',   // red
            'en_attente': '#f59e0b', // yellow/orange
            'active': '#6366f1',    // blue
            'terminée': '#64748b'   // gray
        };

        const backgroundColors = labels.map(label => {
            const cleanLabel = label.trim().toLowerCase();
            return statusColors[cleanLabel] || '#94a3b8';
        });

        new Chart(userStatusCanvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#64748b',
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 }
                    }
                }
            }
        });
    }
});

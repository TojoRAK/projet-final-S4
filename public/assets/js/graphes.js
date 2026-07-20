document.addEventListener('DOMContentLoaded', function () {
    const palette = {
        retrait: '#1856c4',
        transfert: '#1b8a5a',
    };

    function parseEvolution(canvas) {
        try {
            return JSON.parse(canvas.dataset.evolution || '[]');
        } catch (e) {
            return [];
        }
    }

    function lineChart(canvasId, label, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const data = parseEvolution(canvas);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.map((row) => row.jour),
                datasets: [{
                    label: label,
                    data: data.map((row) => Number(row.gain)),
                    borderColor: color,
                    backgroundColor: color + '22',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    function combinedChart(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const data = parseEvolution(canvas);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.map((row) => row.jour),
                datasets: [
                    {
                        label: 'Retrait',
                        data: data.map((row) => Number(row.retrait)),
                        borderColor: palette.retrait,
                        backgroundColor: palette.retrait + '22',
                        tension: 0.3,
                    },
                    {
                        label: 'Transfert',
                        data: data.map((row) => Number(row.transfert)),
                        borderColor: palette.transfert,
                        backgroundColor: palette.transfert + '22',
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    lineChart('chart-retrait', 'Retrait', palette.retrait);
    lineChart('chart-transfert', 'Transfert', palette.transfert);
    combinedChart('chart-superpose');
});

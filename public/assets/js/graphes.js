document.addEventListener('DOMContentLoaded', function () {
    var palette = ['#1856c4', '#1b8a5a', '#9a3b3b', '#6b7280'];

    function formatNombre(valeur) {
        return Number(valeur).toLocaleString('fr-FR');
    }

    document.querySelectorAll('canvas[data-evolution]').forEach(function (canvas) {
        var data = [];

        try {
            data = JSON.parse(canvas.dataset.evolution || '[]');
        } catch (e) {
            data = [];
        }

        if (! data.length) {
            return;
        }

        var labels = data.map(function (row) { return row.jour; });
        var cles = Object.keys(data[0]).filter(function (k) { return k !== 'jour'; });

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: cles.map(function (cle, i) {
                    var couleur = palette[i % palette.length];
                    return {
                        label: cle.charAt(0).toUpperCase() + cle.slice(1),
                        data: data.map(function (row) { return row[cle]; }),
                        borderColor: couleur,
                        backgroundColor: couleur + '1a',
                        borderWidth: 2,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        tension: 0.35,
                        fill: cles.length === 1
                    };
                })
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        display: cles.length > 1,
                        position: 'top',
                        labels: { boxWidth: 10, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ' : ' + formatNombre(ctx.parsed.y) + ' Ar';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, maxRotation: 0 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: {
                            font: { size: 10 },
                            callback: function (v) { return formatNombre(v); }
                        }
                    }
                }
            }
        });
    });
});
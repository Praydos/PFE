const Chart = window.Chart;
if (!Chart) {
    console.warn('Chart.js not loaded');
}

const palette = {
    blue: '#5b8dee',
    teal: '#0cb8b6',
    violet: '#7c6fcd',
    amber: '#e8a020',
    rose: '#e8506a',
    green: '#28c76f',
    muted: ['#5b8dee', '#0cb8b6', '#7c6fcd', '#e8a020', '#e8506a', '#28c76f', '#9ba8c5'],
};

function baseOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    font: { family: "'DM Sans', sans-serif", size: 11 },
                    color: '#525f7f',
                },
            },
        },
        scales: {
            x: {
                ticks: { font: { family: "'DM Sans', sans-serif", size: 10 }, color: '#9ba8c5' },
                grid: { color: 'rgba(228,231,240,.6)' },
            },
            y: {
                beginAtZero: true,
                ticks: { font: { family: "'DM Sans', sans-serif", size: 10 }, color: '#9ba8c5' },
                grid: { color: 'rgba(228,231,240,.6)' },
            },
        },
    };
}

function makeBar(canvas, labels, values, color = palette.blue) {
    if (!Chart || !canvas) return;
    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: canvas.dataset.label || '',
                data: values,
                backgroundColor: color,
                borderRadius: 6,
                maxBarThickness: 36,
            }],
        },
        options: baseOptions(),
    });
}

function makeLine(canvas, labels, values, color = palette.teal) {
    if (!Chart || !canvas) return;
    return new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: canvas.dataset.label || '',
                data: values,
                borderColor: color,
                backgroundColor: color + '22',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
            }],
        },
        options: baseOptions(),
    });
}

function makeDoughnut(canvas, labels, values) {
    if (!Chart || !canvas) return;
    return new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: palette.muted.slice(0, labels.length),
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: "'DM Sans', sans-serif", size: 11 },
                        color: '#525f7f',
                    },
                },
            },
        },
    });
}

function makePie(canvas, labels, values) {
    if (!Chart || !canvas) return;
    return new Chart(canvas, {
        type: 'pie',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: palette.muted.slice(0, labels.length),
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: "'DM Sans', sans-serif", size: 11 },
                        color: '#525f7f',
                    },
                },
            },
        },
    });
}

function makeGroupedBar(canvas, labels, datasets) {
    if (!Chart || !canvas) return;
    const colors = [palette.blue, palette.green, palette.amber, palette.violet];
    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: datasets.map((ds, i) => ({
                label: ds.label,
                data: ds.values,
                backgroundColor: colors[i % colors.length],
                borderRadius: 5,
                maxBarThickness: 28,
            })),
        },
        options: {
            ...baseOptions(),
            scales: {
                ...baseOptions().scales,
                x: { ...baseOptions().scales.x, stacked: false },
            },
        },
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('dashboard-root');
    if (!root) return;

    let payload = {};
    try {
        payload = JSON.parse(root.dataset.charts || '{}');
    } catch {
        return;
    }

    const d = payload.delegate_stats;
    if (d) {
        const actions = d.actions;
        if (actions?.monthly_realised) {
            makeLine(
                document.getElementById('chart-actions-monthly'),
                actions.monthly_realised.labels,
                actions.monthly_realised.values,
                palette.blue
            );
        }
        if (actions?.by_status) {
            const labels = Object.keys(actions.by_status);
            const values = Object.values(actions.by_status);
            makeDoughnut(document.getElementById('chart-actions-status'), labels, values);
        }

        const specimens = d.specimens;
        if (specimens?.by_status) {
            const labels = Object.keys(specimens.by_status);
            const values = Object.values(specimens.by_status);
            makePie(document.getElementById('chart-specimens-status'), labels, values);
        }
        if (specimens) {
            makeBar(
                document.getElementById('chart-specimens-flow'),
                ['Créés', 'Livrés', 'Adoptés', 'Retours'],
                [specimens.created, specimens.delivered, specimens.adopted, specimens.retour],
                palette.teal
            );
        }

        const activities = d.activities;
        if (activities) {
            makeGroupedBar(
                document.getElementById('chart-activities'),
                ['Examens', 'Formations', 'Événements', 'Tâches'],
                [
                    { label: 'Créés', values: [
                        activities.examens.created,
                        activities.formations.created,
                        activities.events.created,
                        activities.taches.created,
                    ]},
                    { label: 'Terminés', values: [
                        activities.examens.completed,
                        activities.formations.completed,
                        activities.events.completed,
                        activities.taches.completed,
                    ]},
                ]
            );
        }
    }

    const admin = payload.admin_stats;
    if (admin?.crm_distribution) {
        const roles = admin.crm_distribution.users_by_role || {};
        makeDoughnut(
            document.getElementById('chart-admin-roles'),
            Object.keys(roles),
            Object.values(roles)
        );
        const spec = admin.crm_distribution.specimens_by_status || {};
        makePie(
            document.getElementById('chart-admin-specimens'),
            Object.keys(spec),
            Object.values(spec)
        );
    }

    const rbo = payload.rbo_stats;
    if (rbo?.validation) {
        makeBar(
            document.getElementById('chart-rbo-validation'),
            ['En attente', 'Approuvées', 'Rejetées'],
            [rbo.validation.pending, rbo.validation.approved, rbo.validation.rejected],
            palette.violet
        );
    }
    if (rbo?.team) {
        makeBar(
            document.getElementById('chart-rbo-team'),
            ['Délégués', 'Écoles', 'Actifs'],
            [rbo.team.managed_delegates, rbo.team.managed_schools, rbo.team.active_delegates],
            palette.amber
        );
    }
});

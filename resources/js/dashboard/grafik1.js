
import Chart from 'chart.js/auto';

/*
|--------------------------------------------------------------------------
| KIRAAI - DASHBOARD CHARTS
|--------------------------------------------------------------------------
| Grafik utama    : Area chart pemasukan vs pengeluaran.
| Grafik kategori : Horizontal bar chart.
| Sumber data     : JSON dari komponen Livewire.
|--------------------------------------------------------------------------
*/

const activeCharts = new Set();

const rupiahFormatter = new Intl.NumberFormat('id-ID', {
    maximumFractionDigits: 0,
});

/*
|--------------------------------------------------------------------------
| 1. FORMAT NOMINAL RUPIAH
|--------------------------------------------------------------------------
*/

function formatRupiah(value) {
    return `Rp ${rupiahFormatter.format(Number(value) || 0)}`;
}

function formatCompactRupiah(value) {
    const amount = Number(value) || 0;

    if (amount >= 1_000_000_000) {
        return `Rp ${(amount / 1_000_000_000).toLocaleString('id-ID', {
            maximumFractionDigits: 1,
        })} M`;
    }

    if (amount >= 1_000_000) {
        return `Rp ${(amount / 1_000_000).toLocaleString('id-ID', {
            maximumFractionDigits: 1,
        })} jt`;
    }

    if (amount >= 1_000) {
        return `Rp ${(amount / 1_000).toLocaleString('id-ID', {
            maximumFractionDigits: 1,
        })} rb`;
    }

    return formatRupiah(amount);
}

/*
|--------------------------------------------------------------------------
| 2. MENGHAPUS INSTANCE GRAFIK LAMA
|--------------------------------------------------------------------------
*/

function destroyDashboardCharts() {
    activeCharts.forEach((chart) => {
        chart.destroy();
    });

    activeCharts.clear();
}

/*
|--------------------------------------------------------------------------
| 3. MEMBUAT INSTANCE CHART.JS
|--------------------------------------------------------------------------
*/

function createDashboardChart(canvas, config) {
    if (!(canvas instanceof HTMLCanvasElement)) {
        return null;
    }

    // Hindari dua instance Chart.js pada canvas yang sama.
    const existingChart = Chart.getChart(canvas);

    if (existingChart) {
        activeCharts.delete(existingChart);
        existingChart.destroy();
    }

    const chart = new Chart(canvas, config);

    activeCharts.add(chart);

    return chart;
}

/*
|--------------------------------------------------------------------------
| 4. GRAFIK PEMASUKAN VS PENGELUARAN
|--------------------------------------------------------------------------
*/

function renderPerbandinganChart(root, chartData) {
    const canvas = root.querySelector(
        '[data-dashboard-chart="perbandingan"]'
    );

    const data = chartData?.perbandingan;

    if (
        !canvas ||
        !data ||
        !Array.isArray(data.labels) ||
        !Array.isArray(data.pemasukan) ||
        !Array.isArray(data.pengeluaran)
    ) {
        console.warn(
            'Data grafik perbandingan tidak lengkap.',
            data
        );

        return;
    }

    createDashboardChart(canvas, {
        type: 'line',

        data: {
            labels: data.labels,

            datasets: [
                {
                    label: 'Pemasukan',
                    data: data.pemasukan,

                    borderColor: '#176b49',
                    backgroundColor: 'rgba(23, 107, 73, 0.14)',

                    fill: true,
                    tension: 0.38,

                    borderWidth: 2.5,
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round',

                    pointRadius: data.labels.length > 15 ? 2 : 3,
                    pointHoverRadius: 6,

                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#176b49',
                    pointBorderWidth: 2,

                    spanGaps: true,
                },

                {
                    label: 'Pengeluaran',
                    data: data.pengeluaran,

                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.10)',

                    fill: true,
                    tension: 0.38,

                    borderWidth: 2.5,
                    borderCapStyle: 'round',
                    borderJoinStyle: 'round',

                    pointRadius: data.labels.length > 15 ? 2 : 3,
                    pointHoverRadius: 6,

                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#dc3545',
                    pointBorderWidth: 2,

                    spanGaps: true,
                },
            ],
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false,
            },

            animation: {
                duration: 350,
            },

            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',

                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 8,
                        boxHeight: 8,
                        padding: 18,

                        color: '#65756a',

                        font: {
                            size: 11,
                            weight: '600',
                        },
                    },
                },

                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#24352b',
                    bodyColor: '#526359',

                    borderColor: '#e5ede7',
                    borderWidth: 1,

                    titleFont: {
                        weight: '700',
                    },

                    padding: 12,
                    displayColors: true,
                    usePointStyle: true,

                    callbacks: {
                        label(context) {
                            const value = context.parsed.y ?? 0;

                            return `${context.dataset.label}: ${formatRupiah(value)}`;
                        },
                    },
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },

                    border: {
                        display: false,
                    },

                    ticks: {
                        color: '#7b8980',
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 8,

                        font: {
                            size: 10,
                        },
                    },
                },

                y: {
                    beginAtZero: true,

                    grid: {
                        color: 'rgba(148, 163, 152, 0.14)',
                        drawTicks: false,
                    },

                    border: {
                        display: false,
                        dash: [4, 4],
                    },

                    ticks: {
                        color: '#7b8980',
                        padding: 10,

                        callback(value) {
                            return formatCompactRupiah(value);
                        },

                        font: {
                            size: 10,
                        },
                    },
                },
            },
        },
    });
}

/*
|--------------------------------------------------------------------------
| 5. GRAFIK KATEGORI PEMASUKAN DAN PENGELUARAN
|--------------------------------------------------------------------------
*/

function renderKategoriChart(root, selector, categoryData, color) {
    const canvas = root.querySelector(
        `[data-dashboard-chart="${selector}"]`
    );

    // Canvas bisa tidak tersedia ketika Blade menampilkan empty state.
    if (!canvas) {
        return;
    }

    // Pastikan struktur data kategori sesuai dengan Controller.
    if (
        !categoryData ||
        !Array.isArray(categoryData.labels) ||
        !Array.isArray(categoryData.values)
    ) {
        console.warn(
            `Data grafik kategori "${selector}" tidak valid.`,
            categoryData
        );

        return;
    }

    // Hindari membuat grafik kosong jika data tidak tersedia.
    if (
        categoryData.labels.length === 0 ||
        categoryData.values.length === 0
    ) {
        console.info(
            `Belum ada data untuk grafik kategori "${selector}".`
        );

        return;
    }

    // Label dan nilai harus memiliki jumlah elemen yang sama.
    if (categoryData.labels.length !== categoryData.values.length) {
        console.error(
            `Jumlah label dan nilai grafik "${selector}" tidak sesuai.`,
            categoryData
        );

        return;
    }

    createDashboardChart(canvas, {
        type: 'bar',

        data: {
            labels: categoryData.labels,

            datasets: [
                {
                    label: 'Jumlah transaksi',
                    data: categoryData.values,

                    backgroundColor: color,

                    borderRadius: 7,
                    borderSkipped: false,

                    barThickness: 21,
                    maxBarThickness: 24,
                },
            ],
        },

        options: {
            indexAxis: 'y',

            responsive: true,
            maintainAspectRatio: false,

            animation: {
                duration: 350,
            },

            plugins: {
                legend: {
                    display: false,
                },

                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#24352b',
                    bodyColor: '#526359',

                    borderColor: '#e5ede7',
                    borderWidth: 1,

                    padding: 11,

                    callbacks: {
                        label(context) {
                            const total = context.parsed.x ?? 0;

                            return `${total} transaksi`;
                        },
                    },
                },
            },

            scales: {
                x: {
                    beginAtZero: true,

                    grid: {
                        color: 'rgba(148, 163, 152, 0.14)',
                        drawTicks: false,
                    },

                    border: {
                        display: false,
                    },

                    ticks: {
                        precision: 0,
                        color: '#7b8980',
                        padding: 8,

                        font: {
                            size: 10,
                        },
                    },
                },

                y: {
                    grid: {
                        display: false,
                    },

                    border: {
                        display: false,
                    },

                    ticks: {
                        color: '#5f7065',
                        padding: 8,

                        font: {
                            size: 11,
                        },
                    },
                },
            },
        },
    });
}

/*
|--------------------------------------------------------------------------
| 6. MEMBACA JSON DARI LIVEWIRE
|--------------------------------------------------------------------------
*/

function initDashboardCharts() {
    const roots = document.querySelectorAll(
        '[data-dashboard-grafik]'
    );

    destroyDashboardCharts();

    roots.forEach((root) => {
        const dataElement = root.querySelector(
            '[data-dashboard-chart-data]'
        );

        if (!dataElement) {
            console.error(
                '[KiraAI] Elemen JSON grafik tidak ditemukan.'
            );
            return;
        }

        let chartData;

        try {
            chartData = JSON.parse(dataElement.textContent.trim());
        } catch (error) {
            console.error(
                '[KiraAI] JSON grafik tidak valid:',
                error
            );
            return;
        }

        if (!chartData || typeof chartData !== 'object') {
            console.error(
                '[KiraAI] Data grafik bukan objek yang valid.'
            );
            return;
        }

        // Diagnostik: periksa data kategori dari Controller.
        console.log(
            '[KiraAI] Data kategori pemasukan:',
            chartData.kategoriPemasukan
        );

        console.log(
            '[KiraAI] Data kategori pengeluaran:',
            chartData.kategoriPengeluaran
        );

        // Grafik utama diproses secara terpisah.
        if (chartData.perbandingan) {
            try {
                renderPerbandinganChart(root, chartData);
            } catch (error) {
                console.error(
                    '[KiraAI] Grafik perbandingan gagal dibuat:',
                    error
                );
            }
        } else {
            console.warn(
                '[KiraAI] Data perbandingan tidak tersedia.'
            );
        }

        // Grafik kategori pemasukan tetap diproses
        // meskipun grafik utama bermasalah.
        try {
            renderKategoriChart(
                root,
                'kategori-pemasukan',
                chartData.kategoriPemasukan,
                'rgba(23, 107, 73, 0.82)'
            );
        } catch (error) {
            console.error(
                '[KiraAI] Grafik kategori pemasukan gagal dibuat:',
                error
            );
        }

        // Grafik kategori pengeluaran juga berdiri sendiri.
        try {
            renderKategoriChart(
                root,
                'kategori-pengeluaran',
                chartData.kategoriPengeluaran,
                'rgba(220, 53, 69, 0.78)'
            );
        } catch (error) {
            console.error(
                '[KiraAI] Grafik kategori pengeluaran gagal dibuat:',
                error
            );
        }
    });
}

/*
|--------------------------------------------------------------------------
| 7. INISIALISASI HALAMAN
|--------------------------------------------------------------------------
*/

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initDashboardCharts,
        { once: true }
    );
} else {
    initDashboardCharts();
}

/*
|--------------------------------------------------------------------------
| 8. INTEGRASI LIFECYCLE LIVEWIRE
|--------------------------------------------------------------------------
*/

let livewireHookRegistered = false;
let chartRefreshScheduled = false;

function scheduleDashboardChartRefresh() {
    if (chartRefreshScheduled) {
        return;
    }

    chartRefreshScheduled = true;

    requestAnimationFrame(() => {
        chartRefreshScheduled = false;
        initDashboardCharts();
    });
}

function registerDashboardLivewireHooks() {
    if (
        livewireHookRegistered ||
        !window.Livewire ||
        typeof window.Livewire.hook !== 'function'
    ) {
        return;
    }

    livewireHookRegistered = true;

    // Dijalankan setelah DOM komponen selesai di-morph.
    window.Livewire.hook('morphed', ({ el }) => {
        if (!el) {
            return;
        }

        const isDashboardComponent =
            el.matches?.('[data-dashboard-grafik]') ||
            el.querySelector?.('[data-dashboard-grafik]');

        if (isDashboardComponent) {
            scheduleDashboardChartRefresh();
        }
    });
}

// Mendukung Livewire yang belum diinisialisasi ketika file dimuat.
if (window.Livewire) {
    registerDashboardLivewireHooks();
} else {
    document.addEventListener(
        'livewire:init',
        registerDashboardLivewireHooks,
        { once: true }
    );
}

// Mendukung navigasi wire:navigate.
document.addEventListener(
    'livewire:navigated',
    scheduleDashboardChartRefresh
);

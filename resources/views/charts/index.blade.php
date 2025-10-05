<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grafik Perumahan - Sipgar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100">
    {{-- HEADER --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">SIPGAR</a>
                <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm text-sm font-semibold">
                    &larr; Kembali ke Pencarian
                </a>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Grafik Properti Kabupaten Garut</h1>

        {{-- GRAFIK 1: SEBARAN UNIT --}}
        <div class="bg-white p-6 rounded-lg shadow-lg mb-12">
            <h2 class="text-xl font-semibold mb-4">Sebaran Unit Rumah per Kecamatan</h2>
            <div style="height: 600px;">
                <canvas id="unitsChart"></canvas>
            </div>
        </div>

        {{-- GRAFIK 2: RATA-RATA HARGA --}}
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Rata-rata Harga per Meter Persegi (m²)</h2>
            <div style="height: 400px;">
                <canvas id="avgPriceChart"></canvas>
            </div>
        </div>
    </main>

    {{-- SCRIPT UNTUK CHART.JS --}}
    <script type="module">
        import Chart from 'chart.js/auto';

        (function () {
            // Data dari Controller
            const unitsData = @json($unitsPerDistrict);
            const avgPriceData = @json($avgPricePerDistrict);

            // GRAFIK 1: Sebaran Unit
            const unitsCtx = document.getElementById('unitsChart').getContext('2d');
            new Chart(unitsCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(unitsData),
                    datasets: [{
                        label: 'Total Unit Tersedia',
                        data: Object.values(unitsData),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat bar chart menjadi horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // GRAFIK 2: Rata-rata Harga
            const avgPriceCtx = document.getElementById('avgPriceChart').getContext('2d');
            new Chart(avgPriceCtx, {
                type: 'bar',
                data: {
                    labels: avgPriceData.map(d => d.district_name),
                    datasets: [
                        {
                            label: 'Rata-rata Harga Tanah (per m²)',
                            data: avgPriceData.map(d => d.avg_price_per_land),
                            backgroundColor: 'rgba(255, 159, 64, 0.6)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rata-rata Harga Bangunan (per m²)',
                            data: avgPriceData.map(d => d.avg_price_per_building),
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>
</body>
</html>
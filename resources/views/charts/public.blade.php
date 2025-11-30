<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Grafik Perumahan - SIPGAR</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="antialiased bg-gray-100 font-sans">

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Grafik Data Perumahan</h1>
                <p class="mt-3 text-lg text-gray-600">
                    Visualisasi data sebaran dan rata-rata harga perumahan di Kabupaten Garut.
                </p>
            </div>

            {{-- FILTER – UI BARU --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 mb-10">
                <form action="{{ route('charts.index.public') }}" method="GET">

                    <div class="flex flex-wrap items-center justify-between gap-6">

                        <div class="flex flex-wrap items-center gap-4">
                            <label for="type" class="font-medium text-gray-700 text-sm">
                                Filter berdasarkan Tipe:
                            </label>

                            <select id="type" name="type"
                                class="px-4 py-2 rounded-xl border-gray-300 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Tipe</option>
                                <option value="Komersil" {{ $currentFilter == 'Komersil' ? 'selected' : '' }}>Komersil</option>
                                <option value="Subsidi" {{ $currentFilter == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
                            </select>

                            <button type="submit"
                                class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold shadow hover:bg-blue-700 transition">
                                Terapkan Filter
                            </button>

                            <a href="{{ route('charts.index.public') }}"
                                class="px-5 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
                                Reset
                            </a>
                        </div>

                        <a href="{{ route('home') }}"
                            class="inline-flex items-center px-5 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold shadow hover:bg-green-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>

                </form>
            </div>

            {{-- GRID CHART PREMIUM --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- CARD CHART UNIT --}}
                <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">Sebaran Unit Perumahan</h3>
                        <p class="text-sm text-gray-500 mb-4">Total unit yang tersedia di setiap kecamatan.</p>

                        <div style="height: 420px;">
                            <canvas id="unitsChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- CARD CHART HARGA --}}
                <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">Rata-rata Harga Rumah</h3>
                        <p class="text-sm text-gray-500 mb-4">Estimasi rata-rata harga rumah di setiap kecamatan.</p>

                        <div style="height: 420px;">
                            <canvas id="pricesChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <x-public-footer />

    {{-- SCRIPT CHART --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const districtNames = @json($districtNames ?? []);
            const unitsPerDistrict = @json($unitsPerDistrict ?? []);
            const avgPricePerDistrict = @json($avgPricePerDistrict ?? []);

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#374151' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#374151', maxRotation: 80, minRotation: 45 },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#111827', font: { size: 13 } }
                    }
                }
            };

            new Chart(document.getElementById('unitsChart'), {
                type: 'bar',
                data: {
                    labels: districtNames,
                    datasets: [{
                        label: 'Jumlah Unit',
                        data: unitsPerDistrict,
                        backgroundColor: 'rgba(59, 130, 246, 0.45)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1.2,
                        borderRadius: 6
                    }]
                },
                options: chartOptions
            });

            new Chart(document.getElementById('pricesChart'), {
                type: 'bar',
                data: {
                    labels: districtNames,
                    datasets: [{
                        label: 'Rata-rata Harga',
                        data: avgPricePerDistrict,
                        backgroundColor: 'rgba(16, 185, 129, 0.45)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1.2,
                        borderRadius: 6
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        ...chartOptions.scales,
                        y: {
                            ...chartOptions.scales.y,
                            ticks: {
                                ...chartOptions.scales.y.ticks,
                                callback: value => value === 0 ? 'Rp 0' : 'Rp ' + (value / 1_000_000).toFixed(0) + ' Jt'
                            }
                        }
                    }
                }
            });

        });
    </script>

</body>

</html>

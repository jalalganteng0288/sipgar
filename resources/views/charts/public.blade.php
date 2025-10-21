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
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800">Grafik Data Perumahan</h1>
                <p class="mt-2 text-lg text-gray-600">Visualisasi data sebaran dan harga perumahan di Kabupaten Garut.</p>
            </div>

            {{-- Bagian Filter --}}
            <div class="bg-white p-4 rounded-lg shadow-md mb-8">
                <form action="{{ route('charts.index.public') }}" method="GET">
                    {{-- PERBAIKAN: Menggunakan flexbox untuk merapikan posisi tombol --}}
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        {{-- Bagian Kiri: Filter --}}
                        <div class="flex flex-wrap items-center gap-4">
                            <label for="type" class="text-sm font-medium text-gray-700">Filter berdasarkan Tipe:</label>
                            <select name="type" id="type" class="form-select rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Semua Tipe</option>
                                <option value="Komersil" {{ $currentFilter == 'Komersil' ? 'selected' : '' }}>Komersil</option>
                                <option value="Subsidi" {{ $currentFilter == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 transition">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('charts.index.public') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300 transition">
                                Reset
                            </a>
                        </div>

                        {{-- Bagian Kanan: Tombol Kembali --}}
                        <div>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md shadow hover:bg-green-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Grid untuk Grafik --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-1">Sebaran Unit Perumahan</h3>
                        <p class="text-sm text-gray-500 mb-4">Total unit yang tersedia di setiap kecamatan.</p>
                        <div style="height: 400px;">
                            <canvas id="unitsChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-1">Rata-rata Harga Rumah</h3>
                        <p class="text-sm text-gray-500 mb-4">Estimasi rata-rata harga rumah di setiap kecamatan.</p>
                        <div style="height: 400px;">
                            <canvas id="pricesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <x-public-footer />

    {{-- PERBAIKAN: Kode JavaScript lengkap untuk grafik dikembalikan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const districtNames = @json($districtNames ?? []);
            const unitsPerDistrict = @json($unitsPerDistrict ?? []);
            const avgPricePerDistrict = @json($avgPricePerDistrict ?? []);

            // Konfigurasi Umum untuk Chart
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#6b7280' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#374151' }
                    }
                }
            };

            // Inisialisasi Grafik untuk Unit
            const unitsCanvas = document.getElementById('unitsChart');
            if (unitsCanvas) {
                new Chart(unitsCanvas, {
                    type: 'bar',
                    data: {
                        labels: districtNames,
                        datasets: [{
                            label: 'Jumlah Unit',
                            data: unitsPerDistrict,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: chartOptions
                });
            }

            // Inisialisasi Grafik untuk Harga
            const pricesCanvas = document.getElementById('pricesChart');
            if(pricesCanvas) {
                new Chart(pricesCanvas, {
                    type: 'bar',
                    data: {
                        labels: districtNames,
                        datasets: [{
                            label: 'Rata-rata Harga',
                            data: avgPricePerDistrict,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
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
                                    callback: function(value) {
                                        if (value === 0) return 'Rp 0';
                                        return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
{{-- Ganti layout dari 'x-app-layout' menjadi layout publik --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grafik Data Perumahan - Sipgar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Tambahkan script chart.js jika belum ada di layout utama publik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="antialiased bg-gray-100">
    <x-public-header />

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 px-4 sm:px-0">Grafik Data Perumahan</h1>
            
            {{-- Bagian Filter --}}
            {{-- Ganti route() ke nama rute publik yang baru --}}
            <div class="bg-white p-4 rounded-lg shadow-md mb-8">
                <form action="{{ route('charts.index.public') }}" method="GET">
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
                </form>
            </div>

            {{-- Grid untuk Grafik (Sama seperti sebelumnya) --}}
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

    {{-- Script untuk render chart (Sama seperti sebelumnya, hanya dipindahkan ke sini) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const districtNames = @json($districtNames);
            const unitsPerDistrict = @json($unitsPerDistrict);
            const avgPricePerDistrict = @json($avgPricePerDistrict);

            const chartOptions = { /* ... (Opsi chart Anda tidak berubah) ... */ };

            new Chart(document.getElementById('unitsChart'), { /* ... (Kode chart unit Anda tidak berubah) ... */ });
            new Chart(document.getElementById('pricesChart'), { /* ... (Kode chart harga Anda tidak berubah) ... */ });
        });
    </script>
</body>
</html>
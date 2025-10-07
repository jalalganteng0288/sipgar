<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grafik Data Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian Filter --}}
            <div class="bg-white p-4 rounded-lg shadow-md mb-8">
                <form action="{{ route('charts.index') }}" method="GET">
                    <div class="flex items-center space-x-4">
                        <label for="type" class="text-sm font-medium text-gray-700">Filter berdasarkan Tipe:</label>
                        <select name="type" id="type" class="form-select rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Semua Tipe</option>
                            <option value="Komersil" {{ $currentFilter == 'Komersil' ? 'selected' : '' }}>Komersil</option>
                            <option value="Subsidi" {{ $currentFilter == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('charts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Grid untuk Grafik --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Grafik Sebaran Unit --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-1">Sebaran Unit Perumahan</h3>
                        <p class="text-sm text-gray-500 mb-4">Total unit yang tersedia di setiap kecamatan.</p>
                        <div style="height: 400px;">
                            <canvas id="unitsChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik Rata-rata Harga --}}
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
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const districtNames = @json($districtNames);
                const unitsPerDistrict = @json($unitsPerDistrict);
                const avgPricePerDistrict = @json($avgPricePerDistrict);

                // Konfigurasi Umum
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

                // Grafik untuk Unit
                new Chart(document.getElementById('unitsChart'), {
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

                // Grafik untuk Harga
                new Chart(document.getElementById('pricesChart'), {
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
            });
        </script>
    @endpush
</x-app-layout>
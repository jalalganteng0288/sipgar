<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Selamat Datang Personal --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-gray-500">Berikut adalah ringkasan data terbaru dari Sistem Informasi Perumahan
                        Garut.</p>
                </div>
            </div>

            {{-- Kartu Statistik Modern --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                {{-- Card 1: Total Proyek --}}
                <div
                    class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white overflow-hidden shadow-lg rounded-xl p-6 flex flex-col justify-between transform transition-all duration-300 hover:scale-105">
                    <div>
                        <svg class="h-10 w-10 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6m-6 4.5h6M5.25 6.75h.008v.008H5.25V6.75zm0 4.5h.008v.008H5.25v-4.5zm0 4.5h.008v.008H5.25v-4.5z" />
                        </svg>
                        <h3 class="text-3xl font-bold mt-4">{{ number_format($totalProjects) }}</h3>
                    </div>
                    <p class="mt-2 text-sm font-medium opacity-90">Total Proyek</p>
                </div>

                {{-- Card 2: Total Developer --}}
                <div
                    class="bg-gradient-to-br from-green-500 to-teal-600 text-white overflow-hidden shadow-lg rounded-xl p-6 flex flex-col justify-between transform transition-all duration-300 hover:scale-105">
                    <div>
                        <svg class="h-10 w-10 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.284.126A9.094 9.094 0 0112 15.75a9.094 9.094 0 01-2.258.124m-4.282.287a3 3 0 01-4.682-2.72 9.094 9.094 0 013.741-.479M12 12.75a3 3 0 110-6 3 3 0 010 6z" />
                        </svg>
                        <h3 class="text-3xl font-bold mt-4">{{ number_format($totalDevelopers) }}</h3>
                    </div>
                    <p class="mt-2 text-sm font-medium opacity-90">Total Developer</p>
                </div>

                {{-- Card 3: Total Unit --}}
                <div
                    class="bg-gradient-to-br from-yellow-500 to-orange-600 text-white overflow-hidden shadow-lg rounded-xl p-6 flex flex-col justify-between transform transition-all duration-300 hover:scale-105">
                    <div>
                        <svg class="h-10 w-10 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-3 1.091m0 0l-3-1.091m0 0l-1.5-.545M12 12l-3 1.091" />
                        </svg>
                        <h3 class="text-3xl font-bold mt-4">{{ number_format($totalUnits) }}</h3>
                    </div>
                    <p class="mt-2 text-sm font-medium opacity-90">Total Unit</p>
                </div>

                {{-- Card 4: Unit Terjual --}}
                <div
                    class="bg-gradient-to-br from-red-500 to-pink-600 text-white overflow-hidden shadow-lg rounded-xl p-6 flex flex-col justify-between transform transition-all duration-300 hover:scale-105">
                    <div>
                        <svg class="h-10 w-10 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18h16.5M5.25 6h13.5m-13.5 4.5h13.5m-13.5 4.5h13.5M5.25 21V3" />
                        </svg>
                        <h3 class="text-3xl font-bold mt-4">{{ number_format($totalUnitsSold) }}</h3>
                    </div>
                    <p class="mt-2 text-sm font-medium opacity-90">Unit Terjual</p>
                </div>
            </div>

            {{-- === BLOK PETA GIS BARU === --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Peta Persebaran Proyek</h3>

                    {{-- 1. Load Library Leaflet --}}
                    {{-- (Penting: Pastikan ini ada di dalam 'x-app-layout' atau di sini) --}}

                    {{-- 2. Siapkan Kanvas Peta --}}
                    <div id="map" style="height: 500px; width: 100%;" class="mt-4 rounded-lg z-0"></div>

                    {{-- 3. Inisialisasi Peta (Script) --}}
                    {{-- 3. Inisialisasi Peta (Script) --}}
                    {{-- 3. Inisialisasi Peta (Script) --}}
                    @push('scripts')
                        <script>
                            // Tentukan titik tengah (pusat Garut)
                            var map = L.map('map').setView([-7.216639, 107.908611], 12);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            // Ambil data dari controller
                            var geoJsonData = @json($geoJsonData);

                            // Tampilkan marker di peta
                            if (geoJsonData && geoJsonData.length > 0) {
                                L.geoJSON(geoJsonData, {
                                    onEachFeature: function(feature, layer) {
                                        var popupContent = `
                                        <b>${feature.properties.name}</b><br>
                                        Status: ${feature.properties.status}<br>
                                        <a href="${feature.properties.url}" target="_blank">Lihat Detail</a>
                                    `;
                                        layer.bindPopup(popupContent);
                                    }
                                }).addTo(map);
                            } else {
                                console.log("Tidak ada data GeoJSON untuk ditampilkan.");
                            }
                        </script>
                    @endpush
                </div>
            </div>
            {{-- === AKHIR BLOK PETA GIS === --}}

            {{-- Tombol Aksi Cepat --}}
            <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.projects.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Proyek Baru
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

{{-- resources/views/admin/gis/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Judul di Kiri --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Peta Persebaran Perumahan') }}
            </h2>

            {{-- Tombol di Kanan --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <div id="map" style="height: 600px; width: 100%;"></div>

                    <script>
                        // ------------------------------------------------------------------
                        // 1. FUNGSI GENERATOR WARNA (Masih sama seperti sebelumnya)
                        // ------------------------------------------------------------------
                        function stringToColor(str) {
                            var hash = 0;
                            for (var i = 0; i < str.length; i++) {
                                hash = str.charCodeAt(i) + ((hash << 5) - hash);
                            }
                            var color = '#';
                            for (var i = 0; i < 3; i++) {
                                var value = (hash >> (i * 8)) & 0xFF;
                                color += ('00' + value.toString(16)).substr(-2);
                            }
                            return color;
                        }

                        // ------------------------------------------------------------------
                        // 2. FUNGSI PEMBUAT ICON PIN SVG BERWARNA (INI YANG BARU!)
                        // ------------------------------------------------------------------
                        function createColoredPin(color) {
                            // Ini adalah kode gambar SVG bentuk pin lokasi standar
                            const svgIconContent = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="30" height="40">
                <path fill="${color}" stroke="#333" stroke-width="8"
                      d="M384 192c0 87.4-117 243-168.3 307.2c-12.3 15.3-35.1 15.3-47.4 0C117 435 0 279.4 0 192C0 86 86 0 192 0s192 86 192 192z"/>
                <circle cx="192" cy="192" r="64" fill="#fff" />
            </svg>`;

                            // Menggunakan L.divIcon untuk menampung SVG
                            return L.divIcon({
                                className: 'custom-svg-icon', // Class untuk CSS shadow di atas
                                html: svgIconContent,
                                iconSize: [30, 40], // Ukuran icon (lebar, tinggi)
                                iconAnchor: [15, 40], // Titik jangkar (tengah bawah), agar ujung pin pas di koordinat
                                popupAnchor: [0, -45] // Posisi popup muncul di atas pin
                            });
                        }

                        // ------------------------------------------------------------------
                        // 3. INISIALISASI PETA
                        // ------------------------------------------------------------------
                        // Hapus konfigurasi L.Icon.Default yang lama karena kita pakai icon custom
                        var map = L.map('map').setView([-7.216639, 107.908611], 12);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        var geoJsonData = @json($geoJsonData);

                        // ------------------------------------------------------------------
                        // 4. RENDER GEOJSON DENGAN PIN SVG WARNA-WARNI
                        // ------------------------------------------------------------------
                        L.geoJSON(geoJsonData, {
                            // A. pointToLayer: Mengubah titik jadi Marker dengan Icon SVG Custom
                            pointToLayer: function(feature, latlng) {
                                // 1. Generate warna unik berdasarkan nama
                                var uniqueColor = stringToColor(feature.properties.name);

                                // 2. Buat icon pin menggunakan warna tersebut
                                var customIcon = createColoredPin(uniqueColor);

                                // 3. Return marker biasa tapi pakai icon custom kita
                                return L.marker(latlng, {
                                    icon: customIcon
                                });
                            },

                            onEachFeature: function(feature, layer) {
                                var popupContent = `
                <div style="text-align:center; font-family: sans-serif;">
                    <b style="font-size:14px; display:block; margin-bottom:8px;">${feature.properties.name}</b>
                    <a href="${feature.properties.url}" target="_blank"
                       style="background-color:#3b82f6; color:white; padding:6px 12px; text-decoration:none; border-radius:6px; font-size:12px; display:inline-block;">
                       Lihat Detail
                    </a>
                </div>
            `;
                                layer.bindPopup(popupContent);
                            }
                        }).addTo(map);
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta Persebaran Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4">
                {{-- Legend Warna --}}
                <div class="flex gap-4 bg-white p-2 rounded-md shadow-sm border text-xs font-bold">
                    <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Subsidi
                    </div>
                    <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Komersil
                    </div>
                    <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span> Lainnya
                    </div>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <div id="map" style="height: 600px; width: 100%; border-radius: 8px;"></div>

                    <script>
                        // Fungsi Menentukan Warna berdasarkan Tipe
                        function getMarkerColor(type) {
                            if (!type) return '#9ca3af'; // Gray
                            if (type.toLowerCase() === 'subsidi') return '#22c55e'; // Green
                            if (type.toLowerCase() === 'komersil') return '#3b82f6'; // Blue
                            return '#9ca3af';
                        }

                        function createColoredPin(color) {
                            const svgIconContent = `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="30" height="40">
                                <path fill="${color}" stroke="#fff" stroke-width="20"
                                      d="M384 192c0 87.4-117 243-168.3 307.2c-12.3 15.3-35.1 15.3-47.4 0C117 435 0 279.4 0 192C0 86 86 0 192 0s192 86 192 192z"/>
                                <circle cx="192" cy="192" r="70" fill="#fff" />
                            </svg>`;
                            return L.divIcon({
                                className: 'custom-svg-icon',
                                html: svgIconContent,
                                iconSize: [30, 40],
                                iconAnchor: [15, 40],
                                popupAnchor: [0, -45]
                            });
                        }

                        var map = L.map('map').setView([-7.216639, 107.908611], 12);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        var geoJsonData = @json($geoJsonData);

                        L.geoJSON(geoJsonData, {
                            pointToLayer: function(feature, latlng) {
                                // Ambil warna berdasarkan tipe perumahan
                                var color = getMarkerColor(feature.properties.type);
                                var customIcon = createColoredPin(color);
                                return L.marker(latlng, {
                                    icon: customIcon
                                });
                            },
                            onEachFeature: function(feature, layer) {
                                // Cek status untuk kelayakan RT/RW
                                // Ubah baris ini
                                var statusLabel = (feature.properties.status && feature.properties.status.toLowerCase() ===
                                        'approved') ?
                                    '<span style="color:green; font-weight:bold;">Sesuai RT/RW (Layak)</span>' :
                                    '<span style="color:red; font-weight:bold;">Belum Terverifikasi</span>';

                                var popupContent = `
                                    <div style="text-align:center; min-width:150px;">
                                        <b style="font-size:14px; display:block; margin-bottom:5px;">${feature.properties.name}</b>
                                        <div style="font-size:12px; margin-bottom:10px;">
                                            Tipe: <b>${feature.properties.type || '-'}</b><br>
                                            Status: ${statusLabel}
                                        </div>
                                        <a href="${feature.properties.url}" target="_blank"
                                           style="background-color:#3b82f6; color:white; padding:5px 10px; text-decoration:none; border-radius:4px; font-size:11px; display:inline-block;">
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

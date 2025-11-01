{{-- resources/views/admin/gis/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta Persebaran Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <div id="map" style="height: 600px; width: 100%;"></div>

                    <script>
                        // Tentukan titik tengah (pusat Garut)
                        var map = L.map('map').setView([-7.216639, 107.908611], 12);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        var geoJsonData = @json($geoJsonData);

                        L.geoJSON(geoJsonData, {
                            onEachFeature: function (feature, layer) {
                                var popupContent = `
                                    <b>${feature.properties.name}</b><br>
                                    Status: ${feature.properties.status}<br>
                                    <a href="${feature.properties.url}" target="_blank">Lihat Detail</a>
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
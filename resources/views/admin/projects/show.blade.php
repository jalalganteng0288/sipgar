<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Menampilkan nama perumahan sebagai judul --}}
            Detail: {{ $project->name }}
        </h2>
    </x-slot>

    {{-- Memanggil file CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-4">{{ $project->name }}</h1>
                    <p class="text-gray-600 mb-2"><strong>Pengembang:</strong> {{ $project->developer_name }}</p>
                    <p class="text-gray-600 mb-4"><strong>Alamat:</strong> {{ $project->address }}</p>
                    <p class="mb-4">{{ $project->description }}</p>

                    {{-- Placeholder untuk Peta --}}
                    <div id="map" style="height: 400px;" class="mt-4 rounded-lg"></div>

                </div>
            </div>
        </div>
    </div>

    {{-- Memanggil file JavaScript Leaflet (Cukup sekali) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Script untuk menampilkan peta --}}
    <script>
        // Ambil latitude dan longitude dari data project
        // Jika datanya kosong/null, gunakan koordinat default (misal: Garut)
        const lat = {{ $project->latitude ?? -7.21667 }};
        const lng = {{ $project->longitude ?? 107.9 }};

        // Inisialisasi peta dan setel tampilan awal ke koordinat
        var map = L.map('map').setView([lat, lng], 13); // Zoom 13 agar tidak terlalu dekat

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan penanda (marker) di lokasi perumahan hanya jika koordinatnya valid
        if ({{ $project->latitude ? 'true' : 'false' }}) { // Cek jika latitude ada isinya
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>{{ $project->name }}</b><br>{{ $project->address }}')
                .openPopup();
        }
    </script>
</x-app-layout>
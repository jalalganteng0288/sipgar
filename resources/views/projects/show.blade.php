<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail: {{ $project->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Memanggil file CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body class="antialiased bg-gray-100">
    <div class="max-w-4xl mx-auto p-6 lg:p-8">

        {{-- CUKUP SATU TOMBOL KEMBALI --}}
        <div class="mb-4">
            <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                &larr; Kembali ke Daftar Proyek
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $project->name }}</h1>
                <p class="text-gray-600 mb-2"><strong>Pengembang:</strong> {{ $project->developer_name }}</p>
                <p class="text-gray-600 mb-4"><strong>Alamat:</strong> {{ $project->address }}</p>
                <div class="prose max-w-none mb-4">
                    {!! nl2br(e($project->description)) !!}
                </div>

                {{-- Placeholder untuk Peta --}}
                <div id="map" style="height: 400px;" class="mt-4 rounded-lg"></div>
            </div>
        </div>
    </div>

    {{-- Memanggil file JavaScript Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    {{-- Script untuk menampilkan peta --}}
    <script>
        const lat = {{ $project->latitude ?? -7.21667 }};
        const lng = {{ $project->longitude ?? 107.9 }};

        var map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        if ({{ $project->latitude ? 'true' : 'false' }}) {
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>{{ $project->name }}</b><br>{{ $project->address }}')
                .openPopup();
        }
    </script>
</body>

</html>
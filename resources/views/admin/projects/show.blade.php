<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-t">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail: {{ $project->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .hero-section {
            background-image: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url('{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/1200x400.png?text=Lokasi+Perumahan' }}');
            background-size: cover;
            background-position: center;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased bg-gray-100">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">SIPGAR</a>
                <nav>
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600">&larr; Kembali</a>
                </nav>
            </div>
        </div>
    </header>

    <section class="hero-section text-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl font-extrabold tracking-tight drop-shadow-lg">{{ $project->name }}</h1>
            <p class="mt-4 text-lg max-w-3xl opacity-95">
                {{ $project->address }}, {{ optional($project->village)->name }}, {{ optional($project->district)->name }}
                <br>
                <span class="font-semibold">Pengembang:</span> {{ $project->developer_name }}
            </p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'tipe' }">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-8" aria-label="Tabs">
                    <button @click="activeTab = 'tipe'" :class="activeTab === 'tipe' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Tipe Rumah</button>
                    <button @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Detail Proyek</button>
                    <button @click="activeTab = 'galeri'" :class="activeTab === 'galeri' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Galeri</button>
                    <button @click="activeTab = 'siteplan'" :class="activeTab === 'siteplan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Siteplan</button>
                </nav>
            </div>

            <div class="p-6 md:p-8">
                {{-- TAB: TIPE RUMAH (DEFAULT) --}}
                <div x-show="activeTab === 'tipe'" x-cloak>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tipe Rumah Tersedia</h2>
                    <div class="space-y-12">
                        @forelse ($project->houseTypes as $type)
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 border-b pb-8 last:border-b-0">
                                <div class="lg:col-span-1">
                                    <h3 class="text-xl font-bold text-indigo-700 mb-3">{{ $type->name }}</h3>
                                    <img src="{{ $type->image ? asset('storage/' . $type->image) : 'https://via.placeholder.com/400x250.png?text=Foto+Tipe' }}" alt="Foto {{ $type->name }}" class="rounded-lg shadow-md w-full object-cover aspect-video">
                                </div>
                                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Spesifikasi</h4>
                                        <ul class="text-gray-600 space-y-1 text-sm">
                                            <li><strong>Harga:</strong> <span class="text-green-600 font-bold">Rp {{ number_format($type->price, 0, ',', '.') }}</span></li>
                                            <li><strong>Luas Bangunan:</strong> {{ $type->building_area }} m²</li>
                                            <li><strong>Luas Lahan:</strong> {{ $type->land_area }} m²</li>
                                        </ul>
                                        @if($type->specifications)
                                        <h4 class="font-semibold text-gray-700 mt-4 mb-2">Spesifikasi Teknis</h4>
                                        <ul class="text-gray-600 space-y-1 text-sm">
                                            @foreach(json_decode($type->specifications) as $key => $value)
                                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Denah</h4>
                                        <img src="{{ $type->floor_plan ? asset('storage/' . $type->floor_plan) : 'https://via.placeholder.com/400x250.png?text=Denah' }}" alt="Denah {{ $type->name }}" class="rounded-lg border w-full object-cover aspect-video">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-8">Belum ada tipe rumah yang ditambahkan.</p>
                        @endforelse
                    </div>
                </div>

                {{-- TAB: DETAIL --}}
                <div x-show="activeTab === 'detail'" x-cloak>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Proyek</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Peta Lokasi</h3>
                            <div id="map" class="h-96 w-full rounded-lg border"></div>
                        </div>
                        <div class="space-y-6">
                             <div>
                                <h3 class="text-lg font-semibold">Deskripsi</h3>
                                <p class="text-gray-600 mt-1 prose max-w-none">{{ $project->description ?: 'Tidak ada deskripsi.' }}</p>
                            </div>
                             <div>
                                <h3 class="text-lg font-semibold">Kantor Pemasaran</h3>
                                <p class="text-gray-600 mt-1">{{ $project->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: GALERI --}}
                <div x-show="activeTab === 'galeri'" x-cloak>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Galeri Foto</h2>
                     <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if ($project->image)
                            <a href="{{ asset('storage/' . $project->image) }}" target="_blank"><img src="{{ asset('storage/' . $project->image) }}" alt="Foto Utama" class="aspect-video w-full object-cover rounded-lg shadow hover:shadow-xl transition-shadow"></a>
                        @endif
                        @forelse($project->images as $image)
                            <a href="{{ asset('storage/' . $image->path) }}" target="_blank"><img src="{{ asset('storage/' . $image->path) }}" alt="Foto Galeri" class="aspect-video w-full object-cover rounded-lg shadow hover:shadow-xl transition-shadow"></a>
                        @empty
                            @if (!$project->image)<p class="col-span-full text-center text-gray-500 py-8">Tidak ada foto.</p>@endif
                        @endforelse
                    </div>
                </div>

                {{-- TAB: SITEPLAN --}}
                <div x-show="activeTab === 'siteplan'" x-cloak>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Siteplan</h2>
                    @if($project->site_plan)
                        <img src="{{ asset('storage/' . $project->site_plan) }}" alt="Siteplan {{ $project->name }}" class="rounded-lg border w-full">
                    @else
                        <div class="bg-gray-200 rounded-lg flex items-center justify-center min-h-[400px]"><p class="text-gray-500">Siteplan Digital Belum Tersedia.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $project->latitude ?? -7.21667 }};
            const lng = {{ $project->longitude ?? 107.9 }};
            let mapInitialized = false;
            let map = null;

            function initMap() {
                if (mapInitialized) {
                    setTimeout(() => map.invalidateSize(), 100);
                    return;
                };
                mapInitialized = true;

                map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                if ({{ $project->latitude ? 'true' : 'false' }}) {
                    L.marker([lat, lng]).addTo(map).bindPopup('<b>{{ $project->name }}</b>').openPopup();
                }
            }

            const observer = new MutationObserver((mutations) => {
                for (const mutation of mutations) {
                    if (mutation.attributeName === 'style' && mutation.target.style.display !== 'none' && mutation.target.getAttribute('x-show') === 'activeTab === \'detail\'') {
                        initMap();
                        break;
                    }
                }
            });

            const mapContainerParent = document.querySelector('[x-show="activeTab === \'detail\'"]');
            if(mapContainerParent) {
                 observer.observe(mapContainerParent, { attributes: true });
            }
        });
    </script>
</body>
</html>
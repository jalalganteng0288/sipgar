<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sipgar - Sistem Informasi Perumahan Garut</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS Kustom untuk animasi --}}
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="relative min-h-screen">

        {{-- BAGIAN 1: HERO (HANYA BACKGROUND DAN HEADER) --}}
        <section class="relative h-[60vh] flex flex-col justify-start items-center text-white pt-12"
            style="background-image: url('{{ asset('images/bg-hero.jpg') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="animate-fade-in">
                <x-public-header />
            </div>
        </section>

        {{-- BAGIAN 2: KONTEN UTAMA (SEMUA KONTEN SELAIN HERO) --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-40">

            {{-- TOMBOL AKSI & FORM PENCARIAN --}}
            <div x-data="{ activeTab: 'wilayah' }" class="bg-white p-6 rounded-2xl shadow-2xl mb-12 animate-fade-in"
                style="animation-delay: 100ms;">
                <div class="flex items-center space-x-4 mb-6">
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                        LOGIN PENGEMBANG
                    </a>
                    <a href="{{ route('charts.index.public') }}"
                        class="px-5 py-2.5 bg-gray-700 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
                        GRAFIK PERUMAHAN
                    </a>

                </div>
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <button @click="activeTab = 'wilayah'"
                            :class="activeTab === 'wilayah' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            Wilayah Lokasi
                        </button>
                        <button @click="activeTab = 'data'"
                            :class="activeTab === 'data' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            Data Lokasi
                        </button>
                        <button @click="activeTab = 'id_lokasi'"
                            :class="activeTab === 'id_lokasi' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            ID Lokasi
                        </button>
                        <button @click="activeTab = 'id_rumah'"
                            :class="activeTab === 'id_rumah' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            ID Rumah
                        </button>
                    </nav>
                </div>

                {{-- Form Pencarian (satu form untuk semua tab) --}}
                <form action="{{ route('home') }}" method="GET">
                    {{-- Panel Tab 1: Wilayah Lokasi --}}
                    <div x-show="activeTab === 'wilayah'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-5 items-end">
                            <div class="md:col-span-2">
                                <label for="kabupaten"
                                    class="block text-sm font-medium text-gray-500 mb-1">Kabupaten/Kota</label>
                                <select id="kabupaten"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm bg-gray-100"
                                    disabled>
                                    <option>KABUPATEN GARUT</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="district"
                                    class="block text-sm font-medium text-gray-500 mb-1">Kecamatan</label>
                                <select name="district" id="district"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->code }}"
                                            {{ request('district') == $district->code ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="village" class="block text-sm font-medium text-gray-500 mb-1">Desa</label>
                                <select name="village" id="village"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm" disabled>
                                    <option value="">Pilih Kecamatan Dahulu</option>
                                </select>
                            </div>
                            <div class="md:col-span-6">
                                <label for="search" class="block text-sm font-medium text-gray-500 mb-1">Nama
                                    Perumahan</label>
                                <input class="form-input w-full rounded-md border-gray-300 shadow-sm" type="text"
                                    id="search" name="search" placeholder="Cari nama perumahan..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    {{-- Panel Tab 2: Data Lokasi --}}
                    <div x-show="activeTab === 'data'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                            <div>
                                <label for="price_min" class="block text-sm font-medium text-gray-500 mb-1">Harga
                                    Minimum</label>
                                <input class="form-input w-full rounded-md border-gray-300 shadow-sm" type="number"
                                    id="price_min" name="price_min" placeholder="Contoh: 150000000"
                                    value="{{ request('price_min') }}">
                            </div>
                            <div>
                                <label for="price_max" class="block text-sm font-medium text-gray-500 mb-1">Harga
                                    Maksimum</label>
                                <input class="form-input w-full rounded-md border-gray-300 shadow-sm" type="number"
                                    id="price_max" name="price_max" placeholder="Contoh: 500000000"
                                    value="{{ request('price_max') }}">
                            </div>
                        </div>
                    </div>
                    {{-- Panel Tab 3: ID Lokasi --}}
                    <div x-show="activeTab === 'id_lokasi'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                            <div class="md:col-span-2">
                                <label for="project_id" class="block text-sm font-medium text-gray-500 mb-1">ID
                                    Lokasi</label>
                                <input class="form-input w-full rounded-md border-gray-300 shadow-sm" type="text"
                                    id="project_id" name="project_id" placeholder="Masukkan ID Lokasi Perumahan"
                                    value="{{ request('project_id') }}">
                            </div>
                        </div>
                    </div>
                    {{-- Panel Tab 4: ID Rumah --}}
                    <div x-show="activeTab === 'id_rumah'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                            <div class="md:col-span-2">
                                <label for="house_type_id" class="block text-sm font-medium text-gray-500 mb-1">ID
                                    Rumah</label>
                                <input class="form-input w-full rounded-md border-gray-300 shadow-sm" type="text"
                                    id="house_type_id" name="house_type_id" placeholder="Masukkan ID Tipe Rumah"
                                    value="{{ request('house_type_id') }}">
                            </div>
                        </div>
                    </div>
                    {{-- Tombol Cari (berlaku untuk semua tab) --}}
                    <div class="flex justify-end mt-6">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-10 rounded-md shadow-sm transition-colors duration-300"
                            type="submit">
                            CARI
                        </button>
                    </div>
                </form>
            </div>

            {{-- BAGIAN STATISTIK BARU --}}
            <section class="animate-fade-in" style="animation-delay: 200ms;">
                {{-- Total Keseluruhan --}}
                <div class="text-center my-8">
                    <p class="text-xl text-gray-700 font-light">
                        <strong
                            class="font-semibold text-blue-600">{{ number_format($stats['total_developers']) }}</strong>
                        PENGEMBANG,
                        <strong
                            class="font-semibold text-blue-600">{{ number_format($stats['total_locations']) }}</strong>
                        LOKASI,
                        <strong
                            class="font-semibold text-blue-600">{{ number_format($stats['total_units']) }}</strong>
                        UNIT
                    </p>
                </div>

                {{-- Statistik Komersil --}}
                <div class="bg-gray-200 p-4 rounded-lg shadow-md mb-6">
                    <h3 class="font-bold text-gray-800">Komersil <span class="font-light text-sm text-gray-600">|
                            Antrian : {{ number_format($stats['komersil']['antrian']) }}</span></h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mt-2 text-white text-center">
                        <div class="bg-gray-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['komersil']['kavling']) }}</div>
                            <div class="text-sm">Kavling</div>
                        </div>
                        <div class="bg-yellow-800 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['komersil']['pembangunan']) }}
                            </div>
                            <div class="text-sm">Pembangunan</div>
                        </div>
                        <div class="bg-gray-800 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['komersil']['ready_stock']) }}
                            </div>
                            <div class="text-sm">Ready Stock</div>
                        </div>
                        <div class="bg-indigo-600 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['komersil']['dipesan']) }}</div>
                            <div class="text-sm">Dipesan</div>
                        </div>
                        <div class="bg-gray-900 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['komersil']['terjual']) }}</div>
                            <div class="text-sm">Terjual</div>
                        </div>
                    </div>
                </div>

                {{-- Statistik Subsidi --}}
                <div class="bg-yellow-100 p-4 rounded-lg shadow-md mb-12">
                    <h3 class="font-bold text-yellow-900">Subsidi <span class="font-light text-sm text-yellow-800">|
                            Antrian : {{ number_format($stats['subsidi']['antrian']) }}</span></h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mt-2 text-white text-center">
                        <div class="bg-yellow-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['subsidi']['kavling']) }}</div>
                            <div class="text-sm">Kavling</div>
                        </div>
                        <div class="bg-orange-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['subsidi']['pembangunan']) }}</div>
                            <div class="text-sm">Pembangunan</div>
                        </div>
                        <div class="bg-green-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['subsidi']['ready_stock']) }}</div>
                            <div class="text-sm">Ready Stock</div>
                        </div>
                        <div class="bg-blue-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['subsidi']['proses_bank']) }}</div>
                            <div class="text-sm">Proses Bank</div>
                        </div>
                        <div class="bg-red-500 p-3 rounded-md">
                            <div class="font-bold text-lg">{{ number_format($stats['subsidi']['terjual']) }}</div>
                            <div class="text-sm">Terjual</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- DAFTAR KARTU PERUMAHAN --}}
            <section class="mt-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($projects as $project)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 animate-fade-in group"
                            style="animation-delay: {{ 200 + $loop->index * 100 }}ms;">

                            {{-- Bagian Gambar --}}
                            <div class="relative">
                                <a href="{{ route('projects.show', $project) }}" class="block">
                                    <img class="h-56 w-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                                        src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x250.png?text=Gambar+Tidak+Tersedia' }}"
                                        alt="Gambar {{ $project->name }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                </a>
                                <div class="absolute top-0 right-0 mt-3 mr-3">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold text-white rounded-full {{ $project->type == 'Subsidi' ? 'bg-green-600' : 'bg-blue-600' }}">
                                        {{ $project->type }}
                                    </span>
                                </div>
                                {{-- Indikator Lokasi Aktif --}}
                                @if ($project->latitude && $project->longitude)
                                    <div
                                        class="absolute bottom-0 left-0 mb-3 ml-3 flex items-center bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 21l-4.95-6.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Lokasi Aktif
                                    </div>
                                @endif
                            </div>

                            {{-- Bagian Konten --}}
                            <div class="p-5 flex-grow flex flex-col">
                                <p class="text-xs text-gray-500">{{ $project->developer_name }}</p>
                                <h3 class="text-lg font-bold text-gray-900 mt-1">
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="hover:text-indigo-600 transition-colors">{{ $project->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-2 flex-grow">
                                    {{ Str::limit($project->address, 70) }}
                                </p>

                                {{-- Garis Pemisah --}}
                                <hr class="my-4">

                                {{-- Info Unit & Harga --}}
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-semibold text-gray-800">
                                        {{ $project->houseTypes()->sum('total_units') }} Unit
                                    </span>
                                    <p class="text-blue-600 font-bold text-lg">
                                        @if ($project->houseTypes()->min('price'))
                                            Rp {{ number_format($project->houseTypes()->min('price'), 0, ',', '.') }}
                                        @else
                                            <span class="text-sm font-medium text-gray-500">Hubungi Pemasaran</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Tombol Lihat Detail --}}
                            <div class="px-5 pb-5 pt-1">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="block w-full text-center bg-indigo-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                                    Lihat Detail Lokasi
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-lg shadow-md animate-fade-in"
                            style="animation-delay: 300ms;">
                            <p class="text-xl text-gray-500">Oops! Tidak ada perumahan yang cocok dengan kriteria Anda.
                            </p>
                            <p class="text-gray-400 mt-1">Coba ubah kata kunci pencarian atau filter Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-16">
                    {{ $projects->appends(request()->query())->links() }}
                </div>
            </section>

        </main>
    </div>

    {{-- SCRIPT UNTUK DROPDOWN DESA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const districtSelect = document.getElementById('district');
            const villageSelect = document.getElementById('village');

            function fetchVillages(districtCode) {
                if (!districtCode) {
                    villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu</option>';
                    villageSelect.disabled = true;
                    return;
                }

                villageSelect.innerHTML = '<option value="">Memuat...</option>';
                villageSelect.disabled = true;

                fetch(`/get-villages/${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        villageSelect.innerHTML = '<option value="">Semua Desa</option>';
                        // Di sini kita gunakan Object.entries agar lebih modern
                        for (const [code, name] of Object.entries(data)) {
                            const option = document.createElement('option');
                            option.value = code;
                            option.textContent = name;
                            villageSelect.appendChild(option);
                        }
                        villageSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching villages:', error);
                        villageSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                    });
            }

            districtSelect.addEventListener('change', function() {
                fetchVillages(this.value);
            });
        }

        districtSelect.addEventListener('change', function() {
            fetchVillages(this.value);
        });

        if (districtSelect.value) {
            fetchVillages(districtSelect.value);
            setTimeout(() => {
                const oldVillage = "{{ request('village') }}";
                if (oldVillage) {
                    villageSelect.value = oldVillage;
                }
            }, 500);
        }
        });
    </script>

    <x-public-footer />
</body>

</html>

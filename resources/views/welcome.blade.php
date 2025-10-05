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
    </head>

    <body class="antialiased bg-gray-100">
        <div class="relative min-h-screen">
            {{-- HEADER --}}
            {{-- HEADER --}}
            <header class="bg-white shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <div>
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">SIPGAR</a>
                        </div>
                        {{-- HANYA TAMPILKAN TOMBOL LOGIN JIKA PENGGUNA ADALAH TAMU --}}
                        <div>
                            @guest
                                <a href="{{ route('login') }}"
                                    class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm text-sm font-semibold">
                                    Login Pengembang
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </header>

            {{-- KONTEN UTAMA --}}
            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

                {{-- TOMBOL AKSI ATAS --}}
                <section class="mb-6 flex space-x-4">
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                        LOGIN PENGEMBANG
                    </a>
                    {{-- GANTI href PADA TOMBOL INI --}}
                    <a href="{{ route('charts.index') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                        GRAFIK PERUMAHAN
                    </a>
                </section>

                {{-- FORM PENCARIAN & FILTER BARU --}}
                <section class="bg-white p-6 rounded-lg shadow-lg">
                    {{-- Navigasi Tab (Styling Saja) --}}
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6">
                            <a href="#"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm text-blue-600 border-blue-600">
                                Wilayah Lokasi
                            </a>
                            <a href="#"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Data Lokasi
                            </a>
                            <a href="#"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                ID Lokasi
                            </a>
                            <a href="#"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                ID Rumah
                            </a>
                        </nav>
                    </div>

                    <form action="{{ route('home') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                            {{-- Placeholder Provinsi --}}
                            <div>
                                <label for="provinsi" class="block text-sm font-medium text-gray-500 mb-1">Pilih
                                    Provinsi</label>
                                <select id="provinsi"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    disabled>
                                    <option>JAWA BARAT</option>
                                </select>
                            </div>

                            {{-- Placeholder Kabupaten/Kota --}}
                            <div>
                                <label for="kabupaten" class="block text-sm font-medium text-gray-500 mb-1">Pilih
                                    Kabupaten/Kota</label>
                                <select id="kabupaten"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    disabled>
                                    <option>KABUPATEN GARUT</option>
                                </select>
                            </div>

                            {{-- Dropdown Kecamatan --}}
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-500 mb-1">Pilih
                                    Kecamatan</label>
                                <select name="district" id="district"
                                    class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->code }}"
                                            {{ request('district') == $district->code ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Input Nama Perumahan --}}
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-500 mb-1">Nama
                                    Perumahan</label>
                                <input
                                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    type="text" id="search" name="search" placeholder="Cari nama perumahan"
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Tombol Cari --}}
                        <div class="flex justify-end mt-6">
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-10 rounded-md shadow-sm"
                                type="submit">
                                CARI
                            </button>
                        </div>
                    </form>
                </section>

                {{-- Teks Ringkasan Data --}}
                <section class="text-center my-8">
                    <p class="text-gray-800 font-bold">
                        <span class="text-blue-600">{{ number_format($stats['total_projects'], 0, ',', '.') }}</span>
                        PENGEMBANG,
                        <span class="text-blue-600">{{ number_format($stats['total_locations'], 0, ',', '.') }}</span>
                        LOKASI,
                        <span class="text-blue-600">{{ number_format($stats['total_units'], 0, ',', '.') }}</span> UNIT
                    </p>
                </section>


                {{-- DAFTAR KARTU PERUMAHAN --}}
                <section class="mt-12">
                    {{-- Bagian ini sama seperti sebelumnya, untuk menampilkan hasil pencarian --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($projects as $project)
                            <div
                                class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transition-transform duration-300 hover:-translate-y-2">
                                <a href="{{ route('projects.show', $project) }}">
                                    <img class="h-56 w-full object-cover"
                                        src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x250.png?text=Gambar+Tidak+Tersedia' }}"
                                        alt="Gambar {{ $project->name }}">
                                </a>
                                <div class="p-5 flex-grow">
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <a href="{{ route('projects.show', $project) }}"
                                            class="hover:text-indigo-600">{{ $project->name }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{-- Icon Lokasi --}}
                                        <svg class="w-4 h-4 inline-block -mt-1 mr-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $project->district->name ?? 'Lokasi tidak diketahui' }}, KAB. GARUT
                                    </p>
                                    <div class="mt-4 flex justify-between items-center text-sm">
                                        <span
                                            class="inline-block bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                                            {{ $project->houseTypes()->sum('units_available') }} Unit Tersedia
                                        </span>
                                        <p class="text-gray-700 font-semibold">
                                            @if ($project->houseTypes()->min('price'))
                                                Mulai Rp
                                                {{ number_format($project->houseTypes()->min('price'), 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="px-5 py-4 bg-gray-50 border-t">
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                                        Lihat Detail Lokasi
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-lg shadow-md">
                                <p class="text-xl text-gray-500">Oops! Tidak ada perumahan yang cocok dengan kriteria
                                    Anda.</p>
                                <p class="text-gray-400 mt-1">Coba ubah kata kunci pencarian atau filter Anda.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-16">
                        {{ $projects->appends(request()->query())->links() }}
                    </div>
                </section>
            </main>
        </div>
    </body>

    </html>

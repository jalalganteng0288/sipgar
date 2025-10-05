<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sipgar - Sistem Informasi Perumahan Garut</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen">
        {{-- HEADER --}}
        <header class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    {{-- Logo --}}
                    <div>
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                            SIPGAR
                        </a>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm text-sm font-semibold">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm text-sm font-semibold">
                                    Login Pengembang
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- KONTEN UTAMA --}}
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            {{-- FORM PENCARIAN & FILTER --}}
            <section class="mb-12">
                <form action="{{ route('home') }}" method="GET"
                    class="w-full p-6 border rounded-lg bg-white shadow-lg">
                    <div class="flex items-center border-b mb-6">
                        <button type="button" class="py-2 px-4 text-blue-600 border-b-2 border-blue-600 font-semibold">
                            Wilayah Lokasi
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Perumahan
                            </label>
                            <input
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text" id="search" name="search" placeholder="Contoh: Griya Candra Asri"
                                value="{{ request('search') }}">
                        </div>
                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">
                                Kecamatan
                            </label>
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
                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1">
                                Rentang Harga
                            </label>
                            <select name="price_range" id="price_range"
                                class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Harga</option>
                                <option value="0-200000000"
                                    {{ request('price_range') == '0-200000000' ? 'selected' : '' }}> &lt; Rp 200 Juta</option>
                                <option value="200000000-500000000"
                                    {{ request('price_range') == '200000000-500000000' ? 'selected' : '' }}>Rp 200 Juta -
                                    Rp 500 Juta</option>
                                <option value="500000000-9999999999"
                                    {{ request('price_range') == '500000000-9999999999' ? 'selected' : '' }}> &gt; Rp 500
                                    Juta</option>
                            </select>
                        </div>
                        <div>
                            <button
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-md shadow-sm"
                                type="submit">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            {{-- DAFTAR KARTU PERUMAHAN --}}
            <section>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($projects as $project)
                        <div
                            class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transition-transform duration-300 hover:scale-105">
                            <a href="{{ route('projects.show', $project) }}">
                                <img class="h-56 w-full object-cover"
                                    src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x250.png?text=Gambar+Tidak+Tersedia' }}"
                                    alt="Gambar {{ $project->name }}">
                            </a>
                            <div class="p-5 flex-grow">
                                <h3 class="text-xl font-bold text-gray-800">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">
                                        {{ $project->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4 inline-block -mt-1 mr-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16">
                            <p class="text-xl text-gray-500">Oops! Tidak ada perumahan yang cocok dengan kriteria Anda.
                            </p>
                            <p class="text-gray-400 mt-1">Coba ubah kata kunci pencarian atau filter Anda.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination Links --}}
                <div class="mt-16">
                    {{ $projects->links() }}
                </div>
            </section>
        </main>
    </div>
</body>

</html>
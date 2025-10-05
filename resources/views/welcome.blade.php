<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Info Perumahan Garut</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-100">
    <div class="relative min-h-screen">
        @if (Route::has('login'))
            <div class="p-6 text-right">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 inline-block px-5 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <h1 class="text-4xl font-bold text-gray-800">Sistem Informasi Perumahan Garut</h1>
            </div>

            {{-- FORM PENCARIAN DENGAN FILTER KECAMATAN --}}
            <div class="mt-8 mb-16 flex justify-center">
                <form action="{{ url('/') }}" method="GET" class="w-full max-w-4xl">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 border rounded-lg bg-white shadow-md">

                        {{-- Input Nama Perumahan --}}
                        <div class="col-span-1">
                            <input
                                class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" name="search" placeholder="Cari nama perumahan..."
                                value="{{ request('search') }}">
                        </div>

                        {{-- Dropdown Kecamatan --}}
                        <div class="col-span-1">
                            <select name="district"
                                class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($districts as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ request('district') == $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Cari --}}
                        <div class="col-span-1">
                            <button
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm"
                                type="submit">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>


            {{-- Ganti bagian ini di resources/views/welcome.blade.php --}}

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($projects as $project)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                            <a href="{{ route('projects.show', $project) }}">
                                <img class="h-48 w-full object-cover"
                                    src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x250.png?text=Gambar+Tidak+Tersedia' }}"
                                    alt="Gambar {{ $project->name }}">
                            </a>
                            <div class="p-4 bg-yellow-100 border-t border-yellow-300 flex-grow">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">
                                        {{ Str::upper($project->name) }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{-- Mengambil nama kecamatan dari relasi --}}
                                    {{ $project->district->name ?? 'Lokasi tidak diketahui' }}, KAB. GARUT
                                </p>

                                <div class="mt-4">
                                    <span class="inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                        {{ $project->houseTypes()->sum('units_available') }} Unit tersedia
                                    </span>
                                    {{-- Anda bisa menambahkan info lain di sini --}}
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="block w-full text-center bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                                    Lihat Detail Lokasi
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 col-span-3">Tidak ada data perumahan yang cocok dengan
                            pencarian.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</body>

</html>

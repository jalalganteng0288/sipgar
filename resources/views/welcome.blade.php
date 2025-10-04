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


            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($projects as $project)
                        {{-- KARTU DIBUNGKUS DENGAN LINK YANG BENAR --}}
                        <a href="{{ route('projects.show', $project) }}" class="block">
                            <div
                                class="scale-100 p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 motion-safe:hover:scale-[1.01] transition-all duration-250 h-full">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $project->name }}</h2>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Oleh: {{ $project->developer_name }}
                                    </p>
                                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                                        {{ Str::limit($project->description, 100) }}
                                    </p>
                                </div>
                            </div>
                        </a>
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

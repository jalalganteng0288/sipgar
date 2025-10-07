<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- =============================================================== --}}
            {{-- AWAL PERBAIKAN --}}
            {{-- =============================================================== --}}

            {{-- Tombol Aksi Utama (Tombol Tambah Proyek Dikembalikan) --}}
            <div class="mb-8 flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-700">Ringkasan Data</h3>
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Proyek Baru
                </a>
            </div>

            {{-- Kartu Statistik dengan Ikon dan Animasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Card 1: Total Proyek --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 flex items-center space-x-4 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6m-6 4.5h6M5.25 6.75h.008v.008H5.25V6.75zm0 4.5h.008v.008H5.25v-4.5zm0 4.5h.008v.008H5.25v-4.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Proyek</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalProjects) }}</p>
                    </div>
                </div>

                {{-- Card 2: Total Developer --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 flex items-center space-x-4 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-green-100 p-4 rounded-full">
                        <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.284.126A9.094 9.094 0 0112 15.75a9.094 9.094 0 01-2.258.124m-4.282.287a3 3 0 01-4.682-2.72 9.094 9.094 0 013.741-.479M12 12.75a3 3 0 110-6 3 3 0 010 6z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Developer</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalDevelopers) }}</p>
                    </div>
                </div>

                {{-- Card 3: Total Unit --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 flex items-center space-x-4 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-yellow-100 p-4 rounded-full">
                         <svg class="h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-3 1.091m0 0l-3-1.091m0 0l-1.5-.545M12 12l-3 1.091" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Unit</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalUnits) }}</p>
                    </div>
                </div>
                
                {{-- Card 4: Unit Terjual --}}
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 flex items-center space-x-4 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="bg-red-100 p-4 rounded-full">
                        <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18h16.5M5.25 6h13.5m-13.5 4.5h13.5m-13.5 4.5h13.5M5.25 21V3" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Unit Terjual</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalUnitsSold) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            {{-- =============================================================== --}}
            {{-- AKHIR PERBAIKAN --}}
            {{-- =============================================================== --}}
        </div>
    </div>
</x-app-layout>
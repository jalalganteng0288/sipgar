<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Aksi Utama --}}
            <div class="mb-6">
                <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kelola Data Perumahan
                </a>
            </div>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500">Total Proyek Perumahan</h3>
                        <p class="mt-1 text-4xl font-semibold text-gray-900">
                            {{ $totalProjects }}
                        </p>
                    </div>
                </div>

                {{-- Contoh Kartu Lain --}}
                {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500">Ready Stock</h3>
                        <p class="mt-1 text-4xl font-semibold text-gray-900">
                            0
                        </p>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</x-app-layout>
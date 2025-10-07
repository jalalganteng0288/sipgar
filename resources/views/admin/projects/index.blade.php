<x-app-layout>
    <x-slot name="header">
        {{-- AWAL PERBAIKAN --}}
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Perumahan') }}
            </h2>
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
        {{-- AKHIR PERBAIKAN --}}
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                {{-- Loop untuk setiap proyek --}}
                @forelse ($projects as $project)
                    <div
                        class="bg-white overflow-hidden shadow-lg rounded-xl p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            {{-- Informasi Proyek --}}
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-gray-900">
                                    <a href="{{ route('admin.projects.show', $project) }}"
                                        class="hover:text-indigo-600 transition-colors">{{ $project->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $project->developer_name }}</p>
                                <p class="text-sm text-gray-600 mt-2">{{ $project->address }},
                                    {{ optional($project->village)->name }}, {{ optional($project->district)->name }}
                                </p>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0 flex items-center space-x-3">
                                <a href="{{ route('admin.projects.show', $project) }}" title="Lihat Detail"
                                    class="p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 rounded-full transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.projects.edit', $project) }}" title="Edit"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-full transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada data proyek --}}
                    <div class="bg-white text-center p-12 rounded-lg shadow-md">
                        <p class="text-xl text-gray-500">Belum ada data proyek perumahan.</p>
                        <p class="text-sm text-gray-400 mt-2">Gunakan tombol "Tambah Proyek Baru" di halaman Dashboard
                            untuk memulai.</p>
                    </div>
                @endforelse
            </div>
            {{-- Tampilkan Paginasi jika ada --}}
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

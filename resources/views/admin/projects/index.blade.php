<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Perumahan') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PERBAIKAN: Mengubah layout menjadi Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse ($projects as $project)
                    {{-- Kartu Proyek Modern --}}
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        {{-- Gambar Latar Belakang Kartu --}}
                        <div class="absolute inset-0 z-0">
                            <img src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x500.png?text=No+Image' }}" alt="Foto {{ $project->name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                            {{-- Overlay Gradien Gelap --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        </div>

                        {{-- Konten Teks di Atas Gambar --}}
                        <div class="relative z-10 p-6 flex flex-col h-full text-white">
                            {{-- Header Kartu: Tipe & Status --}}
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-xs font-semibold uppercase tracking-wider py-1 px-3 rounded-full {{ $project->project_type === 'Subsidi' ? 'bg-blue-500/80' : 'bg-green-500/80' }}">
                                    {{ $project->project_type }}
                                </span>
                                @if($project->created_at->isAfter(now()->subDays(7)))
                                    <span class="text-xs font-semibold py-1 px-3 rounded-full bg-yellow-500/80 animate-pulse">
                                        Terbaru
                                    </span>
                                @endif
                            </div>

                            {{-- Informasi Utama --}}
                            <div class="mt-auto">
                                <h3 class="text-2xl font-bold drop-shadow-lg">{{ $project->name }}</h3>
                                <p class="text-sm opacity-80 drop-shadow-md">{{ $project->developer_name }}</p>
                                <p class="text-xs opacity-70 mt-2 drop-shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                    {{ optional($project->district)->name }}
                                </p>
                            </div>

                            {{-- Detail Tambahan --}}
                            <div class="border-t border-white/20 mt-4 pt-4 flex justify-between text-sm">
                                <div class="text-center">
                                    <span class="font-bold text-lg">{{ $project->house_types_count }}</span>
                                    <span class="block text-xs opacity-70">Tipe Rumah</span>
                                </div>
                                <div class="text-center">
                                    <span class="font-bold text-lg">{{ $project->total_units }}</span>
                                    <span class="block text-xs opacity-70">Total Unit</span>
                                </div>
                                <div class="text-center">
                                    <span class="font-bold text-lg">{{ $project->available_units }}</span>
                                    <span class="block text-xs opacity-70">Unit Tersedia</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi (Muncul saat hover) --}}
                        <div class="absolute inset-0 z-20 bg-black/50 flex items-center justify-center space-x-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('admin.projects.show', $project) }}" class="p-3 bg-white/20 rounded-full hover:bg-white/40 transform hover:scale-110 transition-all" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <a href="{{ route('admin.projects.edit', $project) }}" class="p-3 bg-white/20 rounded-full hover:bg-white/40 transform hover:scale-110 transition-all" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-white/20 rounded-full hover:bg-red-500/80 transform hover:scale-110 transition-all" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white text-center p-12 rounded-lg shadow-md">
                        <p class="text-xl text-gray-500">Belum ada data proyek perumahan.</p>
                        <p class="text-sm text-gray-400 mt-2">Gunakan tombol "Tambah Proyek Baru" di halaman Dashboard untuk memulai.</p>
                    </div>
                @endforelse
            </div>
            
            {{-- Paginasi --}}
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
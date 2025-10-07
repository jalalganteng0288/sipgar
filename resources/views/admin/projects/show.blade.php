<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Proyek: <span class="font-normal">{{ $project->name }}</span>
            </h2>
            <a href="{{ route('admin.projects.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Proyek
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white p-8 rounded-lg shadow-lg">
                {{-- Bagian informasi proyek tidak perlu diubah, biarkan seperti sebelumnya --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h3>
                        <p class="text-md text-gray-600 mt-1">oleh {{ $project->developer_name }}</p>
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-sm font-semibold mt-4 px-3 py-1 rounded-full">{{ $project->type }}</span>
                        <p class="mt-6 text-gray-700 leading-relaxed">{{ $project->description }}</p>
                        <div class="mt-6 border-t pt-6">
                            <h4 class="font-semibold text-gray-800">Detail Lokasi:</h4>
                            <p class="text-gray-600 mt-2">{{ $project->address }}, {{ optional($project->village)->name }}, {{ optional($project->district)->name }}</p>
                            @if($project->latitude && $project->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $project->latitude }},{{ $project->longitude }}" target="_blank" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Lihat di Google Maps</a>
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-1 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                            <img src="{{ $project->image ? asset('storage/' . $project->image) : 'https://via.placeholder.com/400x300' }}" alt="Gambar Proyek" class="mt-2 rounded-lg shadow-md w-full h-auto object-cover">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Site Plan</label>
                            <img src="{{ $project->site_plan ? asset('storage/' . $project->site_plan) : 'https://via.placeholder.com/400x300' }}" alt="Site Plan" class="mt-2 rounded-lg shadow-md w-full h-auto object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Manajemen Tipe Rumah</h3>
                    {{-- Tombol ini akan mengarah ke form tambah tipe rumah --}}
                    <a href="{{ route('admin.projects.house-types.create', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-700">
                        Tambah Tipe Rumah
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Unit</th>
                                <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($project->houseTypes as $houseType)
                                <tr>
                                    <td class="py-4 px-6 whitespace-nowrap font-medium text-gray-900">{{ $houseType->name }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap text-gray-600">Rp {{ number_format($houseType->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($houseType->status == 'Tersedia') bg-green-100 text-green-800
                                            @elseif($houseType->status == 'Booking') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $houseType->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-gray-600">{{ $houseType->total_units }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.house-types.edit', $houseType->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.house-types.destroy', $houseType->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Yakin ingin menghapus tipe rumah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-6 text-center text-gray-500">
                                        Belum ada tipe rumah untuk proyek ini. Silakan tambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
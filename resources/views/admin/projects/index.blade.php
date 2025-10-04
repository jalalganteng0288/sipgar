<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol Tambah Data --}}
                    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 ...">
                        Tambah Data Proyek
                    </a>

                    {{-- Tabel untuk Menampilkan Data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Perumahan</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Pengembang</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Alamat</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($projects as $project)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $project->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $project->developer_name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $project->address }}</td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 py-4">
                                        Belum ada data proyek perumahan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
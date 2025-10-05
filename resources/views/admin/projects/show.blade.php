{{-- resources/views/admin/projects/show.blade.php --}}

<x-app-layout>
    {{-- ... (kode untuk menampilkan detail proyek) --}}

    <div class="mt-8">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Tipe Rumah</h2>
            <a href="{{ route('admin.house-types.create', $project) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Tipe Rumah
            </a>
        </div>

        <div class="mt-4">
            {{-- Tabel untuk menampilkan daftar tipe rumah --}}
            @if ($project->houseTypes->count() > 0)
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Tipe</th>
                            <th scope="col" class="px-6 py-3">Harga</th>
                            <th scope="col" class="px-6 py-3">Luas Tanah</th>
                            <th scope="col" class="px-6 py-3">Luas Bangunan</th>
                            <th scope="col" class="px-6 py-3">Unit Tersedia</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->houseTypes as $houseType)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4">{{ $houseType->name }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($houseType->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $houseType->land_area }} m²</td>
                                <td class="px-6 py-4">{{ $houseType->building_area }} m²</td>
                                <td class="px-6 py-4">{{ $houseType->units_available }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('house-types.edit', $houseType) }}"
                                        class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('house-types.destroy', $houseType) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada data tipe rumah untuk proyek ini.</p>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            <i class="fas fa-users mr-2 text-blue-600"></i> Manajemen Developer
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-xl p-6 border border-gray-100">

                {{-- Header Section --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-3 sm:space-y-0">
                    <a href="{{ route('admin.developers.create') }}"
                        class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition-all duration-300">
                        <i class="fas fa-plus"></i> Tambah Developer
                    </a>

                    <input type="text" x-model="search" placeholder="Cari developer..."
                        class="w-full sm:w-64 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700" />
                </div>

                {{-- Table Section --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 uppercase">Email</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($developers as $developer)
                                <tr x-show="{{ json_encode($developer->name) }}.toLowerCase().includes(search.toLowerCase()) ||
                                            {{ json_encode($developer->email) }}.toLowerCase().includes(search.toLowerCase())"
                                    class="hover:bg-blue-50 transition duration-150 ease-in-out">

                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $developer->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $developer->email }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.developers.edit', $developer->id) }}"
                                                class="text-yellow-500 hover:text-yellow-600 font-semibold transition-colors duration-150">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>

                                            <form action="{{ route('admin.developers.destroy', $developer->id) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-semibold transition-colors duration-150">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($developers->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-gray-500 italic">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada data developer
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="mt-6 text-sm text-gray-500 flex justify-end">
                    <span>Total: {{ $developers->count() }} developer</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js untuk reaktivitas --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- Font Awesome untuk ikon --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</x-app-layout>

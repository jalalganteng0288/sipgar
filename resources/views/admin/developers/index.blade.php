<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- Judul Halaman --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Developer') }}
            </h2>

            {{-- Tombol Kembali --}}
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold">{{ __('Semua Developer Terdaftar') }}</h3>
                        <!-- Tombol Tambah Developer Baru -->
                        <a href="{{ route('admin.developers.create') }}">
                            <x-primary-button>
                                {{ __('Tambah Developer Baru') }}
                            </x-primary-button>
                        </a>
                    </div>

                    <!-- Menampilkan Pesan Sukses/Error -->
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tabel Daftar Developer -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Perusahaan / Kontak') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Info Akun') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status Akses') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Aksi') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($developers as $developer)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <p class="font-semibold">{{ $developer->company_name }}</p>
                                            <span class="text-xs text-gray-500">{{ $developer->address }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($developer->user)
                                                <p class="font-medium">{{ $developer->user->name }}</p>
                                                <p class="text-xs">{{ $developer->user->email }}</p>
                                            @else
                                                <span
                                                    class="text-red-500 font-semibold">{{ __('User Tidak Ditemukan') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($developer->user && $developer->user->hasRole('developer'))
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Nonaktif/Suspended
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.developers.edit', $developer) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                {{ __('Edit') }}
                                            </a>

                                            <!-- Tombol Aktif/Nonaktif (Suspend Access) -->
                                            @if ($developer->user)
                                                <form action="{{ route('admin.developers.toggleRole', $developer) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        onclick="return confirm('{{ $developer->user->hasRole('developer') ? 'Yakin NONAKTIFKAN akses Developer ini? Developer tidak bisa menambah/mengelola proyek.' : 'Yakin AKTIFKAN kembali akses Developer ini?' }}')"
                                                        class="text-xs font-medium px-2 py-1 rounded 
                                                                    {{ $developer->user->hasRole('developer') ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white' }}">
                                                        {{ $developer->user->hasRole('developer') ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Tombol Hapus Total -->
                                            <form action="{{ route('admin.developers.destroy', $developer) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('PERINGATAN! Anda yakin ingin MENGHAPUS developer, akun user, dan semua tautan ke proyek mereka secara PERMANEN?')"
                                                    class="text-red-600 hover:text-red-900 ml-3">
                                                    {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            {{ __('Belum ada data developer yang ditambahkan.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $developers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

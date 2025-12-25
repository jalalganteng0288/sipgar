<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tipe Rumah untuk Proyek: <span class="font-normal">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <form action="{{ route('admin.projects.house-types.store', $project->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Tipe (Contoh: Tipe 36/72)')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required />
                        </div>
                        <div>
                            <x-input-label for="price" :value="__('Harga')" />
                            <x-text-input id="price" name="price" type="number" class="mt-1 block w-full"
                                :value="old('price')" required />
                        </div>
                        <div>
                            <x-input-label for="land_area" :value="__('Luas Tanah (m²)')" />
                            <x-text-input id="land_area" name="land_area" type="number" class="mt-1 block w-full"
                                :value="old('land_area')" required />
                        </div>
                        <div>
                            <x-input-label for="building_area" :value="__('Luas Bangunan (m²)')" />
                            <x-text-input id="building_area" name="building_area" type="number"
                                class="mt-1 block w-full" :value="old('building_area')" required />
                        </div>
                        <div>
                            <x-input-label for="status" :value="__('Status Unit')" />
                            <select id="status" name="status"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Ready Stock">Ready Stock</option>
                                <option value="Kavling">Kavling</option>
                                <option value="Pembangunan">Pembangunan</option>
                                <option value="Dipesan">Dipesan</option>
                                <option value="Proses Bank">Proses Bank</option>
                                <option value="Terjual">Terjual</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="total_units" :value="__('Total Unit')" />
                            <x-text-input id="total_units" name="total_units" type="number" class="mt-1 block w-full"
                                required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="ready_stock" :value="__('Unit Tersedia (Ready Stock)')" />
                            <x-text-input id="ready_stock" name="ready_stock" type="number" class="mt-1 block w-full"
                                placeholder="Jumlah unit yang siap huni" required />
                            <p class="text-xs text-gray-500 mt-1">*Angka ini yang akan muncul di Dashboard Admin.</p>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Deskripsi Singkat Tipe Rumah')" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        {{-- AWAL PERBAIKAN --}}
                        <div class="md:col-span-1">
                            <x-input-label for="image" :value="__('Foto Tipe Rumah')" />
                            <input id="image" name="image" type="file"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        </div>
                        <div class="md:col-span-1">
                            <x-input-label for="floor_plan" :value="__('Gambar Denah')" />
                            <input id="floor_plan" name="floor_plan" type="file"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        </div>
                        {{-- AKHIR PERBAIKAN --}}
                    </div>
                    <div class="flex items-center justify-end gap-4 mt-8">
                        <a href="{{ route('admin.projects.show', $project->id) }}"
                            class="text-gray-600 hover:underline">Batal</a>
                        <x-primary-button>Simpan Tipe Rumah</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

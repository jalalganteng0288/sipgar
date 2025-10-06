<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Tipe Rumah: {{ $houseType->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('house-types.update', $houseType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Tipe')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name', $houseType->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="floor_plan" :value="__('Gambar Denah')" />
                                <input id="floor_plan" name="floor_plan" type="file" class="block mt-1 w-full" />
                                @if ($houseType->floor_plan)
                                    <img src="{{ asset('storage/' . $houseType->floor_plan) }}" alt="Denah"
                                        class="w-48 h-auto mt-2 rounded">
                                @endif
                            </div>

                            <div class="mt-4">
                                <x-input-label for="specifications" :value="__('Spesifikasi Teknis (Format JSON)')" />
                                <textarea id="specifications" name="specifications" rows="8"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm">{{ old('specifications', json_encode(json_decode($houseType->specifications), JSON_PRETTY_PRINT)) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Contoh: {"Pondasi": "Batu Kali", "Dinding": "Bata
                                    Merah"}</p>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Simpan Perubahan') }}
                                </x-primary-button>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="image" :value="__('Foto Tipe Rumah')" />
                                <input id="image" name="image" type="file" class="block mt-1 w-full" />
                                @if ($houseType->image)
                                    <img src="{{ asset('storage/' . $houseType->image) }}" alt="Foto Tipe"
                                        class="w-48 h-auto mt-2 rounded">
                                @endif
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Harga')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price"
                                    :value="old('price', $houseType->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="land_area" :value="__('Luas Tanah (m²)')" />
                                <x-text-input id="land_area" class="block mt-1 w-full" type="number" name="land_area"
                                    :value="old('land_area', $houseType->land_area)" required />
                                <x-input-error :messages="$errors->get('land_area')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="building_area" :value="__('Luas Bangunan (m²)')" />
                                <x-text-input id="building_area" class="block mt-1 w-full" type="number"
                                    name="building_area" :value="old('building_area', $houseType->building_area)" required />
                                <x-input-error :messages="$errors->get('building_area')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="units_available" :value="__('Unit Tersedia')" />
                                <x-text-input id="units_available" class="block mt-1 w-full" type="number"
                                    name="units_available" :value="old('units_available', $houseType->units_available)" required />
                                <x-input-error :messages="$errors->get('units_available')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="specifications" :value="__('Spesifikasi Teknis (Format JSON)')" />
                                <textarea id="specifications" name="specifications" rows="10"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm">
        {{ old('specifications', json_encode($houseType->specifications, JSON_PRETTY_PRINT)) }}
    </textarea>
                                <p class="mt-1 text-xs text-gray-500">Contoh: {"Pondasi": "Batu Kali", "Dinding": "Bata
                                    Merah"}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.projects.show', $houseType->housing_project_id) }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

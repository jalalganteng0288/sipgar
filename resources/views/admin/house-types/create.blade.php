<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tipe Rumah untuk Proyek: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.projects.house-types.store', $project) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Status Unit --}}
                            <div>
                                <x-input-label for="status" :value="__('Status Unit')" />
                                <select id="status" name="status"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Ready Stock" {{ old('status') == 'Ready Stock' ? 'selected' : '' }}>
                                        Ready Stock</option>
                                    <option value="Kavling" {{ old('status') == 'Kavling' ? 'selected' : '' }}>Kavling
                                    </option>
                                    <option value="Pembangunan" {{ old('status') == 'Pembangunan' ? 'selected' : '' }}>
                                        Pembangunan</option>
                                    <option value="Dipesan" {{ old('status') == 'Dipesan' ? 'selected' : '' }}>Dipesan
                                    </option>
                                    <option value="Proses Bank" {{ old('status') == 'Proses Bank' ? 'selected' : '' }}>
                                        Proses Bank</option>
                                    <option value="Terjual" {{ old('status') == 'Terjual' ? 'selected' : '' }}>Terjual
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="price" :value="__('Harga')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price"
                                    :value="old('price')" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="land_area" :value="__('Luas Tanah (m²)')" />
                                <x-text-input id="land_area" class="block mt-1 w-full" type="number" name="land_area"
                                    :value="old('land_area')" required />
                                <x-input-error :messages="$errors->get('land_area')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="building_area" :value="__('Luas Bangunan (m²)')" />
                                <x-text-input id="building_area" class="block mt-1 w-full" type="number"
                                    name="building_area" :value="old('building_area')" required />
                                <x-input-error :messages="$errors->get('building_area')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="units_available" :value="__('Unit Tersedia')" />
                                <x-text-input id="units_available" class="block mt-1 w-full" type="number"
                                    name="units_available" :value="old('units_available')" required />
                                <x-input-error :messages="$errors->get('units_available')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.projects.show', $project) }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

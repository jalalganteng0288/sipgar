<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Perumahan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Menampilkan error validasi umum jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa kesalahan dengan input Anda.</span>
                        </div>
                    @endif

                    {{-- Form harus memiliki enctype untuk upload file --}}
                    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama Perumahan --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Nama Pengembang --}}
                        <div class="mt-4">
                            <x-input-label for="developer_name" :value="__('Nama Pengembang')" />
                            <x-text-input id="developer_name" class="block mt-1 w-full" type="text"
                                name="developer_name" :value="old('developer_name')" required />
                            <x-input-error :messages="$errors->get('developer_name')" class="mt-2" />
                        </div>

                        {{-- Alamat --}}
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Input Gambar Utama --}}
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Gambar Utama')" />
                            <input id="image"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- =============================================================== --}}
                        {{-- BAGIAN LOKASI YANG DIPERBAIKI --}}
                        {{-- =============================================================== --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="district_code" :value="__('Kecamatan')" />
                                <select id="district_code" name="district_code" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Kecamatan</option>
                                    {{-- Opsi kecamatan akan diisi oleh JavaScript --}}
                                </select>
                                <x-input-error :messages="$errors->get('district_code')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                                <select id="village_code" name="village_code" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                                </select>
                                <x-input-error :messages="$errors->get('village_code')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Grid untuk Koordinat --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            {{-- Latitude --}}
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text"
                                    name="latitude" :value="old('latitude')" placeholder="-7.21667" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>

                            {{-- Longitude --}}
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text"
                                    name="longitude" :value="old('longitude')" placeholder="107.90000" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>


                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.projects.index') }}"
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

    {{-- =============================================================== --}}
    {{-- SCRIPT JAVASCRIPT YANG DIPERBAIKI --}}
    {{-- =============================================================== --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Ganti dengan kode kabupaten Anda. Contoh: 3205 untuk Garut.
                const cityCode = '3205'; 
                
                const districtSelect = document.getElementById('district_code');
                const villageSelect = document.getElementById('village_code');
                const oldDistrict = "{{ old('district_code') }}";
                const oldVillage = "{{ old('village_code') }}";

                // Fungsi untuk mengambil data dari API publik laravolt/indonesia
                async function fetchData(url) {
                    try {
                        let response = await fetch(url);
                        return await response.json();
                    } catch (error) {
                        console.error('Gagal mengambil data:', error);
                        return [];
                    }
                }

                // Fungsi untuk mengisi dropdown
                function populateSelect(selectElement, data, selectedValue = null) {
                    selectElement.innerHTML = `<option value="">${selectElement.id === 'district_code' ? 'Pilih Kecamatan' : 'Pilih Desa/Kelurahan'}</option>`;
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.code;
                        option.textContent = item.name;
                        if (item.code == selectedValue) {
                            option.selected = true;
                        }
                        selectElement.appendChild(option);
                    });
                }

                // Memuat kecamatan saat halaman dibuka
                fetchData(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${cityCode}.json`)
                    .then(districts => {
                        populateSelect(districtSelect, districts, oldDistrict);
                        // Jika ada data lama untuk kecamatan, trigger change untuk memuat desa
                        if(oldDistrict) {
                             districtSelect.dispatchEvent(new Event('change'));
                        }
                    });

                // Event listener saat kecamatan dipilih
                districtSelect.addEventListener('change', function () {
                    const districtCode = this.value;
                    villageSelect.innerHTML = '<option value="">Memuat...</option>';
                    if (districtCode) {
                        fetchData(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtCode}.json`)
                            .then(villages => {
                                populateSelect(villageSelect, villages, oldVillage);
                            });
                    } else {
                        villageSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
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

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa kesalahan dengan input Anda.</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama Perumahan --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Nama Pengembang & Tipe Proyek --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="developer_name" :value="__('Nama Pengembang')" />
                                <x-text-input id="developer_name" class="block mt-1 w-full" type="text" name="developer_name" :value="old('developer_name')" required />
                                <x-input-error :messages="$errors->get('developer_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="type" :value="__('Tipe Proyek')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Komersil" {{ old('type') == 'Komersil' ? 'selected' : '' }}>Komersil</option>
                                    <option value="Subsidi" {{ old('type') == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        
                        {{-- Kecamatan & Desa --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="district_code" :value="__('Kecamatan')" />
                                <select id="district_code" name="district_code" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach($districts as $code => $name)
                                        <option value="{{ $code }}" {{ old('district_code') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('district_code')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                                <select id="village_code" name="village_code" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required disabled>
                                    <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                                </select>
                                <x-input-error :messages="$errors->get('village_code')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Latitude & Longitude --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude')" placeholder="Contoh: -7.21667" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude')" placeholder="Contoh: 107.90000" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi Proyek')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        {{-- Gambar Utama & Siteplan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="image" :value="__('Gambar Utama')" />
                                <input id="image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="image" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="site_plan" :value="__('Gambar Siteplan')" />
                                <input id="site_plan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="site_plan" />
                                <x-input-error :messages="$errors->get('site_plan')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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
            const districtSelect = document.getElementById('district_code');
            const villageSelect = document.getElementById('village_code');
            // Nilai lama (jika ada error validasi)
            const oldVillage = "{{ old('village_code') }}";

            function fetchVillages(districtCode, selectedVillage = null) {
                if (!districtCode) {
                    villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu</option>';
                    villageSelect.disabled = true;
                    return;
                }

                villageSelect.innerHTML = '<option value="">Memuat...</option>';
                villageSelect.disabled = true;

                // Menggunakan rute yang sudah kita punya di web.php
                fetch(`{{ route('dependent-dropdown.villages') }}?district_code=${districtCode}`)
                    .then(response => response.json())
                    .then(data => {
                        villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        for (const code in data) {
                            const option = document.createElement('option');
                            option.value = code;
                            option.textContent = data[code];
                            if (code === selectedVillage) {
                                option.selected = true;
                            }
                            villageSelect.appendChild(option);
                        }
                        // Aktifkan dropdown setelah data dimuat
                        villageSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching villages:', error);
                        villageSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                        villageSelect.disabled = true;
                    });
            }

            // Event listener saat dropdown kecamatan berubah
            districtSelect.addEventListener('change', function () {
                fetchVillages(this.value);
            });

            // Pemicu awal jika ada data kecamatan lama (misalnya saat validasi error)
            if (districtSelect.value) {
                fetchVillages(districtSelect.value, oldVillage);
            }
        });
    </script>
    @endpush
</x-app-layout>
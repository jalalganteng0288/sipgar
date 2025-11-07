<x-app-layout>
    <x-slot name="header">
        {{-- AWAL PERBAIKAN --}}
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Data Proyek Baru') }}
            </h2>
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
        {{-- AKHIR PERBAIKAN --}}
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">1. Informasi Utama Proyek</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="developer_name" :value="__('Nama Developer')" />
                            <x-text-input id="developer_name" name="developer_name" type="text"
                                class="mt-1 block w-full" :value="old('developer_name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('developer_name')" />
                        </div>
                        <div>
                            <x-input-label for="type" :value="__('Tipe Proyek')" />
                            <select id="type" name="type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Komersil" {{ old('type') == 'Komersil' ? 'selected' : '' }}>Komersil
                                </option>
                                <option value="Subsidi" {{ old('type') == 'Subsidi' ? 'selected' : '' }}>Subsidi
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">2. Lokasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                            <textarea id="address" name="address" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                        <div>
                            <!-- Kecamatan -->
                            <x-input-label for="district_code" :value="__('Kecamatan')" />
                            <select id="district_code" name="district_code" class="mt-1 block w-full">
                                <option value="">{{ __('Pilih Kecamatan') }}</option>
                                @forelse ($districts ?? [] as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @empty
                                    <option value="">{{ __('Tidak ada data kecamatan') }}</option>
                                @endforelse
                            </select>

                            <!-- Desa / Kelurahan -->
                            <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                            <select id="village_code" name="village_code" class="mt-1 block w-full">
                                <option value="">{{ __('Pilih Kecamatan Dahulu') }}</option>
                                @forelse ($villages ?? [] as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @empty
                                    {{-- kosong awalnya --}}
                                @endforelse
                            </select>

                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-input-label for="developer_id" :value="__('Developer')" />
                        <select id="developer_id" name="developer_id"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih Developer</option>
                            @forelse ($districts ?? [] as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @empty
                                <option value="">Tidak ada data kecamatan</option>
                            @endforelse

                        </select>
                        <x-input-error :messages="$errors->get('developer_id')" class="mt-2" />
                    </div>
                    <div class="mt-6">
                        <label class="block font-medium text-sm text-gray-700">Pilih Titik Koordinat (Latitude &
                            Longitude)</label>
                        <div id="map" class="mt-2 h-64 w-full rounded-md shadow-sm"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" name="latitude" type="text"
                                    class="mt-1 block w-full bg-gray-100" :value="old('latitude', '-7.2278')" readonly />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" name="longitude" type="text"
                                    class="mt-1 block w-full bg-gray-100" :value="old('longitude', '107.9087')" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">3. Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="image" :value="__('Gambar Utama (Thumbnail)')" />
                            <input id="image" name="image" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'image-preview')">
                            <img id="image-preview" src="" alt="Image Preview"
                                class="mt-4 rounded-md shadow-sm" style="display:none; max-height: 200px;">
                        </div>
                        <div>
                            <x-input-label for="site_plan" :value="__('Gambar Site Plan')" />
                            <input id="site_plan" name="site_plan" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'site_plan-preview')">
                            <img id="site_plan-preview" src="" alt="Site Plan Preview"
                                class="mt-4 rounded-md shadow-sm" style="display:none; max-height: 200px;">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.projects.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">Batal</a>
                    <x-primary-button>{{ __('Simpan Proyek') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fungsi preview gambar
            function previewImage(event, previewId) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById(previewId);
                    output.src = reader.result;
                    output.style.display = 'block';
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Dropdown dinamis
                const districtSelect = document.getElementById('district_code');
                const villageSelect = document.getElementById('village_code');
                const oldVillage = "{{ old('village_code') }}";

                function fetchVillages(districtCode, selectedVillage = null) {
                    if (!districtCode) {
                        villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu</option>';
                        villageSelect.disabled = true;
                        return;
                    }
                    villageSelect.disabled = false;
                    fetch(`/get-villages/${districtCode}`)
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
                        });
                }
                districtSelect.addEventListener('change', () => fetchVillages(districtSelect.value));
                if (districtSelect.value) {
                    fetchVillages(districtSelect.value, oldVillage);
                }

                // Peta Leaflet
                const latInput = document.getElementById('latitude');
                const lonInput = document.getElementById('longitude');
                const map = L.map('map').setView([latInput.value, lonInput.value], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                let marker = L.marker([latInput.value, lonInput.value]).addTo(map);

                map.on('click', function(e) {
                    latInput.value = e.latlng.lat;
                    lonInput.value = e.latlng.lng;
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);
                });
            });
        </script>
    @endpush
</x-app-layout>

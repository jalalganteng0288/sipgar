<x-app-layout>
    {{-- ================================================================= --}}
    {{-- TAMBAHAN: LIBRARY LEAFLET & GEOCODER (WAJIB ADA) --}}
    {{-- ================================================================= --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                </svg>
                {{ __('Tambah Proyek Perumahan') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Gagal Menyimpan!</strong>
                    <span class="block sm:inline">Terdapat kesalahan input:</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-10">
                @csrf

                {{-- INFORMASI UTAMA --}}
                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-2">
                        <span class="text-indigo-600">①</span> Informasi Utama Proyek
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                placeholder="Contoh: Griya Harmoni" :value="old('name')" required />
                        </div>

                        @if (auth()->user()->hasRole('admin'))
                            <div>
                                <x-input-label for="developer_id" :value="__('Pilih Developer')" />
                                <select id="developer_id" name="developer_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                    <option value="">Pilih Perusahaan Developer</option>
                                    @foreach ($developers as $dev)
                                        <option value="{{ $dev->id }}"
                                            {{ old('developer_id') == $dev->id ? 'selected' : '' }}>
                                            {{ $dev->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('developer_id')" class="mt-2" />
                            </div>
                        @else
                            <div>
                                <x-input-label :value="__('Nama Developer')" />
                                <x-text-input class="block mt-1 w-full bg-gray-100" type="text" :value="auth()->user()->developer->company_name ?? 'PROFIL ANDA BELUM LENGKAP'"
                                    readonly disabled />
                                @if (!auth()->user()->developer)
                                    <x-input-error :messages="['Data perusahaan Anda belum terhubung. Proyek tidak bisa dibuat.']" class="mt-2" />
                                @endif
                            </div>
                        @endif
                        <div>
                            <x-input-label for="type" :value="__('Tipe Proyek')" />
                            <select id="type" name="type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Tipe</option>
                                <option value="Komersil" {{ old('type') == 'Komersil' ? 'selected' : '' }}>Komersil
                                </option>
                                <option value="Subsidi" {{ old('type') == 'Subsidi' ? 'selected' : '' }}>Subsidi
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- LOKASI --}}
                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-2">
                        <span class="text-indigo-600">②</span> Lokasi Proyek
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                            <textarea id="address" name="address" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <x-input-label for="district_code" :value="__('Kecamatan')" />
                            <select id="district_code" name="district_code"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districts ?? [] as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ old('district_code') == $code ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>

                            <x-input-label for="village_code" :value="__('Desa/Kelurahan')" class="mt-4" />
                            <select id="village_code" name="village_code"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Pilih Kecamatan Dahulu') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Tuliskan deskripsi singkat proyek">{{ old('description') }}</textarea>
                    </div>

                    <div class="mt-8">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Pilih Lokasi di Peta (Gunakan Pencarian di Pojok Kanan Atas Peta)</label>
                        {{-- Container Peta --}}
                        <div id="map" class="rounded-md border h-64 z-0"></div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" name="latitude" type="text"
                                    class="mt-1 block w-full bg-gray-50" :value="old('latitude', '-7.2278')" readonly />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" name="longitude" type="text"
                                    class="mt-1 block w-full bg-gray-50" :value="old('longitude', '107.9087')" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MEDIA --}}
                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 text-gray-700 flex items-center gap-2">
                        <span class="text-indigo-600">③</span> Media Proyek
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="image" :value="__('Gambar Utama')" />
                            <input id="image" name="image" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'image-preview')">
                            <img id="image-preview" class="mt-4 rounded-md shadow-sm hidden max-h-48 object-cover">
                        </div>

                        <div>
                            <x-input-label for="site_plan" :value="__('Gambar Site Plan')" />
                            <input id="site_plan" name="site_plan" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'site_plan-preview')">
                            <img id="site_plan-preview"
                                class="mt-4 rounded-md shadow-sm hidden max-h-48 object-cover">
                        </div>
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.projects.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                        Batal
                    </a>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Simpan Proyek') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Preview gambar dinamis
            function previewImage(event, id) {
                const reader = new FileReader();
                reader.onload = function() {
                    const img = document.getElementById(id);
                    img.src = reader.result;
                    img.classList.remove('hidden');
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Skrip Dropdown Desa
                const districtSelect = document.getElementById('district_code');
                const villageSelect = document.getElementById('village_code');
                const oldVillage = "{{ old('village_code') }}";

                function fetchVillages(code, selected = null) {
                    if (!code) {
                        villageSelect.innerHTML = '<option value="">Pilih Kecamatan Dahulu</option>';
                        return;
                    }
                    fetch(`/get-villages/${code}`)
                        .then(res => res.json())
                        .then(data => {
                            villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                            for (const [vCode, vName] of Object.entries(data)) {
                                const opt = document.createElement('option');
                                opt.value = vCode;
                                opt.textContent = vName;
                                if (vCode === selected) opt.selected = true;
                                villageSelect.appendChild(opt);
                            }
                        });
                }

                districtSelect.addEventListener('change', () => fetchVillages(districtSelect.value));
                if (districtSelect.value) fetchVillages(districtSelect.value, oldVillage);

                // ==================================================================
                // === SCRIPT PETA LEAFLET DENGAN FITUR PENCARIAN ===
                // ==================================================================
                const latInput = document.getElementById('latitude');
                const lonInput = document.getElementById('longitude');

                // 1. Inisialisasi Peta
                const map = L.map('map').setView([latInput.value, lonInput.value], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // 2. Buat marker awal
                let marker = L.marker([latInput.value, lonInput.value]).addTo(map);

                // 3. FUNGSI UPDATE MARKER & INPUT
                function updateMarkerAndInputs(lat, lng) {
                    latInput.value = lat;
                    lonInput.value = lng;

                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 16); // Zoom lebih dekat saat lokasi ditemukan
                }

                // 4. EVENT CLICK PADA PETA
                map.on('click', e => {
                    updateMarkerAndInputs(e.latlng.lat, e.latlng.lng);
                });

                // 5. FITUR PENCARIAN (GEOCODER)
                L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: 'Cari desa, kecamatan... (e.g. Wanaraja)',
                    errorMessage: 'Lokasi tidak ditemukan.',
                })
                .on('markgeocode', function(e) {
                    const { lat, lng } = e.geocode.center;
                    updateMarkerAndInputs(lat, lng);
                })
                .addTo(map);
            });
        </script>
    @endpush
</x-app-layout>
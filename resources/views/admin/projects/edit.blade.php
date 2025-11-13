<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Proyek: ') }} <span class="font-normal">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">1. Informasi Utama Proyek</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $project->name)" required />
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
                                            {{ old('developer_id', $project->developer_id) == $dev->id ? 'selected' : '' }}>
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
                            </div>
                        @endif
                        <div>
                            <x-input-label for="type" :value="__('Tipe Proyek')" />
                            <select id="type" name="type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Komersil"
                                    {{ old('type', $project->type) == 'Komersil' ? 'selected' : '' }}>Komersil</option>
                                <option value="Subsidi"
                                    {{ old('type', $project->type) == 'Subsidi' ? 'selected' : '' }}>Subsidi</option>
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
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $project->address) }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="district_code" :value="__('Kecamatan')" />
                            <select id="district_code" name="district_code"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districts as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ old('district_code', $project->district_code) == $code ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                            <select id="village_code" name="village_code"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($villages as $code => $name)
                                    <option value="{{ $code }}"
                                        {{ old('village_code', $project->village_code) == $code ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block font-medium text-sm text-gray-700">Pilih Titik Koordinat (Latitude &
                            Longitude)</label>
                        <div id="map" class="mt-2 h-64 w-full rounded-md shadow-sm z-0"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" name="latitude" type="text"
                                    class="mt-1 block w-full bg-gray-100" :value="old('latitude', $project->latitude ?? '-7.2278')" readonly />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" name="longitude" type="text"
                                    class="mt-1 block w-full bg-gray-100" :value="old('longitude', $project->longitude ?? '107.9087')" readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">3. Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label for="image" :value="__('Ganti Gambar Utama (Thumbnail)')" />
                            <input id="image" name="image" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'image-preview')">
                            <img id="image-preview"
                                src="{{ $project->image ? asset('storage/' . $project->image) : '' }}"
                                alt="Image Preview" class="mt-4 rounded-md shadow-sm"
                                style="{{ $project->image ? '' : 'display:none;' }} max-height: 200px;">
                        </div>
                        <div>
                            <x-input-label for="site_plan" :value="__('Ganti Gambar Site Plan')" />
                            <input id="site_plan" name="site_plan" type="file" class="mt-1 block w-full"
                                onchange="previewImage(event, 'site_plan-preview')">
                            <img id="site_plan-preview"
                                src="{{ $project->site_plan ? asset('storage/' . $project->site_plan) : '' }}"
                                alt="Site Plan Preview" class="mt-4 rounded-md shadow-sm"
                                style="{{ $project->site_plan ? '' : 'display:none;' }} max-height: 200px;">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.projects.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">Batal</a>
                    <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
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
                // Skrip Dropdown Desa (KODE ASLI ANDA - SUDAH BENAR)
                const districtSelect = document.getElementById('district_code');
                const villageSelect = document.getElementById('village_code');
                const oldVillage = "{{ old('village_code', $project->village_code) }}";

                function fetchVillages(districtCode, selectedVillage = null) {
                    if (!districtCode) return;
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

                // ==================================================================
                // === SCRIPT PETA LEAFLET YANG SUDAH DIMODIFIKASI (Mulai) ===
                // ==================================================================
                const latInput = document.getElementById('latitude');
                const lonInput = document.getElementById('longitude');

                // 1. Inisialisasi Peta
                const map = L.map('map').setView([latInput.value, lonInput.value], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                // 2. Buat marker awal
                let marker = L.marker([latInput.value, lonInput.value]).addTo(map);

                // 3. BUAT FUNGSI BARU (untuk update marker & input)
                function updateMarkerAndInputs(lat, lng) {
                    latInput.value = lat;
                    lonInput.value = lng;

                    if (marker) {
                        map.removeLayer(marker); // Hapus marker lama
                    }
                    marker = L.marker([lat, lng]).addTo(map); // Tambah marker baru
                    map.setView([lat, lng], 15); // Zoom ke lokasi baru
                }

                // 4. MODIFIKASI EVENT CLICK (sekarang memanggil fungsi baru)
                map.on('click', function(e) {
                    updateMarkerAndInputs(e.latlng.lat, e.latlng.lng);
                });

                // 5. TAMBAHAN BARU: GEOCORGER (PENCARIAN)
                L.Control.geocoder({
                    defaultMarkGeocode: false, // Kita handle marker-nya sendiri
                    placeholder: 'Cari lokasi (contoh: Tarogong Kidul)...' // Teks placeholder
                }).on('markgeocode', function(e) {
                    // Dipanggil saat hasil pencarian dipilih
                    const {
                        lat,
                        lng
                    } = e.geocode.center;
                    updateMarkerAndInputs(lat, lng);
                }).addTo(map);
                // ==================================================================
                // === SCRIPT PETA LEAFLET (Selesai) ===
                // ==================================================================
            });
        </script>
    @endpush
</x-app-layout>

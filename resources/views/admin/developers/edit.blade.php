<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Developer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.developers.update', $developer) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Data Form --}}
                        <div class="mb-4">
                            <x-input-label for="user_id" :value="__('Akun User (Pemilik)')" />
                            <div class="relative">
                                <select id="user_id" name="user_id" required
                                    class="block appearance-none w-full bg-white border border-gray-300 px-3 py-2 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">{{ __('Pilih Akun User') }}</option>
                                    @forelse($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $developer->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @empty
                                        <option value="">
                                            {{ __('-- Tidak ada akun user dengan role developer --') }}</option>
                                    @endforelse
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name"
                                :value="old('company_name', $developer->company_name)" required />
                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                :value="old('phone', $developer->phone)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Alamat Perusahaan')" />
                            <textarea id="address" name="address" rows="3"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $developer->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        {{-- ====================================================== --}}
                        {{-- TAMBAHAN: Input Peta, Latitude, dan Longitude --}}
                        {{-- ====================================================== --}}
                        <div class="mb-4">
                            <x-input-label for="map" :value="__('Lokasi Peta')" />
                            <div id="map" style="height: 400px; width: 100%;" class="mt-1 rounded-md shadow-sm">
                            </div>
                        </div>

                        {{-- Input tersembunyi untuk menyimpan nilai lat/lng --}}
                        <input type="hidden" id="latitude" name="latitude"
                            value="{{ old('latitude', $developer->latitude ?? '-6.9175') }}">
                        <input type="hidden" id="longitude" name="longitude"
                            value="{{ old('longitude', $developer->longitude ?? '107.6191') }}">
                        {{-- ====================================================== --}}

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- TAMBAHAN: Skrip Khusus Untuk Halaman Ini --}}
    {{-- ====================================================== --}}
    @push('scripts')
        <script>
            // Pastikan DOM sudah dimuat
            document.addEventListener('DOMContentLoaded', function() {

                // Ambil nilai awal dari input (beri nilai default jika null)
                let lat = document.getElementById('latitude').value || -6.9175; // Default Bandung
                let lng = document.getElementById('longitude').value || 107.6191;

                // Inisialisasi peta
                let map = L.map('map').setView([lat, lng], 13);

                // Tambahkan tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Tambahkan marker awal
                let marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                // Event saat marker digeser
                marker.on('dragend', function(e) {
                    let latlng = marker.getLatLng();
                    document.getElementById('latitude').value = latlng.lat;
                    document.getElementById('longitude').value = latlng.lng;
                });

                // Tambahkan Geocoder (pencarian)
                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', function(e) {
                        let latlng = e.geocode.center;
                        map.setView(latlng, 13);
                        marker.setLatLng(latlng);
                        document.getElementById('latitude').value = latlng.lat;
                        document.getElementById('longitude').value = latlng.lng;
                    })
                    .addTo(map);

                // Event saat peta diklik
                map.on('click', function(e) {
                    let latlng = e.latlng;
                    marker.setLatLng(latlng);
                    document.getElementById('latitude').value = latlng.lat;
                    document.getElementById('longitude').value = latlng.lng;
                });

            });
        </script>
    @endpush
</x-app-layout>

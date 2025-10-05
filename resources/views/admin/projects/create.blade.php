<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Perumahan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan error validasi umum jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
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
                            <input id="image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Grid untuk Lokasi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            {{-- Kecamatan --}}
                            <div>
                                <x-input-label for="district_code" :value="__('Kecamatan')" />
                                <select name="district_code" id="district_code"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach ($districts as $code => $name)
                                        <option value="{{ $code }}" {{ old('district_code') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('district_code')" class="mt-2" />
                            </div>

                            {{-- Desa/Kelurahan --}}
                            <div>
                                <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                                <select name="village_code" id="village_code"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude"
                                    :value="old('latitude')" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>

                            {{-- Longitude --}}
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude"
                                    :value="old('longitude')" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>


                        {{-- Tombol Simpan --}}
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

    {{-- Script hanya perlu di-push satu kali --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#district_code').on('change', function() {
                    let districtCode = $(this).val();
                    let villageSelect = $('#village_code');

                    villageSelect.empty().append('<option value="">Memuat...</option>');

                    if (districtCode) {
                        $.ajax({
                            url: "{{ route('dependent-dropdown.villages') }}",
                            type: "GET",
                            data: { district_code: districtCode },
                            dataType: "json",
                            success: function(data) {
                                villageSelect.empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                                $.each(data, function(code, name) {
                                    villageSelect.append('<option value="' + code + '">' + name + '</option>');
                                });
                            },
                            error: function() {
                                villageSelect.empty().append('<option value="">Gagal memuat data</option>');
                            }
                        });
                    } else {
                        villageSelect.empty().append('<option value="">Pilih Kecamatan Terlebih Dahulu</option>');
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
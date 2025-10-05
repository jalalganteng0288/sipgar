<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Perumahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- 1. Menambahkan enctype untuk upload file --}}
                    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Metode untuk update --}}

                        {{-- Nama Perumahan --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Nama Pengembang --}}
                        <div class="mt-4">
                            <x-input-label for="developer_name" :value="__('Nama Pengembang')" />
                            <x-text-input id="developer_name" class="block mt-1 w-full" type="text"
                                name="developer_name" :value="old('developer_name', $project->developer_name)" required />
                            <x-input-error :messages="$errors->get('developer_name')" class="mt-2" />
                        </div>

                        {{-- Gambar Utama --}}
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Gambar Utama (Opsional)')" />
                            <x-text-input id="image" class="block mt-1 w-full border p-2 rounded-md" type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Menampilkan Gambar Saat Ini --}}
                        @if ($project->image)
                            <div class="mt-4">
                                <p class="block font-medium text-sm text-gray-700">Gambar saat ini:</p>
                                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->name }}"
                                     class="w-48 h-auto rounded-md mt-2 border p-1">
                            </div>
                        @endif

                        {{-- Alamat --}}
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address" rows="3"
                                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      required>{{ old('address', $project->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        {{-- 2. Mengganti input Kecamatan dan Desa dengan Dropdown --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="district_code" :value="__('Kecamatan')" />
                                <select name="district_code" id="district_code" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option>Pilih Kecamatan</option>
                                    @foreach ($districts as $code => $name)
                                        <option value="{{ $code }}" {{ old('district_code', $project->district_code) == $code ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('district_code')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="village_code" :value="__('Desa/Kelurahan')" />
                                <select name="village_code" id="village_code" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                     <option>Pilih Desa/Kelurahan</option>
                                     {{-- Opsi desa akan diisi oleh JavaScript --}}
                                     @foreach ($villages as $code => $name)
                                        <option value="{{ $code }}" {{ old('village_code', $project->village_code) == $code ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('village_code')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="5"
                                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                        </div>

                        {{-- Latitude & Longitude --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                             <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude"
                                    :value="old('latitude', $project->latitude)" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude"
                                    :value="old('longitude', $project->longitude)" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>


                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Menambahkan script untuk dependent dropdown --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#district_code').on('change', function() {
                    var districtCode = $(this).val();
                    if (districtCode) {
                        $.ajax({
                            url: '{{ route("dependent-dropdown.villages") }}',
                            type: "GET",
                            data: { district_code: districtCode, _token: '{{ csrf_token() }}' },
                            dataType: "json",
                            success: function(data) {
                                if (data) {
                                    $('#village_code').empty();
                                    $('#village_code').append('<option>Pilih Desa/Kelurahan</option>');
                                    $.each(data, function(key, value) {
                                        $('#village_code').append('<option value="' + key + '">' + value + '</option>');
                                    });
                                } else {
                                    $('#village_code').empty();
                                }
                            }
                        });
                    } else {
                        $('#village_code').empty();
                        $('#village_code').append('<option>Pilih Kecamatan Terlebih Dahulu</option>');
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
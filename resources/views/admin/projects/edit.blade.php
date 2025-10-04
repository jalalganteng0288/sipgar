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

                    {{-- Nanti form ini akan mengarah ke route 'update' --}}
                    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                        @csrf
                        @method('PUT') {{-- Metode untuk update --}}

                        <div>
                            <x-input-label for="name" :value="__('Nama Perumahan')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="developer_name" :value="__('Nama Pengembang')" />
                            <x-text-input id="developer_name" class="block mt-1 w-full" type="text"
                                name="developer_name" :value="old('developer_name', $project->developer_name)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address" class="block mt-1 w-full ...">{{ old('address', $project->address) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" class="block mt-1 w-full ...">{{ old('description', $project->description) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude"
                                :value="old('latitude', $project->latitude)" />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude"
                                :value="old('longitude', $project->longitude)" />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="district" :value="__('Kecamatan')" />
                            <x-text-input id="district" class="block mt-1 w-full" type="text" name="district"
                                :value="old('district', $project->district)" />
                            <x-input-error :messages="$errors->get('district')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

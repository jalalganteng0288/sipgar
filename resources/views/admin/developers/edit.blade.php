<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Developer: ') . $developer->company_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- ACTION menggunakan PATCH untuk update data --}}
                    <form action="{{ route('admin.developers.update', $developer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- ============================================== -->
                        <!-- BAGIAN 1: DATA AKUN USER (LOGIN) -->
                        <!-- ============================================== -->
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">{{ __('1. Edit Data Akun Login') }}</h3>

                        {{-- NAMA USER --}}
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Lengkap User')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                :value="old('name', $developer->user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        {{-- EMAIL USER --}}
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email (Untuk Login)')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                                :value="old('email', $developer->user->email)" required />
                            <p class="mt-1 text-sm text-gray-500">{{ __('Perubahan email akan memengaruhi akses login Developer.') }}</p>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- PASSWORD BARU --}}
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password Baru (Kosongkan jika tidak diubah)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <hr class="my-8 border-gray-300">

                        <!-- ============================================== -->
                        <!-- BAGIAN 2: DATA PERUSAHAAN DEVELOPER -->
                        <!-- ============================================== -->
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">{{ __('2. Edit Data Perusahaan Developer') }}</h3>

                        {{-- NAMA PERUSAHAAN --}}
                        <div class="mb-4">
                            <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" 
                                :value="old('company_name', $developer->company_name)" required />
                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                        </div>

                        {{-- KONTAK PERSON --}}
                        <div class="mb-4">
                            <x-input-label for="contact_person" :value="__('Nama Kontak Person')" />
                            <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" 
                                :value="old('contact_person', $developer->contact_person)" placeholder="Diisi jika berbeda dengan Nama Lengkap User" />
                            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
                        </div>

                        {{-- NOMOR TELEPON --}}
                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Nomor Telepon Perusahaan')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" 
                                :value="old('phone', $developer->phone)" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        {{-- ALAMAT --}}
                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Alamat Perusahaan')" />
                            <textarea id="address" name="address" rows="3" 
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('address', $developer->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Perbarui Developer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
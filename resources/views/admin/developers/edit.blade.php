@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Developer: ') . $developer->company_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tampilkan Pesan Sukses atau Error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.developers.update', $developer) }}" class="space-y-6">
                        @csrf
                        @method('PUT') {{-- Gunakan method PUT/PATCH untuk update Resource --}}

                        {{-- Nama Perusahaan --}}
                        <div>
                            <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                            <x-text-input id="company_name" name="company_name" type="text" 
                                class="mt-1 block w-full" 
                                :value="old('company_name', $developer->company_name)" 
                                required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                        </div>

                        {{-- User ID (Relasi dengan Pengguna) --}}
                        <div>
                            <x-input-label for="user_id" :value="__('Pengguna Akun (Role Developer)')" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Pengguna...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ old('user_id', $developer->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Hanya menampilkan pengguna role 'developer' yang belum terikat ke developer lain (kecuali pengguna saat ini).</p>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address" rows="3" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $developer->address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone_number" name="phone_number" type="text" 
                                class="mt-1 block w-full" 
                                :value="old('phone_number', $developer->phone_number)" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                            <a href="{{ route('admin.developers.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
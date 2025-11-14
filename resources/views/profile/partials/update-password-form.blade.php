{{-- 
  File: resources/views/profile/partials/update-developer-profile-form.blade.php 
--}}

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Developer Information
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Update your company's developer profile information.
        </p>
    </header>

    {{-- GANTI ACTION DI BAWAH INI --}}
    <form method="post" action="{{ route('admin.profile.developer.update') }}" class="mt-6 space-y-6"> @csrf
        @method('patch')

        {{-- (Input 'company_name', 'contact_person', 'phone', 'address') --}}

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $developer->company_name)"
                required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        {{-- ... (input-input lainnya) ... --}}

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'developer-profile-updated')
                <p ...>Saved.</p>
            @endif
        </div>
    </form>
</section>

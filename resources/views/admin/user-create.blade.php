<x-app-layout>
    <div class="flex justify-center items-center min-h-screen  p-6">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">

            <div class="flex flex-col items-center mb-6">
                <x-authentication-card-logo class="w-20 h-20" />
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Tambah Akun Baru</h2>
                <p class="text-sm text-gray-500">Isi data berikut untuk membuat akun baru.</p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="mb-4">
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mb-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <div class="mb-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mb-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
                <div class="mb-4">
                    <x-label for="nomor" value="{{ __('Nomor Telepon') }}" />
                    <x-input id="nomor" class="block mt-1 w-full" type="text" name="nomor" :value="old('nomor')" required autocomplete="tel" placeholder="Contoh: 081234567890" />
                </div>

                <div class="mb-4">
                    <x-label for="role" value="{{ __('Register as') }}" />
                    <select id="role" name="role" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2 text-sm text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-indigo-600 hover:text-indigo-800">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-indigo-600 hover:text-indigo-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
                @endif

                <div class="flex justify-end">
                    <x-button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                        {{ __('Tambahkan Akun') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
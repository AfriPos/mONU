<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-medium text-gray-900">
                            {{ __('Edit User') }}
                        </h1>
                    </header>

                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-sm text-green-600 rounded-md p-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->hasRole($role) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <div class="flex gap-2">
                                <x-text-input id="password" class="block mt-1" type="password" name="password" />
                                <button type="button" onclick="togglePassword('password')"
                                    class="mt-1 px-3 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path id="password-eye-icon" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path id="password-eye-outline" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button type="button" onclick="generatePassword()"
                                    class="mt-1 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Generate</button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <div class="flex gap-2">
                                <x-text-input id="password_confirmation" class="block mt-1" type="password"
                                    name="password_confirmation" />
                                <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="mt-1 px-3 py-2 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path id="password-confirmation-eye-icon" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path id="password-confirmation-eye-outline" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update User') }}</x-primary-button>
                            <a href="{{ route('users.index') }}"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    }
</script>

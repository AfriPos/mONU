
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-medium text-gray-900">
                            {{ __('Create MAC Prefix') }}
                        </h1>
                    </header>

                    <form method="POST" action="{{ route('mac.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="prefix" :value="__('MAC Prefix')" />
                            <x-text-input id="prefix" class="block mt-1 w-full" type="text" name="prefix"
                                :value="old('prefix')" required autofocus placeholder="eg: 02:00:00" />
                            <x-input-error :messages="$errors->get('prefix')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Prefix') }}</x-primary-button>
                            <a href="{{ route('mac.index') }}"
                                class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

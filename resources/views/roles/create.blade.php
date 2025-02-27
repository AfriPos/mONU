<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            {{ __('Create New Role') }}
                        </h1>
                    </header>

                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 text-sm text-green-600 rounded-md p-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700">{{ __('Role Name') }}</label>
                            <input id="name" type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm @error('name') border-red-500 @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Permissions') }}</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox"
                                                class="focus:ring-gray-500 h-4 w-4 text-gray-600 border-gray-300 rounded"
                                                id="permission_{{ $permission->id }}" name="permissions[]"
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="permission_{{ $permission->id }}"
                                                class="font-medium text-gray-700">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex items-center space-x-4">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                {{ __('Create Role') }}
                            </button>
                            <a href="{{ route('roles.index') }}"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

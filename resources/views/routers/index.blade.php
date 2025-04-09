<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="flex justify-between items-center w-full p-4 bg-white">
                        <h1 class="text-4xl font-medium text-gray-900">
                            {{ __('Configured Routers') }}
                        </h1>
                    </header>

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
                    <form method="GET" action="{{ route('routers.index') }}" class="mb-4">
                        <div class="flex">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="border border-gray-300 rounded-l-md px-4 py-2 w-full"
                                placeholder="Search by serial number...">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">
                                Search
                            </button>
                        </div>
                    </form>
                    
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Router Model
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Serial Number
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MAC Batch
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Configured by
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($routers as $router)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $router->routerModel->brand }}
                                            {{ $router->routerModel->model_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $router->serial_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \App\Models\MacAddress::where('batchid', $router->mac_batch)->where('assigned', true)->first()?->mac_address ?? 'No MAC assigned' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $router->user->name }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $routers->appends(request()->query())->links() }}
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

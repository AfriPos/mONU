<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="flex justify-between items-center w-full p-4 bg-white">
                        <h1 class="text-4xl font-medium text-gray-900">
                            {{ __('MAC Addresses') }}
                        </h1>
                        <a href="{{ route('mac.create') }}"
                            style="background-color: #1f2937; color: white; font-weight: bold; padding: 8px 16px; border-radius: 6px;">
                            {{ __('Add MAC Address') }}
                        </a>
                    </header>

                    @if (session('status'))
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MAC Address
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created At
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($macs as $mac)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $mac->prefix }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($mac->status)
                                            <span class="text-green-500">Active</span>
                                        @else
                                            <span class="text-red-500">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $mac->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($mac->status)
                                            <form action="{{ route('mac.deactivate', $mac->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure?')">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('mac.activate', $mac->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

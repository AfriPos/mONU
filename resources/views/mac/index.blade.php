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
                                            <form id="deactivate-form-{{ $mac->id }}"
                                                action="{{ route('mac.deactivate', $mac->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="confirmDeactivation({{ $mac->id }})">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form id="activate-form-{{ $mac->id }}"
                                                action="{{ route('mac.activate', $mac->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="button" class="text-green-600 hover:text-green-900"
                                                    onclick="confirmActivation({{ $mac->id }})">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <script>
                                        function confirmDeactivation(macId) {
                                            Swal.fire({
                                                title: "Are you sure?",
                                                text: "This will deactivate the MAC address.",
                                                icon: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#d33",
                                                cancelButtonColor: "#3085d6",
                                                confirmButtonText: "Yes, deactivate it!"
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('deactivate-form-' + macId).submit();
                                                }
                                            });
                                        }

                                        function confirmActivation(macId) {
                                            Swal.fire({
                                                title: "Activate MAC Address?",
                                                text: "This will activate the MAC address.",
                                                icon: "info",
                                                showCancelButton: true,
                                                confirmButtonColor: "#28a745",
                                                cancelButtonColor: "#6c757d",
                                                confirmButtonText: "Yes, activate it!"
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('activate-form-' + macId).submit();
                                                }
                                            });
                                        }
                                    </script>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

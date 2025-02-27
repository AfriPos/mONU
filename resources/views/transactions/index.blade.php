<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="flex justify-between items-center w-full p-4 bg-white">
                        <h1 class="text-4xl font-medium text-gray-900">
                            {{ __('Transactions') }}
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

                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-sm text-green-600 rounded-md p-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="space-y-8">
                        @foreach ($payments as $payment)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-50 p-4 flex justify-between items-center">
                                    <h3 class="text-lg font-medium">Invoice #{{ $payment->invoice->invoice_number }}
                                    </h3>
                                    <div>
                                        Invoice status:
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if ($payment->invoice->status === 'paid') bg-green-100 text-green-800
                                        @elseif($payment->invoice->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($payment->invoice->status === 'overdue')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800 @endif
                                    ">
                                            {{ ucfirst($payment->invoice->status) }}
                                        </span>

                                    </div>
                                </div>
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Payment Method
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Transaction ID
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Payment Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->amount }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->payment_method }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->transaction_id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->status }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $payment->payment_date }}</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

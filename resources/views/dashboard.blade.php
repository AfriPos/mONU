<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    @php
                        $credit = App\Models\Credit::first();
                    @endphp

                    <div class="space-y-4">
                        <h2 class="text-3xl font-bold text-gray-800">
                            Account Balance: ${{ number_format($credit->account_balance, 2) }}
                        </h2>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Available Credits: {{ number_format($credit->balance) }}
                        </h2>
                    </div>


                    <div class="mt-6 flex justify-center">
                        <div class="w-full max-w-md">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Topup Credits</label>
                            <input type="number" id="amount" name="amount"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-center"
                                min="1" value="" placeholder="Enter amount in USD" />
                        </div>
                    </div>


                    <!-- Centering the PayPal Button using Grid -->
                    <div class="grid place-items-center mt-6">
                        <div id="paypal-button-container" class="w-full max-w-md"></div>
                    </div>
                    <p id="result-message" class="text-center mt-4"></p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    window.paypal
        .Buttons({
            style: {
                shape: "pill",
                layout: "vertical",
                color: "gold",
                label: "paypal",
            },

            async createOrder() {
                // Get the latest amount
                let amount = document.getElementById("amount").value;

                if (!amount || isNaN(amount) || amount <= 0) {
                    alert("Please enter a valid amount.");
                    return;
                }

                const response = await fetch("/api/orders", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        amount: amount
                    }),
                });

                const orderData = await response.json();
                return orderData.id;
            },

            async onApprove(data, actions) {
                const response = await fetch(`/api/orders/${data.orderID}/capture`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                });

                const orderData = await response.json();

                if (orderData.status === "COMPLETED") {
                    alert(`Transaction Successful! ID: ${orderData.id}`);
                    window.location.reload();
                } else {
                    alert("Transaction failed. Please try again.");
                }
            },
        })
        .render("#paypal-button-container");
</script>

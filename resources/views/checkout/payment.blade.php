<x-app-layout>
    <div class="max-w-xl mx-auto px-4 py-12 text-center">
        <h1 class="text-2xl font-bold mb-4">Complete Your Payment</h1>
        <p class="text-gray-600 mb-6">Order #{{ $order->order_number }} — ₹{{ number_format($order->total_minor / 100, 2) }}</p>

        <button id="rzp-button" class="bg-gray-800 text-white px-6 py-3 rounded">Pay Now</button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const options = {
            key: "{{ $razorpayKeyId }}",
            amount: "{{ $order->total_minor }}",
            currency: "INR",
            name: "Rasova",
            description: "Order #{{ $order->order_number }}",
            order_id: "{{ $order->payment->gateway_order_id }}",
            handler: function (response) {
                window.location.href = "{{ route('checkout.confirmation', $order->id) }}";
            },
            prefill: {
                name: "{{ $order->shipping_full_name }}",
                contact: "{{ $order->shipping_phone }}",
            },
            theme: {
                color: "#1f2937"
            }
        };

        const rzp = new Razorpay(options);
        document.getElementById('rzp-button').onclick = function () {
            rzp.open();
        };
    </script>
</x-app-layout>
@extends('layouts.storefront')

@section('title', 'Payment — Rasova')

@section('extra-styles')
.payment-wrap { padding: 56px 0; text-align: center; }
.payment-wrap h1 { font-size: clamp(22px, 6vw, 30px); margin: 0 0 10px; }
.payment-wrap p { opacity: 0.7; margin: 0 0 28px; }
.payment-wrap .btn { min-height: 48px; padding: 0 36px; }
@endsection

@section('content')

<div class="wrap payment-wrap">
    <h1>Complete your payment</h1>
    <p>Order #{{ $order->order_number }}  &#8377;{{ number_format($order->total_minor / 100, 2) }}</p>

    <button id="rzp-button" class="btn btn-primary">Pay now</button>
</div>

@endsection

@push('scripts')
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
            color: "#b3132d"
        }
    };

    const rzp = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function () {
        rzp.open();
    };
</script>
@endpush
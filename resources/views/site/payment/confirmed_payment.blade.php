@extends('site.template')

@section('css')
<style>
    .checkoutbox {
        color: #fff;
        font-size: 13px;
    }

    ul.checkoutbox li {
        color: #fff;
        border-bottom: 1px solid #c136ac;
        padding: 5px 0;
    }

    .ticket-user {
        background: #8932d1;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 12px;
        color: #fff;
    }

    label {
        font-weight: bold;
    }
    .text-white{
        color: #fff!important;
    }
</style>
@stop

@section('content')

@php
    $amount = $order->grandtotal + $order->servicecharge;

    $isSandbox = env('SENANGPAY_MODE') === 'SANDBOX';
    $merchant_id = $isSandbox ? env('SENANGPAY_SANDBOX_MERCHANT_ID') : env('SENANGPAY_LIVE_MERCHANT_ID');
    $secretkey = $isSandbox ? env('SENANGPAY_SANDBOX_SECRETKEY') : env('SENANGPAY_LIVE_SECRETKEY');
    $payment_url = $isSandbox ? 'https://sandbox.senangpay.my/payment/' . $merchant_id : 'https://app.senangpay.my/payment/' . $merchant_id;

    $order_id = 'W-' . $order->id;
    $detail = $eventname . ' Event Reservation - ' . $order_id;

    $hash_string = $secretkey . $detail . number_format($amount, 2, '.', '') . $order_id;
    $hashed_string = hash_hmac('SHA256', $hash_string, $secretkey);
@endphp

<section>
    <div class="block gray half-parallax blackish remove-bottom">
        <div class="parallax" style="background:url({{ asset('site/images/parallax8.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="page-title">
                        <h1><span>Ready <span>to Pay</span></span></h1>
                        <p>Review your reservation and proceed to secure payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="block gray">
    <div class="container">
        <div class="row">

            {{-- LEFT SIDE --}}
            <div class="col-md-8">
                <div class="checkout-form" style="background:#7831c8; padding: 40px">
                    <h4 style="color: yellow">Ticket Holders Summary</h4>
                    <hr>

                    @foreach($order->orderPackages->first()->ticketUsers as $index => $ticketUser)
                        <div class="ticket-user">
                            <p class="text-white"><strong>Ticket #{{ $index + 1 }}</strong></p>
                            <p class="text-white">Name: {{ $ticketUser->name ?? 'N/A' }}</p>
                            <p class="text-white">Seat: 
                                @if ($ticketUser->seat)
                                    {{ $ticketUser->seat->row_label }}{{ $ticketUser->seat->seat_number }}
                                @else
                                    <span class="text-danger">Not Assigned</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-md-4">
                <div class="single-posts" style="background:#c836c5; padding: 30px">
                    <h4 style="color: yellow">Payment Summary</h4>
                    <ul class="checkoutbox">
                        <li>Event: {{ $eventname ?? '-' }}</li>
                        <li>Package: {{ $order->orderPackages->first()->package->title ?? '-' }}</li>
                        <li>Total: RM {{ number_format($order->carttotalamount, 2) }}</li>
                        <li>SST: RM {{ number_format($order->servicecharge, 2) }}</li>
                        @if($order->coupon)
                            <li>Discount: RM {{ number_format($order->discount_amount ?? 0.00, 2) }}</li>
                        @endif
                        <li><strong>Grand Total: RM {{ number_format($amount, 2) }}</strong></li>
                    </ul>

                    <form action="{{ $payment_url }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="detail" value="{{ $detail }}">
                        <input type="hidden" name="amount" value="{{ number_format($amount, 2, '.', '') }}">
                        <input type="hidden" name="order_id" value="{{ $order_id }}">
                        <input type="hidden" name="hash" value="{{ $hashed_string }}">
                        <input type="hidden" name="name" value="{{ $order->name }}">
                        <input type="hidden" name="email" value="{{ $order->email }}">
                        <input type="hidden" name="phone" value="{{ $order->phone }}">
                        <button type="submit" class="btn btn-primary" style="width: 100%; background:#7831c8">
                            Pay Now via SenangPay
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@stop

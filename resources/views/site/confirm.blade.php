@extends('site.template')
@section('css')
    <style>
        .checkoutbox {
            color: #fff;
            font-size: 11px;
        }

        ul.checkoutbox li {
            color: #fff;
            border-bottom: 1px solid #c136ac;
            padding: 5px 0;
            font-size: 14px;
        }

        .checkbox-form .checkout-form-list {
            margin-bottom: 30px;
        }

        .checkbox-form .checkout-form-list label {
            color: #222222;
            margin-bottom: 5px;
        }

        .checkbox-form .checkout-form-list input[type=text],
        .checkbox-form .checkout-form-list input[type=password],
        .checkbox-form .checkout-form-list input[type=email] {
            background: #ffffff;
            border: 1px solid #efefef;
            border-radius: 0;
            height: 42px;
            width: 100%;
            padding: 0 10px 0 10px;
            font-size: 14px;
        }
    </style>
@stop
@section('content')
    <section>
        <div class="block gray half-parallax blackish remove-bottom">
            <div style="background:url({{ asset('site/images/parallax8.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="page-title">

                            <h1><span>Purchase<span>Confirm</span></h1>
                            <p>Fill your information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="block gray">
            <div class="container">
                <div class="row">

                    <div class="col-md-8">
                        <div class="checkbox-form" style="background:#7831c8; padding: 40px">
                            <div class="row">
                                <h4 style="color: yellow"> Billing Address</h4>
                                <hr>
                                <ul>
                                    <li style="color: #fff">Full Name : {{ $order->name }}</li>
                                    <!--<li style="color: #fff">Address : {{ $order->address }}</li>-->
                                    <!--<li style="color: #fff">Country : {{ $order->country }}</li>-->
                                    <!--<li style="color: #fff">State : {{ $order->state }}</li>-->
                                    <!--<li style="color: #fff">City : {{ $order->city }} </li>-->
                                    <!--<li style="color: #fff">Postalcode : {{ $order->postcode }}</li>-->
                                    <li style="color: #fff">Email Address : {{ $order->email }} <small>(ticket will get in
                                            this email)</small></li>
                                    <li style="color: #fff">Phone : {{ $order->phone }}</li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="padding: 40px">
                                    <a href="{{ route('confirm.modify') }}" class="btn btn-danger">Modify</a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-4">
                        <div class="single-posts" style="background:#c836c5; text-align:left; padding: 40px">
                            <div class="row">
                                <h4 style="color: yellow"> Your Tickets Costing</h4>
                                <hr>
                                <ul class="checkoutbox">
                                    {{-- @foreach ($carts as $cart)
                                        @php
                                            $package = App\Models\Package::find($cart->package_id);
                                        @endphp
                                        <li>{{ $package->title }} X {{ $cart->qty }} <span style="float: right"> RM
                                                {{ $cart->totalamount }}</span></li>
                                    @endforeach --}}


                                    <li>Total <span style="float: right">RM
                                            {{ number_format($order->carttotalamount, 2) }}</span></li>
                                    <li>SST <span style="float: right">RM
                                            {{ number_format($order->servicecharge, 2) }}</span></li>
                                    @if ($order->coupon)
                                        <li>Discount / Offer <span style="float: right">RM
                                                {{ $order->discount_amount ?? 0.0 }}</span></li>
                                    @endif
                                    <li><strong>Grand Total <span style="float: right">RM
                                                {{ number_format($order->grandtotal + $order->servicecharge, 2) }}</span></strong>
                                    </li>
                                </ul>
                                @php
                                    if (env('SENANGPAY_MODE') == 'SANDBOX') {
                                        $merchant_id = env('SENANGPAY_SANDBOX_MERCHANT_ID');
                                        $secretkey = env('SENANGPAY_SANDBOX_SECRETKEY');
                                        $payment_url = 'https://sandbox.senangpay.my/payment/' . $merchant_id;
                                    } else {
                                        $merchant_id = env('SENANGPAY_LIVE_MERCHANT_ID');
                                        $secretkey = env('SENANGPAY_LIVE_SECRETKEY');
                                        $payment_url = 'https://app.senangpay.my/payment/' . $merchant_id;
                                    }

                                    $order_id = 'W-' . $order->id;
                                    $detail = $eventname . ' Event Reservation-' . $order_id;
                                    // dd($payment_url, $merchant_id, $secretkey, $paid_total_amount, $detail, $order_id);

                                    $str =
                                        $secretkey .
                                        '' .
                                        $detail .
                                        '' .
                                        $order->grandtotal +
                                        $order->servicecharge .
                                        '' .
                                        $order_id;

                                    $hashed_string = hash_hmac('SHA256', $str, $secretkey);
                                @endphp
                                <h4 style="color: yellow; margin-top:10px"> Payment Via</h4>
                                <br />
                                <img src="{{ asset('site/images/onlinepayment.jpg') }}" alt="" width="100%">
                                <br />
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:10px;">
                                        @if ($remainingSeconds > 0)
                                            <form action="{{ $payment_url }}" method="post" id="paymentForm">
                                                @csrf
                                                <input type="hidden" name="detail" value="{{ $detail }}">
                                                <input type="hidden" name="amount"
                                                    value="{{ $order->grandtotal + $order->servicecharge }}">
                                                <input type="hidden" name="order_id" value="{{ $order_id }}">
                                                <input type="hidden" name="hash" value="{{ $hashed_string }}">
                                                <input type="hidden" name="name" value="{{ Auth()->user()->name }}">
                                                <input type="hidden" name="email" value="{{ $order->email }}">
                                                <input type="hidden" name="phone" value="{{ $order->phone }}">
                                                <button type="submit" class="btn btn-primary"
                                                    style="width: 100%; background:#7831c8">
                                                    Process to Payment
                                                </button>

                                            </form>
                                        @endif

                                        {{-- <a href="" class="btn btn-primary"
                                            style="width: 100%; background:#7831c8">Process to Payment</a> --}}
                                    </div>
                                </div>
                                <!-- Payment Countdown Section -->
                                <div style="margin-top:20px;">
                                    <p style="color: yellow; font-size:18px; font-weight:500; margin-bottom:10px;">
                                        Complete your payment within:
                                    </p>
                                    <div id="countdown"
                                        style="display:inline-block;
                                            font-size:32px;
                                            font-weight:bold;
                                            background:#fff;
                                            color:#c836c5;
                                            padding:10px 25px;
                                            border-radius:10px;
                                            box-shadow:0 4px 10px rgba(0,0,0,0.2);
                                            letter-spacing:2px;
                                        ">
                                        00:00
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <script>
        let remaining = {{ $remainingSeconds }}; // From backend
        const countdownEl = document.getElementById('countdown');
        const myForm = document.getElementById('paymentForm');

        function updateCountdown() {
            if (remaining <= 0) {
                countdownEl.textContent = "Expired!";
                countdownEl.style.background = "#ff3b3b";
                countdownEl.style.color = "#fff";
                countdownEl.style.boxShadow = "0 4px 10px rgba(255,0,0,0.4)";

                // Remove the payment form
                if (myForm) {
                    myForm.remove();
                }
                return;
            }
            let minutes = Math.floor(remaining / 60);
            let seconds = remaining % 60;
            countdownEl.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            remaining--;
            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    </script>
@stop

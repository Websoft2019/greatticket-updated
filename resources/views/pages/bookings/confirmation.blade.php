@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Booking Confirmation</h4>
            </div>
            <div class="card-body">
                <h5>Order Details</h5>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Client Name:</strong> {{ $order->name }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $order->email }}</li>
                    <li class="list-group-item"><strong>Phone:</strong> {{ $order->phone }}</li>
                    <li class="list-group-item"><strong>Package:</strong>
                        {{ $order->orderPackages->first()->package->title }}</li>
                    <li class="list-group-item"><strong>Quantity:</strong> {{ $order->orderPackages->first()->quantity }}
                    </li>
                    <li class="list-group-item"><strong>Total Price:</strong> RM {{ number_format($order->grandtotal, 2) }}
                    </li>
                </ul>

                <h5>Attendee List</h5>
                <ul class="list-group mb-4">
                    @foreach ($order->orderPackages as $package)
                        @foreach ($package->ticketUsers as $attendee)
                            <li class="list-group-item">{{ $attendee->name }}</li>
                        @endforeach
                    @endforeach
                </ul>

                <div class="text-center">
                    @php
                        if (env('SENANGPAY_MODE') == 'LIVE') {
                            $merchant_id = env('SENANGPAY_LIVE_MERCHANT_ID');
                            $secretkey = env('SENANGPAY_LIVE_SECRETKEY');
                            $payment_url = 'https://app.senangpay.my/payment/' . $merchant_id;
                        } else {
                            $merchant_id = env('SENANGPAY_SANDBOX_MERCHANT_ID');
                            $secretkey = env('SENANGPAY_SANDBOX_SECRETKEY');
                            $payment_url = 'https://sandbox.senangpay.my/payment/' . $merchant_id;
                        }

                        $order_id = 'W-' . $order->id;
                        // $detail = $eventname . ' Event Reservation-' . $order_id;
                        $detail = ' Event Reservation-' . $order_id;
                        // dd($payment_url, $merchant_id, $secretkey, $paid_total_amount, $detail, $order_id);

                        $str = $secretkey . '' . $detail . '' . $order->grandtotal . '' . $order_id;

                        $hashed_string = hash_hmac('SHA256', $str, $secretkey);
                    @endphp
                    <h4 style="color: yellow; margin-top:10px"> Payment Via</h4>
                    <br />
                    <img src="{{ asset('site/images/onlinepayment.jpg') }}" alt="" width="100%">
                    <br />
                    <div class="row">
                        <div class="col-md-12" style="margin-top:10px;">
                            <form action="{{ $payment_url }}" method="post">
                                @csrf
                                <input type="hidden" name="detail" value="{{ $detail }}">
                                <input type="hidden" name="amount" value="{{ $order->grandtotal }}">
                                <input type="hidden" name="order_id" value="{{ $order_id }}">
                                <input type="hidden" name="hash" value="{{ $hashed_string }}">
                                <input type="hidden" name="name" value="{{ Auth()->user()->name }}">
                                <input type="hidden" name="email" value="{{ $order->email }}">
                                <input type="hidden" name="phone" value="{{ $order->phone }}">
                                <button type="submit" class="btn btn-primary" style="width: 100%; background:#7831c8">
                                    Process to Payment
                                </button>

                            </form>
                            {{-- <a href="" class="btn btn-primary"
                                            style="width: 100%; background:#7831c8">Process to Payment</a> --}}
                        </div>
                    </div>
                    {{-- <a href="{{ route('payment.page', ['order' => $order->id]) }}" class="btn btn-success">Proceed to
                        Payment</a> --}}
                    <a href="{{ route('home') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </div>
    </div>
@endsection

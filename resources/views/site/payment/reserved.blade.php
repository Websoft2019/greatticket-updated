@extends('site.template')

@section('css')
    <style>
        .checkoutbox {
            color: #000;
            font-size: 13px;
        }
        ul.checkoutbox li {
            color: #000;
            border-bottom: 1px solid #c136ac;
            padding: 5px 0;
        }
        label {
            font-weight: bold;
        }
    </style>
@stop

@section('content')
<section>
    <div class="block gray half-parallax blackish remove-bottom">
        <div class="parallax" style="background:url({{ asset('site/images/parallax8.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="page-title">
                        <h1><span>Confirm <span>Reservation</span></span></h1>
                        <p>Review your ticket details before payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="block gray">
    <div class="container">
        <form action="{{ route('payment.reserved.confirm', $order->qr_code) }}" method="POST">
            @csrf
            <div class="row">
                {{-- LEFT SIDE --}}
                <div class="col-md-8">
                    <div class="checkout-form" style="background:#7831c8; padding: 40px">
                        <h4 style="color: yellow">Your Ticket Holders</h4>
                        <hr>

                        @foreach($order->orderPackages->first()->ticketUsers as $index => $ticketUser)
                            <div class="mb-4" style="color: #000; padding: 10px; background: #8932d1; margin-bottom: 15px; border-radius: 6px;">
                                <div class="form-group">
                                    <label>Name for Ticket #{{ $index + 1 }}</label>
                                    <input type="text" name="ticket_users[{{ $ticketUser->id }}][name]"
                                           value="{{ old('ticket_users.' . $ticketUser->id . '.name', $ticketUser->name) }}"
                                           class="form-control" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label>Assigned Seat:</label>
                                    <div>
                                        @if($ticketUser->seat)
                                            {{ $ticketUser->seat->row_label }}{{ $ticketUser->seat->seat_number }}
                                        @else
                                            <span class="text-danger">Not Assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-right mt-4">
                            <button class="btn btn-warning" type="submit">Save & Continue to Payment</button>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="col-md-4">
                    <div class="single-posts" style="background:#c836c5; padding: 30px; color: #000;">
                        <h4 style="color: yellow">Summary</h4>
                        <ul class="checkoutbox">
                            <li>Event: {{ $order->orderPackages->first()->package->event->title ?? '-' }}</li>
                            <li>Package: {{ $order->orderPackages->first()->package->title ?? '-' }}</li>
                            <li>Total: RM {{ number_format($order->carttotalamount, 2) }}</li>
                            <li>SST: RM {{ number_format($order->servicecharge, 2) }}</li>
                            @if($order->coupon)
                                <li>Discount: RM {{ number_format($order->discount_amount ?? 0.00, 2) }}</li>
                            @endif
                            <li><strong>Grand Total: RM {{ number_format($order->grandtotal + $order->servicecharge, 2) }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@stop

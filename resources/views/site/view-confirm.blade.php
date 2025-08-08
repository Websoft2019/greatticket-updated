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
                            <h1><span>Ticket <span>Information</span></h1>
                            <p>Fill your information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('notify::components.notify')
    </section>

    <section>
        <div class="block gray">
            <div class="container">
                <div class="row">
                    <h4>Billing Address</h4> <br />
                    <form action="{{ route('confirm.update', $order->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="col-md-8">
                            <div class="checkbox-form">
                                <div class="row">
                                    <!-- Full Name Input Start -->
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Full Name <span class="required">*</span></label>
                                            <input name="name" type="text" value="{{ $order->name ?? old('name') }}"
                                                required>
                                        </div>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Full Name Input End -->

                                    <!-- Address Input Start -->
                                    <!--<div class="col-md-12">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Address <span class="required">*</span></label>-->
                                    <!--        <input placeholder="Street address" name="address" type="text"-->
                                    <!--            value="{{ $order->address ?? old('address') }}" required>-->
                                    <!--    </div>-->
                                    <!--    @error('address')-->
                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                    <!--    @enderror-->
                                    <!--</div>-->
                                    <!-- Address Input End -->

                                    <!-- Country, State, City, and Postcode Input Start -->
                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Country <span class="required">*</span></label>-->
                                    <!--        <select class="form-control" name="country" required>-->
                                    <!--            <option value="Malaysia"-->
                                    <!--                {{ $order->country == 'Malaysia' ? 'selected' : '' }}>-->
                                    <!--                Malaysia-->
                                    <!--            </option>-->
                                    <!--        </select>-->
                                    <!--    </div>-->
                                    <!--    @error('country')-->
                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                    <!--    @enderror-->
                                    <!--</div>-->

                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label for="">State</label>-->
                                    <!--        <select class="form-control" name="state" id="malaysiastate" required>-->
                                    <!--            @foreach (['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Putra Jaya', 'Sarawak', 'Sabah', 'Labuan'] as $state)-->
                                    <!--                <option value="{{ $state }}"-->
                                    <!--                    {{ $order->state == $state ? 'selected' : '' }}>-->
                                    <!--                    {{ $state }}-->
                                    <!--                </option>-->
                                    <!--            @endforeach-->
                                    <!--        </select>-->

                                    <!--    </div>-->
                                    <!--    @error('state')-->
                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                    <!--    @enderror-->
                                    <!--</div>-->

                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>City <span class="required">*</span></label>-->
                                    <!--        <input type="text" name="city" value="{{ $order->city ?? old('city') }}"-->
                                    <!--            required>-->
                                    <!--    </div>-->
                                    <!--    @error('city')-->
                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                    <!--    @enderror-->
                                    <!--</div>-->

                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Postcode <span class="required">*</span></label>-->
                                    <!--        <input placeholder="" type="text" name="postcode"-->
                                    <!--            value="{{ $order->postcode ?? old('postcode') }}" required>-->
                                    <!--    </div>-->
                                    <!--    @error('postcode')-->
                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                    <!--    @enderror-->
                                    <!--</div>-->
                                    <!-- Country, State, City, and Postcode Input End -->

                                    <!-- Email Address Input Start -->
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Email Address <span class="required">*</span></label>
                                            <input name="email" type="email"
                                                value="{{ $order->email ?? old('email') }}" required>
                                        </div>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone Input Start -->
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Phone <span class="required">*</span></label>
                                            <input type="text" name="phone"
                                                value="{{ $order->phone ?? old('phone') }}" required>
                                        </div>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Phone Input End -->
                                </div>
                            </div>

                            <!-- Package Details Section Start -->
                            @foreach ($order->orderPackages as $orderItem)
                                <div class="checkbox-form">
                                    <div class="row">
                                        <div>Enter Details for package => {{ $orderItem->package->title }}</div>
                                        @for ($i = 0; $i < ($orderItem->quantity*$orderItem->package->maxticket); $i++)
                                            <div class="col-md-6">
                                                <div class="checkout-form-list">
                                                    <label>Full Name {{ $i + 1 }} <span
                                                            class="required">*</span></label>
                                                    <input
                                                        name="package-{{ $orderItem->package_id }}-name-{{ $i }}"
                                                        type="text"
                                                        value="{{ old('package-' . $orderItem->package_id . '-name-' . $i, $orderItem->ticketUsers[$i]->name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checkout-form-list">
                                                    <label>IC Number {{ $i + 1 }}<span
                                                            class="required">*</span></label>
                                                    <input
                                                        name="package-{{ $orderItem->package_id }}-ic-{{ $i }}"
                                                        type="text"
                                                        value="{{ old('package-' . $orderItem->package_id . '-ic-' . $i, $orderItem->ticketUsers[$i]->ic ?? '') }}">
                                                </div>
                                                @error('package-{{ $orderItem->package_id }}-ic-{{ $i }}')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                            <!-- Package Details Section End -->

                        </div>
                        <div class="col-md-4">
                            <div class="single-posts" style="background:#c836c5; text-align:left; padding: 40px">
                                <div class="row">
                                    <h4 style="color: yellow">Tickets</h4>
                                    <hr>
                                    <ul class="checkoutbox">
                                        @foreach ($order->orderPackages as $orderItem)
                                            <li>{{ $orderItem->package->title }} X {{ $orderItem->quantity }} <span
                                                    style="float: right"> RM {{ number_format(($orderItem->package->actual_cost * $orderItem->quantity),2) }}</span></li>
                                            <hr>
                                        @endforeach
                                        <li>Total <span style="float: right">RM {{ $order->carttotalamount }}</span></li>
                                        <li>SST <span style="float: right" id="servicecharge">RM {{number_format($order->servicecharge, 2)}}</span></li>
                                        <li>Coupon Code <span id="dcost"></span> Offer <span style="float: right" id="discount">RM {{number_format($order->discount_amount, 2)}}</span></li>
                                        <li><strong>Grand Total <span style="float: right">RM
                                                    {{ number_format(($order->grandtotal+$order->servicecharge),2) }}</span></strong></li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <h4 style="color: yellow">Payment Method</h4>
                                    <hr>
                                    <input type="radio" name="paymentmethod" checked required> <span
                                        style="color: #fff">Online Payment</span>
                                    <img src="{{ asset('site/images/onlinepayment.jpg') }}" alt="" width="100%">
                                </div>
                            </div>

                            <div class="row" style="text-align: right; margin-right:20px; padding-top:20px;">
                                <div class="col-md-12">
                                    <input type="submit" value="Confirm Order" class="btn btn-danger">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop

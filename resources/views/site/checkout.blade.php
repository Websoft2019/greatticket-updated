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
        .checkbox-form .checkout-form-list input[type=email],
        .checkbox-form .checkout-form-list input[type=tel] {
            background: #ffffff;
            border: 1px solid #efefef;
            border-radius: 0;
            height: 42px;
            width: 100%;
            padding: 0 10px 0 10px;
            font-size: 14px;
        }

        .checkbox-form .iti__country-list--dropup {
            bottom: 100%;
            margin-bottom: -1px;
            width: 300px;
        }

        .iti {
            position: relative;
            display: block !important;
        }

        .iti__country-list {
            /*z-index: 9999; /* Bring dropdown above everything
        /*max-height: 300px;*/
            /*overflow-y: auto;*/
            width: 250px;
        }
    </style>
    <!-- Include CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17/build/css/intlTelInput.css" />

    <!-- Include JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17/build/js/intlTelInput.min.js"></script>
@stop

@section('content')
    <section>
        <div class="block gray half-parallax blackish remove-bottom">
            <div style="background:url({{ asset('site/images/parallax8.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="page-title">

                            <h1><span>Ticket <span>Checkout</span></h1>
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
                    {{-- @dd($errors->all()) --}}
                    <form action="{{ route('postCheckout', Session::get('cartcode')) }}" method="POST">
                        @csrf()
                        <div class="col-md-8">
                            <div class="checkbox-form">
                                <div class="row">

                                    <!-- Select Country Name End -->

                                    <!-- First Name Input Start -->
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Full Name <span class="required">*</span></label>
                                            <input name="name" type="text" id="full_name"
                                                value="{{ auth()->user()->name ?? old('name') }}" required="">
                                        </div>
                                    </div>
                                    <!-- First Name Input End -->

                                    <!-- Last Name Input Start -->


                                    <!-- Address Input Start -->
                                    <!--<div class="col-md-12">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Address<span class="required">*</span></label>-->
                                    <!--        <input placeholder="Street address" name="address" type="text"-->
                                    <!--            value="{{ old('address') }}" required="">-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <!-- Address Input End -->

                                    <!-- Optional Text Input Start -->
                                    <!--<div class="col-md-12">-->
                                    <!--    <div class="row">-->

                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Country <span class="required">*</span></label>-->

                                    <!--        <select class="form-control" id="country" name="country"-->
                                    <!--            required="">-->

                                    <!--            <option value="Malaysia">Malaysia</option>-->

                                    <!--        </select>-->


                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label for="">State</label>-->


                                    <!--        <select class="form-control" name="state" id="malaysiastate"-->
                                    <!--            style="">-->
                                    <!--            <option value="Johor">Johor</option>-->
                                    <!--            <option value="Kedah">Kedah</option>-->
                                    <!--            <option value="Kelantan">Kelantan</option>-->
                                    <!--            <option value="Melaka">Melaka</option>-->
                                    <!--            <option value="Negeri Sembilan">Negeri Sembilan</option>-->
                                    <!--            <option value="Pahang">Pahang</option>-->
                                    <!--            <option value="Perak">Perak</option>-->
                                    <!--            <option value="Perlis">Perlis</option>-->
                                    <!--            <option value="Pulau Pinang">Pulau Pinang</option>-->
                                    <!--            <option value="Selangor">Selangor</option>-->
                                    <!--            <option value="Terengganu">Terengganu</option>-->
                                    <!--            <option value="Kuala Lumpur">Kuala Lumpur</option>-->
                                    <!--            <option value="Putra Jaya">Putra Jaya</option>-->
                                    <!--            <option value="Sarawak">Sarawak</option>-->
                                    <!--            <option value="Sabah">Sabah</option>-->
                                    <!--            <option value="Labuan">Labuan</option>-->
                                    <!--        </select>-->
                                    <!--        <div class="error" id="stateerror" style="color: red; display:none">-->
                                    <!--            <p>Must fillup state</p>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>City <span class="required">*</span></label>-->
                                    <!--        <input type="text" name="city" value="// if ($order) {-->
                                            <!--            //echo $order->city;-->
                                            <!--       // } else {-->
                                            <!--          //  echo old('city');-->
                                            <!--       // } ?>"-->
                                    <!--            required="">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="col-md-3">-->
                                    <!--    <div class="checkout-form-list">-->
                                    <!--        <label>Postcode <span class="required">*</span></label>-->
                                    <!--        <input placeholder="" type="text" id="bpostalcode" name="postcode"-->
                                    <!--            value="{{ old('postcode') }}" required="">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Email Address <span class="required">*</span></label>
                                            <input placeholder="" name="email" type="email" value="{{ old('email') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Phone <span class="required">*</span></label>
                                            <input type="tel" id="contact_number" name="phone"
                                                class="form-control phone-input"
                                                value="{{ old('phone', auth()->user()->contact) }}"
                                                style="padding:0 14px 0 50px;" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @if ($errors->any())
                                @dd($errors->all())
                            @endif  --}}

                            @if ($noOfTicket > 0)
                                <div class="checkbox-form">
                                    <div class="row">
                                        @foreach ($carts as $cart)
                                            <!--<div class="package-title1">-->
                                            <div class="col-md-12 ml-2"
                                                style="color: blue; background-color: pink; display: inline-block; margin-bottom:15px;">
                                                Enter Details for package => {{ $cart->package->title }}
                                                <small
                                                    style="color: green; display:block; line-height:9px; padding-bottom:8px;">In
                                                    this package {{ $cart->package->maxticket }} pax entry. </small>
                                            </div>
                                            <!--</div>-->
                                            @for ($i = 0; $i < $cart->quantity * $cart->package->maxticket; $i++)
                                                @if (isset($cart->seats[$i]))
                                                    <div class="col-md-12">
                                                        <div class="checkout-form-list">
                                                            <label>Seat Assigned:</label>
                                                            <input type="text" class="form-control"
                                                                value="Row: {{ $cart->seats[$i]->row_label }}, Seat: {{ $cart->seats[$i]->seat_number }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    <div class="checkout-form-list" id="participants">
                                                        <label> {{ $i + 1 }}. Participant Full Name<span
                                                                class="required">*</span></label>
                                                        <input
                                                            name="package-{{ $cart->package_id }}-name-{{ $i }}"
                                                            type="text" id="participant_name_{{ $i }}"
                                                            value="{{ old('package-' . $cart->package_id . '-name-' . $i, $i === 0 ? auth()->user()->name ?? '' : '') }}"
                                                            required>
                                                    </div>
                                                    @error('package-' . $cart->package_id . '-name-' . $i)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="checkout-form-list">-->
                                                <!--        <label>  Participant IC Number<span-->
                                                <!--                class="required"> ( optional ) </span></label>-->
                                                <!--        <input-->
                                                <!--            name="package-{{ $cart->package_id }}-ic-{{ $i }}"-->
                                                <!--            type="text"-->
                                                <!--            value="{{ old('package-' . $cart->package_id . '-ic-' . $i) }}">-->
                                                <!--    </div>-->
                                                <!--    @error('package-' . $cart->package_id . '-ic-' . $i)
        -->
                                                    <!--        <div class="text-danger">{{ $message }}</div>-->
                                                    <!--
    @enderror-->
                                                <!--</div>-->
                                                <hr>
                                            @endfor
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="single-posts" style="background:#c836c5; text-align:left; padding: 40px">
                                <div class="row">
                                    <h4 style="color: yellow">Tickets</h4>
                                    <hr>
                                    <ul class="checkoutbox">
                                        @foreach ($carts as $cart)
                                            <li>
                                                {{ $cart->package->title }} X {{ $cart->quantity }}
                                                <span style="float: right"> RM {{ number_format($cart->cost, 2) }}</span>
                                            </li>
                                            <hr>
                                        @endforeach

                                        <li>Total <span style="float: right">RM {{ number_format($carttotal, 2) }}</span>
                                        </li>
                                        <li>SST <span style="float: right" id="servicecharge">RM
                                                {{ number_format($totalcommision, 2) }}</span></li>

                                        <li>
                                            <input type="text" id="coupon_code" name="coupon_code"
                                                placeholder="Coupon/Discount Code"
                                                style="height: 33px; padding:0 15px; color:#000;">
                                            <button type="button" id="apply_coupon"
                                                class="btn btn-danger">Apply</button>
                                        </li>
                                        <li>Coupon Code <span id="dcost"></span> Offer <span style="float: right"
                                                id="discount">RM 0.00</span></li>
                                        <li><strong>Grand Total <span style="float: right" id="grand_total">RM
                                                    {{ number_format($carttotal + $totalcommision, 2) }}</span></strong></li>
                                    </ul>

                                </div>
                                <div class="row">
                                    <h4 style="color: yellow"> Payment Method</h4>
                                    <hr>
                                    <input type="radio" name="paymentmethod" checked required> <span
                                        style="color: #fff">Online
                                        Payment</span>
                                    <img src="{{ asset('site/images/onlinepayment.jpg') }}" alt=""
                                        width="100%">
                                </div>

                            </div>
                            <div class="row" style="text-align: right; margin-right:20px; padding-top:20px;">
                                <div class="col-md-12">
                                    <input type="submit" value="Checkout" class="btn btn-danger">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fullNameInput = document.getElementById("full_name");
            const firstParticipantInput = document.getElementById("participant_name_0");

            if (fullNameInput && firstParticipantInput) {
                // Update participant name whenever full name changes
                fullNameInput.addEventListener("input", function() {
                    firstParticipantInput.value = fullNameInput.value;
                });
            }

            var input = document.querySelector("#contact_number");
            window.intlTelInput(input, {
                initialCountry: "my",
                geoIpLookup: function(callback) {
                    fetch('https://ipinfo.io/json?token=2facb9e810b2ca')
                        .then(response => response.json())
                        .then(data => callback(data.country))
                        .catch(() => callback('us'));
                },
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17/build/js/utils.js" // for formatting/validation
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#apply_coupon').click(function() {
                let code = $('#coupon_code').val();
                let packageId =
                    {{ $carts->first()->package->id ?? 'null' }}; // Assuming a single package for simplicity
                let userId = {{ Auth()->user()->id }}

                if (!code || !packageId) {
                    alert('Please enter a valid coupon code.');
                    return;
                }

                $.ajax({
                    url: "{{ env('APP_URL') }}/api/coupon",
                    type: "POST",
                    data: {
                        code: code,
                        package_id: packageId,
                        user_id: userId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.success) {

                            let discount = response.data.discount;
                            let cartTotal = {{ $carttotal }};
                            let grandTotal = (cartTotal - discount) + {{ $totalcommision }};
                            let dcost = response.data.dcost;



                            // Update discount and grand total in the UI
                            $
                            $('#discount').text('RM ' + discount.toFixed(2));
                            $('#grand_total').text('RM ' + grandTotal.toFixed(2));
                            $('#dcost').text(dcost);

                        } else {
                            alert('Unable to apply coupon. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        let errorResponse = xhr.responseJSON;
                        if (errorResponse && errorResponse.message) {
                            alert(errorResponse.message);
                        } else {
                            alert('An error occurred while applying the coupon.');
                        }
                    }
                });
            });
        });
    </script>
@endpush

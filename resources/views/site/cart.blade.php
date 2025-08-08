@extends('site.template')
@section('css')
    <style>
        thead {

            padding-bottom: 30px;

            width: 100%;
        }

        .cartlist tr th {
            text-align: center;
        }

        thead tr {
            background: #fc409f;
            padding: 10px 0;
        }

        .table thead tr th {
            line-height: 40px;
            color: #fff;
        }

        tfoot {
            font-weight: bold;
            padding-top: 40px;
            overflow: hidden;
            /* display: table; */
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

                            <h1><span>Ticket <span>Cart</span></h1>
                            <p>Your ticket cart lists</p>
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
                    @include('notify::components.notify')
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="col-md-12 column">
                        <div class="single-post">
                            <div class="row">
                                <div class="table-responsive"> <!-- Add this wrapper for responsiveness -->
                                    <table class="table cartlist">
                                        <thead>
                                            <tr>
                                                <th>Event</th>
                                                <th>Package</th>
                                                <th>Cost</th>
                                                <th>Qty</th>
                                                <th>Total Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carts as $cart)
                                                <tr>
                                                    <td>{{ $cart->event->title }}</td>
                                                    <td>{{ $cart->package->title }}</td>
                                                    <td>RM {{ number_format($cart->package->actual_cost, 2) }}</td>
                                                    <td>
                                                        @if ($cart->seats()->exists())
                                                            {{ $cart->quantity }} <br/>
                                                            <strong>Reserved Seats:</strong>
                                                            @foreach ($cart->seats as $seat)
                                                                <span class="badge badge-secondary"
                                                                    style="margin-right: 5px;">
                                                                    {{ $seat->row_label }}{{ $seat->seat_number }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <form action="{{ route('updateCart', $cart->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="number" value="{{ $cart->quantity }}"
                                                                    class="form-control"
                                                                    style="width: 80px; display: inline-block; margin-right: 10px;"
                                                                    id="cart_qty" name="cart_qty">
                                                                <input type="hidden"
                                                                    value="{{ $cart->package->actual_cost }}"
                                                                    name="actual_cost">
                                                                <button type="submit" class="btn btn-sm"
                                                                    style="background-color: #c66eed; color: white;">Update</button>
                                                            </form>
                                                        @endif

                                                    </td>
                                                    <td>RM {{ number_format($cart->cost, 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('deleteCart', $cart->id) }}" method="post"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr style="background: #ccc;">
                                                <th colspan="5" style="text-align: right">Grand Total</th>
                                                <th>RM {{ number_format($totalamount, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div> <!-- End of .table-responsive -->

                                <div class="row mt-3"> <!-- Added margin-top for spacing -->
                                    <div class="col-md-12">
                                        <div class="row" style="margin : auto  10px;">
                                            <div style="float: left">
                                                <a href="{{ route('getEventDetail', $event->slug) }}"
                                                    class="btn btn-danger">Continue Shopping</a>
                                            </div>
                                            <div style="float: right;">
                                                <a href="{{ route('getCheckout', Session::get('cartcode')) }}"
                                                    class="btn btn-danger">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

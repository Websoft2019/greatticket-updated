@extends('site.template')
@section('content')
    <section>
        <div class="block gray half-parallax blackish remove-bottom">
            <div style="background:url({{ asset('site/images/parallax8.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="page-title">
                            <span>User Dashboard</span>
                            <h1>HISTORY<span> DETAILS</span></h1>
                            <p>List of your orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="block gray">
        <div class="container">
            <div class="row">
                <div class="col-md-12 column">
                    
                        <div class="upcoming-event">
                            <div class="event-detail">
                                <h3>{{ $event->title }}</h3>
                                <ul class="countdown">
                                    @foreach ($order->orderPackages as $packageDetails)
                                    <h4>Package : {{ $packageDetails->package->title }}</h4>
                                    <br />
                                    <table class="table table-bordered">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th scope="col"> Name</th>
                                                {{-- <th scope="col">Cost (RM)</th> --}}
                                                <th>Payment / QR</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packageDetails->ticketUsers as $ticketUser)
                                                <tr>
                                                    <td>{{ $ticketUser->name }}</td>
                                                    
                                                    <td>
                                                        @if ($ticketUser->qr_image)
                                                            <a href="{{ asset('storage/' . $ticketUser->qr_image) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('storage/' . $ticketUser->qr_image) }}"
                                                                    alt="" srcset="" height="40px"
                                                                    width="40px"> <br />
                                                                    
                                                            </a>
                                                        @else
                                                            Unpaid
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->paymentstatus == 'Y')
                                                        {{-- <a href="" class="btn btn-success btn-sm">Downlod Indiviual Ticket</a> --}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <hr />
                                    @endforeach
                                    <h4>Billing</h4>
                                    <table class="table">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th scope="col"> Ticket Cost</th>
                                                <th scope="col">SST</th>
                                                <th>Coupon Code / Offer Discount</th>
                                                <th>Paid Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>RM {{$order->carttotalamount}}</td>
                                                <td>RM {{$order->servicecharge}}</td>
                                                <td>RM {{$order->discount_amount}}</td>
                                                <td><strong>RM {{number_format($order->grandtotal+$order->servicecharge, 2)}}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @if ($order->paymentstatus == 'N')
                                        {{--  --}}
                                        <a href="{{ route('getconfirm' , 'order_id=' . $order->id . '&event_name=' . $event->title ) }}" class="btn btn-success">Pay Now</a>
                                        <form action="{{ route('order.delete', $order->id) }}"
                                            method="post" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                Remove
                                            </button>

                                        </form>
                                    @else
                                        <a href="{{ route('tickets.download.pdf', $order->id) }}"
                                            class="btn btn-success">Download Ticket</a>
                                    @endif
                                </ul><!-- Event Countdown -->
                                <span
                                    class="event-date"><strong>{{ $event->date->format('d') }}</strong><i>{{ $event->date->format('M') }}</i><i>{{ $event->date->format('Y') }}</i></span>
                            </div><!-- Event Details -->
                            <div class="event-img">
                                <img src="{{ asset('storage/' . $event->primary_photo) }}"
                                    alt="">
                            </div><!-- Event Image -->
                        </div>
                   
                </div>

            </div>
        </div>
    </div>
@stop

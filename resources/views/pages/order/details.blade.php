@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('title', 'Order Details')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Order Details</p>
                            <a name="" id="" class="btn btn-sm ms-auto btn-primary"
                                href="{{ route('organizer.order.index') }}" role="button">View</a>

                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Order Details</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Name</label>
                                    <input class="form-control" type="text" value="{{ $order->name }}" name="firstname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Address</label>
                                    <input class="form-control" type="text" value="{{ $order->address }}"
                                        name="lastname">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email</label>
                                    <input class="form-control" type="email" name="email" value="{{ $order->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Phone</label>
                                   <input class="form-control" type="text" name="phone" value="{{$order->phone}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Country</label>
                                    <input class="form-control" type="email" name="email" value="{{ $order->country }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">State</label>
                                    <input class="form-control" type="text" name="password" value="{{ $order->state }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">City</label>
                                    <input class="form-control" type="text" value="{{ $order->city }}" name="number">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Post Code</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ $order->postcode }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Grand Total</label>
                                    <input class="form-control" type="text" name="password"
                                        value="{{ $order->grandtotal }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Payment Status</label>
                                    <input class="form-control" type="text" value="{{ $order->paymentmethod }}"
                                        name="number">
                                </div>
                            </div>


                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Coupon code</p>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div>Coupon code : {{ $order?->coupon?->code }}</div>
                                <div>
                                    Discount :
                                    @if($order?->coupon)
                                        @if($order->coupon->coupontype === 'percentage')
                                            {{ $order->coupon->cost }}%
                                        @else
                                            RM {{ $order->coupon->cost }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </div>

                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <p class="text-uppercase text-sm">Package And User</p>
                        @foreach ($order->orderPackages as $packageDetails)
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Package: {{ $packageDetails->package->title }}</h5>
                                    <small>Event: {{ $packageDetails->package->event->title }}</small>
                                </div>

                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th scope="col">Ticket User Name</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Cost (RM)</th>
                                                <th>Seat No.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packageDetails->ticketUsers as $ticketUser)
                                                <tr>
                                                    <td>{{ $ticketUser->name }}</td>
                                                    <td>{{ $packageDetails->package->title }}</td>
                                                    <td>RM{{ $packageDetails->package->cost }}</td>
                                                    <td>
                                                        {{$ticketUser->seat_id ? ($ticketUser->seat->row_label . $ticketUser->seat->seat_number) : 'N/A'}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

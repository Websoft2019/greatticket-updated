@extends('layouts.app', ['class' => 'g-sidenav-show bg-light'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Book Event'])
    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2>Booking Details</h2>
                        <p><strong>Customer:</strong> {{ $order->name }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p><strong>Total Tickets:</strong> {{ $totalTickets }}</p>
                        <p>
                            <strong>Total Price:</strong>
                            @if($order->grandtotal == '0.00')
                                Complementary
                            @else
                                {{ $order->grandtotal }}
                            @endif
                        </p>

                        @foreach ($data as $item)
                            <hr>
                            <h4>Event: {{ $item['event'] }}</h4>
                            <p><strong>Package:</strong> {{ $item['package']->title }}</p>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Participant</th>
                                        <!-- <th>Contact Number</th> -->
                                        <!-- <th>Membership No</th> -->
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['ticket_users'] as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <!-- <td>{{ $user->phone }}</td> -->
                                            <!-- <td>{{ $user->membership_no }}</td> -->
                                            <td><img src="{{ asset('storage/' . $user->qr_image) }}" width="80"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach

                        <a href="{{ route('organizer.bookings.pdf', $order->id) }}" class="btn btn-primary mt-3">Download
                            PDF</a>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

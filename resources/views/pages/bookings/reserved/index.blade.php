@extends('layouts.app', ['class' => 'g-sidenav-show bg-light'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Book Event'])

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="mb-4">Reserved Bookings</h2>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    </div>

                    @if ($reservedBookings->count())
                        <div class="container-fluid">
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive px-2">
                                    <table id="myTable" class="table py-1 align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Event</th>
                                                <th>Package</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Booked At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reservedBookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->name ?? 'N/A' }}</td>
                                                    <td>{{ $booking->orderPackages->first()->package->event->title ?? '-' }}</td>
                                                    <td>{{ $booking->orderPackages->first()->package->title ?? '-' }}</td>
                                                    <td>{{ $booking->orderPackages->sum('quantity') }}</td>
                                                    <td>RM {{ number_format($booking->total_amount, 2) }}</td>
                                                    <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('bookings.reserved.show', $booking) }}"
                                                            class="btn btn-sm btn-primary">Details</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{-- {{ $reservedBookings->links() }} --}}
                                @else
                                    <p>No reserved bookings found.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Order Management'])


    <div class="row mt-4 mx-4">
        <div class="col-12">
            <!-- Add this at the top of your report.blade.php file -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Filter Report3</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.salesReport') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="payment_method" class="form-label">Payment Method:</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="">All Payment Methods</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}" {{ $paymentMethod == $method ? 'selected' : '' }}>
                                        {{ $method == 'SanangPay' ? 'online' : $method }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                            <a href="{{ route('admin.salesReport') }}" class="btn btn-secondary ms-2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-4">

                <div class="card-header pb-0">
                    {{-- <form method="GET" action="{{ route('admin.salesReport') }}">
                        <input type="date" name="datefrom" value="{{ $dateFrom }}">
                        <input type="date" name="dateto" value="{{ $dateTo }}">

                        <select name="payment_method">
                            <option value="">All Payment Methods</option>
                            <option value="manual" {{ $paymentMethod == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="SanangPay" {{ $paymentMethod == 'SanangPay' ? 'selected' : '' }}>SanangPay
                            </option>
                        </select>

                        <button type="submit">Filter</button>
                    </form> --}}
                    <h6>Organizers</h6>

                </div>
                <div class="container-fluid">
                    <div class="row">
                        @foreach ($organizers as $organizer)
                            <div class="col-12" style="background: #ccc; margin:10px 0; padding:15px;">
                                <h5>{{ $organizer->name }}</h5>
                                <small>{{ $organizer->email }}</small>
                                
                                <div class="table-responsive"> <!-- Make table scrollable on small screens -->
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Event</th>
                                                <th>Commission Type</th>
                                                <th>Commission Value</th>
                                                <th>Total Amount</th>
                                                <th>Greatticket Amount</th>
                                                <th>Organizer Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($organizer->events as $event)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td> {{ Illuminate\Support\Str::limit($event->title, 20) }}</td>
                                                    <td>{{ $event->cm_type }}</td>
                                                    <td>{{ $event->cm_value }}</td>
                                                    <td>{{ round($event->totalamount, 2) }}</td>
                                                    <td>{{ round($event->admin_amount, 2) }}</td>
                                                    <td>{{ round($event->totalamount - $event->admin_amount, 2) }}</td>
                                                    <td>
                                                        <a class="btn btn-warning send-daily-report" 
                                                        href="javascript:void(0);" 
                                                        data-event-id="{{ $event->id }}" 
                                                        data-event-title="{{ $event->title }}">
                                                            Send Daily Report
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                
                                            <tr>
                                                <td colspan="5" class="text-right"><strong>Total Event Cost:</strong></td>
                                                <td>{{ round($organizer->events->sum('totalamount'), 2) }}</td>
                                                <td>{{ round($organizer->events->sum('admin_amount'), 2) }}</td>
                                                <td><strong>{{ round($organizer->events->sum('totalamount') - $organizer->events->sum('admin_amount'), 2) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                

            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="dailyReportModal" tabindex="-1" role="dialog" aria-labelledby="dailyReportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{route('admin.senddailyreport')}}">
        @csrf
        <input type="hidden" name="event_id" id="modalEventId">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dailyReportModalLabel">Send Daily Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Event:</strong> <span id="modalEventTitle"></span></p>
                <div class="form-group">
                    <label for="report_date">Select Date</label>
                    <input type="date" class="form-control" name="report_date" value="{{ now()->toDateString() }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Send Report</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
@section('js')
<script>
    $(document).on('click', '.send-daily-report', function () {
        var eventId = $(this).data('event-id');
        var eventTitle = $(this).data('event-title');

        $('#modalEventId').val(eventId);
        $('#modalEventTitle').text(eventTitle);
        $('#dailyReportModal').modal('show');
    });
</script>
@stop

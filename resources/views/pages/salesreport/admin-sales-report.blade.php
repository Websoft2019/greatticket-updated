@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sales Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Sales Management2</h6>
                </div>

                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Package Name</th>
                                            <th>Event Name</th>
                                            <th>Organizer Name</th>
                                            <th>Total Tickets Sold</th>
                                            <th>Total Revenue</th>
                                            <th>Commission Type</th>
                                            <th>Commission Value</th>
                                            <th>Total Commission Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salesData as $data)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{ $data->package_title }}</td>
                                                <td>{{ $data->event_title }}</td>
                                                <td>{{ $data->organizer_name }}</td>
                                                <td>{{ $data->total_tickets_sold }}</td>
                                                <td>{{ number_format($data->total_revenue, 2) }}</td>
                                                <td>{{ $data->cm_type }}</td>
                                                <td>{{ $data->cm_value }}</td>
                                                <td>{{ number_format($data->commission_money, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

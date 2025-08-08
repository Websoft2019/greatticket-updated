@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Sales Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Sales Management1</h6>
                </div>

                <div class="container-fluid">
                    <form action="{{ route('organizer.salesReport') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-control-label">From <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="datefrom"
                                        value="{{ $_GET['datefrom'] ?? '' }}">

                                    @error('datefrom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-control-label">To <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="dateto"
                                        value="{{ $_GET['dateto'] ?? '' }}">

                                    @error('dateto')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <input type="submit" value="Fetch" class="btn btn-primary">
                        </div>
                    </form>
                </div>

                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs font-weight-bolder ">ID
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">SN
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Name
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Event
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Email
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Total Cost
                                        </th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Payment Status</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Payment Method</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesReports as $order)
                                        <tr>
                                            <td>{{$order->id}}</td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $order->name }}
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">
                                                    {{ $order->orderPackage->package->event->title ?? '' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ $order->email }}</p>
                                            </td>
                                            

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">RM {{ $order->grandtotal }}</p>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $order->paymentstatus }}</p>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $order->paymentmethod }}</p>
                                            </td>

                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a name="" id="" class="btn btn-success px-3 py-2 btn-sm me-2" href="{{route('organizer.order.details',$order->id)}}"
                                                        role="button" title="Edit"><i
                                                            class="fa-solid fa-eye"></i></a>

                                                    <form action="{{}}" class="d-inline-block" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" title="Delete" class="btn btn-danger px-3 py-2 btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                                    </form>

                                                </div>
                                            </td>
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

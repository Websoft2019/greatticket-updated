@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Order Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders</h6>

                </div>
                <div class="card-header pb-0" style="width: 150px;">
                    <select class="form-control" name="status" id="status" onchange="handleSelection()">
                        <option value="all" @selected($_GET['status'] == 'all')>All</option>
                        <option value="completed" @selected($_GET['status'] == 'completed')>Completed</option>
                        <option value="not-completed" @selected($_GET['status'] == 'not-completed')>Not Completed</option>
                    </select>
                    <script>
                        function handleSelection() {
                            var selectedValue = document.getElementById("status").value;

                            window.location.href = "{{ route('organizer.order.index') }}" + "?status=" + selectedValue;
                        }
                    </script>
                </div>
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
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
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $order->name }}
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ $order->orderPackages->first()->package->event->title }}</p>
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

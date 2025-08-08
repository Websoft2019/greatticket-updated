@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Package Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Package</h6>
                    <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                        href="{{ route('organizer.event.package.create') }}" role="button">Create</a>

                </div>
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs font-weight-bolder ">SN
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Event
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Title
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Status
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Cost
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Actual Cost
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Remaining ticket
                                        </th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Photo</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($packages as $package)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $package->event->title }}
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ $package->title }} <br />
                                                    No. of Ticket : {{$package->maxticket}}
                                                </p>
                                            </td>
                                            <td class="text-sm">
                                                {!! $package->status ? '<span class="badge bg-success">Show</span>' : '<span class="badge bg-danger">Hide</span>'!!}
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $package->cost }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $package->actual_cost }}</p>
                                            </td>
                                            
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ ($package->capacity - $package->consumed_seat
                                                    ) }}</p>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <a href="{{ Str::startsWith($package->photo, 'http') ? $package->photo : asset('storage/' . $package->photo) }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ Str::startsWith($package->photo, 'http') ? $package->photo : asset('storage/' . $package->photo) }}" alt="" width="200px" height="200px">
                                                </a>
                                            </td>

                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a name="" id="" class="btn btn-info px-3 py-2 btn-sm me-2" href="{{route('organizer.event.package.edit',$package->id)}}"
                                                        role="button" title="Edit"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>

                                                    <form action="{{route('organizer.event.package.delete',$package->id)}}" class="d-inline-block" method="post">
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

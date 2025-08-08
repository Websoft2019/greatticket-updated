@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'CheckIn Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Events</h6>

                </div>
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs font-weight-bolder ">SN
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Title
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Event at
                                        </th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Organizer</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                            {{ \Illuminate\Support\Str::limit($event->title ?? 'N/A', 30) }}
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $event->date->format('d M Y') }} – {{ $event->time }}</p>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm mb-0">{{ $event->user->name }}</p>
                                            </td>

                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <a name="" id="" class="btn btn-success px-3 py-2 btn-sm me-2" href="{{route('admin.checkin.checkin',$event->id)}}"
                                                        role="button" title="Edit"><i
                                                            class="fa-solid fa-eye"></i></a>

                                                    

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

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Organizer</h6>
                    <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                        href="{{ route('admin.organizer.create') }}" role="button">Create</a>

                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive px-2">
                        <table id="myTable" class="table py-1 align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Email
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Address</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($organizers as $organizer)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <a href="{{ $organizer->organizer->photo ? asset('storage/' . $organizer->organizer->photo) : asset('img/team-2.png') }}"
                                                        target="_blank"><img
                                                            src="{{ $organizer->organizer->photo ? asset('storage/' . $organizer->organizer->photo) : asset('img/team-2.png') }}"
                                                            class="avatar me-3" alt="image"></a>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $organizer->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $organizer->email }}</p>
                                        </td>
                                        <td>
                                            <span
                                                class="badge 
                                                {{ $organizer->organizer->verify ? 'bg-success' : 'bg-warning' }}">
                                                {{ $organizer->organizer->verify ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $organizer->organizer->address }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a name="" id="" class="btn btn-sm me-1 btn-primary"
                                                    href="{{ route('admin.organizer.edit', $organizer->id) }}"
                                                    role="button">Edit</a>

                                                @if (!$organizer->organizer->verify)
                                                    <form action="{{ route('admin.organizer.delete', $organizer->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif

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
@endsection

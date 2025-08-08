@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Check-In Management'])
    <div class="row mt-4 mx-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
            </div>
        @endif
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">CheckIn</div>
                <div class="card-body">
                    <form action="{{ route('admin.checkin.package.checkin.store', $package->id) }}" method="post">
                        @csrf
                        <div class="">
                            <label for="" class="form-label">Code</label>
                            <input type="text" class="form-control" name="code" id=""
                                aria-describedby="helpId" placeholder="" autofocus />
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary my-1">
                            Submit
                        </button>


                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Event Name: <span class="text-primary">{{ $package->event->title }}</span></h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-secondary">Package: {{ $package->title }}</h6>
                        <div class="table-responsive">
                            <table id="myTable" class="table table-bordered table-hover align-items-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase text-xs font-weight-bold">SN</th>
                                        <th class="text-uppercase text-xs font-weight-bold">User Name</th>
                                        <th class="text-uppercase text-xs font-weight-bold">Checked In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sn = 1; @endphp
                                    @foreach ($package->orderPackages as $order)
                                        @foreach ($order->ticketUsers as $user)
                                            <tr>
                                                <td>{{ $sn++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>
                                                    @if ($user->checkedin)
                                                        <span class="badge bg-success">Arrived</span>
                                                    @else
                                                        <span class="badge bg-danger">Haven't Arrived</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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
@push('js')
    <script>
        $(document).ready(function() {
            $('myTable').DataTable();
        });
    </script>
@endpush

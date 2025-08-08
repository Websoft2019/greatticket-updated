@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('header')
<style>
    @media (max-width: 768px) {
        table.dataTable td {
            white-space: normal !important;
        }
        
    }
    .dt-search{
            text-align: right;
        }
</style>
@endpush
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
                    <form action="{{ route('admin.checkin.checkin.store', $event->id) }}" method="post">
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
                    <h6 class="mb-0"><span class="text-primary">{{ $event->title }}</span></h6>
                </div>
                <div class="card-body">
                    @forelse ($event->packages as $package)
                        <div class="mb-4" style="background: pink; padding:10px 15px">
                            <a href="{{ route('admin.checkin.package.checkin', $package->id) }}">
                                <h6 class="text-secondary"><strong><u>Package: {{ $package->title }}</u></strong></h6>
                            </a>
                            <div class="table-responsive">
                                <table id="myTable{{ $package->id }}"
                                    class="table table-bordered table-hover align-items-center">
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
                    @empty
                        <div class="alert alert-warning">No packages found for this event.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Loop through all tables with IDs starting with "myTable"
            $('table[id^="myTable"]').each(function() {
                let tableId = $(this).attr('id'); // Get table ID
                $('#' + tableId).DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    autoWidth: false,
                    responsive: true,
                    dom: '<"row mb-2"<"col-12 text-md-right"f>>tip' // Removed 'l'
                });
            });
        });
    </script>
@endpush

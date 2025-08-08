@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('header')
<style>
@media (max-width: 768px) {
    .responsive-table thead {
        display: none;
    }

    .responsive-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 0.75rem;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .responsive-table tbody tr td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border: none;
        border-bottom: 1px solid #eee;
    }

    .responsive-table tbody tr td:last-child {
        border-bottom: none;
    }

    .responsive-table tbody tr td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #333;
        text-align: left;
    }
    /* .responsive-table tbody tr td.event-title-cell {
        max-width: 280px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: right;
    } */
}
</style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Trending Events Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Trending Events</h6>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Create
                    </button>

                    <!-- Create Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.trending-events.store') }}" method="post">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createModalLabel">Create Trending Event</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="event_id" class="col-form-label">Event:</label>
                                            <select class="form-control" name="event_id" id="event_id">
                                                @foreach($events as $event)
                                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('event_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="priority" class="col-form-label">Priority:</label>
                                            <input type="number" class="form-control" name="priority" id="priority" value="5" min="1">
                                            @error('priority')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive px-2">
                        <table class="table responsive-table py-1 align-items-center mb-0">
                            <thead class="d-md-table-header-group">
                                <tr>
                                    <!-- <th>SN</th> -->
                                    <th>Event Name</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trendingEvents as $event)
                                    <tr>
                                        <!-- <td data-label="SN">{{ $loop->iteration }}</td> -->
                                        <td data-label="Event Name " class="event-title-cell">  {{ \Illuminate\Support\Str::limit($event->event->title ?? 'N/A', 18) }} </td>
                                        <td data-label="Priority">{{ $event->priority }}</td>
                                        <td data-label="Action">
                                            <form action="{{ route('admin.trending-events.destroy', $event->id) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
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

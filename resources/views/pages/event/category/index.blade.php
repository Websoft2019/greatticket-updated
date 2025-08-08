@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Category Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Category</h6>
                        <button type="button" class="btn btn-primary btn-sm ms-auto"
                        data-bs-toggle="modal"
                        data-bs-target="#categoryModalCreate">
                        Create
                    </button>

                    <div class="modal fade" id="categoryModalCreate"
                        tabindex="-1">
                        <div class="modal-dialog">
                            <form id="myForm"
                                action="{{ route('admin.event.category.store')}}"
                                method="POST" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Create Category</h5>
                                        <button type="button" class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            @csrf

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="name"
                                                    class="form-control-label">Name </label>
                                                <input class="form-control" id="name"
                                                    type="text" name="name"
                                                    value="{{ old('name') }}">
                                            </div>
                                            @error('name')
                                                <div class="text-danger">* {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="status"
                                                    class="form-control-label">Status
                                                </label>
                                                <select class="form-select form-select-lg"
                                                    name="status" id="status">
                                                    <option value="show"
                                                        @selected(old('status') == 'show')>Show
                                                    </option>
                                                    <option value="hide"
                                                        @selected(old('status') == 'hide')>Hide
                                                    </option>
                                                </select>
                                            </div>
                                            @error('status')
                                                <div class="text-danger">* {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit"
                                            class="btn btn-primary">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

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
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Status</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $category->name }}
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                {{ $category->status }}
                                            </td>

                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">

                                                    <button type="button" class="btn btn-sm btn-info px-3 py-2 me-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#categoryModal{{ $category->id }}">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>

                                                    <div class="modal fade" id="categoryModal{{ $category->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form id="myForm"
                                                                action="{{ route('admin.event.category.update', $category->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Category</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>
                                                                            @csrf
                                                                            @method('PUT')

                                                                        <div class="col">
                                                                            <div class="form-group">
                                                                                <label for="name"
                                                                                    class="form-control-label">Name </label>
                                                                                <input class="form-control" id="name"
                                                                                    type="text" name="name"
                                                                                    value="{{ old('name', $category->name) }}">
                                                                            </div>
                                                                            @error('name')
                                                                                <div class="text-danger">* {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="form-group">
                                                                                <label for="status"
                                                                                    class="form-control-label">Status
                                                                                </label>
                                                                                <select class="form-select form-select-lg"
                                                                                    name="status" id="status">
                                                                                    <option value="show"
                                                                                        @selected(old('status', $category->status) == 'show')>Show
                                                                                    </option>
                                                                                    <option value="hide"
                                                                                        @selected(old('status', $category->status) == 'hide')>Hide
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            @error('status')
                                                                                <div class="text-danger">* {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <form
                                                        action="{{ route('admin.event.category.delete', $category->id) }}"
                                                        class="d-inline-block" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" title="Delete"
                                                            class="btn btn-danger px-3 py-2 btn-sm"><i
                                                                class="fa-solid fa-trash-can"></i></button>
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
@push('js')
    <script>
        let submit = document.getElementById('loading');
        document.getElementById('myForm').addEventListener('submit', function(e) {
            submit.innerHTML = "Submitting ...";

            submit.setAttribute('disabled', true);
        });

        document.getElementById('reset').addEventListener('click', function() {
            previewContainer.innerHTML = "";
        });
    </script>
@endpush

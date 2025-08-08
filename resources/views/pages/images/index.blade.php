@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('header')
    <style>
        .img-preview {
            width: auto;
            height: 300px;
            object-fit: contain;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Image Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>image</h6>
                    <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                        href="{{ route('organizer.event.image.create') }}" role="button">Create</a>

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
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Image</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($images as $image)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $image->event->title }}
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <a data-bs-toggle="modal" href="#imageModal{{ $image->id }}"
                                                    role="button">
                                                    <img src="{{ asset('storage/' . $image->photo) }}" style="width:200px;"
                                                        alt="" srcset="">
                                                </a>
                                            </td>

                                            {{-- modal starts --}}
                                            <div class="modal fade" id="imageModal{{ $image->id }}" aria-hidden="true"
                                                style="z-index: 9999;" aria-labelledby="imageModal{{ $image->id }}Label"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="imageModal{{ $image->id }}Label">
                                                                Image</h5>
                                                            <button type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"><i
                                                                    class="fa-solid fa-xmark"></i></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $image->photo) }}"
                                                                class="img-fluid" alt="" srcset="">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- modal ends  --}}

                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">

                                                    <button type="button" class="btn btn-sm btn-info px-3 py-2 me-2" data-bs-toggle="modal"
                                                        data-bs-target="#ImageModal{{$image->id}}">
                                                        <i
                                                            class="fa-solid fa-pen-to-square"></i>
                                                    </button>

                                                    <div class="modal fade" id="ImageModal{{$image->id}}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form id="myForm"
                                                                action="{{ route('organizer.event.image.update', $image->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Edit Image</h5>
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
                                                                                <label for="photo"
                                                                                    class="form-control-label">Photo</label>
                                                                                <input class="form-control" id="photo"
                                                                                    type="file" name="photo"
                                                                                    accept="image/jpeg, image/png, image/gif, image/webp">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="imagePreview">Image
                                                                                    Preview:</label>
                                                                                <div id="imagePreview" class="mt-2">
                                                                                    <!-- Preview image will be displayed here -->
                                                                                </div>
                                                                            </div>
                                                                            @error('photo')
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

                                                    <form action="{{ route('organizer.event.image.delete', $image->id) }}"
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
        const previewContainer = document.getElementById('imagePreview');
        document.getElementById('photo').addEventListener('change', function(event) {
            const input = event.target;

            // Clear any previous preview
            previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-preview');
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

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
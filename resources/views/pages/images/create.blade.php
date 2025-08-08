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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item fs-4"><a href="{{route('organizer.event.index')}}">Event</a></li>
                            <li class="breadcrumb-item fs-4"><a href="{{route('organizer.event.image.index',session()->get('e_id'))}}">Image</a></li>
                            <li class="breadcrumb-item fs-4 active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Create Event</p>
                            <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                                href="{{ route('organizer.event.index') }}" role="button">View</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Image Information</p>
                        <form id="myForm" action="{{ route('organizer.event.image.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="col">
                                <div class="form-group">
                                    <label for="photo" class="form-control-label">Photo</label>
                                    <input class="form-control" id="photo" type="file" name="photo"
                                        accept="image/jpeg, image/png, image/gif, image/webp">
                                </div>
                                <div class="form-group">
                                    <label for="imagePreview">Image Preview:</label>
                                    <div id="imagePreview" class="mt-2">
                                        <!-- Preview image will be displayed here -->
                                    </div>
                                </div>
                                @error('photo')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm" id="loading">Submit</button>
                                <button type="reset" id="reset" class="btn btn-primary btn-sm">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('layouts.footers.auth.footer')
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

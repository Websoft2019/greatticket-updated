@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('header')
    <style>
        .img-preview {
            width: auto;
            height: 300px;
            object-fit: contain;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/ckeditor5/ckeditor5.css') }}">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Event'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a name="" id="" class="btn btn-primary btn-sm"
                                    href="{{ route('organizer.event.index') }}" role="button">View</a>
                            </div>
                            <div>
                                <a name="" id="" class="btn btn-primary btn-sm"
                                    href="{{ route('organizer.event.image.index', $event->id) }}" role="button">Images</a>
                                <a name="" id="" class="btn btn-primary btn-sm"
                                    href="{{ route('organizer.event.package.index', $event->id) }}"
                                    role="button">Packages</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Event</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Event Information</p>
                        <form id="myForm" action="{{ route('organizer.event.update', $event->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Title</label>
                                    <input class="form-control" id="title" type="text" name="title"
                                        value="{{ $event->title }}">
                                </div>
                                @error('title')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vennue" class="form-control-label">Vennue</label>
                                        <input class="form-control" id="vennue" type="text"
                                            value="{{ old('vennue', $event->vennue) }}" name="vennue">
                                    </div>
                                    @error('vennue')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id" class="form-control-label">Category</label>
                                        <select class="form-select form-select-lg" name="category_id" id="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id) == $category->id)>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date" class="form-control-label">Date</label>
                                        <input class="form-control" type="text" name="date" id="date"
                                            value="{{ \Carbon\Carbon::parse(old('date', $event->date))->format('Y-m-d') }}">
                                    </div>
                                    @error('date')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time" class="form-control-label">Time</label>
                                        <input class="form-control" id="time" name="time" type="time"
                                            value="{{ $event->time }}">
                                    </div>
                                    @error('time')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude" class="form-control-label text-capitalize">longitude</label>
                                        <input class="form-control" type="longitude" name="longitude" id="longitude"
                                            value="{{ $event->longitude }}">
                                    </div>
                                    @error('longitude')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude" class="form-control-label text-capitalize">latitude</label>
                                        <input class="form-control" id="latitude" name="latitude" type="latitude"
                                            value="{{ $event->latitude }}">
                                    </div>
                                    @error('latitude')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="photo" class="form-control-label">Primary Photo</label>
                                    <input class="form-control" id="photo" type="file" name="primary_photo"
                                        accept="image/jpeg, image/png, image/gif, image/webp">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="imagePreview">Image Preview:</label>
                                            <div id="imagePreview" class="mt-2">
                                                <!-- Preview image will be displayed here -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Current Image:</label>
                                            <div class="mt-2">
                                                <img src="{{ str_contains($event->primary_photo, 'http') ? $event->primary_photo : asset('storage/' . $event->primary_photo) }}"
                                                    class="img-thumbnail" alt="" srcset="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('primary_photo')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="seat_photo" class="form-control-label">Seat View Photo</label>
                                    <input class="form-control" id="seat_photo" type="file" name="seat_view"
                                        accept="image/jpeg, image/png, image/gif, image/webp">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seat_imagePreview">Image Preview:</label>
                                            <div id="seat_imagePreview" class="mt-2">
                                                <!-- Preview image will be displayed here -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Current Image:</label>
                                            <div class="mt-2">
                                                <img src="{{ str_contains($event->seat_view, 'http') ? $event->seat_view : asset('storage/' . $event->seat_view) }}"
                                                    class="img-thumbnail" alt="" srcset="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('seat_view')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        role="switch" id="flexSwitchCheckChecked" @checked($event->status)>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">This will determine
                                        whether to show or hide event</label>
                                </div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="highlight" class="form-label">Highlight</label>
                                    <textarea class="form-control" name="highlight" id="highlight" rows="3">{{ $event->highlight }}</textarea>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="3">{{ $event->description }}</textarea>
                                </div>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm" id="loading">Update</button>
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

        const seat_previewContainer = document.getElementById('seat_imagePreview');
        document.getElementById('seat_photo').addEventListener('change', function(event) {
            const input = event.target;

            // Clear any previous preview
            seat_previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-preview');
                    seat_previewContainer.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        let submit = document.getElementById('loading');
        document.getElementById('myForm').addEventListener('submit', function(e) {
            submit.innerHTML = "Processing ...";

            submit.setAttribute('disabled', true);
        });

        document.getElementById('reset').addEventListener('click', function() {
            previewContainer.innerHTML = "";
        });
    </script>

    <script type="importmap">
    {
        "imports": {
            "ckeditor5": "{{asset('vendor/ckeditor5/ckeditor5.js')}}",
            "ckeditor5/": "{{asset('vendor/ckeditor5')}}"
        }
    }
</script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Subscript,
            Superscript,
            Font,
            Link,
            List,
            Indent,
            IndentBlock,
            BlockQuote,
        } from 'ckeditor5';

        ClassicEditor
            .create(document.querySelector('#description'), {
                plugins: [Essentials, Paragraph, Bold, Italic, Subscript, Superscript, Link, Font, List, Indent,
                    IndentBlock, BlockQuote
                ],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', 'subscript', 'superscript', 'link', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote',
                ],
                // Set the height of the editor
                height: '200px',
            })
            .then(editor => {
                window.editor = editor;
                // Apply custom height via CSS
                const editableElement = editor.ui.view.editable.element;
                editableElement.style.height = '200px'; // Set desired height
                editableElement.style.overflowY = 'auto'; // Handle overflow for long content
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

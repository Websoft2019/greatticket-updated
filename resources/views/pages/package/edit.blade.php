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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item fs-4"><a href="{{ route('organizer.event.index') }}">Event</a></li>
                            <li class="breadcrumb-item fs-4"><a
                                    href="{{ route('organizer.event.package.index', session()->get('e_id')) }}">Package</a>
                            </li>
                            <li class="breadcrumb-item fs-4 active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0">Edit Package</p>

                            <div class="col-mb-4 col-lg-3 ms-auto">

                                <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                                    href="{{ route('organizer.event.package.index', session('e_id')) }}" role="button">View
                                    Packages</a>

                                @if ($package->seats()->exists())
                                    <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                                        href="{{ route('organizer.event.package.seats.edit', $package->id) }}"
                                        role="button">Edit Seats</a>
                                @else
                                    <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                                        href="{{ route('organizer.event.package.seats.create', $package->id) }}"
                                        role="button">Add Seats</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-uppercase text-sm">Package Information</p>
                        <form id="myForm" action="{{ route('organizer.event.package.update', $package->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="title" class="form-control-label">Title</label>
                                        <input class="form-control" id="title" type="text" name="title"
                                            value="{{ $package->title }}">
                                    </div>
                                    @error('title')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="maxticket" class="form-control-label">Max Ticket</label>
                                        <input class="form-control" id="maxticket" type="number" name="maxticket"
                                            value="{{ $package->maxticket }}">
                                    </div>
                                    @error('title')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cost" class="form-control-label">Cost </label>
                                        <input class="form-control" id="cost" type="number" step="0.01"
                                            name="cost" value="{{ old('cost', $package->cost) }}">
                                    </div>
                                    @error('cost')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_price" class="form-control-label">Discount Price</label>
                                        <input class="form-control" id="discount_price" type="number" name="discount_price"
                                            value="{{ old('discount_price', $package->discount_price) }}">
                                    </div>
                                    @error('discount_price')
                                        <div class="text-danger">* {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="capacity" class="form-control-label">Capacity </label>
                                    <input class="form-control" id="capacity" type="number" min="1"
                                        value="{{ old('capacity', $package->capacity) }}" name="capacity">
                                </div>
                                @error('capacity')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="photo" class="form-control-label">Photo</label>
                                    <input class="form-control" id="photo" type="file" name="photo"
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
                                                <img src="{{ asset('storage/' . $package->photo) }}"
                                                    class="img-thumbnail" alt="" srcset="">
                                                <img src="{{ str_contains($package->photo, 'http') ? $package->photo : asset('storage/' . $package->photo) }}"
                                                    class="img-thumbnail" alt="" srcset="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('photo')
                                    <div class="text-danger">* {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        role="switch" id="flexSwitchCheckChecked" @checked($package->status)>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">This will determine
                                        whether to show or hide package</label>
                                </div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col mb-3">
                                <label for="seat_status" class="form-label">Seat Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="seat_status" value="0">
                                    <input class="form-check-input" type="checkbox" name="seat_status" value="1"
                                        role="switch" id="flexSwitchCheckChecked" @checked($package->seat_status)>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">This will determine
                                        whether to show or hide seats to the user for choosing</label>
                                </div>
                                @error('seat_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $package->description) }}</textarea>
                                </div>
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

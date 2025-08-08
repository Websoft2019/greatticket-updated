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
    @include('layouts.navbars.auth.topnav', ['title' => 'Organizer'])

    <div class="container-fluid py-4">
        <div class="row">
            <form role="form" method="POST" action={{ route('admin.organizer.update', $organizer->id) }}
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="col">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Organizer</p>
                                <a name="" id="" class="btn btn-primary btn-sm ms-auto"
                                    href="{{ route('admin.organizer.index') }}" role="button">View</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Organizer Information</p>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label"> Organizer Name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text"
                                            value="{{ old('name', $organizer->name) }}" name="name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $organizer->email) }}">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Password ( Leave this
                                            field empty if you don't want to change password )<span
                                                class="text-danger"></span></label>
                                        <input class="form-control" type="text" value="{{ old('password') }}"
                                            name="password">
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address
                                            (optional)</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('address', $organizer->organizer->address) }}" name="address">
                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact" class="form-control-label">Contact <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="contact" class="form-control" id="contact"
                                            value="{{ old('contact', $organizer->contact) }}">

                                        @error('contact')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cm_type" class="form-control-label">Commission Type <span
                                                class="text-danger">*</span></label>
                                        <select name="cm_type" id="cm_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="flat" @selected(old('cm_type', $organizer->organizer->cm_type) == 'flat')>Flat</option>
                                            <option value="percentage" @selected(old('cm_type', $organizer->organizer->cm_type) == 'percentage')>Percentage</option>
                                        </select>

                                        @error('cm_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cm_value" class="form-control-label">Commission Value <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="cm_value"
                                            value="{{ old('cm_value', $organizer->organizer->cm_value) }}">

                                        <small class="">Flat: eg. MYR.100, MYR.200, MYR.300</small><br>
                                        <small class="">Perc: eg. 10, 11.5, 60.4 | Range: 0 - 100%</small>

                                        @error('cm_value')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="religion" class="form-control-label">Religion</label>

                                        <select class="form-select form-select-lg" name="religion_id" id="religion">
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->id }}"
                                                    {{ old('religion_id') == $religion->id ? 'selected' : '' }}>
                                                    {{ $religion->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('religion_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                            </div>


                            <hr class="horizontal dark">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photo" class="form-control-label">Photo (optional)</label>
                                        <input class="form-control" id="photo" type="file" name="photo"
                                            accept="image/jpeg, image/png, image/gif, image/webp">
                                    </div>


                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="imagePreview">Image Preview:</label>
                                                <div id="imagePreview" class="mt-2">
                                                    <!-- Preview image will be displayed here -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="oldImage">Current Image</label>
                                                <div class="mt-2">
                                                    <img src="{{ str_contains($organizer->organizer->photo, 'http') ? $organizer->organizer->photo : asset('storage/' . $organizer->organizer->photo) }}"
                                                        class="img-thumbnail" alt="" srcset="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                            <hr class="horizontal dark">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="about" class="form-control-label">About Organizer <span
                                                class="text-danger">*</span></label>
                                        <textarea name="about" class="form-control" id="about" cols="30" rows="10">{{ old('about', $organizer->organizer->about) }}</textarea>
                                        @error('about')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="loading">Submit</button>
                            @if (!$organizer->organizer->verify)
                                <a name="" id="" class="btn btn-primary" onclick="return confirm('Are you sure ?')" href="{{route('admin.organizer.verify', $organizer->id)}}"
                                    role="button">Verify</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
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

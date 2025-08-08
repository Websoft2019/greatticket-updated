@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('header')
    {{-- <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" /> --}}
    <link rel="stylesheet" href="{{ asset('vendor/ckeditor5/ckeditor5.css') }}">
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Privacy Policy'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Privacy Policy</h6>
                </div>
                <div class="card-body pt-0 pb-2">
                    <form action="{{route('admin.page.privacy.update',$privacy->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label text-capitalize">Title</label>
                            <input type="title" class="form-control" name="title" id="title" value="{{$privacy->title}}"/>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label text-capitalize">description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="10">{{$privacy->description}}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
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
                height: '400px',
            })
            .then(editor => {
                window.editor = editor;
                // Apply custom height via CSS
                const editableElement = editor.ui.view.editable.element;
                editableElement.style.height = '400px'; // Set desired height
                editableElement.style.overflowY = 'auto'; // Handle overflow for long content
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

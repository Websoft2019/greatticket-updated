@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Contact Us Messages'])

    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <h1>Carousel Images</h1>

                        <!-- Button to trigger the modal for creating a new image -->
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Add New
                            Image</button>

                        <!-- Table displaying images -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">SN</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carousels as $carousel)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>
                                            <img src="{{ asset('storage/' . $carousel->image) }}" alt="Carousel Image"
                                                width="100">
                                        </td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-image="{{ asset('storage/' . $carousel->image) }}"
                                                data-id="{{ $carousel->id }}" data-old-image="{{ $carousel->image }}">
                                                Edit
                                            </button>
                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.carousel.destroy', $carousel) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Modal for creating a new image -->
                        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createModalLabel">Upload New Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.carousel.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Choose Image</label>
                                                <input type="file" class="form-control" id="createImage" name="image"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="imagePreview" class="form-label">Image Preview</label>
                                                <img id="imagePreview" class="img-fluid"
                                                    style="max-height: 200px; display: none;">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for editing an image -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data" id="editForm">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="editImage" class="form-label">Choose Image</label>
                                                <input type="file" class="form-control" id="editImage" name="image"
                                                    accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label for="imagePreview" class="form-label">Image Preview</label>
                                                <img id="editImagePreview" class="img-fluid"
                                                    style="max-height: 200px; display: none;">
                                            </div>
                                            <div class="mb-3">
                                                <img id="oldImage" class="img-fluid" style="max-height: 200px;">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Live preview for creating new image
            document.getElementById('createImage').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.onload = function() {
                    const preview = document.getElementById('imagePreview');
                    preview.src = reader.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });

            // When the edit modal is triggered
            $('#editModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const imageId = button.data('id'); // Get carousel ID from data-id attribute
                const imageUrl = button.data('image'); // Get image URL

                // Set the old image in the preview
                const oldImage = document.getElementById('oldImage');
                oldImage.src = imageUrl;
                oldImage.style.display = 'block'; // Ensure it's visible

                // Set the form action dynamically
                const actionUrl = '{{ route('admin.carousel.update', ':id') }}'.replace(':id', imageId);
                $('#editForm').attr('action', actionUrl);

                // Handle live preview of the new image being uploaded
                const editImageInput = document.getElementById('editImage');
                const editImagePreview = document.getElementById('editImagePreview');

                editImageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function() {
                            editImagePreview.src = reader.result; // Set the preview source to the file data
                            editImagePreview.style.display = 'block'; // Show the image
                        };
                        reader.readAsDataURL(file); // Read the file data
                    }
                });
            });
        </script>
    @endpush
@endsection

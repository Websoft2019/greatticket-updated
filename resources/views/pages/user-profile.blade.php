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
    @include('layouts.navbars.auth.topnav', ['title' => 'Your Profile'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        @if(auth()->user()->role == 'o' && auth()->user()->organizer->photo)
                            <img src="{{asset('storage/'. auth()->user()->organizer->photo)}}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        @else
                            <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        @endif
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->name ?? 'Name' }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            @if (auth()->user()->role == 'a')
                                Admin
                            @elseif (auth()->user()->role == 'o')
                                Organizer
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('name', auth()->user()->name) }}" name="name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}">
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date of Birth</label>
                                        <input class="form-control" type="date" name="dob"
                                            value="{{ old('dob', auth()->user()->dob) }}">
                                        @error('dob')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="form-control-label">Gender</label>

                                        <select class="form-select form-select-lg" name="gender" id="gender">
                                            <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female" @selected(auth()->user()->gender == 'female')>Female</option>
                                            <option value="others" @selected(auth()->user()->gender == 'others')>Others</option>
                                        </select>

                                        @error('gender')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact" class="form-control-label">Contact</label>
                                        <input type="number" name="contact" class="form-control" id="contact"
                                            value="{{ old('contact', auth()->user()->contact) }}">

                                        @error('contact')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="icnumber" class="form-control-label">IC Number</label>
                                        <input type="number" name="icnumber" class="form-control" id="icnumber"
                                            value="{{ old('icnumber', auth()->user()->icnumber) }}">

                                        @error('icnumber')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="religion" class="form-control-label">Religion</label>

                                        <select class="form-select form-select-lg" name="religion_id" id="religion">
                                            @foreach ($religions as $religion)
                                                <option value="{{$religion->id}}"
                                                    {{ auth()->user()->religion_id == $religion->id ? 'selected' : '' }}>
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

                        </div>

                        @if (auth()->user()->role == 'o')
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm mx-3">Contact Information</p>
                            <div class="row mx-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="form-control-label">Country</label>
                                        <input type="text" name="country" class="form-control" id="country"
                                            value="{{ old('country', auth()->user()->country) }}">

                                        @error('country')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state" class="form-control-label">State</label>
                                        <input type="text" name="state" class="form-control" id="state"
                                            value="{{ old('state', auth()->user()->state) }}">

                                        @error('state')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city" class="form-control-label">City</label>
                                        <input type="text" name="city" class="form-control" id="city"
                                            value="{{ old('city', auth()->user()->city) }}">

                                        @error('city')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postcode" class="form-control-label">Postcode</label>
                                        <input type="number" name="postcode" class="form-control" id="postcode"
                                            value="{{ old('postcode', auth()->user()->postcode) }}">

                                        @error('postcode')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
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
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mx-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('address', auth()->user()->organizer->address) }}"
                                            name="address">
                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm mx-3">About me</p>
                            <div class="row mx-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="about" class="form-control-label">About me</label>
                                        <textarea name="about" class="form-control" id="about" cols="30" rows="10">{{ old('about', auth()->user()->about) }}</textarea>
                                        @error('about')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="card">
                    <form role="form" method="POST" action={{ route('updatePassword') }}>
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Change Password</p>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Old Password</label>
                                        <input class="form-control" type="text" name="old_password">
                                        @error('old_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">New Password</label>
                                        <input class="form-control" type="text" name="password">
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>


                        </div>

                    </form>
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

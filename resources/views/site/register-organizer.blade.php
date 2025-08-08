<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Organizer</title>

    <link href="{{ asset('site/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Container for the form */
        .custom-container {
            max-width: 800px;
            margin: 50px auto;
        }

        /* Form styling */
        .custom-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
        }

        .custom-card h3 {
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #333;
            border-bottom: 2px solid #7b1fa2;
            display: inline-block;
            padding-bottom: 5px;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #7b1fa2;
            box-shadow: 0 0 5px rgba(123, 31, 162, 0.5);
        }

        .btn-primary {
            background-color: #7b1fa2;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #7c3bf3;
        }

        .img-preview {
            width: auto;
            height: 300px;
            object-fit: contain;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .custom-container {
                padding: 20px;
            }

            .btn-primary {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>


    <div class="custom-container">
        <h1 class="text-center">Register as an Organizer</h1>
        <a name="" id="" class="btn btn-primary" href="{{route('getHome')}}" role="button">Go Back</a>

        <form action="{{ route('organizer.store') }}" method="POST" enctype="multipart/form-data" class="custom-card">
            @csrf

            <h3>Basic Information</h3>
            <div class="mb-3">
                <label for="user_name" class="form-label">Full Name <span class="text-danger">* </span></label>
                <input type="text" name="user[name]" id="user_name" class="form-control"
                    value="{{ old('user.name') }}" required>
                @error('user.name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">Email Address <span class="text-danger">* </span></label>
                <input type="email" name="user[email]" id="user_email" class="form-control"
                    value="{{ old('user.email') }}" required>
                @error('user.email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_password" class="form-label">Password <span class="text-danger">* </span></label>
                <input type="password" name="user[password]" id="user_password" class="form-control" required>
                @error('user.password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*
                    </span></label>
                <input type="password" name="user[password_confirmation]" id="user_password_confirmation"
                    class="form-control" required>
                @error('user.password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_phone" class="form-label">Phone Number <span class="text-danger">* </span></label>
                <input type="text" name="user[phone]" id="user_phone" class="form-control"
                    value="{{ old('user.phone') }}">
                @error('user.phone')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <h3>Detail Information</h3>
            <div class="mb-3">
                {{-- <div class="col-md-12"> --}}
                <div class="form-group">
                    <label for="photo" class="form-control-label">Photo <span class="text-danger">* </span></label>
                    <input class="form-control" id="photo" type="file" name="photo"
                        accept="image/jpeg, image/png, image/gif, image/webp">
                    @error('user.photo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
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
                {{-- </div> --}}
            </div>
            <div class="mb-3">
                <label for="about" class="form-label">About (You and your company) <span class="text-danger">*
                    </span></label>
                <textarea name="about" id="about" class="form-control" rows="4">{{ old('about') }}</textarea>
                @error('user.about')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address <span class="text-danger">* </span></label>
                <input type="text" name="address" id="address" class="form-control"
                    value="{{ old('address') }}">
                @error('user.address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>
        </form>
    </div>
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
</body>

</html>

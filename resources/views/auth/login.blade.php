@extends('site.template')
@section('css')
<style>

body {
    background: url(data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwcNDQ0NBw0HBwgNBw0HDQcHBw8ICQcNFREWFhURExUYHSggGBolGxUVITEhJSkrLi4uFx8zODMsNygtLisBCgoKDQ0NDw0NDisZFRkrNysrKystKysrKy0rKzcrKy0tKysrKysrKy0tKy0tKysrKysrKysrKysrKysrKysrK//AABEIALwBDAMBIgACEQEDEQH/xAAYAAEBAQEBAAAAAAAAAAAAAAABAgAGB//EABYQAQEBAAAAAAAAAAAAAAAAAAABEf/EABkBAQEBAQEBAAAAAAAAAAAAAAIBBgUAA//EABURAQEAAAAAAAAAAAAAAAAAAAAB/9oADAMBAAIRAxEAPwDzxmZtnzZmLzzQsRKGGCKiUoYY0VBONDGInDC0MGlDDGioNONDGkVBpRoYyohRoqCKgnGioIqDTjGNDBOQxUEVBpRoqCKgnI0VBFQacMLESjkWZncZllQQosJjQwThhjQwaUMUIqIcaKgihKNDGhg04YYyoNKNFQRQnGioFQaUjKgioNONCxgnIYqCKiUo0VGMGnGio0I05DDGhg04YWhEpHHkKjuMuxYxDhhjQwaUMVBFDTjKgioJyNDGioNKNFQQwacMVBFRCjKgioJRoqCKgnGioFQacjQxoqCcaKjSEaUaKgioJyNFSNDBpwwxoUKQwscE446KgLuMvDDGiolONDGipBpRoYyhORooRUGlGioIoacZUEVBpRoqCKgnGioIqDSjRUaFKcaKgioJyNFQRUGlIYY0ME4YY0hg04YY0VBONFQQwaUMUIoTkcaYxjusvDIY0VBpRooRUE40VBFRKcaKgioJRoqCKg040VBFQSjRUEUNORoqNIYJRoqBUE5GioIqJThhjQhSkMMaFKchioIqCcaKEVBONFQRUGlGVgihOONioIqO7WXjRUEUJRoqCKg05GVBFQaUaKEVBONFRoRKNFRoYlOGGNDBpQwxjBORoqCKgnGkVGhg05CY0MGlIYqCKg040VBFQTjRUEVINKNFMYNOGERSHHGxUEVHcrLQwwRUQ5GigqDTjKgihKNFQSKgnDDGhEoYWhgnDDGI0o0VBFRDkMMjQwachMaGCUhhjRUGnGioIqCcaKEVBpxoqNDBpSNFRoYJyGFpChyONimhjuMtGioIqCcaKEVBpRoqCKiU4YY0MEoYY0MGnIYY0MEoTGhgnIYY0MGnDDGhgnDDGiolKRoqCKgHGioFRKcaKjSGDSkJgioJyGGNDBpyGFiJyONio0Md1loyoIqDSjRTQwacMMaGCUMLQxKchMaGCcMMaGDSkaKjQyCcMVBFQacjQxoqCcjRUEVBpRoqCKgnI0VBFSDThkLGCUhMaGIcMLQwachhaQicjjoYxjustI0VBFDSjRUaGCcMMaGCUMMYwacMLQjSkMMaGCchioIqJTkZUgioJyNFQRUGlGioIqCcjRUEVBpwwxoYNOQwxoYJQxUEihpyNDGipBpyNDjRSHI42GNDHcZWGGNDBpwwxjEKGGNDBOQxUEMEoTGME5DDI0VBpxooKgnI0VBFQacjSKgioJSNFRoYlORoqNCNOQmRoYJSGKkEVBpyNDGVBpyNIoKg05GihFYJyONhEVHdrKwwxoYNOGGNDBpQqkEMSnDCxg0oYZGME4YoQwacMVBFQacaKgioNKGGCKgnGipAqCcMMYwacMMaGDShioClOGKgioNONFQRUE40UIoSkf/Z) no-repeat center center fixed;
    background-size: cover
}
</style>
@stop
@section('content')
   
    <main class="main-content  mt-0">
        <div class="container">
            <section>
                <div class="page-header min-vh-100" style="margin-top: 180px">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                @if (session('error'))
                                    <div class="alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Warning!</strong> {{ session('error') }}
                                    </div>
                                    <br />
                                @endif
                                @if (session('succes'))
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Success!</strong> {{ session('succes') }}
                                    </div>
                                    <br />
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5" style="background: #fff; padding:30px;">
                                <h3 style="color: #6b30be;">Sign In</h3>
                                <p class="mb-0">Enter your email and password to sign in as a user</p>
                                <br />
                                <div>
                                
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="Email Address" aria-label="Email" required>
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <br />
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" required>
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        @if(request()->has('returnurl'))
											<input type="hidden" name="returnurl" value="{{ request()->get('returnurl') }}">
										@endif
                                        <div class=" text-right pt-0 px-lg-2 px-1">
                                            <p class="mb-1 mx-auto">
                                            <a href="{{ route('reset-password') }}" class="font-weight-bold" style="color: #6b30be;"> Forgot your password?</a>
                                            </p>
                                        </div>
                                        <div class="text-center" style="margin-top: 10px; margin-bottom:20px">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0" style="background-color: #6b30be; border-color: #6b30be;">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                
                                    
                            </div>
                            <div class="col-md-6 col-md-offset-1" style="background: #fff; padding:30px;">
                                <h3 style="color: #6b30be;">Register</h3>
                                <p class="mb-0">Enter your login informations</p>
                                <br />
                                <div>
                                    <form role="form" method="POST" action="{{ route('register') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" placeholder="Full Name" aria-label="Name" required>
                                            @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <br />
                                        <div class="flex flex-col mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="Email" aria-label="Email" required>
                                                    @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" required>
                                                    @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                                </div>
                                                @if(request()->has('returnurl'))
                                                    <input type="hidden" name="returnurl" value="{{ request()->get('returnurl') }}">
                                                @endif
                                            </div>
                                            
                                        </div>

                                        <div class="form-check form-check-info text-start" style="margin:10px 0">
                                            <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" required>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                I agree the <a href="{{route('termsCondition')}}" class="text-dark font-weight-bolder">Terms and Conditions</a>
                                            </label>
                                            @error('terms')
                                                <p class='text-danger text-xs'> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0" style="background-color: #6b30be; border-color: #6b30be;">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
@section('js')
<script>
$(function() {
$(".btn").click(function() {
$(".form-signin").toggleClass("form-signin-left");
$(".form-signup").toggleClass("form-signup-left");
$(".frame").toggleClass("frame-long");
$(".signup-inactive").toggleClass("signup-active");
$(".signin-active").toggleClass("signin-inactive");
$(".forgot").toggleClass("forgot-left");
$(this).removeClass("idle").addClass("active");
});
});

$(function() {
$(".btn-signup").click(function() {
$(".nav").toggleClass("nav-up");
$(".form-signup-left").toggleClass("form-signup-down");
$(".success").toggleClass("success-left");
$(".frame").toggleClass("frame-short");
});
});

$(function() {
$(".btn-signin").click(function() {
$(".btn-animate").toggleClass("btn-animate-grow");
$(".welcome").toggleClass("welcome-left");
$(".cover-photo").toggleClass("cover-photo-down");
$(".frame").toggleClass("frame-short");
$(".profile-photo").toggleClass("profile-photo-down");
$(".btn-goback").toggleClass("btn-goback-up");
$(".forgot").toggleClass("forgot-fade");
});
});
</script>
@stop


@extends('layouts.app')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder" style="color: #6b30be;">Admin Sign In</h4>
                                    <p class="mb-0">Enter your email and password to sign in as an admin</p>
                                </div>
                                <div class="card-body">

                                    <!-- Display Dismissable Messages -->
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                                            {{ session('error') }}

                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">X</button>
                                        </div>
                                    @endif

                                    <form role="form" method="POST" action="{{ route('admin.login.perform') }}">
                                        @csrf
                                        @method('post')

                                        <!-- Email Field -->
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                value="{{ old('email') }}" placeholder="Email" aria-label="Email">
                                        </div>

                                        <!-- Password Field -->
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                placeholder="Password" aria-label="Password">
                                        </div>

                                        <!-- Remember Me Checkbox -->
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0"
                                                style="background-color: #6b30be; border-color: #6b30be;">
                                                Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Forgot your password? Reset it
                                        <a href="{{ route('reset-password') }}"
                                            class="text-primary text-gradient font-weight-bold">here</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

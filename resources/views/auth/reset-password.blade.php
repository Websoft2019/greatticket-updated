@extends('site.template')
@section('css')
<style>

/* Hero wrapper to maintain proper spacing */
.about-hero-wrapper {
    position: relative;
    width: 100%;
    height: 40vh;
    min-height: 165px;
    background: linear-gradient(rgba(44, 19, 56, 0.9), rgba(44, 19, 56, 0.9)), 
                url('{{ asset('site/images/parallax1.jpg') }}') no-repeat center center;
    background-size: cover;
}

.about-hero-section {
    position: absolute;
    width: 100%;
    top: 50%;
    transform: translateY(-50%);
    padding: 20px 0;
    text-align: center;
}

/* Main content styling */
.about-content {
    position: relative;
    padding: 60px 0;
    background: url(data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwcNDQ0NBw0HBwgNBw0HDQcHBw8ICQcNFREWFhURExUYHSggGBolGxUVITEhJSkrLi4uFx8zODMsNygtLisBCgoKDQ0NDw0NDisZFRkrNysrKystKysrKy0rKzcrKy0tKysrKysrKy0tKy0tKysrKysrKysrKysrKysrKysrK//AABEIALwBDAMBIgACEQEDEQH/xAAYAAEBAQEBAAAAAAAAAAAAAAABAgAGB//EABYQAQEBAAAAAAAAAAAAAAAAAAABEf/EABkBAQEBAQEBAAAAAAAAAAAAAAIBBgUAA//EABURAQEAAAAAAAAAAAAAAAAAAAAB/9oADAMBAAIRAxEAPwDzxmZtnzZmLzzQsRKGGCKiUoYY0VBONDGInDC0MGlDDGioNONDGkVBpRoYyohRoqCKgnGioIqDTjGNDBOQxUEVBpRoqCKgnI0VBFQacMLESjkWZncZllQQosJjQwThhjQwaUMUIqIcaKgihKNDGhg04YYyoNKNFQRQnGioFQaUjKgioNONCxgnIYqCKiUo0VGMGnGio0I05DDGhg04YWhEpHHkKjuMuxYxDhhjQwaUMVBFDTjKgioJyNDGioNKNFQQwacMVBFRCjKgioJRoqCKgnGioFQacjQxoqCcaKjSEaUaKgioJyNFSNDBpwwxoUKQwscE446KgLuMvDDGiolONDGipBpRoYyhORooRUGlGioIoacZUEVBpRoqCKgnGioIqDSjRUaFKcaKgioJyNFQRUGlIYY0ME4YY0hg04YY0VBONFQQwaUMUIoTkcaYxjusvDIY0VBpRooRUE40VBFRKcaKgioJRoqCKg040VBFQSjRUEUNORoqNIYJRoqBUE5GioIqJThhjQhSkMMaFKchioIqCcaKEVBONFQRUGlGVgihOONioIqO7WXjRUEUJRoqCKg05GVBFQaUaKEVBONFRoRKNFRoYlOGGNDBpQwxjBORoqCKgnGkVGhg05CY0MGlIYqCKg040VBFQTjRUEVINKNFMYNOGERSHHGxUEVHcrLQwwRUQ5GigqDTjKgihKNFQSKgnDDGhEoYWhgnDDGI0o0VBFRDkMMjQwachMaGCUhhjRUGnGioIqCcaKEVBpxoqNDBpSNFRoYJyGFpChyONimhjuMtGioIqCcaKEVBpRoqCKiU4YY0MEoYY0MGnIYY0MEoTGhgnIYY0MGnDDGhgnDDGiolKRoqCKgHGioFRKcaKjSGDSkJgioJyGGNDBpyGFiJyONio0Md1loyoIqDSjRTQwacMMaGCUMLQxKchMaGCcMMaGDSkaKjQyCcMVBFQacjQxoqCcjRUEVBpRoqCKgnI0VBFSDThkLGCUhMaGIcMLQwachhaQicjjoYxjustI0VBFDSjRUaGCcMMaGCUMMYwacMLQjSkMMaGCchioIqJTkZUgioJyNFQRUGlGioIqCcjRUEVBpwwxoYNOQwxoYJQxUEihpyNDGipBpyNDjRSHI42GNDHcZWGGNDBpwwxjEKGGNDBOQxUEMEoTGME5DDI0VBpxooKgnI0VBFQacjSKgioJSNFRoYlORoqNCNOQmRoYJSGKkEVBpyNDGVBpyNIoKg05GihFYJyONhEVHdrKwwxoYNOGGNDBpQqkEMSnDCxg0oYZGME4YoQwacMVBFQacaKgioNKGGCKgnGipAqCcMMYwacMMaGDShioClOGKgioNONFQRUE40UIoSkf/Z) no-repeat center center fixed;
    background-size: cover;
}

.section-title {
    color: #2c1338;
    margin-bottom: 30px;
    font-weight: 600;
}

.service-box {
    padding: 30px;
    text-align: center;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.service-box:hover {
    transform: translateY(-5px);
}

.icon-wrapper {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #9c27b0, #673ab7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-wrapper i {
    font-size: 30px;
    color: #fff;
}

.feature-item {
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.feature-item h4 {
    color: #2c1338;
    margin-bottom: 10px;
}

.feature-item i {
    color: #9c27b0;
    margin-right: 10px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .about-hero-wrapper {
        height: 60vh;
    }
    
    .about-hero-section {
        padding: 40px 0;
    }
    
    .col-md-4 {
        margin-bottom: 30px;
    }
}

.hero-text-container {
    margin-top: 145px;
    padding: 20px;
    position: relative;
}

.hero-title {
    font-size: 4em;
    margin-bottom: 30px;
    position: relative;
    display: inline-block;
}

.hero-text-main {
    color: #fff;
    display: inline-block;
    margin-right: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

.hero-text-highlight {
    color: #9c27b0;
    background: linear-gradient(45deg, #9c27b0, #673ab7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
    position: relative;
    transition: all 0.3s ease;
}

.hero-text-highlight::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(45deg, #9c27b0, #673ab7);
    transition: width 0.3s ease;
}

.hero-text-container:hover .hero-text-highlight::after {
    width: 100%;
}

.hero-subtitle-wrapper {
    position: relative;
    overflow: hidden;
    padding: 10px 0;
}

.hero-subtitle {
    font-size: 1.8em;
    color: #fff;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.subtitle-line {
    display: inline-block;
    position: relative;
    padding: 5px 10px;
    transition: all 0.3s ease;
    transform: translateY(0);
    opacity: 0.9;
}

.subtitle-line.highlight {
    color: #ff69b4;
    font-weight: 600;
}

.subtitle-line::before {
    content: '';
    position: absolute;
    left: -10px;
    top: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #9c27b0);
    transform: translateY(-50%);
    transition: width 0.3s ease;
}

.subtitle-line::after {
    content: '';
    position: absolute;
    right: -10px;
    top: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #673ab7, transparent);
    transform: translateY(-50%);
    transition: width 0.3s ease;
}

.hero-text-container:hover .subtitle-line::before,
.hero-text-container:hover .subtitle-line::after {
    width: 30px;
}

.hero-text-container:hover .subtitle-line {
    transform: translateY(-3px);
    opacity: 1;
}

.hero-text-container:hover .hero-text-main {
    text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
}

/* Animation for text on page load */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-title, .subtitle-line {
    animation: fadeInUp 0.8s ease forwards;
    opacity: 0;
}

.subtitle-line:nth-child(1) { animation-delay: 0.2s; }
.subtitle-line:nth-child(2) { animation-delay: 0.4s; }
.subtitle-line:nth-child(3) { animation-delay: 0.6s; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-title {
        margin-top: 250px;
        font-size: 2.5em;
    }
    
    .hero-subtitle {
        font-size: 1.4em;
    }
    
    .hero-text-container {
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        margin-top: 100px;
        font-size: 2em;
    }
    
    .hero-subtitle {
        display: none;
        font-size: 1.2em;
    }
}
</style>
@stop

@section('content')
<div class="about-hero-wrapper">
    <!-- Hero Section -->
    <div class="about-hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="hero-text-container">
                        <h1 class="hero-title">
                            <span class="hero-text-highlight">Forgot Password</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="about-content">
    <div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-auto">
            <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                    <h3 style="color: #fff;">Reset your password</h3>
                    <p class="mb-0" style="color: #fff;">Enter your email and please wait a few seconds</p>
                    @if (session('succes'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Success!</strong> {{ session('succes') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Warning!</strong> {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('reset.perform') }}">
                        @csrf
                        @method('post')
                        <div class="flex flex-col mb-3">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email Address" value="{{ old('email') }}" aria-label="Email">
                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                        </div>
                        <div class="text-left" style="margin-top: 10px;">
                        <button type="submit" class="btn btn-primary mt-4 mb-0" style="background-color: #6b30be; border-color: #6b30be;">Send Reset Link</button>
                        </div>
                    </form>
                </div>
                <div id="alert">
                    @include('components.alert')
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
    
@endsection

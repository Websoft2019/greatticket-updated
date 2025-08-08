@extends('site.template')
@section('css')
<style>
/* Ensure the navigation stays transparent */
.navbar {
    background: transparent !important;
    position: absolute;
    width: 100%;
    z-index: 1000;
}

/* Hero wrapper to maintain proper spacing */
.about-hero-wrapper {
    position: relative;
    width: 100%;
    height: 80vh;
    min-height: 500px;
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
    background-color: #fff;
    padding: 60px 0;
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
    margin-top: 200px;
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
                            <span class="hero-text-highlight">About Great Ticket</span>
                        </h1>
                        <div class="hero-subtitle-wrapper">
                            <p class="hero-subtitle">
                                <span class="subtitle-line">Great Life</span>
                                <span class="subtitle-line">Begins with</span>
                                <span class="subtitle-line highlight">Great Live</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Content -->
<div class="about-content">
    <div class="container">
        <!-- Mission Statement -->
        <div class="row mb-5">
            <div class="col-md-8 col-md-offset-2 text-center">
                <h2 class="section-title">Our Mission</h2>
                <p class="lead">We're dedicated to bringing unforgettable live experiences to our customers through seamless ticket booking services.</p>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="row services-grid">
            <div class="col-md-4">
                <div class="service-box">
                    <div class="icon-wrapper">
                        <i class="fa fa-ticket"></i>
                    </div>
                    <h3>Event Tickets</h3>
                    <p>Access to premium tickets for concerts, sports events, and entertainment shows.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box">
                    <div class="icon-wrapper">
                        <i class="fa fa-music"></i>
                    </div>
                    <h3>Music Events</h3>
                    <p>Exclusive access to live concerts, music festivals, and band performances.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box">
                    <div class="icon-wrapper">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <h3>Special Events</h3>
                    <p>VIP experiences and special event bookings for memorable occasions.</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h2 class="section-title text-center">Why Choose Us</h2>
            </div>
            <div class="col-md-6">
                <div class="feature-item">
                    <h4><i class="fa fa-check-circle"></i> Secure Booking</h4>
                    <p>Safe and encrypted transactions for all your ticket purchases.</p>
                </div>
                <div class="feature-item">
                    <h4><i class="fa fa-clock-o"></i> 24/7 Support</h4>
                    <p>Round-the-clock customer service to assist with your bookings.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-item">
                    <h4><i class="fa fa-star"></i> Premium Selection</h4>
                    <p>Access to the best seats and exclusive event packages.</p>
                </div>
                <div class="feature-item">
                    <h4><i class="fa fa-mobile"></i> Easy Booking</h4>
                    <p>Simple and intuitive booking process across all devices.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
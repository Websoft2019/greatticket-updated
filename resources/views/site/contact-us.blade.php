@extends('site.template')
@section('css')

    <!-- Custom CSS -->
    <style>
        /* Ensure the navigation stays transparent */
        .navbar {
            background: transparent !important;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        /* Hero wrapper to maintain proper spacing */
        .contact-hero-wrapper {
            position: relative;
            width: 100%;
            height: 60vh;
            min-height: 400px;
            background: linear-gradient(rgba(44, 19, 56, 0.9), rgba(44, 19, 56, 0.9)),
                url('{{ asset('site/images/parallax1.jpg') }}') no-repeat center center;
            background-size: cover;
            background-size: cover;
            z-index: -1;
        }

        .contact-hero-section {
            position: absolute;
            width: 100%;
            top: 50%;
            transform: translateY(-50%);
            padding: 20px 0;
            text-align: center;
        }

        /* Main content styling */
        .contact-content {
            position: relative;
            background-color: #fff;
            padding: 60px 0;
        }

        .contact-info-section {
            margin-bottom: 60px;
        }

        .contact-info-box {
            padding: 30px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .contact-info-box:hover {
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

        .section-title {
            color: #2c1338;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Contact Form Styling */
        .contact-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 10px 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            border: none;
            padding: 12px 30px;
            margin-top: 15px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #673ab7, #9c27b0);
        }

        /* Social Links */
        .social-links {
            margin-top: 30px;
        }

        .social-icon {

            margin-top: 20px;
            display: inline-block;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            color: #fff;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            color: #fff;
            text-decoration: none;
        }

        /* Map Section */
        .map-section {
            height: 400px;
            width: 100%;
            overflow: hidden;
        }

        .map-container {
            width: 100%;
            height: 100%;
        }

        .map-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Enhanced Contact Text Styling */
        .contact-text {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            height: auto;
            min-height: 64vh;
        }

        .contact-text .section-title {
            font-size: 2.5em;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .contact-info-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .contact-info-list li {
            margin-bottom: 20px;
            padding-left: 40px;
            position: relative;
        }

        .contact-info-list li i {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #9c27b0;
            font-size: 24px;
        }

        .contact-text-divider {
            width: 50px;
            height: 4px;
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            margin: 20px 0;
            border-radius: 2px;
        }

        /* Enhanced Contact Form Styling */
        .contact-form {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c1338;
            font-weight: 500;
            font-size: 0.95em;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1em;
        }

        .form-control:focus {
            border-color: #9c27b0;
            box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 30px;
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.3);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn-submit:active::after {
            width: 200%;
            height: 200%;
        }

        /* Enhanced Social Links */
        .social-links {
            margin-top: 40px;
        }

        .social-links h4 {
            margin-bottom: 20px;
            color: #2c1338;
            font-size: 1.2em;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #9c27b0, #673ab7);
            color: #fff;
            border-radius: 50%;
            margin-right: 15px;
            transition: all 0.3s ease;
            font-size: 1.2em;
        }

        .social-icon:hover {
            transform: translateY(-5px) rotate(360deg);
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.3);
            color: #fff;
            text-decoration: none;
        }

        /* Form validation styles */
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23dc3545' viewBox='0 0 12 12'%3E%3Cpath d='M6 0a6 6 0 1 0 0 12A6 6 0 0 0 6 0zm1 9H5V7h2v2zm0-3H5V3h2v3z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
        }

        .form-control.is-invalid+.invalid-feedback {
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .contact-hero-wrapper {
                height: 50vh;
            }

            .contact-hero-section {
                padding: 40px 0;
            }

            .contact-form-section {
                margin-top: 30px;
            }

            .contact-text {

                margin-bottom: 30px;
            }
        }


        /* h */

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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
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
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
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

        .hero-title,
        .subtitle-line {
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
        }

        .subtitle-line:nth-child(1) {
            animation-delay: 0.2s;
        }

        .subtitle-line:nth-child(2) {
            animation-delay: 0.4s;
        }

        .subtitle-line:nth-child(3) {
            animation-delay: 0.6s;
        }

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
    <!-- Hero Section with Nav Overlay -->
    @include('notify::components.notify')
    <div class="contact-hero-wrapper">
        <!-- Hero Section -->
        <div class="contact-hero-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="hero-text-container">
                            <h1 class="hero-title">
                                <span class="hero-text-highlight">Get in Touch</span>
                            </h1>
                            <div class="hero-subtitle-wrapper">
                                <p class="hero-subtitle">
                                    <span class="subtitle-line">We'd Love to Hear</span>
                                    <span class="subtitle-line">From You!</span>
                                    <span class="subtitle-line highlight">Contact Us Today</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="contact-content">
        <div class="container">
            <!-- Contact Information Cards -->
            <div class="row contact-info-section">
                <div class="col-md-4">
                    <div class="contact-info-box">
                        <div class="icon-wrapper">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>271, Jalan Permai 2, Taman Desa Permai, <br>09600 Lunas, Kedah</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-box">
                        <div class="icon-wrapper">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h3>Phone Number</h3>
                        <p>011-55512444<br>011-16386959</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info-box">
                        <div class="icon-wrapper">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h3>Email Address</h3>
                        <p>enquiry@greatticket.my<br>&nbsp &nbsp &nbsp</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Contact Form Section -->
            <div class="row contact-form-section">
                <div class="col-md-6">
                    <div class="contact-text">
                        <h2 class="section-title">Let's Connect</h2>
                        <div class="contact-text-divider"></div>
                        <p>We're dedicated to making your event experience extraordinary. Whether you need assistance with:
                        </p>

                        <ul class="contact-info-list">
                            <li><i class="fa fa-ticket"></i> Event booking inquiries</li>
                            <li><i class="fa fa-users"></i> Group bookings and special arrangements</li>
                            <li><i class="fa fa-star"></i> VIP package information</li>
                            <li><i class="fa fa-question-circle"></i> General support and assistance</li>
                        </ul>

                        <div class="social-links">
                            <h4>Connect With Us</h4>
                            <a href="https://www.facebook.com/share/1EzYWPCckN/?mibextid=wwXIfr" class="social-icon" title="Facebook" target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            
                            <a href="https://www.instagram.com/greatticket2?igsh=MTl4OHowdnBxOGxmdQ==" class="social-icon" title="Instagram" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@greatticket2?_t=ZS-8ti50Gtl4Ec&_r=1" class="social-icon" title="Tiktok" target="_blank">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-form">
                        <form method="POST" action="{{ route('postSendEmail') }}" id="contactForm">@csrf
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter your full name" id="name" name="name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" placeholder="Enter your email address"
                                    class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact">Contact Number</label>
                                <input type="number" placeholder="Enter your contact number"
                                    class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact"
                                    value="{{ old('contact') }}" required>
                                @error('contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" placeholder="Enter the subject of your message"
                                    class="form-control @error('subject') is-invalid @enderror" id="subject"
                                    name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" placeholder="Enter your message here"
                                    id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fa fa-paper-plane mr-2"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

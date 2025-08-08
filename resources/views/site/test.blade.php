@extends('site.template')
@section('css')
<style>
/* Existing styles remain the same */

/* Enhanced Contact Text Styling */
.contact-text {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
    background: rgba(255,255,255,0.2);
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

.form-control.is-invalid + .invalid-feedback {
    display: block;
}
</style>
@stop

@section('content')
<!-- Hero Section remains the same -->

<!-- Enhanced Main Content -->
<div class="contact-content">
    <div class="container">
        <!-- Contact Information Cards remain the same -->
        
        <!-- Enhanced Contact Form Section -->
        <div class="row contact-form-section">
            <div class="col-md-6">
                <div class="contact-text">
                    <h2 class="section-title">Let's Connect</h2>
                    <div class="contact-text-divider"></div>
                    <p>We're dedicated to making your event experience extraordinary. Whether you need assistance with:</p>
                    
                    <ul class="contact-info-list">
                        <li><i class="fa fa-ticket"></i> Event booking inquiries</li>
                        <li><i class="fa fa-calendar"></i> Schedule changes or updates</li>
                        <li><i class="fa fa-users"></i> Group bookings and special arrangements</li>
                        <li><i class="fa fa-star"></i> VIP package information</li>
                        <li><i class="fa fa-question-circle"></i> General support and assistance</li>
                    </ul>
                    
                    <p>Our dedicated team is here to ensure your experience is seamless and memorable. Expect a response within 24 hours during business days.</p>
                    
                    <div class="contact-text-divider"></div>
                    
                    <div class="social-links">
                        <h4>Connect With Us</h4>
                        <a href="#" class="social-icon" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="social-icon" title="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" title="LinkedIn">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-form">
                    <form method="POST" action="{{ route('contact.submit') }}" id="contactForm">
                        @csrf
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required>{{ old('message') }}</textarea>
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
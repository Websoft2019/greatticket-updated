<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Great Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet'
        type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
    @notifyCss
    <!-- Styles -->
    <link href="{{ asset('site/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('site/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('site/css/revolution.css') }}" media="screen" />
    <link rel="stylesheet" href="{{ asset('site/css/audioplayer.css') }}" />
    <link rel="stylesheet" href="{{ asset('site/css/style.css') }}" type="text/css" />

    <link href="{{ asset('site/css/responsive.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <link rel="stylesheet" type="text/css" href="{{ asset('site/css/color/color.css') }}" title="color" />
    <style>
        .topmenu {}

        .topmenu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>
    <style>
        @charset "UTF-8";
        @import url(https://fonts.googleapis.com/css?family=Oswald|Roboto);


        

        

        .rabbit {
            width: 50px;
            height: 50px;
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 3;
            fill: #fff;
        }

        .modal-close {
            position: absolute;
            z-index: 999999;
            right: -10px;
            top: -10px;
            opacity: 1;
            background: #fff !important;
            border-radius: 30px;
            width: 30px;
            height: 30px;
        }

        .cartcount {
            width: 10px;
            height: 10px;
            background: #ff5592;
            border-radius: 10px;
            color: #000;
            padding: 5px;
        }

        .dropdown1 {
            background: #7b1fa2;
            color: #fff;
            cursor: pointer;
            height: 50px;
            line-height: 50px;
            position: relative;
            width: 226px;
            text-align: center;
            text-decoration: none;
            z-index: 1;
            transform: perspective(1000px);
            -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu1 {
            background-color: #fff;
            list-style-type: none;
            margin: 0;
            padding: 0;
            position: absolute;
            left: 0;
            opacity: 0;
            text-align: center;
            top: 0;
            visibility: hidden;
            z-index: -99999;
        }

        .dropdown-menu1 li:first-child {
            cursor: default;
        }

        .dropdown-menu1 a {
            color: #000;
            display: inline-block;
            width: 100%;
            text-decoration: none;
            -webkit-transition: all 1s;
            transition: all 1s;
        }

        .dropdown-menu1 a:hover {
            background: #ba68c8;
            color: #fff;
        }

        .dropdown1:hover .dropdown-menu1 {
            background: #7b1fa2;
            opacity: 1;
            visibility: visible;
            top: 100%;
            width: 100%;
            -webkit-transition: all .5s, background, 2s .5s linear;
            transition: all .5s, background 2s .5s linear;
        }
    </style>
    @yield('css')

</head>

<body>
    <div class="page-loader">
        <div class="item one"></div>
    </div><!-- Page Loader -->
    @include('notify::components.notify')
    <header style="background: rgba(0,0,0,0.7)">
        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <div class="logo">
                        <a href="{{ route('getHome') }}" title=""><img src="{{ asset('site/images/logo.png') }}"
                                alt="" width="150"></a>
                    </div>
                </div>
                <div class="col-md-11">
                    <div style="text-align: right; float:right">
                        <div class="col-md-6 dropdown1">
                            @if (!auth()->check())
                                <a href="{{route('login')}}" style="color: #fff">
                                    <i class="fa fa-user"></i> Login/Register
                                </a>
                            @else
                                @if (auth()->user()->role == 'a')
                                    <a name="" id="" class="btn btn-primary mb-1" href="{{ route('home') }}" role="button">Welcome {{ Auth::user()->name }}</a>
                                @elseif (auth()->user()->role == 'o')
                                    <a name="" id="" class="btn btn-primary mb-1" href="{{ route('organizer.dashboard') }}" role="button">Welcome {{ Auth::user()->name }}</a>
                                @else
                                    <a name="" id="" class="" href="{{ route('history') }}" style="color: #fff">Welcome {{ Auth::user()->name }}</a>
                                @endif
                                |
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #fff">
                                    Logout
                                </a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                                    @csrf
                                </form>

                            @endif
                        </div>
                        @php
                            if (auth()->check()) {
                                $carts = App\Models\Cart::where('user_id', auth()->id())->get();
                                $cartcount = $carts->count();
                                $cartCost = $carts->sum('cost');
                            } else {
                                $cartcount = 0;
                            }
                        @endphp
                        <div class="dropdown1 col-md-6"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ticket
                            <span class="cartcount">{{ $cartcount }}</span>
                            @if ($cartcount >= 1)
                                <div class="dropdown-menu1" style="width: 100%; float:left">
                                    <ul class="" style="text-align:left; padding:20px">
                                        <li style="color:yellow; font-weight: bold">Total Amount<span
                                                style="display:block; line-height:3px;">RM {{ $cartCost }}</span>
                                        </li>

                                    </ul>
                                    <div style="padding:0 10px;">
                                        <a href="{{ route('getCart') }}" class="btn btn-danger">Cart</a>
                                        <a href="{{ route('getCheckout') }}" class="btn btn-primary">Checkout</a>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                    <nav>
                        <ul>
                            <li><a href="{{ route('getHome') }}" title=""><span><i class="fa fa-home"></i></span>Home</a></li>
                            <li><a href="{{ route('getEvents') }}" title=""><span><i class="fa fa-calendar-week"></i></span>Events</a>
                            </li>
                            <li><a href="{{ route('getAboutUs') }}" title=""><span><i class="fa fa-ticket-alt"></i></span>About Us</a></li>
                           
                            <li><a href="{{ route('getContactUs') }}" title=""><span><i class="fa fa-location-arrow"></i></span>Contact
                                    Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header><!-- Header -->

    <div class="responsive-header">
        <div class="responsive-logo">
            <a href="{{ route('getHome') }}" title=""><img src="{{ asset('site/images/logo.png') }}"
                    alt="Logo" width="150" /></a>
        </div><!-- Responsive Logo -->
        <span><i class="fa fa-align-justify"></i></span>

        <ul>
            <li><a href="{{ route('getHome') }}" title=""><span><i class="fa fa-home"></i></span>Home</a>
            </li>
            <li><a href="{{ route('getEvents') }}" title=""><span><i class="fa fa-calendar-week"></i></span>Events</a></li>
            <li><a href="{{ route('getAboutUs') }}" title=""><span><i class="fa fa-ticket-alt"></i></span>About
                    Us</a></li>
            <li><a href="{{route('getContactUs')}}" title=""><span><<i class="fa fa-location-arrow"></i></span>Contact Us</a></li>
            <li>
                @if (!auth()->check())
                    <a href="{{route('login')}}" style="color: #fff">
                        <i class="fa fa-user"></i> Login/Register
                    </a>
                @else
                    @if (auth()->user()->role == 'a')
                        <a href="{{ route('home') }}" style="color: #fff">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                        </a>
                    @elseif (auth()->user()->role == 'o')
                        <a href="{{ route('organizer.dashboard') }}" style="color: #fff">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('history') }}" style="color: #fff">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }}
                        </a>
                    @endif

                @endif
            </li>
            @if (auth()->check())
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #fff;">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                    @csrf
                </form>
            </li>
            @endif
        </ul>


    </div><!--Responsive header -->
    
            <!-- Modal -->
        
    
    @yield('content')



    <footer>
        <div class="block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 column">

                        <div class="footer-widgets">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="widget">

                                        <ul class="contact-info">
                                            <li><span><i class="fa fa-phone"></i></span>011-55512444, 011-16386959</li>
                                            <li><span><i class="fa fa-envelope-o"></i></span>enquiry@greatticket.my
                                            </li>
                                            <li><span><i class="fa fa-map-marker"></i></span>
                                                <address>271, Jalan Permai 2, Taman Desa Permai, 09600 Lunas, Kedah
                                                </address>
                                            </li>
                                        </ul>
                                    </div><!-- About Widget -->
                                </div>
                                <div class="col-md-4">
                                    <div class="widget">
                                        <ul class="contact-info">
                                            <li><a href="{{ route('getAboutUs') }}">About Us</a></li>
                                            <li><a href="">News and Events</a></li>
                                            <li><a href="">Work with Us</a></li>
                                            <li><a href="">Our Fee</a></li>
                                            <li><a href="{{route('getContactUs')}}">Contact Us</a></li>
                                        </ul>

                                    </div><!-- Contact Form Widget -->

                                </div>

                                <div class="col-md-4">
                                    <div class="widget" style="margin-bottom: 3px;">
                                        <ul class="contact-info">
                                            <li><a href="{{ route('privacyPolicy') }}">Privacy Policy</a></li>
                                            <li><a href="{{ route('termsCondition') }}">Terms and Condition</a></li>
                                            
                                        </ul>
                                    </div>
                                    <img src="{{asset('site/images/qrforgreatticket.jpg')}}" width="200">
                                </div>
                                <div class="col-md-4">
                                    <div class="widget">

                                    </div><!-- Map Widget -->
                                </div>
                            </div>
                        </div><!-- Footer Widgets -->
                    </div>
                </div>
            </div>
        </div>



    </footer><!-- Footer -->
    <div class="bottom-footer">
        <div class="container">
            <p>All rights reserved {{ date('Y') }}-<a title="" href="#">Great Ticket</a> By <a
                    title="" href="http://websofttechnology.com.my">Websoft Technology</a></p>
        </div>
    </div><!-- Bottom Footer -->

    @notifyJs
    <script type="text/javascript" src="{{ asset('site/js/modernizr.custom.97074.js') }}"></script>

    <script type="text/javascript" src="{{ asset('site/js/jquery2.1.1.js') }}"></script>
    <!-- SLIDER REVOLUTION -->
    <script type="text/javascript" src="{{ asset('site/js/revolution/jquery.themepunch.plugins.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/revolution/jquery.themepunch.revolution.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('site/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/prettyPhoto.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/jquery.downCount.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/audioplayer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/jquery.counterup.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/jquery.downCount.js') }}"></script>
    <script type="text/javascript" src="{{ asset('site/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".info-item .btn1").click(function() {
                $(".container1").toggleClass("log-in");
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('.countdown').downCount({
                date: '09/16/2024 12:00:00',
                offset: +10
            });


            /* =============== Service Carousel ===================== */
            $('.service-carousel').owlCarousel({
                loop: true,
                smartSpeed: 1000,
                autoplay: true,
                autoplayTimeout: 3000,
                dots: true,
                mouseDrag: false,
                items: 1,
                margin: 0,
                singleItem: true,
                autoplayHoverPause: true,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn'
            });

            $(function() {
                $('audio').audioPlayer();
            });
        });

        $(window).load(function() {
            /* =============== Fun Facts Counter ===================== */
            $(".counter").counterUp({
                time: 1000
            });

            /* =============== Revolution Slider ===================== */
            var revapi;
            revapi = jQuery('.tp-banner').revolution({
                delay: 9000,
                startwidth: 1170,
                startheight: 768,
                hideThumbs: 10,
                fullWidth: "on",
                forceFullWidth: "off"
            });
        });
    </script>
    @stack('js')

</body>

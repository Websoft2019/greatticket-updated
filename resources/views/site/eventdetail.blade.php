@extends('site.template')
@section('css')
    <style>
        .custom-container {
            max-width: 800px;
            margin: 50px auto;
        }

        .carousel .item {
            height: 500px;
            width: 100%;
        }

        .carousel .item img {
            position: absolute;
            top: 0;
            left: 0;
            min-height: 500px;
            width: 100%;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background-color: #fff;
            color: #000;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .tab-buttons button.active {
            background-color: skyblue;
            color: #000;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .custom-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile-card {
            max-width: 400px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card .panel-heading {
            padding: 0;
            overflow: hidden;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .profile-card .profile-img {
            width: 100%;
            height: auto;
        }

        .profile-card .panel-body {
            padding: 20px;
        }

        .profile-card h4 {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .profile-card p {
            margin: 0;
            color: #555;
        }

        .profile-card .address,
        .profile-card .about {
            margin-top: 15px;
        }

        .vcenter {
            align-items: center;
        }

        .package-description ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .package-description li {
            margin-bottom: 5px;
            line-height: 20px;
        }

        @media (max-width: 767.98px) {
            .vcenter {
                display: block;
                margin-top: 15px;
            }

            .package-description {
                color: #000;
            }
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .blink {
            background: red;
            padding: 5px;
            animation: blink 1s infinite;
        }

        /* Seat Map Styling */
        .seat-map-container {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin: 20px 0;
        }

        .screen {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .screen::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            border-radius: 15px;
            z-index: -1;
        }

        /* ✅ New scroll setup */
        .scroll-container {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 10px;
        }

        .seat-rows-wrapper {
            display: inline-block;
            white-space: nowrap;
            padding: 10px;
            min-width: max-content;
        }

        .seat-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .row-label {
            width: 40px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            text-align: center;
            height: 40px;
            line-height: 40px;
            border-radius: 8px;
            margin-right: 10px;
            font-weight: bold;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .seat {
            width: 40px;
            height: 40px;
            margin: 3px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            transition: all 0.3s ease;
            flex-shrink: 0;
            display: inline-block;
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .seat:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .seat.available {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            animation: pulse 2s infinite;
        }

        .seat.booked {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .seat.reserved {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .seat.selected {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            border: 2px solid #ffffff;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(155, 89, 182, 0.6);
        }

        .seat.selecting {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: 2px solid #ffffff;
            animation: selecting 0.5s ease;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            }

            50% {
                box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
            }

            100% {
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            }
        }

        @keyframes selecting {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .seat-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-size: 14px;
        }

        .legend-seat {
            width: 20px;
            height: 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .legend-available {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .legend-booked {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .legend-reserved {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .legend-selected {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }

        .seat-selection-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }

        .selected-seats-display {
            color: #fff;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .seat-selection-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-seat-action {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .btn-clear {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-clear:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .seat-counter {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 0 10px;
        }

        .package-seat-selection {
            display: none;
            background: rgba(0, 0, 0, 0.9);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            overflow-y: auto;
        }

        .seat-selection-modal {
            max-width: 900px;
            margin: 50px auto;
            position: relative;
            padding: 20px;
        }

        .close-seat-selection {
            position: absolute;
            top: 10px;
            right: 20px;
            background: #e74c3c;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 10001;
        }

        .seat-selection-header {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        .seat-selection-header h3 {
            margin: 0;
            font-size: 24px;
        }

        .seat-selection-header p {
            margin: 10px 0;
            opacity: 0.8;
        }
    </style>
@endsection


@section('content')
    <section>
        <div class="block gray half-parallax blackish remove-bottom" style="background: #000">
            <div style="background:url({{ asset('site/images/parallax8.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="page-title" style="background: #000">
                            <h1><span style="color: skyblue !important">{{ $event->title }}</span></h1>
                            <div class="bar">
                                <span style="color: #000 !important"><i class="fa fa-user"></i> <a href="#"
                                        title="">{{ $event->date->format('d M Y') }}</a></span>
                                <span style="color: #000 !important"><i class="fa fa-user"></i> <a href="#"
                                        title="">{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</a></span>
                                <span style="color: #000 !important"><i class="fa fa-user"></i> <a href="#"
                                        title="">{{ $event->vennue }}</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="block gray" style="background: #000">
            <div class="container">
                <div class="row">
                    @include('notify::components.notify')
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="col-md-12 column">
                        <div class="custom-container">
                            <!-- Card Design for Tab Content -->
                            <div class="custom-card">
                                <!-- Tab buttons -->
                                <div class="tab-buttons">
                                    <button class="tab-btn active" data-target="account"><i class="fas fa-ticket-alt"></i>
                                        Ticket</button>
                                    <button class="tab-btn" data-target="seat"><i class="fas fa-chair"></i> Seat
                                        View</button>
                                    <button class="tab-btn" data-target="notifications"><i class="fas fa-info-circle"></i>
                                        Details</button>
                                    <button class="tab-btn" data-target="connections"><i class="fas fa-user-tie"></i>
                                        Organizer</button>
                                </div>

                                <!-- Tab content sections within the card -->
                                <div id="account" class="tab-content active">
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                        <!-- Indicators -->
                                        <ol class="carousel-indicators">
                                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                            @for ($i = 0; $i < count($event->images); $i++)
                                                <li data-target="#myCarousel" data-slide-to="{{ $i + 1 }}"></li>
                                            @endfor
                                        </ol>

                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner">
                                            <div class="item active">
                                                <a href="{{ $event->primary_photo ? asset('storage/' . $event->primary_photo) : asset('site/images/parallax1.jpg') }}"
                                                    target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ $event->primary_photo ? asset('storage/' . $event->primary_photo) : asset('site/images/parallax1.jpg') }}"
                                                        alt="Slide 1">
                                                </a>
                                            </div>

                                            @foreach ($event->images as $image)
                                                <div class="item">
                                                    <a href="{{ $image->photo ? asset('storage/' . $image->photo) : asset('site/images/parallax1.jpg') }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <img src="{{ $image->photo ? asset('storage/' . $image->photo) : asset('site/images/parallax1.jpg') }}"
                                                            alt="Slide ">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Controls -->
                                        <a class="left carousel-control" href="#myCarousel" role="button"
                                            data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#myCarousel" role="button"
                                            data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>

                                <div id="notifications" class="tab-content">
                                    <h5 style="color:#fff"><strong>Event Highlight</strong></h5>
                                    <p style="color:#fff !important">{!! $event->highlight !!}</p>
                                    <hr>
                                    <h5 style="color:#fff"><strong>Event Description</strong></h5>
                                    <div style="color:#fff">{!! strip_tags($event->description) !!}</div>
                                </div>

                                <div id="seat" class="tab-content">
                                    <h3 class="text-center mb-2">Seat View</h3>
                                    @if ($event->seat_view)
                                        <p><a href="{{ asset('storage/' . $event->seat_view) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $event->seat_view) }}" alt="Seat View"
                                                    class="img-fluid rounded" />
                                            </a>
                                        </p>
                                    @else
                                        <div class="text-danger">Seat View Image not available</div>
                                    @endif
                                </div>

                                <div id="connections" class="tab-content">
                                    <h5 style="color:#fff !important">Organizer</h5>
                                    <div class="custom-container">
                                        <!-- Profile Card -->
                                        <div class="panel panel-default profile-card">
                                            <!-- Image section -->
                                            <div class="panel-heading">
                                                <img src="{{ $event->user->organizer ? asset('storage/' . $event->user->organizer->photo) : asset('site/images/profile.avif') }}"
                                                    alt="User Photo" class="profile-img">
                                            </div>
                                            <!-- User details -->
                                            <div class="panel-body text-center">
                                                <h4 class="name">{{ $event->user->name ?? '' }}</h4>
                                                <div class="address">
                                                    <h5>Address</h5>
                                                    <p>{{ $event->user->organizer->address ?? '' }}</p>
                                                </div>
                                                <div class="">
                                                    <h5>About</h5>
                                                    <p>{{ $event->user->organizer->about ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="single-post">
                            <section>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2 style="color:skyblue !important">Buy <span
                                                    style="color:skyblue !important">Tickets And Enjoy</span></h2>
                                            <p style="color: #fff !important">Choose Your Package</p>
                                            @if ($packages->count())
                                                <div id="accordion">
                                                    @foreach ($packages as $package)
                                                        <div class="row" style="display: flex; align-items: center;">
                                                            <div class="col-md-12"
                                                                style="background: skyblue; text-align: left; margin: 15px; padding: 15px; color: #fff; display: flex; align-items: center; border: 2px solid #fff">
                                                                <div class="col-md-8">
                                                                    <h4><strong
                                                                            style="color: #000; font-family:'Times New Roman', Times, serif">{{ $package->title }}</strong>
                                                                    </h4>
                                                                    @if ($package->discount_price > 0)
                                                                        <del> RM
                                                                            {{ number_format($package->discount_price, 2) }}</del>
                                                                        <span
                                                                            style="color: #000 font-family:'Times New Roman', Times, serif">RM
                                                                            {{ number_format($package->actual_cost, 2) }}</span>
                                                                    @else
                                                                        <strong
                                                                            style="color: #000; font-family:'Times New Roman', Times, serif">RM
                                                                            <span
                                                                                style="font-size: 18px;">{{ number_format($package->actual_cost, 2) }}</span></strong>
                                                                    @endif
                                                                    <span style="color: #000">
                                                                        <div class="package-description">
                                                                            {!! $package->description !!}
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($package->seats->count())
                                                                    <div class="col-md-4 vcenter"
                                                                        style="text-align: center">
                                                                        <button class="btn btn-primary select-seats-btn"
                                                                            data-package-id="{{ $package->id }}"
                                                                            data-package-title="{{ $package->title }}"
                                                                            data-package-price="{{ $package->actual_cost }}"
                                                                            data-package-pax="{{ $package->pax ?? 1 }}"
                                                                            style="background: #000; color: #fff; border: 2px solid #fff;">
                                                                            Select Seats
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <div class="col-md-4 vcenter"
                                                                        style="text-align: center">
                                                                        @if ($package->capacity > $package->consumed_seat)
                                                                            <form
                                                                                action="{{ route('postAddtoCart', $package->id) }}"
                                                                                method="POST"
                                                                                class="add-to-cart-form form-horizontal"
                                                                                data-package-id="{{ $package->id }}">
                                                                                @csrf
                                                                                <div class="form-group">
                                                                                    <label for=""
                                                                                        class="col-md-3 control-label"
                                                                                        style="color: #000;">Qty</label>
                                                                                    <div class="col-md-6">
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            value="1" min="1"
                                                                                            max="{{ $package->capacity - $package->consumed_seat }}"
                                                                                            name="quantity"
                                                                                            style="background: #000; color: #fff;">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="col-md-offset-3 col-md-6">
                                                                                        <input type="submit"
                                                                                            class="btn btn-danger purchase-btn"
                                                                                            value="Purchase Now"
                                                                                            style="background-color: #fff; color:#000; border-color: #000;">
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        @else
                                                                            <div class="d-grid gap-2">
                                                                                <button type="button" name=""
                                                                                    id=""
                                                                                    class="btn btn-danger">Sold
                                                                                    Out</button>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p><br /><strong style="color: red">No any Package has been published yet
                                                        ...</strong> <br /></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Seat Selection Modal -->
    <div class="package-seat-selection" id="seatSelectionModal">
        <div class="seat-selection-modal">
            <button class="close-seat-selection" onclick="closeSeatSelection()">&times;</button>
            <div class="seat-selection-header">
                <h3 id="packageTitle">Select Your Seats</h3>
                <p id="packageInfo">Choose your preferred seats</p>
            </div>

            <div class="seat-map-container">
                <div class="screen">SCREEN / STAGE</div>

                <div class="seat-selection-info">
                    <div class="selected-seats-display">
                        <span>Selected Seats: </span>
                        <span class="seat-counter" id="selectedSeatsCount">0</span>
                        <span>/ </span>
                        <span id="requiredSeats">1</span>
                    </div>
                    <div class="selected-seats-display">
                        <span>Total Cost: </span>
                        RM <span id="requiredCost">0</span>
                    </div>
                    <div id="selectedSeatsList"></div>
                </div>

                <div class="scroll-container">
                    <div id="seatMapContainer" class="seat-rows-wrapper">
                        <!-- Seats will be loaded here -->
                    </div>
                </div>

                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="legend-seat legend-available"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat legend-selected"></div>
                        <span>Selected</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat legend-booked"></div>
                        <span>Booked</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-seat legend-reserved"></div>
                        <span>Reserved</span>
                    </div>
                </div>

                <div class="seat-selection-controls">
                    <button class="btn-seat-action btn-clear" onclick="clearSelectedSeats()">Clear Selection</button>
                    <button class="btn-seat-action btn-confirm" onclick="confirmSeatSelection()">Confirm
                        Selection</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" style="z-index: 999999">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Login as ...</h4>
                </div>
                <div class="modal-body">
                    <p>You're not logged in. You can proceed with your purchase as a guest, but we recommend logging in or
                        registering to easily view your ticket(s) later through the Ticket History page. <br /></p>
                    <br />
                    <div class="col-md-4" style="text-align: center"></div>
                    <div class="col-md-4" style="text-align: center"></div>
                    <div class="col-md-4" style="text-align:center"></div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        onclick="window.location.href='{{ route('login', ['returnurl' => url()->current()]) }}'"
                        class="btn btn-success" style="background: #7b1fa2;">
                        Login / Register
                    </button>
                    <button type="button" class="btn btn-warning" onclick="continueAsGuest()"
                        style="background: #7b1fa2">Continue as Guest</button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('js')
    @if ($loginstatus == 'false')
        <script>
            $(document).ready(function() {
                $('#loginModal').modal('show');
            });
        </script>
    @endif
    <script>
        const loginStatus = {{ $loginstatus }};
        // Global variables
        let selectedSeats = [];
        let currentPackage = null;
        let requiredSeats = 1;
        let packageCost = 0;
        let packageSeats = [];

        // Function to handle the login check and form submission
        function handleFormSubmission(event) {
            event.preventDefault(); // Prevent default form submission

            var form = event.target.closest('form'); // Get the form element
            var formAction = form.getAttribute('action'); // Get form action URL

            if ({{ $loginstatus ? 'true' : 'false' }}) {
                form.submit(); // Submit the form if user is logged in
            } else {
                $('#loginModal').modal('show');
                window.currentForm = form; // Store current form in a global variable
            }
        }

        // Function to handle seat selection button click
        function handleSeatSelectionClick(event) {
            if ({{ $loginstatus ? 'false' : 'true' }}) {
                // Not logged in, show login modal
                $('#loginModal').modal('show');
                return;
            }

            // User is logged in, proceed with seat selection
            const button = event.target;
            const packageId = button.dataset.packageId;
            const packageTitle = button.dataset.packageTitle;
            const packagePrice = button.dataset.packagePrice;
            packageCost = packagePrice;
            const packagePax = parseInt(button.dataset.packagePax) || 1;

            openSeatSelection(packageId, packageTitle, packagePrice, packagePax);
        }

        function continueAsGuest() {
            $.ajax({
                url: '{{ route('guest.user') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    returnurl: window.location.href // capture current page URL
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Something went wrong.');
                }
            });
        }

        // Function to open seat selection modal
        function openSeatSelection(packageId, packageTitle, packagePrice, packagePax) {
            currentPackage = {
                id: packageId,
                title: packageTitle,
                price: packagePrice,
                pax: packagePax
            };

            requiredSeats = packagePax;
            selectedSeats = [];

            // Update modal content
            document.getElementById('packageTitle').textContent = packageTitle;
            document.getElementById('packageInfo').textContent =
                `Price: RM ${packagePrice} | Required Seats: ${packagePax}`;
            document.getElementById('requiredSeats').textContent = packagePax;
            document.getElementById('requiredCost').textContent = 0;
            document.getElementById('selectedSeatsCount').textContent = '0';
            document.getElementById('selectedSeatsList').innerHTML = '';

            // Load seats for this package
            loadPackageSeats(packageId);

            // Show modal
            document.getElementById('seatSelectionModal').style.display = 'block';
        }

        // Function to load seats for a package
        // function loadPackageSeats(packageId) {
        //     // For now, we'll create a demo layout based on the existing structure
        //     // In a real application, you would fetch this from your backend
        //     const demoSeats = generateDemoSeats(packageId);
        //     renderSeatMap(demoSeats);
        // }
        function loadPackageSeats(packageId) {
            fetch(`/api/packages/${packageId}/seats`)
                .then(response => response.json())
                .then(seats => {
                    renderSeatMap(seats);
                })
                .catch(error => {
                    console.error('Error loading seats:', error);
                    showMessage('Failed to load seat map.', 'error');
                });
        }


        // Function to generate demo seats (replace with actual backend call)
        function generateDemoSeats(packageId) {
            const rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            const seatsPerRow = 12;
            const seats = [];

            for (let i = 0; i < rows.length; i++) {
                for (let j = 1; j <= seatsPerRow; j++) {
                    // Random status for demo (in real app, this comes from backend)
                    const randomStatus = Math.random();
                    let status = 'available';
                    if (randomStatus < 0.1) status = 'booked';
                    else if (randomStatus < 0.15) status = 'reserved';

                    seats.push({
                        id: `${packageId}_${rows[i]}${j}`,
                        row: rows[i],
                        number: j,
                        status: status,
                        price: currentPackage.price
                    });
                }
            }

            return seats;
        }

        // Function to render seat map
        function renderSeatMap(seats) {
            const section = document.getElementById('seatMapContainer');
            const container = document.createElement('div');
            container.className = 'seat-rows-wrapper';
            section.innerHTML = '';
            container.innerHTML = '';

            // Group seats by row
            const seatsByRow = {};
            seats.forEach(seat => {
                if (!seatsByRow[seat.row]) {
                    seatsByRow[seat.row] = [];
                }
                seatsByRow[seat.row].push(seat);
            });

            // Sort rows alphabetically (optional, but good UX)
            const sortedRowKeys = Object.keys(seatsByRow).sort();

            // Render each row
            sortedRowKeys.forEach(rowLetter => {
                const rowSeats = seatsByRow[rowLetter];

                // ✅ Sort the seats in this row by seat.number (ascending)
                rowSeats.sort((a, b) => a.number - b.number);

                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';

                const rowLabel = document.createElement('div');
                rowLabel.className = 'row-label';
                rowLabel.textContent = rowLetter;
                rowDiv.appendChild(rowLabel);

                rowSeats.forEach(seat => {
                    const seatDiv = document.createElement('div');
                    seatDiv.className = `seat ${seat.status}`;
                    seatDiv.textContent = `${seat.row}${seat.number}`;
                    seatDiv.dataset.seatId = seat.id;
                    seatDiv.dataset.seatRow = seat.row;
                    seatDiv.dataset.seatNumber = seat.number;
                    seatDiv.title = `Seat ${seat.row}${seat.number} - ${seat.status}`;

                    if (seat.status === 'available') {
                        seatDiv.addEventListener('click', handleSeatClick);
                    }

                    rowDiv.appendChild(seatDiv);
                });

                container.appendChild(rowDiv);
            });

            section.appendChild(container);
        }


        // Function to handle seat click
        function handleSeatClick(event) {
            const seatElement = event.target;
            const seatId = seatElement.dataset.seatId;
            const seatRow = seatElement.dataset.seatRow;
            const seatNumber = seatElement.dataset.seatNumber;

            // Check if seat is already selected
            const isSelected = selectedSeats.some(seat => seat.id === seatId);

            if (isSelected) {
                // Remove from selection
                selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
                seatElement.classList.remove('selected');
                seatElement.classList.add('available');
            } else {
                // Check if we can select more seats
                // if (selectedSeats.length >= requiredSeats) {
                if (selectedSeats.length % requiredSeats !== 0) {
                    showMessage('You can only select in range' + requiredSeats + ' seat(s) for this package.', 'warning');
                    return;
                }

                // Add to selection
                selectedSeats.push({
                    id: seatId,
                    row: seatRow,
                    number: seatNumber,
                    seatName: seatRow + seatNumber
                });
                seatElement.classList.remove('available');
                seatElement.classList.add('selected');
            }

            // Update display
            updateSelectedSeatsDisplay();
        }

        // Function to update selected seats display
        function updateSelectedSeatsDisplay() {
            const countElement = document.getElementById('selectedSeatsCount');
            const costElement = document.getElementById('requiredCost');
            const listElement = document.getElementById('selectedSeatsList');

            countElement.textContent = selectedSeats.length;
            costElement.textContent = selectedSeats.length * packageCost;

            if (selectedSeats.length > 0) {
                const seatNames = selectedSeats.map(seat => seat.seatName).join(', ');
                listElement.innerHTML = `<div style="color: #fff; margin-top: 10px;">Selected: ${seatNames}</div>`;
            } else {
                listElement.innerHTML = '';
            }
        }

        // Function to clear selected seats
        function clearSelectedSeats() {
            selectedSeats.forEach(seat => {
                const seatElement = document.querySelector(`[data-seat-id="${seat.id}"]`);
                if (seatElement) {
                    seatElement.classList.remove('selected');
                    seatElement.classList.add('available');
                }
            });

            selectedSeats = [];
            updateSelectedSeatsDisplay();
        }

        // Function to confirm seat selection
        function confirmSeatSelection() {

            if (selectedSeats.length % requiredSeats !== 0) {
                showMessage('You can only select in range' + requiredSeats + ' seat(s) for this package.', 'warning');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('postAddtoCart', ':packageId') }}`.replace(':packageId', currentPackage.id);

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Add package_id
            const packageInput = document.createElement('input');
            packageInput.type = 'hidden';
            packageInput.name = 'package_id';
            packageInput.value = currentPackage.id;
            form.appendChild(packageInput);

            // Add seats (stringified)
            const seatsInput = document.createElement('input');
            seatsInput.type = 'hidden';
            seatsInput.name = 'seats';
            seatsInput.value = JSON.stringify(selectedSeats);
            form.appendChild(seatsInput);

            // Add quantity
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = selectedSeats.length;
            form.appendChild(quantityInput);

            // Append and submit
            document.body.appendChild(form);
            form.submit();

        }

        // Function to close seat selection modal
        function closeSeatSelection() {
            document.getElementById('seatSelectionModal').style.display = 'none';
            selectedSeats = [];
            currentPackage = null;
        }

        // Function to show messages
        function showMessage(message, type) {
            // Create a simple toast message
            const messageDiv = document.createElement('div');
            messageDiv.style.position = 'fixed';
            messageDiv.style.top = '20px';
            messageDiv.style.right = '20px';
            messageDiv.style.padding = '15px 20px';
            messageDiv.style.borderRadius = '5px';
            messageDiv.style.color = '#fff';
            messageDiv.style.fontWeight = 'bold';
            messageDiv.style.zIndex = '99999';
            messageDiv.style.maxWidth = '300px';
            messageDiv.textContent = message;

            if (type === 'success') {
                messageDiv.style.backgroundColor = '#27ae60';
            } else if (type === 'error') {
                messageDiv.style.backgroundColor = '#e74c3c';
            } else {
                messageDiv.style.backgroundColor = '#f39c12';
            }

            document.body.appendChild(messageDiv);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 3000);
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to existing purchase forms
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', handleFormSubmission);
            });

            // Add event listeners to seat selection buttons
            document.querySelectorAll('.select-seats-btn').forEach(button => {

                button.addEventListener('click', function(e) {
                    if (!loginStatus) {
                        $('#loginModal').modal('show');
                    } else {
                        handleSeatSelectionClick(e);
                    }
                });
            });

            // Tab switching functionality
            document.querySelectorAll('.tab-btn').forEach(button => {
                button.addEventListener('click', () => {
                    // Remove 'active' class from all buttons
                    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove(
                        'active'));
                    // Add 'active' class to the clicked button
                    button.classList.add('active');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove(
                        'active'));
                    // Show the content for the selected tab
                    const target = button.getAttribute('data-target');
                    document.getElementById(target).classList.add('active');
                });
            });

            // Close modal when clicking outside
            document.getElementById('seatSelectionModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeSeatSelection();
                }
            });
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSeatSelection();
            }
        });
    </script>
@endpush

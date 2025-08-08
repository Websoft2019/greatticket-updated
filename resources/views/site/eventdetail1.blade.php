@extends('site.template')
@section('css')
    <style>
        /* Custom styles */
        .custom-container {
            max-width: 800px;
            margin: 50px auto;
        }

        .carousel .item {
            height: 500px;
            width: 100%;
            /* Adjust to your preferred height */
        }

        .carousel .item img {
            position: absolute;
            top: 0;
            left: 0;
            min-height: 500px;
            width: 100%;
        }

        /* Styling for the tab buttons */
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-buttons button {
            padding: 10px 20px;
            border: none;
            background-color: #fff;;
            color: #000;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .tab-buttons button.active {
            /* background-color: #0056b3; */
            background-color: skyblue;
            color: #000
        }

        /* Hide all tab content by default */
        .tab-content {
            display: none;
        }

        /* Show active tab content */
        .tab-content.active {
            display: block;
        }

        /* Styling for the card container */
        .custom-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Custom styles for the profile card */
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

        
        /* Default behavior for larger screens */
.vcenter {
    /* display: flex; */
    align-items: center;
}
.package-description ul {
        list-style-type: disc;
        padding-left: 20px; /* ensures indentation for bullets */
    }

    .package-description li {
        margin-bottom: 5px;
        line-height: 20px;
    }

/* Override for smaller screens (e.g., mobile devices) */
@media (max-width: 767.98px) {
    .vcenter {
        display: block;
        margin-top: 15px; /* Add some spacing when stacked */
    }
    .package-description{
        color: #000;
    }
}
@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

.blink {
  background: red;
  padding: 5px;
  animation: blink 1s infinite;
}
.seat {
        width: 30px;
        height: 30px;
        margin: 5px;
        background-color: #28a745;
        color: white;
        text-align: center;
        line-height: 30px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: bold;
        transition: 0.2s;
        flex-shrink: 0;
        display:inline-block;
    }

    .seat.booked {
        background-color: #dc3545;
        cursor: not-allowed;
    }

    .seat.reserved {
        background-color: #ffc107;
        color: black;
        cursor: not-allowed;
    }

    .row-label {
        width: 30px;
        font-weight: bold;
        text-align: right;
        margin-right: 5px;
        flex-shrink: 0;
    }

    .scroll-container {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px;
    }

    .scroll-container::-webkit-scrollbar {
        height: 8px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
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
                                                <h2 style="color:skyblue !important">Buy <span style="color:skyblue !important">Tickets And Enjoy</span></h2>
                                                <p style="color: #fff !important">Choose Your Package</p>
                                                @if($packages->count())
                                                    <div id="accordion">
                                                        @foreach ($packages as $package)
                                                            <div class="row" style="display: flex; align-items: center;">
                                                                <div class="col-md-12" style="background: skyblue; text-align: left; margin: 15px; padding: 15px; color: #fff; display: flex; align-items: center; border: 2px solid #fff">
                                                                    <div class="col-md-8">
                                                                        <h4><strong style="color: #000; font-family:'Times New Roman', Times, serif">{{ $package->title }}</strong></h4>
                                                                        @if ($package->discount_price > 0)
                                                                            <del> RM {{ number_format($package->discount_price, 2) }}</del>
                                                                            <span style="color: #000 font-family:'Times New Roman', Times, serif">RM {{ number_format($package->actual_cost, 2) }}</span>
                                                                        @else
                                                                            <strong style="color: #000; font-family:'Times New Roman', Times, serif">RM <span style="font-size: 18px;">{{ number_format($package->actual_cost, 2) }}</span></strong>
                                                                        @endif
                                                                        <span style="color: #000">
                                                                            <!--Available Seat: {{ $package->capacity - $package->consumed_seat }}-->
                                                                            <div class="package-description">{!! $package->description !!}</div>
                                                                        </span>
                                                                    </div>
                                                                    @if($package->seats->count())
                                                                        <div class="col-md-4 vcenter" style="text-align: center">
                                                                            <button data-toggle="collapse" data-parent="#accordion" href="#seatView-{{$package->id}}" aria-expanded="true" aria-controls="seatView-{{$package->id}}">
                                                                              View Seat
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-4 vcenter" style="text-align: center">
                                                                            @if ($package->capacity > $package->consumed_seat)
                                                                            <form action="{{ route('postAddtoCart', $package->id) }}" method="POST" 
                                                                                class="add-to-cart-form form-horizontal" data-package-id="{{ $package->id }}">
                                                                                @csrf
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-3 control-label" style="color: #000;">Qty</label>
                                                                                    <div class="col-md-6">
                                                                                        <input type="number" class="form-control" value="1" min="1" 
                                                                                                max="{{ $package->capacity - $package->consumed_seat }}" 
                                                                                                name="quantity" style="background: #000; color: #fff;">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="col-md-offset-3 col-md-6">
                                                                                        <input type="submit" class="btn btn-danger purchase-btn" value="Purchase Now" 
                                                                                                style="background-color: #fff; color:#000; border-color: #000;">
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                           
                                                                            @else
                                                                                <div class="d-grid gap-2">
                                                                                    <button type="button" name="" id="" class="btn btn-danger">Sold Out</button>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="collapse panel-collapse" 
             id="seatView-{{$package->id}}" 
             data-bs-parent="#accordion" 
             role="tabpanel" 
             aria-labelledby="seatView-{{$package->id}}">
                                                                        <div class="panel-body">
                                                                            <div class="p-3 bg-light rounded">
                                                                                <div class="screen text-center mb-3" style="background: #999; color: white; padding: 5px; border-radius: 5px;">SCREEN</div>
                                                                                <div class="scroll-container">
                                                                                    <div class="d-flex flex-column">
                                                                                        @php
                                                                                            $groupedSeats = $package->seats->groupBy('row_label');
                                                                                        @endphp
                                                                        
                                                                                        @foreach ($groupedSeats as $rowLabel => $seats)
                                                                                            <div class="d-flex align-items-center mb-2">
                                                                                                @foreach ($seats->sortBy('position_x') as $seat)
                                                                                                    <div class="seat {{ $seat->status }}" title="Seat {{ $rowLabel }}{{ $seat->seat_number }}">
                                                                                                         {{ $rowLabel.''.$seat->seat_number }}
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                        <p><br /><strong style="color: red">No any Package has been published yet ...</strong> <br /></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                
                            </section><!--Offers Section-->
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
   
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" style="z-index: 999999">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Login as ...</h4>
            </div>
            <div class="modal-body">
                <p>You’re not logged in. You can proceed with your purchase as a guest, but we recommend logging in or registering to easily view your ticket(s) later through the Ticket History page. <br /></p>
                <br /><div class="col-md-4" style="text-align: center">
                    
                </div>
                <div class="col-md-4" style="text-align: center">
                   
                </div>
                <div class="col-md-4" style="text-align:center">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="window.location.href='{{ route('login', ['returnurl' => url()->current()]) }}'" class="btn btn-success" style="background: #7b1fa2;">
                    Login / Register
                </button>
                <button type="button" class="btn btn-warning" onclick="continueAsGuest()" style="background: #7b1fa2">Continue as Guest</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

       
   
       
@stop

@push('js')
@if($loginstatus == 'false')
    <script>
        $(document).ready(function(){
            $('#loginModal').modal('show');
        });
    </script>
@endif
    <script>
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

        
   
            function continueAsGuest() {
                $.ajax({
                    url: '{{ route("guest.user") }}',
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


       

        // Add event listeners to all forms
        document.querySelectorAll('.add-to-cart-form').forEach(button => {
            button.addEventListener('submit', handleFormSubmission);
        });


        // JavaScript to handle tab switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove 'active' class from all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                // Add 'active' class to the clicked button
                button.classList.add('active');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                // Show the content for the selected tab
                const target = button.getAttribute('data-target');
                document.getElementById(target).classList.add('active');
            });
        });
    </script>
@endpush

@extends('site.template')
@section('css')
    <style>
        /* Basic styling for the button */
        .toggle-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        /* Alert box styling */
        .alert-box {
            padding: 15px;
            background-color: #ffcc00;
            /* Yellow background */
            color: #333;
            border-radius: 4px;
            display: none;
            /* Hidden by default */
            position: relative;
            margin-top: 10px;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
        }
    </style>
@endsection
@section('content')

    <!-- Toggleable Alert Box -->
    @if (session('success') || session('error'))
        <div class="alert-box {{ session('success') ? 'alert-success' : 'alert-error' }}" id="alertBox">
            <span>{{ session('success') ?? session('error') }}</span>
            <button class="close-btn" onclick="closeAlert()">×</button>
        </div>
    @endif

    <div class="slider">
        <div class="tp-banner-container">
            <div class="tp-banner">
                <ul>
                    @forelse ($carousels as $carousel)
                        <li data-transition="fadetotopfadefrombottom" data-slotamount="10" data-masterspeed="1000">
                            <img src="{{ asset('storage/' . $carousel->image) }}" alt="slidebg3" data-bgfit="cover"
                                data-bgposition="left top" data-bgrepeat="no-repeat">
                            <div class="tp-caption sfb box-rotated" data-x="center" data-y="220" data-speed="500"
                                data-start="1000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="width:250px;height:250px;"></div>
                            <div class="tp-caption lft slider-icon" data-x="center" data-y="220" data-speed="2000"
                                data-start="2000" data-easing="Back.easeOut" data-captionhidden="on" style=""><img
                                    src="{{ asset('site/images/slide-icon.png') }}" alt="" /></div>
                            <div class="tp-caption sfb white-text" data-x="center" data-y="260" data-speed="500"
                                data-start="2500" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:13px;">
                                May</div>
                            <div class="tp-caption sfb coloured-text" data-x="center" data-y="290" data-speed="500"
                                data-start="3000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:20px;">
                                Friday</div>
                            <div class="tp-caption sft white-text-big" data-x="center" data-y="360" data-speed="500"
                                data-start="3500" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:105px;">10
                            </div>
                            <div class="tp-caption sfb slide-title" data-x="center" data-y="450" data-speed="500"
                                data-start="4000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:40px; padding:30px 70px;">Krishh Live <strong style="color:#e837c2"> In Penang</strong></div>
                        </li>
                    @empty
                        <li data-transition="fadetotopfadefrombottom" data-slotamount="10" data-masterspeed="1000">
                            <img src="{{ asset('site/images/resource/slider.jpg') }}" alt="slidebg3" data-bgfit="cover"
                                data-bgposition="left top" data-bgrepeat="no-repeat">
                            <div class="tp-caption sfb box-rotated" data-x="center" data-y="220" data-speed="500"
                                data-start="1000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="width:250px;height:250px;"></div>
                            <div class="tp-caption lft slider-icon" data-x="center" data-y="220" data-speed="2000"
                                data-start="2000" data-easing="Back.easeOut" data-captionhidden="on" style=""><img
                                    src="{{ asset('site/images/slide-icon.png') }}" alt="" /></div>
                            <div class="tp-caption sfb white-text" data-x="center" data-y="260" data-speed="500"
                                data-start="2500" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:13px;">
                                AUG</div>
                            <div class="tp-caption sfb coloured-text" data-x="center" data-y="290" data-speed="500"
                                data-start="3000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:20px;">
                                SAT</div>
                            <div class="tp-caption sft white-text-big" data-x="center" data-y="360" data-speed="500"
                                data-start="3500" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:105px;">23
                            </div>
                            <div class="tp-caption sfb slide-title" data-x="center" data-y="450" data-speed="500"
                                data-start="4000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:40px; padding:30px 70px;">HARISH RAGHAVENDRA <strong style="color:#e837c2"> LIVE IN
                                    </strong> PENANG <br/> <span style="font-size:14px; text-align:center">🎟️ TICKET SALES BEGIN : 🌟 30th MAY 2025 AT 8PM ONWARDS.</span>
    
</div>
                        </li>

                        <li data-transition="zoomout" data-slotamount="10" data-masterspeed="1000">
                            <img src="{{ asset('site/images/resource/slider2.jpg') }}" alt="slidebg3" data-bgfit="cover"
                                data-bgposition="left top" data-bgrepeat="no-repeat">
                            <div class="tp-caption lft slider-icon" data-x="center" data-y="300" data-speed="2000"
                                data-start="2000" data-easing="Back.easeOut" data-captionhidden="on" style=""><img
                                    src="{{ asset('site/images/crazy-icon.png') }}" alt="" /></div>
                            <div class="tp-caption sfb slide-title2" data-x="center" data-y="440" data-speed="500"
                                data-start="4000" data-easing="Back.easeOut" data-captionhidden="on"
                                style="font-size:55px;">
                                ENJOY <strong>THE WHOLE</strong> NIGHT</div>
                        </li>
                    @endforelse


                </ul>
            </div>
        </div>
    </div>
    <div class="slider-bar">
        <div class="container">
            <div class="bottom-bar">
                <div class="row">
                    <div class="col-md-12 column">

                        <div class="search-event">
                            <h4>Search Event by Category</h4>
                            <span>Don't Forget To Miss Event</span>
                            <form action="{{ route('searchEvents') }}" method="GET">

                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="category" class="form-control">
                                            <option value="-1"
                                                {{ isset($categoryId) && $categoryId == -1 ? 'selected' : '' }}>All</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ isset($categoryId) && $category->id == $categoryId ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date" id="date" name="date"
                                            value="{{ isset($date) ? $date : date('Y-m-d') }}"
                                            min="{{ \Carbon\Carbon::today()->toDateString() }}" class="form-control" style="width: 100% !important">
                                    </div>
                                    @csrf
                                    <div class="col-md-2">
                                        <button type="submit"
                                            style="width:100%; height:33px; margin-right:5px;" class="btn btn-primary" > Search </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-danger" href="{{ route('getHome') }}" role="button" style="width:100%; height:33px; margin-right:5px; overflow:hidden">Reset</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


   @if ($events->count()) 

        <section>
            <div class="block gray">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 column">
                            <div class="title">

                                <h2>Trending <span>EVENTS</span></h2>
                            </div><!-- Title -->
                            <div class="remove-ext">
                                <div class="row">
                                    @foreach ($trendingevents as $event)
                                        @php
                                        $packagecount = 0;
                                            if(!is_null($event)){
                                            $minpackage = App\Models\Package::where('event_id', $event->id)->where('status', 1)->min('cost');
                                            $packagecount = App\Models\Package::where('event_id', $event->id)->count();
                                            }
                                        @endphp
                                        @if ($packagecount >= 1)
                                            <div class="col-md-6">
                                                <div class="package">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="package-img"><img
                                                                    src="{{ 'storage/' . $event->primary_photo }}"
                                                                    alt="" /></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i>RM </i>{{ $minpackage }}</strong>
                                                            @if ($packagecount > 1)
                                                                <span class="shortline">Package cost start from</span>
                                                            @else
                                                                <span class="shortline">Event Cost</span>
                                                            @endif
                                                            <div class="package-info">
                                                                <h3><a href="{{ route('getEventDetail', $event->slug) }}"
                                                                        title="">{{ $event->title }}</a></h3>
                                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i>
                                                                    {{ $event->date->format('D d M, Y') }} <i
                                                                        class="fa fa-clock-o" aria-hidden="true"></i>
                                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</span>
                                                                <span><i class="fa fa-map-marker" aria-hidden="true"></i>
                                                                    {{ $event->vennue }}</span>
                                                            </div>
                                                            <a href="{{ route('getEventDetail', $event->slug) }}"
                                                                title="">View Detail</a>
                                                        </div>
                                                    </div>
                                                </div><!-- Package -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="block gray">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 column">
                            <div class="title">

                                <h2>UPCOMING <span>EVENTS</span></h2>

                            </div><!-- Title -->

                            <div class="remove-ext">
                                <div class="row">
                                    @foreach ($events as $event)
                                        @php
                                            $minpackage = App\Models\Package::where('event_id', $event->id)->where('status', 1)->min(
                                                'cost',
                                            );
                                            $packagecount = App\Models\Package::where('event_id', $event->id)->count();
                                        @endphp
                                        @if ($packagecount >= 1)
                                            <div class="col-md-6">
                                                <div class="package">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="package-img"><img
                                                                    src="{{ 'storage/' . $event->primary_photo }}"
                                                                    alt="" /></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i>RM </i>{{ $minpackage }}</strong>
                                                            @if ($packagecount > 1)
                                                                <span class="shortline">Package cost start from</span>
                                                            @else
                                                                <span class="shortline">Event Cost</span>
                                                            @endif
                                                            <div class="package-info">
                                                                <h3><a href="{{ route('getEventDetail', $event->slug) }}"
                                                                        title="">{{ $event->title }}</a></h3>
                                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i>
                                                                    {{ $event->date->format('D d M, Y') }} <i
                                                                        class="fa fa-clock-o" aria-hidden="true"></i>
                                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                                    awards</span>
                                                                <span><i class="fa fa-map-marker" aria-hidden="true"></i>
                                                                    {{ $event->vennue }}</span>
                                                            </div>
                                                            <a href="{{ route('getEventDetail', $event->slug) }}"
                                                                title="">View Detail</a>
                                                        </div>
                                                    </div>
                                                </div><!-- Package -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

     @endif 


        <!-- completed events -->
        @if ($completed_events->count()) 
        <section>
            <div class="block gray">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 column">
                            <div class="title">

                                <h2>COMPLETED <span>EVENTS</span></h2>

                            </div><!-- Title -->

                            <div class="remove-ext">
                                <div class="row">
                                    @foreach ($completed_events as $event)
                                        @php
                                            $minpackage = App\Models\Package::where('event_id', $event->id)->min(
                                                'cost',
                                            );
                                            $packagecount = App\Models\Package::where('event_id', $event->id)->count();
                                        @endphp
                                        @if ($packagecount >= 1)
                                            <div class="col-md-6">
                                                <div class="package">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="package-img"><img
                                                                    src="{{ 'storage/' . $event->primary_photo }}"
                                                                    alt="" /></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i>RM </i>{{ $minpackage }}</strong>
                                                            @if ($packagecount > 1)
                                                                <span class="shortline">Package cost start from</span>
                                                            @else
                                                                <span class="shortline">Event Cost</span>
                                                            @endif
                                                            <div class="package-info">
                                                                <h3><a href=""
                                                                        title="">{{ $event->title }}</a></h3>
                                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i>
                                                                    {{ $event->date->format('D d M, Y') }} <i
                                                                        class="fa fa-clock-o" aria-hidden="true"></i>
                                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                                    awards</span>
                                                                <span><i class="fa fa-map-marker" aria-hidden="true"></i>
                                                                    {{ $event->vennue }}</span>
                                                            </div>
                                                            <a href=""
                                                                title="">Completed</a>
                                                        </div>
                                                    </div>
                                                </div><!-- Package -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
                                
        @endif
        <!-- end completed events -->


    <section>
        <div class="block remove-gap gray">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 column">
                        <div class="title">

                            <h2>Our<span>Services</span></h2>

                        </div><!-- Title -->
                        <div class="snaps-gallery">
                            <div class="col-md-6">
                                <div class="snap style1">
                                    <div class="gallery-img">
                                        <img src="{{ asset('site/images/resource/snap1.jpg') }}" alt="" />
                                        <a data-rel="prettyPhoto" href="http://placehold.it/1000x800" title=""><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                    <div class="snap-detail">
                                        <h4>Advance Check In Solutions</h4>
                                        <p>Streamline your event with seamless online ticketing, offering easy advance check-in and secure access control.</p>
                                    </div>
                                </div><!-- Snap Style 1 -->
                                <div class="snap style2">
                                    <div class="gallery-img">
                                        <img src="{{ asset('site/images/resource/snap2.jpg') }}" alt="" />
                                        <a data-rel="prettyPhoto" href="http://placehold.it/1000x800" title=""><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                    <div class="snap-detail">
                                        <h4>Digital Marketing</h4>
                                        <p>Boost sales with our seamless online ticketing solution—secure, fast, and easy event management! </p>
                                    </div>
                                </div><!-- Snap Style 2 -->
                            </div>
                            <div class="col-md-6">
                                <div class="snap style1">
                                    <div class="snap-detail">
                                        <h4>Onsite Support During Events</h4>
                                        <p>Ensure smooth events with our onsite support—real-time assistance for ticketing, entry, and technical issues! </p>
                                    </div>
                                    <div class="gallery-img">
                                        <img src="{{ asset('site/images/resource/snap3.jpg') }}" alt="" />
                                        <a data-rel="prettyPhoto" href="http://placehold.it/1000x800" title=""><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                </div><!-- Snap Style 2 -->
                                <div class="snap style2">
                                    <div class="snap-detail">
                                        <h4>Dashboard & Reporting</h4>
                                        <p>Gain insights with our free event dashboard—real-time reporting, sales tracking, and attendee analytics!</p>
                                    </div>
                                    <div class="gallery-img">
                                        <img src="{{ asset('site/images/resource/snap4.jpg') }}" alt="" />
                                        <a data-rel="prettyPhoto" href="http://placehold.it/1000x800" title=""><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                </div><!-- Snap Style 1 -->
                            </div>
                        </div><!-- Snaps Gallery -->
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Snaps From Show -->








    <section>
        <div class="block remove-gap gray">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 column">
                        <div class="title">
                            <h2>EVENT <span>ORGANIZER</span></h2>
                        </div><!-- Title -->
                        <div class="event-sponsors remove-ext">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor1.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor2.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor3.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor4.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor5.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor6.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor7.png') }}"
                                            alt="" /></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="sponsor"><img src="{{ asset('site/images/resource/sponsor8.png') }}"
                                            alt="" /></div>
                                </div>
                            </div>
                        </div><!-- Event Sponsors -->
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Event Sponsors -->


    <section>
        <div class="block remove-gap gray">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10 column">
                        <div class="become-sponsor">
                            <h3>BECOME A ORGANIZER</h3>
                            <p> Post Events, and connect with clients. If you need help with crafting a message or providing
                                more details about the platform, feel free to ask!</p>
                            <a class="button" href="{{ route('organizer.register') }}" title="">REGISTER NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Become a Sponsor -->


    <section>
        <div class="block coloured-layer extra-gap">
            <div style="background:url({{ asset('site/images/parallax2.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 column">
                        <div class="fun-facts">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="counters">
                                        <img src="{{ asset('site/images/fun-fact1.png') }}" alt="" />
                                        <h4>FUN PARTIES</h4>
                                        <span class="counter">280</span>
                                    </div>
                                </div><!-- Counter -->
                                <div class="col-md-3">
                                    <div class="counters">
                                        <img src="{{ asset('site/images/fun-fact2.png') }}" alt="" />
                                        <h4>GAME PLAYED</h4>
                                        <span class="counter">319</span>
                                    </div>
                                </div><!-- Counter -->
                                <div class="col-md-3">
                                    <div class="counters">
                                        <img src="{{ asset('site/images/fun-fact3.png') }}" alt="" />
                                        <h4>CUP OF COFFEE</h4>
                                        <span class="counter">640</span>
                                    </div>
                                </div><!-- Counter -->
                                <div class="col-md-3">
                                    <div class="counters">
                                        <img src="{{ asset('site/images/fun-fact4.png') }}" alt="" />
                                        <h4>MOVIES WATCHED</h4>
                                        <span class="counter">124</span>
                                    </div><!-- Counter -->
                                </div>
                            </div>
                        </div><!-- Fun Facts -->
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Fun Factors -->
    <!-- Button trigger modal -->
<!-- Modal -->
<!--<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">-->
<!--  <div class="modal-dialog modal-dialog-centered" role="document">-->
<!--    <div class="modal-content">-->
<!--      <div class="modal-header">-->
<!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--          <span aria-hidden="true">&times;</span>-->
<!--        </button>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--        <img src="{{asset('site/images/qrforgreatticket.jpg')}}">-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
@stop

@push('js')
    <script>
        // Function to close the alert box
        function closeAlert() {
            document.getElementById('alertBox').style.display = 'none';
        }

        // Show the alert box if there's a message
        window.onload = () => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                alertBox.style.display = 'block';
            }
        };
    </script>
   <script>
  $(document).ready(function () {
    $('#exampleModalCenter').modal('show');
  });
</script>
@endpush

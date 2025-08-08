<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Great Ticket</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="" />
<meta name="keywords" content="" />

<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>

<!-- Styles -->
<link href="{{ asset('site/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('site/css/style.css') }}" type="text/css" />
<link href="{{ asset('site/css/responsive.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('site/css/color/color.css') }}" title="color" />
</head>
<body>
<div class="page-loader">
  <div class="item one"></div>
</div><!-- Page Loader -->
<div class="comming-soon blackish">
	<div style="background:url({{ asset('site/images/parallax5.jpg') }});" class="parallax"></div>
	<div class="container">
		<div class="soon-logo">
			<a href="#" title=""><img src="{{asset('site/images/logo.jpg')}}" alt="" width="150"></a>
		</div>
		<h1>COMMING SOON</h1>
		<p>Great Life Begins With Great Live</p>

		<ul class="countdown">
			<li> 
				<div class="time-box">
					<span class="days">00</span><p class="days_ref">Days</p>
				</div>
			</li>
			<li> 
				<div class="time-box">
					<span class="hours">00</span><p class="hours_ref">Hours</p>
				</div>
			</li>
			<li> 
				<div class="time-box">
					<span class="minutes">00</span><p class="minutes_ref">Minutes</p>
				</div>
			</li>
			<li> 
				<div class="time-box">
					<span class="seconds">00</span><p class="seconds_ref">Seconds</p>
				</div>
			</li>
		</ul><!-- Event Countdown -->							

		
	</div>
</div>


<div class="bottom-footer">
	<div class="container">
		<p>All rights reserved {{date('Y')}}-<a title="" href="#">Great Ticket</a> By <a title="" href="https://websofttechnology.com.my">Websoft Technology</a></p>
	</div>
</div><!-- Bottom Footer -->


<script type="text/javascript" src="{{ asset('site/js/modernizr.custom.97074.js') }}"></script>
<script type="text/javascript" src="{{ asset('site/js/jquery2.1.1.js') }}"></script>
<script type="text/javascript" src="{{ asset('site/js/prettyPhoto.js') }}"></script>
<script type="text/javascript" src="{{ asset('site/js/jquery.downCount.js') }}"></script> 
<script type="text/javascript" src="{{ asset('site/js/script.js') }}"></script>


<script>
$(document).ready(function() {
$( function() { $( 'audio' ).audioPlayer(); } );

	$('.countdown').downCount({
	    date: '08/25/2024 12:00:00',
	    offset: +10
	});

});
</script>

</body>

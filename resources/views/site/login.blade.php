@extends('site.template')
@section('content')
<section>
	<div class="block gray half-parallax blackish remove-bottom">
		<div style="background:url({{asset('site/images/parallax8.jpg')}});" class="parallax"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="page-title">
						
						<h1><span>Login / <span>Register</span></h1>
                        <p>Login with your email and password or create a new account</p>
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
                    <div class="single-post">
                        <div class="row">
							<div class="col-md-6">
								<div class="widget">
									<div id="message"></div>
                                    <h3>Login</h3>
                                    <hr>
									<form  class="contact" method="post" action="">
										<input  name="email" type="text" id="email"  placeholder="Email" style="background: #ccc; color:#000; font-size:16px;" />
                                        <input name="password" type="password" id="password" placeholder="Password" style="background: #ccc; color:#000; font-size:16px;" />
										@if(request()->has('returnurl'))
											<input type="hidden" name="returnurl" value="{{ request()->get('returnurl') }}">
										@endif
										<button class="button" type="submit" id="submit">Login</button>
									</form>
								</div>
							</div>
							<div class="col-md-6">
								<div class="widget">
									<div id="message"></div>
                                    <h3>Sign Up</h3>
                                    <hr>
									<form  class="contact" method="post" action="">
                                        <input  name="fname" type="text" id="fname"  placeholder="Full Name" style="background: #ccc; color:#000; font-size:16px;" />
										<input  name="email" type="text" id="email"  placeholder="Email" style="background: #ccc; color:#000; font-size:16px;" />
                                        <input name="password" type="password" id="password" placeholder="Password" style="background: #ccc; color:#000; font-size:16px;" />
										@if(request()->has('returnurl'))
											<input type="hidden" name="returnurl" value="{{ request()->get('returnurl') }}">
										@endif
										<button class="button" type="submit" id="submit">Login</button>
									</form>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
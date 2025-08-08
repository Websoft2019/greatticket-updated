@extends('site.template')
@section('content')
<section>
	<div class="block gray half-parallax blackish remove-bottom">
		<div style="background:url({{asset('site/images/parallax8.jpg')}});" class="parallax"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="page-title">
						<span>User Dashboard</span>
						<h1><span> DASHBOARD</span></h1>
						<p>Tikets | Profile | Change Password</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Toggleable Alert Box -->
        @if (session('success') || session('error'))
            <div class="alert-box {{ session('success') ? 'alert-success' : 'alert-warning' }}" id="alertBox">
                <span>{{ session('success') ?? session('error') }}</span>
                <button class="close-btn" onclick="closeAlert()">×</button>
            </div>
        @endif
<section>
	<div class="block gray">
		<div class="container">
			<div class="row">
				<div class="col-md-12 column">
                    <div class="schedule-tabs"> 
						<ul class="nav nav-tabs" id="myTab">
							<li class="col-md-4 active"><a data-toggle="tab" href="#myticket">My Tickets</a></li>
							<li class="col-md-4"><a data-toggle="tab" href="#myprofile">My Profile</a></li>
							<li class="col-md-4"><a data-toggle="tab" href="#changepassword">Change Password</a></li>
						</ul>
                        <div class="tab-content" id="myTabContent2">
							<div id="myticket" class="tab-pane fade active in">
                                <div class="myticket">
                                    <div class="order-history mt-4" style="margin-top: 20px;">
                                        @if ($orders->isEmpty())
                                            <div class="alert alert-info" role="alert">
                                                You have no orders yet.
                                            </div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>SN</th>
                                                            <th>Date</th>
                                                            <th>Event</th>
                                                            <th>Tickets Quantity</th>
                                                            <th>Total Cost</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $order)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                                <td>{{ \Illuminate\Support\Str::limit($order->orderPackages->first()->package->event->title ?? 'Event Name', 50) }}</td>
                                                                <td>{{ $order->orderPackages->sum('quantity') }}</td>
                                                                <td>RM {{ $order->grandtotal+$order->servicecharge }}</td>
                                                                <td>{{ $order->paymentstatus }}</td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary" href="{{ route('history.details', $order->id) }}" role="button">
                                                                        Details
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
						    </div>
                            <div id="myprofile" class="tab-pane fade">
                                    <div class="myprofile">
                                        <div class="order-history my-4" style="margin-top: 20px;">
                                            <div class="profile my-3" style="padding: 0 20px;">
                                                <h4>Profile Detail</h4>
                                                <form action="{{ route('profile.update') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <div class="col-md-6">
                                                            <label for="name">Full Name</label>
                                                            <input type="text"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                placeholder="Enter your full name" id="name"
                                                                name="name" value="{{ old('name',auth()->user()->name) }}" required>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="name">Address</label>
                                                            <input type="text"
                                                                class="form-control @error('address') is-invalid @enderror"
                                                                placeholder="Address" id="name" name="address"
                                                                value="{{ old('address',auth()->user()->address) }}" >
                                                            @error('address')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-3">
                                                            <label for="name">Country</label>
                                                            <select
                                                                class="form-control @error('country') is-invalid @enderror"
                                                                id="country" name="country" required>
                                                                <option value="malaysia">Malaysia</option>
                                                            </select>
                                                            @error('country')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="state">State</label>
                                                            <select
                                                                class="form-control @error('state') is-invalid @enderror"
                                                                id="state" name="state" required>
                                                                <option value="Johor">Johor</option>
                                                                <option value="Kedah">Kedah</option>
                                                                <option value="Kelantan">Kelantan</option>
                                                                <option value="Melaka">Melaka</option>
                                                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                                                <option value="Pahang">Pahang</option>
                                                                <option value="Perak">Perak</option>
                                                                <option value="Perlis">Perlis</option>
                                                                <option value="Pulau Pinang">Pulau Pinang</option>
                                                                <option value="Selangor">Selangor</option>
                                                                <option value="Terengganu">Terengganu</option>
                                                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                                                <option value="Putra Jaya">Putra Jaya</option>
                                                                <option value="Sarawak">Sarawak</option>
                                                                <option value="Sabah">Sabah</option>
                                                                <option value="Labuan">Labuan</option>
                                                            </select>
                                                            @error('state')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="city">City</label>
                                                            <input type="text"
                                                                class="form-control @error('city') is-invalid @enderror"
                                                                placeholder="City" id="city" name="city"
                                                                value="{{ old('city', auth()->user()->city) }}" >
                                                            @error('city')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="postcode">Postcode</label>
                                                            <input type="text"
                                                                class="form-control @error('postcode') is-invalid @enderror"
                                                                placeholder="Postcode" id="postcode" name="postcode"
                                                                value="{{ old('postcode', auth()->user()->postcode) }}" >
                                                            @error('postcode')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-6">
                                                            <label for="email">Email Address</label>
                                                            <input type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="Email Address" id="email"
                                                                value="{{ auth()->user()->email }}" readonly>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="phone">Phone</label>
                                                            <input type="number"
                                                                class="form-control @error('contact') is-invalid @enderror"
                                                                placeholder="Phone Number" id="contact" name="contact"
                                                                value="{{ old('contact',auth()->user()->contact) }}">
                                                            @error('contact')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label for="dob">Date of Birth</label>
                                                            <input type="date"
                                                                class="form-control @error('dob') is-invalid @enderror"
                                                                placeholder="" id="dob" name="dob"
                                                                value="{{ old('dob',auth()->user()->dob) }}">
                                                            @error('dob')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="icnumber">IC Number</label>
                                                            <input type="text"
                                                                class="form-control @error('icnumber') is-invalid @enderror"
                                                                placeholder="" id="icnumber" name="icnumber"
                                                                value="{{ old('icnumber',auth()->user()->icnumber) }}">
                                                            @error('icnumber')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="row" style="display: flex; justify-content: end; align-items: center; height: 100px;">
                                                                <button type="submit" class="btn btn-primary">
                                                                    Update
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div id="changepassword" class="tab-pane fade">
                                <div class="changepassword">
                                    <div class="order-history mt-4" style="margin-top: 20px;">
                                       <form action="{{ route('updatePassword') }}" method="POST">
                                                        @csrf
                                                        {{-- @method('PUT') --}}
                                                        <div class="form-group">
                                                            <div class="col">
                                                                <label for="old_password">Old Password</label>
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <input type="password"
                                                                            class="form-control @error('old_password') is-invalid @enderror"
                                                                            id="old_password" name="old_password"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <span class="input-group-addon"
                                                                            onclick="togglePassword('old_password', this)"
                                                                            style="cursor: pointer;">
                                                                            <i class="fa fa-eye"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @error('old_password')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col">
                                                                <label for="password">New Password</label>
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <input type="password"
                                                                            class="form-control @error('password') is-invalid @enderror"
                                                                            id="password" name="password" required>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <span class="input-group-addon"
                                                                            onclick="togglePassword('password', this)"
                                                                            style="cursor: pointer;">
                                                                            <i class="fa fa-eye"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @error('password')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                Update
                                                            </button>
                                                        </div>
                                                    </form>
                                    </div>
                                </div>
						    </div>
						</div>
					</div>
                </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Toggle Password Visibility
    function togglePassword(fieldId, iconElement) {
        const passwordField = document.getElementById(fieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            iconElement.querySelector('i').classList.remove('fa-eye');
            iconElement.querySelector('i').classList.add('fa-eye-slash');
        } else {
            passwordField.type = "password";
            iconElement.querySelector('i').classList.remove('fa-eye-slash');
            iconElement.querySelector('i').classList.add('fa-eye');
        }
    }
</script>
    
@stop




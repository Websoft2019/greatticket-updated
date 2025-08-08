@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Book Event'])

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 mb-4">Book Package for Customer</h2>

                        <div id="messages"></div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="booking-form" action="{{ route('organizer.bookings.store') }}" method="POST">
                            @csrf
                            <h4>Customer Details</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Full Name</label>
                                    <input name="customer_name" type="text" class="form-control"
                                        value="{{ old('customer_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Email</label>
                                    <input name="customer_email" type="email" class="form-control"
                                        value="{{ old('customer_email') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Contact Number</label>
                                    <input name="customer_phone" type="text" class="form-control"
                                        value="{{ old('customer_phone') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Booking Type</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="normal"
                                            value="normal" checked>
                                        <label class="form-check-label" for="normal">Normal</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type"
                                            id="complementary" value="complementary">
                                        <label class="form-check-label" for="complementary">Complementary</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="reserved"
                                            value="reserved">
                                        <label class="form-check-label" for="reserved">Reserved</label>
                                    </div>
                                </div>

                                <h4 class="mt-3">Select Event & Package</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Event</label>
                                        <select id="event-select" class="form-select">
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->title }}
                                                    ({{ $event->date->format('Y-m-d') }} {{$event->time}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Package</label>
                                        <select name="package_id" id="package-select" class="form-select">
                                            {{-- Populated dynamically --}}
                                        </select>
                                        @error('package_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Quantity</label>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1"
                                            class="form-control" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <h4>Attendee Details</h4>
                                <div id="attendee-section">

                                    @if (old('attendees'))
                                        @foreach (old('attendees') as $index => $attendee)
                                            <div class="col-md-12">
                                                <div class="checkout-form-list">
                                                    <label> Participant Full Name {{ $index + 1 }} <span
                                                            class="required">*</span></label>
                                                    <input name="attendees[]" type="text"
                                                        class="form-control @error('attendees.' . $index) is-invalid @enderror"
                                                        value="{{ $attendee }}" required>
                                                    @error('attendees.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @php
                                            $count = $events?->first()?->packages?->first()->maxticket ?? 1;
                                        @endphp
                                        @for ($i = 1; $i <= $count; $i++)
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label> Participant Full Name {{ $i }} <span
                                                                class="text-danger">*</span></label>
                                                        <input name="attendees[]" type="text" class="form-control"
                                                            required>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                                <h4>Checkout Summary</h4>
                                <div class="p-3 bg-light rounded">
                                    <ul class="list-unstyled">
                                        <li>Sub Total Price: <strong id="sub-total-price">RM 0.00</strong></li>
                                        <li>Discount: <strong id="discount">RM 0.00</strong></li>
                                        <li>Total Price: <strong id="total-price">RM 0.00</strong></li>
                                        <li>Service Charge: <strong id="service-charge">RM 0.00</strong></li>
                                        <li><strong>Grand Total: <span id="grand-total">RM 0.00</span></strong></li>
                                    </ul>
                                    <input type="text" id="coupon_code" name="coupon_code" class="form-control"
                                        placeholder="Coupon Code">
                                    <button type="button" id="apply_coupon" class="btn btn-success mt-2">Apply
                                        Coupon</button>
                                </div>
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">Book Now</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const events = @json($events);
        const organizer = @json($organizer);
        const eventSelect = document.getElementById('event-select');
        const packageSelect = document.getElementById('package-select');
        const quantityInput = document.getElementById('quantity');
        const attendeeSection = document.getElementById('attendee-section');
        const subTotalPriceElement = document.getElementById('sub-total-price');
        const totalPriceElement = document.getElementById('total-price');
        const grandTotalElement = document.getElementById('grand-total');
        const discountElement = document.getElementById('discount');
        const serviceChargeElement = document.getElementById('service-charge');

        function populatePackages(eventId) {
            const selectedEvent = events.find(e => e.id == eventId);
            packageSelect.innerHTML = '';
            selectedEvent.packages.forEach(pkg => {
                const option = document.createElement('option');
                option.value = pkg.id;
                option.dataset.price = pkg.actual_cost;
                option.dataset.pax = pkg.maxticket;
                option.textContent =
                    `${pkg.title} - RM ${pkg.actual_cost} (${pkg.capacity - pkg.consumed_seat} seats left) (${pkg.maxticket} pax)`;
                packageSelect.appendChild(option);
            });
            updateTotal();
        }

        function updateTotal() {

            const bookingTypeElement = document.querySelector('input[name="booking_type"]:checked');
            const price = packageSelect.options[packageSelect.selectedIndex]?.dataset.price || 0;
            const quantity = parseInt(quantityInput.value) || 1;
            const total = price * quantity;

            const bookingType = bookingTypeElement?.value ?? 'normal';
            console.log(bookingType)

            let serviceCharge = 0;

            if (organizer.cm_type == "percentage") {
                serviceCharge = total * organizer.cm_value / 100;
            } else {
                serviceCharge = organizer.cm_value;
            }

            const grandTotal = total + serviceCharge;

            if (bookingType === 'complementary') {
                subTotalPriceElement.innerHTML = `RM 0.00`;
                serviceChargeElement.innerHTML = `RM 0.00`;
                totalPriceElement.innerText = `RM 0.00`;
                grandTotalElement.innerText = `RM 0.00`;
            } else {
                subTotalPriceElement.innerHTML = `RM ${total.toFixed(2)}`;
                serviceChargeElement.innerHTML = `RM ${serviceCharge.toFixed(2)}`;
                totalPriceElement.innerText = `RM ${grandTotal.toFixed(2)}`;
                grandTotalElement.innerText = `RM ${grandTotal.toFixed(2)}`;
            }
        }

        function generateAttendeeFields() {
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const pax = selectedOption.dataset.pax;

            console.log('Selected pax:', pax);

            attendeeSection.innerHTML = '';
            const quantity = parseInt(quantityInput.value) || 1;
            for (let i = 0; i < (quantity * pax); i++) {
                attendeeSection.innerHTML += `
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Participant Full Name ${i+1} <span class="text-danger">*</span></label>
                                <input name="attendees[]" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function showAlert(type, message) {
            const messages = document.getElementById('messages');
            const alertContainer = document.createElement('div');
            alertContainer.classList.add('alert', 'alert-dismissible', 'fade', 'show');
            alertContainer.role = 'alert';

            if (type === 'success') {
                alertContainer.classList.add('alert-success');
                alertContainer.innerHTML =
                    `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>`;
            } else if (type === 'error') {
                alertContainer.classList.add('alert-danger');
                alertContainer.innerHTML =
                    `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>`;
            }

            messages.appendChild(alertContainer);
        }

        document.getElementById('apply_coupon').addEventListener('click', function() {
            const code = document.getElementById('coupon_code').value;
            const packageId = packageSelect.value;
            const quantity = quantityInput.value ?? 1;
            fetch("{{ env('APP_URL') }}/api/organizer/coupon", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code,
                        package_id: packageId,
                        quantity: quantity
                    })
                })
                // .then(response => response.json())
                // .then(data => {
                .then(response => response.text()) // changed from .json() to .text()
                .then(text => {
                    // if (data.success) {
                    // console.log("Raw response:", text);
                    const data = JSON.parse(text); // manually parse
                    // console.log("Parsed data:", data);
                    // Show success message
                    discountElement.innerText = `RM ${data.data.discount.toFixed(2)}`;
                    const total = parseFloat(totalPriceElement.innerText.replace('RM ', '')) - data.data
                        .discount;
                    grandTotalElement.innerText = `RM ${total.toFixed(2)}`;
                    showAlert('success', data.message);
                    // } else {
                    //     // Show error message
                    //     showAlert('error', data.message);
                    // }
                })
                .catch(error => {
                    // Handle any unexpected errors
                    // console.error("Caught error:", error);
                    showAlert('error', 'An error occurred while applying the coupon.');
                });
        });

        eventSelect.addEventListener('change', () => populatePackages(eventSelect.value));
        // packageSelect.addEventListener('change', updateTotal);
        packageSelect.addEventListener('change', () => {
            updateTotal();
            generateAttendeeFields();
        });
        quantityInput.addEventListener('input', () => {
            updateTotal();
            generateAttendeeFields();
        });

        const bookingTypeRadios = document.querySelectorAll('input[name="booking_type"]');

        bookingTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateTotal();
                const selected = document.querySelector('input[name="booking_type"]:checked');
                if (selected) {
                    console.log('Selected booking type:', selected.value);
                    return selected.value;
                }
                return null;
            });
        });

        populatePackages(events[0].id);
    </script>
@endsection

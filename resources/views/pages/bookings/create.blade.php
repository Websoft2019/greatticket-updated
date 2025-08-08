@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('css')
<style>
    .seat-btn {
        min-width: 60px;
        text-align: center;
    }

    .seat-btn.selected {
        background-color: #198754 !important;
        color: white !important;
        border-color: #198754 !important;
    }

    .seat-row {
        border-bottom: 1px dashed #ccc;
        padding-bottom: 10px;
    }
</style>
@stop
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
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mt-3">Booking Type</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="normal"
                                            value="normal" checked>
                                        <label class="form-check-label" for="normal">Counter Purchase <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Counter cash booking"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type"
                                            id="complementary" value="complementary">
                                        <label class="form-check-label" for="complementary">Complementary <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Free (Complementary) / No any cost"></i></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="reserved"
                                            value="reserved">
                                        <label class="form-check-label" for="reserved">Reserved <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Reserved and send payment link via email to customer"></i></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <h5 class="mt-3">Customer Details</h5>
                                <div class="col-md-4 mb-3">
                                    <label>Full Name</label>
                                    <input name="customer_name" type="text" id="customer_name" class="form-control"
                                        value="{{ old('customer_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Email</label>
                                    <input name="customer_email" type="email" id="customer_email" class="form-control"
                                        value="{{ old('customer_email') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Contact Number</label>
                                    <input name="customer_phone" type="text" class="form-control"
                                        value="{{ old('customer_phone') }}">
                                </div>
                                

                                
                                <div class="row">
                                    <h5 class="mt-3">Select Event & Package</h5>
                                    <div class="col-md-5 mb-3">
                                        <label>Event</label>
                                        <select id="event-select" class="form-select">
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->title }}
                                                    ({{ $event->date->format('Y-m-d') }} {{$event->time}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label>Package</label>
                                        <select name="package_id" id="package-select" class="form-select">
                                            {{-- Populated dynamically --}}
                                        </select>
                                        @error('package_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Quantity</label>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1"
                                            class="form-control" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div id="seat-list" class="mt-4" style="display: none;"></div>
                                    </div>
                                    
                                </div>

                                <h4>Attendee Details</h4>
                                @if (old('booking_type') !== 'reserved')
                                    <div id="attendee-section">
                                        @if (old('attendees'))
                                            @foreach (old('attendees') as $index => $attendee)
                                                <div class="col-md-12">
                                                    <div class="checkout-form-list">
                                                        <label> Participant Full Name {{ $index + 1 }} <span class="required">*</span></label>
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
                                                            <label> Participant Full Name {{ $i }} <span class="text-danger">*</span></label>
                                                            <input name="attendees[]" type="text" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                @endif
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
                option.dataset.bogo = pkg.is_bogo ? 1 : 0; // assuming backend sends `is_bogo`
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
            const bookingType = document.querySelector('input[name="booking_type"]:checked')?.value;
        
            console.log('Selected pax:', pax, 'Booking Type:', bookingType);
        
            attendeeSection.innerHTML = '';
            const quantity = parseInt(quantityInput.value) || 1;
            
            for (let i = 0; i < (quantity * pax); i++) {
                const requiredAttr = bookingType !== 'reserved' ? 'required' : '';
                const requiredLabel = bookingType !== 'reserved' ? '<span class="text-danger">*</span>' : '';
        
                attendeeSection.innerHTML += `
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Participant Full Name ${i + 1} ${requiredLabel}</label>
                                <input name="attendees[]" type="text" class="form-control" ${requiredAttr}>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

    function updateSeatSelectionCount() {
    const selectedSeats = document.querySelectorAll('.seat-btn.selected');
    const seatCount = selectedSeats.length;

    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
    const pax = parseInt(selectedOption.dataset.pax) || 1;
    const isBogo = selectedOption.dataset.bogo == '1';

    const baseQuantity = parseInt(quantityInput.value) || 0;
    const allowedSeats = isBogo ? baseQuantity * 2 * pax : baseQuantity * pax;

    // 🚫 Restrict over-selection
    if (seatCount > allowedSeats) {
        showAlert('error', `You can only select up to ${allowedSeats} seat(s) for this package.`);
        // Deselect the last one automatically (optional)
        selectedSeats[seatCount - 1].classList.remove('btn-primary', 'selected');
        selectedSeats[seatCount - 1].classList.add('btn-outline-primary');
        return;
    }

    // 🧹 Remove previous hidden inputs
    document.querySelectorAll('input[name="selected_seats[]"]').forEach(el => el.remove());

    const form = document.getElementById('booking-form');

    // 🆕 Add selected seat inputs
    selectedSeats.forEach(seatBtn => {
        const seatId = seatBtn.dataset.id;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_seats[]';
        input.value = seatId;
        form.appendChild(input);
    });

    updateTotal();
    generateAttendeeFields();
}



        function fetchSeatsByPackage(packageId) {
    const seatListDiv = document.getElementById('seat-list');
    seatListDiv.innerHTML = '<p>Loading seats...</p>';

    return fetch(`/organizer/seats-by-package/${packageId}`)
        .then(res => res.json())
        .then(seats => {
            seatListDiv.innerHTML = '';
            if (seats.length === 0) {
                seatListDiv.innerHTML = '<p>No seats available for this package.</p>';
                quantityInput.readOnly = false;
                return false;
            }
            const grouped = {};
            seats.forEach(seat => {
                if (!grouped[seat.row_label]) grouped[seat.row_label] = [];
                grouped[seat.row_label].push(seat);
                grouped[seat.row_label].sort((a, b) => a.seat_number - b.seat_number);
            });
            for (const row in grouped) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row mb-2';
                const label = document.createElement('div');
                label.className = 'mb-1 fw-bold';
                label.innerText = `Row ${row}:`;
                rowDiv.appendChild(label);

                const seatContainer = document.createElement('div');
                seatContainer.className = 'd-flex flex-wrap gap-2';

                grouped[row].forEach(seat => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn seat-btn';
                    btn.innerText = `${seat.row_label}${seat.seat_number}`;
                    btn.dataset.id = seat.id;
                    btn.disabled = seat.status === 'booked';
                    if (['booked', 'reserved'].includes(seat.status)) {
    btn.disabled = true;

    if (seat.status === 'booked') {
        btn.classList.add('btn-secondary');
    } else if (seat.status === 'reserved') {
        btn.classList.add('btn-outline-dark');
        btn.style.backgroundColor = '#d3d3d3';
        btn.title = 'Reserved';
    }
} else {
    btn.classList.add('btn-outline-primary');
   btn.addEventListener('click', () => {
                            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                            const pax = parseInt(selectedOption.dataset.pax) || 1;
                            const isBogo = selectedOption.dataset.bogo == '1';
                            const baseQuantity = parseInt(quantityInput.value) || 0;
                            const maxAllowed = isBogo ? baseQuantity * 2 * pax : baseQuantity * pax;
                        
                            const selectedSeats = document.querySelectorAll('.seat-btn.selected');
                        
                            if (!btn.classList.contains('selected') && selectedSeats.length >= maxAllowed) {
                                showAlert('error', `You can only select up to ${maxAllowed} seat(s).`);
                                return;
                            }
                            btn.classList.toggle('btn-primary');
                            btn.classList.toggle('btn-outline-primary');
                            btn.classList.toggle('selected');
                            updateSeatSelectionCount();
                        });
}

                    

                    seatContainer.appendChild(btn);
                });

                rowDiv.appendChild(seatContainer);
                seatListDiv.appendChild(rowDiv);
            }

            return true;
        })
        .catch(err => {
            console.error(err);
            seatListDiv.innerHTML = '<p class="text-danger">Error loading seats.</p>';
            quantityInput.readOnly = false;
            return false;
        });
}


        function toggleSeatListByBookingType() {
            const seatListDiv = document.getElementById('seat-list');
            seatListDiv.style.display = 'block';
        
            fetchSeatsByPackage(packageSelect.value).then(hasSeats => {
                // quantityInput.readOnly = hasSeats; // disables typing when seats exist
            });
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
            fetchSeatsByPackage(packageSelect.value);
        });
        quantityInput.addEventListener('input', () => {
            updateTotal();
            generateAttendeeFields();
        });

        const bookingTypeRadios = document.querySelectorAll('input[name="booking_type"]');

        bookingTypeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateTotal();
                toggleSeatListByBookingType();
                updateRequiredFieldsBasedOnBookingType();
                generateAttendeeFields();
            });
        });

        populatePackages(events[0].id);
        toggleSeatListByBookingType();
    </script>
    <script>
        function updateRequiredFieldsBasedOnBookingType() {
            const bookingType = document.querySelector('input[name="booking_type"]:checked')?.value;
            const nameInput = document.getElementById('customer_name');
            const emailInput = document.getElementById('customer_email');
    
            if (bookingType === 'reserved') {
                nameInput.setAttribute('required', 'required');
                emailInput.setAttribute('required', 'required');
            } else {
                nameInput.removeAttribute('required');
                emailInput.removeAttribute('required');
            }
        }
    
        // Run initially to apply correct state on page load
        updateRequiredFieldsBasedOnBookingType();
    
        // Use already-declared `bookingTypeRadios`
        bookingTypeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateRequiredFieldsBasedOnBookingType();
            });
        });
    </script>


@endsection

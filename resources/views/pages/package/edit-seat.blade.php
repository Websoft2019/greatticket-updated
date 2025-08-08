@extends('layouts.app')

@push('header')
    <style>
        .seat {
            width: 35px;
            height: 35px;
            background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            color: #475569;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            user-select: none;
        }

        .seat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-color: #3b82f6;
        }

        .seat.selected {
            background: linear-gradient(145deg, #10b981, #059669);
            color: white;
            border-color: #047857;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .seat.reserved {
            background: linear-gradient(145deg, #f59e0b, #d97706);
            color: white;
            border-color: #b45309;
        }

        .seat.booked {
            background: linear-gradient(145deg, #ef4444, #dc2626);
            color: white;
            border-color: #b91c1c;
            cursor: not-allowed;
        }

        .seat::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #64748b;
            border-radius: 2px;
            opacity: 0.6;
        }

        .seat.selected::before,
        .seat.reserved::before,
        .seat.booked::before {
            background: #ffffff;
            opacity: 0.8;
        }

        .row-label {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .seat-legend {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
        }

        .seat-legend.available {
            background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
            border: 2px solid transparent;
        }

        .seat-legend.selected {
            background: linear-gradient(145deg, #10b981, #059669);
            border: 2px solid #047857;
        }

        .seat-legend.reserved {
            background: linear-gradient(145deg, #f59e0b, #d97706);
            border: 2px solid #b45309;
        }

        .seat-legend.booked {
            background: linear-gradient(145deg, #ef4444, #dc2626);
            border: 2px solid #b91c1c;
        }

        #seat-grid {
            display: grid;
            gap: 8px 4px;
            justify-content: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .seat-row {
            display: contents;
        }

        .edit-controls {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .status-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .status-btn.available {
            background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
            color: #475569;
        }

        .status-btn.selected {
            background: linear-gradient(145deg, #10b981, #059669);
            color: white;
        }

        .status-btn.reserved {
            background: linear-gradient(145deg, #f59e0b, #d97706);
            color: white;
        }

        .status-btn.booked {
            background: linear-gradient(145deg, #ef4444, #dc2626);
            color: white;
        }

        .status-btn.active {
            transform: scale(0.95);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .seat {
                width: 28px;
                height: 28px;
                font-size: 10px;
            }

            .row-label {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            #seat-grid {
                gap: 6px 2px;
                padding: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Edit Seat Layout</h1>
                <p class="text-gray-600">Modify your venue seating arrangement</p>
            </div>

            <!-- Edit Controls -->
            <div class="bg-white rounded-2xl shadow-xl px-4 py-2 mb-3">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Controls
                </h2>

                <div class="edit-controls">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-sm font-medium text-gray-700">Select Status to Apply:</label>
                            <div class="status-buttons">
                                <button type="button" class="status-btn available active" data-status="available">
                                    Available
                                </button>
                                <button type="button" class="status-btn selected" data-status="selected">
                                    Selected
                                </button>
                                <button type="button" class="status-btn reserved" data-status="reserved">
                                    Reserved
                                </button>
                                <button type="button" class="status-btn booked" data-status="booked">
                                    Booked
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-sm font-medium text-gray-700">Quick Actions:</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" id="add-seat-btn" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Add Seat
                                </button>
                                <button type="button" id="remove-seat-btn" class="btn btn-danger btn-sm">
                                    <i class="fas fa-minus"></i> Remove Seat
                                </button>
                                <button type="button" id="clear-selection-btn" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eraser"></i> Clear Selection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seat Grid Container -->
            <div id="seat-container">
                <!-- Legend -->
                <div class="bg-white rounded-2xl shadow-xl px-4 py-2 mb-3">
                    <h3 class="text-lg font-semibold text-gray mb-4">Legend</h3>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <div class="seat-legend available"></div>
                            <span class="ml-2 text-sm text-gray">Available</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="seat-legend selected"></div>
                            <span class="ml-2 text-sm text-gray">Selected</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="seat-legend reserved"></div>
                            <span class="ml-2 text-sm text-gray">Reserved</span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="seat-legend booked"></div>
                            <span class="ml-2 text-sm text-gray">Booked</span>
                        </div>
                    </div>
                </div>

                <!-- Seat Grid -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Current Seat Layout</h3>
                        <div id="layout-info" class="text-sm text-gray">
                            <span>Total: <span id="total-seats">{{ $seats->count() }}</span> seats</span> |
                            <span>Available: <span id="available-count">{{ $seats->where('status', 'available')->count() }}</span></span> |
                            <span>Reserved: <span id="reserved-count">{{ $seats->where('status', 'reserved')->count() }}</span></span> |
                            <span>Booked: <span id="booked-count">{{ $seats->where('status', 'booked')->count() }}</span></span>
                        </div>
                    </div>

                    <div id="seat-grid" class="inline-block border-2 border-dashed border-gray-300 rounded-lg p-4">
                        @if($seats->count() > 0)
                            <!-- Grid will be generated by JavaScript -->
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chair fa-3x mb-4"></i>
                                <p>No seats found for this package.</p>
                                <p>Please create seats first.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center space-x-4">
                    <button id="save-changes-btn"
                        class="btn btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                    
                    <a href="{{ url()->previous() }}" 
                        class="btn btn-secondary px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="loading-overlay"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-lg p-6 flex items-center">
                    <svg class="animate-spin h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-gray-700">Saving changes...</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        class SeatLayoutEditor {
            constructor() {
                this.packageId = {{ $packageId }};
                this.seats = @json($seats);
                this.seatMap = new Map();
                this.currentStatus = 'available';
                this.selectedSeats = new Set();
                this.modifiedSeats = new Map();

                this.initializeElements();
                this.processSeatData();
                this.renderSeatGrid();
                this.bindEvents();
                this.updateCounts();
            }

            initializeElements() {
                this.elements = {
                    seatGrid: document.getElementById('seat-grid'),
                    saveBtn: document.getElementById('save-changes-btn'),
                    addSeatBtn: document.getElementById('add-seat-btn'),
                    removeSeatBtn: document.getElementById('remove-seat-btn'),
                    clearSelectionBtn: document.getElementById('clear-selection-btn'),
                    loadingOverlay: document.getElementById('loading-overlay'),
                    totalSeats: document.getElementById('total-seats'),
                    availableCount: document.getElementById('available-count'),
                    reservedCount: document.getElementById('reserved-count'),
                    bookedCount: document.getElementById('booked-count'),
                    statusButtons: document.querySelectorAll('.status-btn')
                };
            }

            processSeatData() {
                this.seats.forEach(seat => {
                    const key = `${seat.row_label}-${seat.seat_number}`;
                    this.seatMap.set(key, seat);
                });
            }

            bindEvents() {
                // Status button selection
                this.elements.statusButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.elements.statusButtons.forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        this.currentStatus = btn.dataset.status;
                    });
                });

                // Action buttons
                this.elements.saveBtn.addEventListener('click', () => this.saveChanges());
                this.elements.addSeatBtn.addEventListener('click', () => this.showAddSeatDialog());
                this.elements.removeSeatBtn.addEventListener('click', () => this.removeSelectedSeats());
                this.elements.clearSelectionBtn.addEventListener('click', () => this.clearSelection());
            }

            renderSeatGrid() {
                if (this.seats.length === 0) {
                    return;
                }

                // Calculate grid dimensions
                const rows = [...new Set(this.seats.map(s => s.row_label))].sort();
                const maxCols = Math.max(...this.seats.map(s => parseInt(s.seat_number)));

                this.elements.seatGrid.innerHTML = '';
                this.elements.seatGrid.style.gridTemplateColumns = `40px repeat(${maxCols}, 35px)`;

                // Render each row
                rows.forEach(rowLabel => {
                    // Add row label
                    const rowLabelEl = document.createElement('div');
                    rowLabelEl.classList.add('row-label');
                    rowLabelEl.textContent = rowLabel;
                    this.elements.seatGrid.appendChild(rowLabelEl);

                    // Add seats for this row
                    for (let col = 1; col <= maxCols; col++) {
                        const seatKey = `${rowLabel}-${col}`;
                        const seat = this.seatMap.get(seatKey);

                        if (seat) {
                            const seatElement = this.createSeatElement(seat);
                            this.elements.seatGrid.appendChild(seatElement);
                        } else {
                            // Empty space
                            const emptySeat = document.createElement('div');
                            emptySeat.style.width = '35px';
                            emptySeat.style.height = '35px';
                            this.elements.seatGrid.appendChild(emptySeat);
                        }
                    }
                });
            }

            createSeatElement(seat) {
                const seatElement = document.createElement('div');
                seatElement.classList.add('seat', seat.status);
                seatElement.dataset.seatId = seat.id;
                seatElement.dataset.row = seat.row_label;
                seatElement.dataset.number = seat.seat_number;
                seatElement.textContent = seat.seat_number;
                seatElement.title = `Seat ${seat.row_label}${seat.seat_number} - ${seat.status}`;

                // Don't allow booked seats to be modified
                if (seat.status !== 'booked') {
                    seatElement.addEventListener('click', () => this.toggleSeat(seatElement, seat));
                }

                return seatElement;
            }

            toggleSeat(seatElement, seat) {
                const seatKey = `${seat.row_label}-${seat.seat_number}`;

                if (this.selectedSeats.has(seatKey)) {
                    // Deselect
                    this.selectedSeats.delete(seatKey);
                    seatElement.classList.remove('selected');
                } else {
                    // Select and apply current status
                    this.selectedSeats.add(seatKey);
                    seatElement.classList.add('selected');
                    
                    if (this.currentStatus !== 'selected') {
                        this.applySeatStatus(seatElement, seat, this.currentStatus);
                    }
                }
            }

            applySeatStatus(seatElement, seat, status) {
                // Remove existing status classes
                seatElement.classList.remove('available', 'reserved', 'booked');
                
                // Add new status
                seatElement.classList.add(status);
                
                // Update seat data
                seat.status = status;
                const seatKey = `${seat.row_label}-${seat.seat_number}`;
                this.modifiedSeats.set(seatKey, seat);
                
                // Update title
                seatElement.title = `Seat ${seat.row_label}${seat.seat_number} - ${status}`;
                
                this.updateCounts();
            }

            clearSelection() {
                this.selectedSeats.clear();
                document.querySelectorAll('.seat.selected').forEach(seat => {
                    seat.classList.remove('selected');
                });
            }

            removeSelectedSeats() {
                if (this.selectedSeats.size === 0) {
                    this.showAlert('Please select seats to remove', 'warning');
                    return;
                }

                if (!confirm(`Are you sure you want to remove ${this.selectedSeats.size} selected seats?`)) {
                    return;
                }

                this.selectedSeats.forEach(seatKey => {
                    const seat = this.seatMap.get(seatKey);
                    if (seat && seat.status !== 'booked') {
                        // Mark for deletion
                        seat._delete = true;
                        this.modifiedSeats.set(seatKey, seat);
                        
                        // Remove from display
                        const seatElement = document.querySelector(`[data-seat-id="${seat.id}"]`);
                        if (seatElement) {
                            seatElement.remove();
                        }
                    }
                });

                this.clearSelection();
                this.updateCounts();
            }

            showAddSeatDialog() {
                const rowLabel = prompt('Enter row label (e.g., A, B, C):');
                if (!rowLabel) return;

                const seatNumber = prompt('Enter seat number:');
                if (!seatNumber) return;

                const seatKey = `${rowLabel}-${seatNumber}`;
                
                if (this.seatMap.has(seatKey)) {
                    this.showAlert('Seat already exists at this position', 'warning');
                    return;
                }

                // Create new seat
                const newSeat = {
                    id: null, // Will be null for new seats
                    row_label: rowLabel,
                    seat_number: seatNumber,
                    position_x: parseInt(seatNumber) - 1,
                    position_y: rowLabel.charCodeAt(0) - 65,
                    status: 'available',
                    _new: true
                };

                this.seatMap.set(seatKey, newSeat);
                this.modifiedSeats.set(seatKey, newSeat);
                
                // Re-render grid
                this.renderSeatGrid();
                this.updateCounts();
                
                this.showAlert('New seat added successfully', 'success');
            }

            updateCounts() {
                let available = 0, reserved = 0, booked = 0, total = 0;

                this.seatMap.forEach(seat => {
                    if (!seat._delete) {
                        total++;
                        switch (seat.status) {
                            case 'available': available++; break;
                            case 'reserved': reserved++; break;
                            case 'booked': booked++; break;
                        }
                    }
                });

                this.elements.totalSeats.textContent = total;
                this.elements.availableCount.textContent = available;
                this.elements.reservedCount.textContent = reserved;
                this.elements.bookedCount.textContent = booked;
            }

            prepareSeatData() {
                const seatsData = [];

                this.modifiedSeats.forEach(seat => {
                    if (seat._delete) {
                        // Skip deleted seats - they won't be in the update
                        return;
                    }

                    seatsData.push({
                        id: seat.id, // null for new seats
                        row_label: seat.row_label,
                        seat_number: seat.seat_number,
                        position_x: seat.position_x,
                        position_y: seat.position_y,
                        status: seat.status
                    });
                });

                // Include unmodified seats as well
                this.seatMap.forEach(seat => {
                    const seatKey = `${seat.row_label}-${seat.seat_number}`;
                    if (!this.modifiedSeats.has(seatKey) && !seat._delete) {
                        seatsData.push({
                            id: seat.id,
                            row_label: seat.row_label,
                            seat_number: seat.seat_number,
                            position_x: seat.position_x,
                            position_y: seat.position_y,
                            status: seat.status
                        });
                    }
                });

                return { seats: seatsData };
            }

            async saveChanges() {
                if (this.modifiedSeats.size === 0) {
                    this.showAlert('No changes to save', 'info');
                    return;
                }

                const payload = this.prepareSeatData();
                console.log('Saving payload:', payload);

                try {
                    this.showLoading(true);

                    const response = await fetch(`/organizer/event/package/${this.packageId}/seats`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.showAlert('Seat layout updated successfully!', 'success');
                        console.log(data);
                        
                        // Clear modified seats
                        this.modifiedSeats.clear();
                        this.clearSelection();
                        
                        // Optionally reload the page or redirect
                        // setTimeout(() => {
                        //     window.location.reload();
                        // }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to update seats');
                    }
                } catch (error) {
                    console.error('Save error:', error);
                    this.showAlert(error.message || 'Failed to save changes. Please try again.', 'error');
                } finally {
                    this.showLoading(false);
                }
            }

            showLoading(show) {
                this.elements.loadingOverlay.classList.toggle('hidden', !show);
            }

            showAlert(message, type = 'info') {
                const typeClassMap = {
                    success: 'success',
                    error: 'danger',
                    warning: 'warning',
                    info: 'info'
                };

                const alertType = typeClassMap[type] || 'info';

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${alertType} alert-dismissible fade show position-fixed top-0 end-0 m-4`;
                alertDiv.setAttribute('role', 'alert');
                alertDiv.style.minWidth = '300px';
                alertDiv.style.zIndex = '1055';

                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                document.body.appendChild(alertDiv);

                // Auto-close after 3 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        const bsAlert = new bootstrap.Alert(alertDiv);
                        bsAlert.close();
                    }
                }, 3000);
            }
        }

        // Initialize the seat layout editor when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new SeatLayoutEditor();
        });
    </script>
@endpush
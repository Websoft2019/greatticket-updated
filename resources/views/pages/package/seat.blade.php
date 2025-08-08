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

        .seat.selected::before {
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
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Seat Layout Designer</h1>
                <p class="text-gray-600">Create and customize your venue seating arrangement</p>
            </div>

            <!-- Configuration Card -->
            <div class="bg-white rounded-2xl shadow-xl px-4 py-2 mb-3">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Layout Configuration
                </h2>

                <form id="seat-config-form" class="row">
                    <div class="d-none">
                        <label class="text-sm font-medium text-gray-700">Package ID</label>
                        <input type="number" id="package_id" name="package_id" value="{{ $packageId }}"
                            class="form-control" placeholder="Enter package ID" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Rows (A-Z)</label>
                        <input type="number" id="max_rows" min="1" max="26" value="5"
                            class="form-control" placeholder="Max 26 rows" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Columns (1-50)</label>
                        <input type="number" id="max_cols" min="1" max="50" value="5"
                            class="form-control" placeholder="Max 50 columns" required>
                    </div>
                </form>

                <div class="flex flex-wrap gap-4 mt-2">
                    <button type="button" id="generate-layout"
                        class="btn btn-primary text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                            </path>
                        </svg>
                        Generate Layout
                    </button>

                    <button type="button" id="select-all"
                        class="btn btn-success text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 hidden">
                        Select All
                    </button>

                    <button type="button" id="clear-all"
                        class="btn btn-danger px-6 py-3 rounded-lg font-medium transition-all duration-200 hidden">
                        Clear All
                    </button>
                </div>
            </div>

            <!-- Seat Grid Container -->
            <div id="seat-container" class="hidden">
                <!-- Legend -->
                <div class="bg-white rounded-2xl shadow-xl px-4 py-2 mb-3">
                    <h3 class="text-lg font-semibold text-gray mb-4">Legend</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="seat-legend available"></div>
                            <span class="ml-2 text-sm text-gray">Available</span>
                        </div>
                        <div class="col-md-6">
                            <div class="seat-legend selected"></div>
                            <span class="ml-2 text-sm text-gray">Selected</span>
                        </div>
                    </div>
                </div>

                <!-- Seat Grid -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Seat Layout</h3>
                        <div id="selection-info" class="text-sm text-gray">
                            <span id="selected-count">0</span> seats selected
                        </div>
                    </div>

                    <div id="seat-grid" class="inline-block border-2 border-dashed border-gray-300 rounded-lg p-4"></div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button id="submit-seats"
                        class="btn btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Seat Layout
                    </button>
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
                    <span class="text-gray-700">Processing...</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        class SeatLayoutManager {
            constructor() {
                this.selectedSeats = new Set();
                this.totalRows = 0;
                this.totalCols = 0;
                this.packageId = '';

                this.initializeElements();
                this.bindEvents();
            }

            initializeElements() {
                this.elements = {
                    seatGrid: document.getElementById('seat-grid'),
                    seatContainer: document.getElementById('seat-container'),
                    generateBtn: document.getElementById('generate-layout'),
                    selectAllBtn: document.getElementById('select-all'),
                    clearAllBtn: document.getElementById('clear-all'),
                    submitBtn: document.getElementById('submit-seats'),
                    packageIdInput: document.getElementById('package_id'),
                    maxRowsInput: document.getElementById('max_rows'),
                    maxColsInput: document.getElementById('max_cols'),
                    selectedCount: document.getElementById('selected-count'),
                    loadingOverlay: document.getElementById('loading-overlay')
                };
            }

            bindEvents() {
                this.elements.generateBtn.addEventListener('click', () => this.generateLayout());
                this.elements.selectAllBtn.addEventListener('click', () => this.selectAllSeats());
                this.elements.clearAllBtn.addEventListener('click', () => this.clearAllSeats());
                this.elements.submitBtn.addEventListener('click', () => this.submitSeats());
            }

            validateInputs() {
                const packageId = parseInt(this.elements.packageIdInput.value);
                const rows = parseInt(this.elements.maxRowsInput.value);
                const cols = parseInt(this.elements.maxColsInput.value);

                if (!packageId || packageId < 1) {
                    this.showAlert('Please enter a valid package ID', 'error');
                    return false;
                }

                if (!rows || rows < 1 || rows > 26) {
                    console.log('row')
                    this.showAlert('Please enter rows between 1 and 26', 'error');
                    return false;
                }

                if (!cols || cols < 1 || cols > 50) {
                    console.log('column')
                    this.showAlert('Please enter columns between 1 and 50', 'error');
                    return false;
                }

                return {
                    packageId,
                    rows,
                    cols
                };
            }

            generateLayout() {
                const validation = this.validateInputs();
                if (!validation) return;

                const {
                    packageId,
                    rows,
                    cols
                } = validation;

                this.packageId = packageId;
                this.totalRows = rows;
                this.totalCols = cols;
                this.selectedSeats.clear();

                this.renderSeatGrid();
                this.updateUI();
            }

            renderSeatGrid() {
                this.elements.seatGrid.innerHTML = '';
                this.elements.seatGrid.style.gridTemplateColumns = `40px repeat(${this.totalCols}, 35px)`;

                for (let row = 0; row < this.totalRows; row++) {
                    const rowLabel = String.fromCharCode(65 + row);

                    // Add row label
                    const rowLabelEl = document.createElement('div');
                    rowLabelEl.classList.add('row-label');
                    rowLabelEl.textContent = rowLabel;
                    this.elements.seatGrid.appendChild(rowLabelEl);

                    // Add seats for this row
                    for (let col = 1; col <= this.totalCols; col++) {
                        const seat = this.createSeatElement(rowLabel, col, row);
                        this.elements.seatGrid.appendChild(seat);
                    }
                }

                this.elements.seatContainer.classList.remove('hidden');
                this.elements.selectAllBtn.classList.remove('hidden');
                this.elements.clearAllBtn.classList.remove('hidden');
            }

            createSeatElement(rowLabel, seatNumber, rowIndex) {
                const seat = document.createElement('div');
                seat.classList.add('seat');
                seat.dataset.row = rowLabel;
                seat.dataset.number = seatNumber;
                seat.dataset.rowIndex = rowIndex;
                seat.textContent = seatNumber;
                seat.title = `Seat ${rowLabel}${seatNumber}`;

                seat.addEventListener('click', () => this.toggleSeat(seat, rowLabel, seatNumber, rowIndex));

                return seat;
            }

            toggleSeat(seatElement, rowLabel, seatNumber, rowIndex) {
                const seatKey = `${rowLabel}-${seatNumber}`;

                if (this.selectedSeats.has(seatKey)) {
                    this.selectedSeats.delete(seatKey);
                    seatElement.classList.remove('selected');
                } else {
                    this.selectedSeats.add(seatKey);
                    seatElement.classList.add('selected');
                }

                this.updateSelectionCount();
            }

            selectAllSeats() {
                const seats = this.elements.seatGrid.querySelectorAll('.seat');
                seats.forEach(seat => {
                    const rowLabel = seat.dataset.row;
                    const seatNumber = seat.dataset.number;
                    const seatKey = `${rowLabel}-${seatNumber}`;

                    this.selectedSeats.add(seatKey);
                    seat.classList.add('selected');
                });

                this.updateSelectionCount();
            }

            clearAllSeats() {
                const seats = this.elements.seatGrid.querySelectorAll('.seat');
                seats.forEach(seat => seat.classList.remove('selected'));
                this.selectedSeats.clear();
                this.updateSelectionCount();
            }

            updateSelectionCount() {
                this.elements.selectedCount.textContent = this.selectedSeats.size;
            }

            updateUI() {
                this.updateSelectionCount();
            }

            prepareSeatData() {
                const seatsData = [];

                this.selectedSeats.forEach(seatKey => {
                    const [rowLabel, seatNumber] = seatKey.split('-');
                    const rowIndex = rowLabel.charCodeAt(0) - 65;

                    seatsData.push({
                        row_label: rowLabel,
                        seat_number: seatNumber,
                        position_x: parseInt(seatNumber) - 1,
                        position_y: rowIndex,
                        status: 'available'
                    });
                });

                return {
                    package_id: this.packageId,
                    seats: seatsData,
                    layout_info: {
                        total_rows: this.totalRows,
                        total_cols: this.totalCols,
                        total_selected: seatsData.length
                    }
                };
            }

            async submitSeats() {
                if (this.selectedSeats.size === 0) {
                    this.showAlert('Please select at least one seat', 'warning');
                    return;
                }

                if (!this.packageId) {
                    this.showAlert('Package ID is required', 'error');
                    return;
                }

                const payload = this.prepareSeatData();
                console.log(payload);

                try {
                    this.showLoading(true);

                    const response = await fetch('{{ route('seats.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.showAlert('Seat layout saved successfully!', 'success');
                        // this.showAlert(data, 'success');
                        console.log(data);
                        // setTimeout(() => window.location.reload(), 1500);
                    } else {
                        throw new Error(data.message || 'Failed to save seats');
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    this.showAlert(error.message || 'Failed to submit seats. Please try again.', 'error');
                } finally {
                    this.showLoading(false);
                }
            }

            showLoading(show) {
                this.elements.loadingOverlay.classList.toggle('hidden', !show);
            }

            showAlert(message, type = 'info') {
                alert(message);

                const typeClassMap = {
                    success: 'success',
                    error: 'danger', // Bootstrap uses 'danger'
                    warning: 'warning',
                    info: 'info'
                };

                const alertType = typeClassMap[type] || 'info';

                // Create a more sophisticated alert system
                const alertDiv = document.createElement('div');
                alertDiv.className =
                    `alert alert-${alertType} alert-dismissible fade show position-fixed top-0 end-0 m-4 z-50`;
                alertDiv.setAttribute('role', 'alert');
                alertDiv.style.minWidth = '300px';
                alertDiv.style.zIndex = '1055'; // Bootstrap modal z-index is 1050

                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                document.body.appendChild(alertDiv);

                const colors = {
                    success: 'bg-green-500 text-white',
                    error: 'bg-red-500 text-white',
                    warning: 'bg-yellow-500 text-black',
                    info: 'bg-blue-500 text-white'
                };

                alertDiv.className += ` ${colors[type] || colors.info}`;
                alertDiv.textContent = message;

                document.body.appendChild(alertDiv);

                // Auto-close after 3 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 3000);
            }
        }

        // Initialize the seat layout manager when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new SeatLayoutManager();
        });
    </script>
@endpush

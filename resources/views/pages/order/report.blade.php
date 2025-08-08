@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Order Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders3</h6>

                </div>

                <div class="container-fluid">
                    <div class="row">
                        @foreach ($eventPackagesData as $i => $eventData)
                            <div class="col-12 mb-3">
                                <h3>Event: {{ $eventData['event']->title }}</h3>
                                <div class="mb-2">
                                    <button class="btn btn-danger btn-sm download-pdf"
                                        data-event-id = "{{ $eventData['event']->id }}"
                                        data-chart-id="chart-line-{{ $i }}">
                                        Download PDF
                                    </button>
                                </div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Package Title</th>
                                            <th>Capacity</th>
                                            <th>Quantity</th>
                                            <th>Total Cost</th>
                                            <th> Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($eventData['packagesData'] as $index => $packageData)
                                            <tr>
                                                <td>
                                                    <a
                                                        href="{{ route('organizer.order.testList', $packageData['package']->id) }}">
                                                        {{ $index + 1 }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ route('organizer.order.testList', $packageData['package']->id) }}">
                                                        {{ $packageData['package']->title }} @ RM{{ $packageData['package']->actual_cost }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ route('organizer.order.testList', $packageData['package']->id) }}">
                                                        {{ $packageData['package']->capacity }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ route('organizer.order.testList', $packageData['package']->id) }}">
                                                        {{ $packageData['quantity'] }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ route('organizer.order.testList', $packageData['package']->id) }}">
                                                        {{ $packageData['orderTotal'] }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($packageData['package']->status == 0)
                                                        <span style="color:red">Unactive</span>
                                                    @else
                                                        <span style="color:green">Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Total Event Cost:</strong></td>
                                            <td> {{ $eventData['totalOrderCost'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-4" id="event-charts">
                                <div class="col-12 mb-4">
                                    <div class="card z-index-2 h-100">
                                        <div class="card-header pb-0 pt-3 bg-transparent">
                                            <h6 class="text-capitalize">Orders</h6>
                                            <p class="text-sm mb-0">
                                                <i class="fa fa-arrow-up text-success"></i>
                                                <span class="font-weight-bold">{{ $eventData['event']->title }}</span>
                                            </p>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="chart">
                                                <canvas id="chart-line-{{ $i }}" class="chart-canvas"
                                                    height="300"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@php
    $events = $eventList;
@endphp

@push('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        const charts = {};
        const events = @json($events);

        events.forEach((event, index) => {
            const ctx = document.getElementById(`chart-line-${index}`);
            if (!ctx) return; // Safety check

            const context = ctx.getContext('2d');
            const gradientStroke = context.createLinearGradient(0, 230, 0, 50);
            gradientStroke.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
            gradientStroke.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
            gradientStroke.addColorStop(0, 'rgba(251, 99, 64, 0)');

            charts[`chart-line-${index}`] = new Chart(context, {
                type: "line",
                data: {
                    labels: event.dates,
                    datasets: [{
                        label: "Daily Orders",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 3,
                        borderColor: "#fb6340",
                        backgroundColor: gradientStroke,
                        borderWidth: 3,
                        fill: true,
                        data: event.orderCounts,
                        maxBarThickness: 6
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            // Add y-axis title here
                            title: {
                                display: true,
                                text: 'Order Count', // Your y-axis label
                                color: '#ccc', // Match the tick color
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                }
                            },
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#ccc',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                        x: {
                            title: { // Optional: Add x-axis title if needed
                                display: true,
                                text: 'Dates', // Already using dates as labels
                                color: '#ccc',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                }
                            },
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#ccc',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                }
            });

        });

        // PDF download handler
        document.querySelectorAll(".download-pdf").forEach(button=>{
            button.addEventListener("click", async() => {
                const eventId = button.dataset.eventId;
                const chartId = button.dataset.chartId;
                const chart = charts[chartId];

                // Temporarily increase chart size for better quality
                const originalWidth = chart.canvas.style.width;
                const originalHeight = chart.canvas.style.height;
                chart.canvas.style.width = '1600px';
                chart.canvas.style.height = '900px';
                chart.resize();

                // Get chart as base64 image 
                const chartImage = chart.toBase64Image('image/png', 1);

                // Reset to original size
                chart.canvas.style.width = originalWidth;
                chart.canvas.style.height = originalHeight;
                chart.resize();

                // Submit form with hidden inputs
                const form = document.createElement('form');
                form.method = 'POST';
                form.setAttribute('enctype', "multipart/form-data");
                form.action = "{{route('organizer.event.export.pdf')}}";

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";

                const imageInput = document.createElement('input');
                imageInput.type = 'hidden';
                imageInput.name = 'chart_image';
                imageInput.value = chartImage;

                const eventIdInput = document.createElement('input');
                eventIdInput.type = 'hidden';
                eventIdInput.name = 'event_id';
                eventIdInput.value = eventId;

                form.appendChild(csrfToken);
                form.appendChild(imageInput);
                form.appendChild(eventIdInput);
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@endpush

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Order Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders List</h6>

                </div>
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-uppercase text-xs font-weight-bolder ">SN
                                        </th> --}}
                                        <th class="text-uppercase text-xs font-weight-bolder ">Name
                                        </th>
                                        {{-- <th class="text-uppercase text-xs font-weight-bolder ">Email --}}
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Quantity
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Total Cost
                                        </th>
                                        {{-- <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Coupon
                                        </th> --}}
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Payment Status</th>
                                        <th>Date</th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderPackages as $orderId => $packages)
                                        @foreach ($packages as $index => $order)
                                            <tr>
                                                {{-- <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td> --}}
                                                <td class="text-sm">
                                                   #{{ $order?->order?->id }} <strong>{{ $order?->order?->name }}</strong><br />
                                                    {{ $order?->order?->email }} <br />
                                                    
                                                    @php
                                                        $cleanPhone = Illuminate\Support\Str::replace('-', '', $order?->order?->phone);
                                                    @endphp
                                                   <a href="https://wa.me/+60{{ $cleanPhone }}" target="_bank">{{$order?->order?->phone}}</a> 
                                                </td>
                                                {{-- <td class="text-sm"></td> --}}
                                                <td class="text-sm">{{ $order?->quantity }}</td>
                                                <td class="align-middle text-center text-sm">RM
                                                    {{ $order?->order?->carttotalamount - $order?->order?->discount_amount }}<br />
                                                    RM {{ $order?->order?->discount_amount ?? '0.00' }} | {{$order?->order?->coupon?->code ?? 'Not used'}}
                                                </td>

                                                {{-- Show discount only once for the first package in the group --}}
                                                {{-- @if ($index === 0)
                                                    <td class="align-middle text-center text-sm"
                                                        rowspan="{{ $packages->count() }}">
                                                        
                                                    </td>
                                                @endif --}}

                                                <td class="align-middle text-center text-sm">
                                                    <p class="text-sm mb-0 {{ $order?->order?->paymentstatus == 'Y' ? 'text-success' : ($order?->order?->paymentstatus == 'R' ? 'text-warning' : 'text-danger')  }}">
                                                        {{ $order?->order?->paymentstatus == 'Y' ? 'Success' : ($order?->order?->paymentstatus == 'R' ? 'Reserved' : 'Failed') }}
                                                        <br />{{$order?->order?->payerid}}
                                                    </p>
                                                </td>

                                                <td class="align-middle text-center text-sm">
                                                    <p class="text-sm mb-0">{{ $order?->created_at->format('Y-m-d') }}</p>
                                                </td>

                                                <td class="">
                                                    <div class="d-flex px-3 py-1">
                                                        @if ($order->order)
                                                            <a class="btn btn-success px-3 py-2 btn-sm me-2"
                                                                href="{{ route('organizer.order.details', $order?->order?->id) }}"
                                                                role="button" title="Edit">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                        @endif
                                                        <a href="#"
                                                        class="btn btn-warning px-3 py-2 btn-sm me-2"
                                                        title="Send Email"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#emailModal"
                                                        data-order-id="{{ $order?->order?->id }}">
                                                        <i class="fa-solid fa-envelope"></i>
                                                        </a>

                                                        @if (
                                                            $order->order &&
                                                                ($order?->order?->paymentstatus == 'N' || $order?->ticketUsers?->where('checkedin', true)->isEmpty()))
                                                            <form
                                                                action="{{ route('organizer.order.destroy', $order?->order?->id) }}"
                                                                class="d-inline-block" method="post"
                                                                onsubmit="return confirm('Are you sure ?')">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" title="Delete"
                                                                    class="btn btn-danger px-3 py-2 btn-sm">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">Re-send Ticket via Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="emailForm" action="{{route('organizer.postCheckOrderAndSendEmail')}}" method="POST" class="p-4">
        @csrf
        <div class="modal-body">
          <input type="hidden" id="orderId" name="orderId">

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="customerName" class="form-label">Name</label>
                <input type="text" class="form-control" id="customerName" name="name" placeholder="Customer name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="customerEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="customerEmail" name="email" placeholder="Customer email" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="payerId" class="form-label">Payer ID</label>
                <input type="text" class="form-control" id="payerId" name="payer_id" placeholder="Payer ID" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="paymentMethod" class="form-label">Payment Method</label>
                <input type="text" class="form-control" id="paymentMethod" name="payment_method" placeholder="Payment Method" required>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Ticket</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const emailModalEl = document.getElementById('emailModal');

  emailModalEl.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const orderId = button.getAttribute('data-order-id');

    const customerNameInput = emailModalEl.querySelector('#customerName');
    const customerEmailInput = emailModalEl.querySelector('#customerEmail');
    const payerIdInput = emailModalEl.querySelector('#payerId');
    const paymentMethodInput = emailModalEl.querySelector('#paymentMethod');
    const orderIdInput = emailModalEl.querySelector('#orderId');

    // Reset all fields
    customerNameInput.value = '';
    customerEmailInput.value = '';
    payerIdInput.value = '';
    paymentMethodInput.value = '';
    orderIdInput.value = orderId;

    // Load order data via AJAX
    fetch(`/organizer/ajax/orders/${orderId}/details`)
      .then(response => {
        if (!response.ok) throw new Error('Failed to fetch order details');
        return response.json();
      })
      .then(data => {
        customerNameInput.value = data.name || '';
        customerEmailInput.value = data.email || '';
        payerIdInput.value = data.payer_id || '';
        paymentMethodInput.value = data.payment_method || '';
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Could not load order details.');
      });
  });

  // Avoid focus conflict warning when modal is hidden
  emailModalEl.addEventListener('hide.bs.modal', function () {
    if (document.activeElement) document.activeElement.blur();
  });
});
</script>





@stop

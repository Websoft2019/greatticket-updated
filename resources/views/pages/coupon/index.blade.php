@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Coupon Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Coupon</h6>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-bs-whatever="@mdo">Create</button>
                        @if(Session::has('error_message'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
                            <div class="me-auto text-white">{{ Session::get('error_message') }}</div>
                        </div>
                        @endif

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('coupons.store') }}" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Create Coupon</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf
                                        <div class="mb-3">
                                            <label for="coupontype" class="col-form-label">Coupon Type:</label>
                                            <select class="form-control" name="coupontype" id="coupontype" required>
                                                <option value="percentage">Percentage</option>
                                                <option value="flat"> Flat Cost</option>
                                            </select>
                                            @error('coupontype')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="coupon" class="col-form-label">Coupon:</label>
                                            <input type="text" class="form-control" name="code" id="coupon" required>
                                            @error('code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="cost" class="col-form-label">Coupon Rate:</label>
                                            <input type="text" class="form-control" name="cost" id="cost" required>
                                            @error('cost')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="expire_at" class="col-form-label">Expire At:</label>
                                            <input type="date" class="form-control" name="expire_at" id="expire_at" required>
                                            @error('expire_at')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="couponlimitation" class="col-form-label">Coupon Limitation:</label>
                                            <small>put empty if no limitation</small>
                                            <input type="number" class="form-control" name="couponlimitation" id="couponlimitation">
                                            @error('couponlimitation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive px-2">
                        <table id="myTable" class="table py-1 align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Coupon
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Cost
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        No. of times used
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Expire At
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Limitation
                                    </th>

                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $loop->iteration }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $coupon->code }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">
                                                @if($coupon->coupontype == 'flat')
                                                    RM {{ $coupon->cost }}
                                                @else
                                                    {{ $coupon->cost }}%
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $coupon->orders_count }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $coupon->expire_at }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">
                                                @if($coupon->couponlimitation == Null)
                                                    No Limitation
                                                @else
                                                    {{ $coupon->couponlimitation }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">

                                                <button type="button" class="btn btn-sm btn-primary me-1"
                                                    data-bs-toggle="modal" data-bs-target="#coupon{{ $coupon->id }}"
                                                    data-bs-whatever="@mdo">Edit</button>

                                                <form action="{{ route('coupons.delete', $coupon->id) }}"
                                                    onsubmit="return confirm('Are you sure ?')" method="post"
                                                    class="d-inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>

                                                </form>
                                            </div>
                                        </td>

                                        <div class="modal fade" id="coupon{{ $coupon->id }}" tabindex="-1"
                                            aria-labelledby="coupon{{ $coupon->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('coupons.update', $coupon->id) }}"
                                                        method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="coupon{{ $coupon->id }}Label">
                                                                Edit Coupon
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="coupon"
                                                                    class="col-form-label">Coupon:</label>
                                                                <input type="text" class="form-control" name="code"
                                                                    value="{{ $coupon->code }}" id="coupon">
                                                                @error('code')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="coupontype" class="col-form-label">Coupon Type:</label>
                                                                <select class="form-control" name="coupontype" id="coupontype" required>
                                                                    <option value="percentage" @selected($coupon->coupontype == 'percentage')>Percentage</option>
                                                                    <option value="flat" @selected($coupon->coupontype == 'flat')> Flat Cost</option>
                                                                </select>
                                                                @error('coupontype')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="cost" class="col-form-label">Coupon rate:</label>
                                                                <input type="text" class="form-control" name="cost"
                                                                    value="{{ $coupon->cost }}" id="cost">
                                                                @error('cost')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="couponlimitation" class="col-form-label">Coupon Limitation:</label>
                                                                <small>put empty if no limitation</small>
                                                                <input type="number" class="form-control" name="couponlimitation" value="{{$coupon->couponlimitation}}" id="couponlimitation">
                                                                @error('couponlimitation')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="expire_at" class="col-form-label">Expire
                                                                    At:</label>
                                                                <input type="date" class="form-control"
                                                                    name="expire_at" value="{{ $coupon->expire_at }}"
                                                                    id="expire_at">
                                                                @error('expire_at')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

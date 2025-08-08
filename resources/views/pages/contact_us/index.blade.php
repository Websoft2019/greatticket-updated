@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Contact Us Messages'])
    
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="container-fluid">
                    <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                        <div class="table-responsive px-2">
                            <table id="myTable" class="table py-1 align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs font-weight-bolder ">SN
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">name
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Email
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                            Phone
                                        </th>
                                        <th class="text-uppercase text-xs font-weight-bolder ">Subject
                                        </th>
     
                                        <th class="text-uppercase text-xs font-weight-bolder  ps-2">
                                           Message
                                        </th>
                                        <th class="text-center text-uppercase text-xs font-weight-bolder ">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-sm">
                                                {{ $message->name }}
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ $message->email }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0">{{ $message->contact }}</p>
                                            </td>

                                            <td class="text-sm">
                                                <p class="text-sm mb-0">{{ $message->subject }}</p>
                                            </td>

                                            <td class="text-sm">
                                                <p class="text-sm mb-0">{{ $message->message }}</p>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                    <form action="{{route('admin.deleteContactUs',$message->id)}}" onsubmit="return confirm('Are you sure ? ')" class="d-inline-block" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" title="Delete" class="btn btn-danger px-3 py-2 btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

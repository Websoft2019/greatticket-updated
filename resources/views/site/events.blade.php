@extends('site.template')

@section('css')
    <style>
        .top {
            width: 100%;
            height: 300px;
            background-image: url('{{ asset('site/images/resource/slider.jpg') }}');
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination-link {
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .pagination-link:hover {
            background-color: #0056b3;
        }

        .disabled {
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #ddd;
            color: #999;
        }

        .current-page {
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
        }
    </style>

@endsection

@section('content')
    <div>
        <div class="top"></div>

        <section class="m-top">
            <div class="slider-bar ">
                <div class="container">
                    <div class="bottom-bar">
                        <div class="row">
                            <div class="col-md-12 column">
                                <div class="search-event">
                                    <h4>Search Event by Category</h4>
                                    <span>Don't Forget To Miss Event</span>
                                    <form action="{{ route('searchEvents') }}" method="GET">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <select name="category" class="form-control">
                                                    <option value="-1"
                                                        {{ isset($categoryId) && $categoryId == -1 ? 'selected' : '' }}>All
                                                    </option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ isset($categoryId) && $category->id == $categoryId ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="date" id="date" name="date"
                                                    value="{{ isset($date) ? $date : '' }}"
                                                    min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                                    class="form-control">
                                            </div>
                                            @csrf
                                            <div class="col-md-4">
                                                <input type="submit" value="Search"
                                                    style="width:60%; height:33px; margin-right:5px;" />
                                                <a name="" id="" class="btn btn-danger"
                                                    href="{{ route('getEvents') }}" role="button">Reset</a>

                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if ($events->count()) 
        <section>
            <div class="block gray">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 column">
                            <div class="title">

                                <h2><span>EVENTS</span></h2>

                            </div><!-- Title -->

                            <div class="remove-ext">
                                <div class="row">
                                    @if ($events->isEmpty())
                                        <h3 class="text-center text-danger">No events found.</h3>
                                    @else
                                        @foreach ($events as $event)
                                            @php
                                                $minpackage = App\Models\Package::where('event_id', $event->id)->min(
                                                    'cost',
                                                );
                                                $packagecount = App\Models\Package::where(
                                                    'event_id',
                                                    $event->id,
                                                )->count();
                                            @endphp
                                            @if ($packagecount >= 1)
                                                <div class="col-md-6">
                                                    <div class="package">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="package-img"><img
                                                                        src="{{ 'storage/' .$event->primary_photo }}"
                                                                        alt="" /></div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong><i>RM </i>{{ $minpackage }}</strong>
                                                                @if ($packagecount > 1)
                                                                    <span class="shortline">Package cost start
                                                                        from</span>
                                                                @else
                                                                    <span class="shortline">Event Cost</span>
                                                                @endif
                                                                <div class="package-info">
                                                                    <h3><a href=""
                                                                            title="">{{ $event->title }}</a></h3>
                                                                    <span><i class="fa fa-calendar-o"
                                                                            aria-hidden="true"></i>
                                                                        {{ $event->date->format('D d M, Y') }} <i
                                                                            class="fa fa-clock-o" aria-hidden="true"></i>
                                                                        {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                                        awards</span>
                                                                    <span><i class="fa fa-map-marker"
                                                                            aria-hidden="true"></i>
                                                                        {{ $event->vennue }}</span>
                                                                </div>
                                                                @if($event->date < now()->toDateString())
                                                                <a href="#"
                                                                title="">Completed</a>
                                                                @else
                                                                <a href="{{ route('getEventDetail', $event->slug) }}"
                                                                    title="">View Details</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div><!-- Package -->
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                
                                @if ($events->lastPage() > 1)
                                    <div class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($events->onFirstPage())
                                            <span class="disabled">&laquo; First</span>
                                        @else
                                            <a href="{{ $events->url(1) }}" class="pagination-link">&laquo; First</a>
                                        @endif

                                        {{-- Previous Page Link --}}
                                        @if ($events->previousPageUrl())
                                            <a href="{{ $events->previousPageUrl() }}" class="pagination-link">Previous</a>
                                        @else
                                            <span class="disabled">Previous</span>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                            @if ($page == $events->currentPage())
                                                <span class="current-page">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}"
                                                    class="pagination-link">{{ $page }}</a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($events->nextPageUrl())
                                            <a href="{{ $events->nextPageUrl() }}" class="pagination-link">Next</a>
                                        @else
                                            <span class="disabled">Next</span>
                                        @endif

                                        {{-- Last Page Link --}}
                                        @if ($events->hasMorePages())
                                            <a href="{{ $events->url($events->lastPage()) }}" class="pagination-link">Last
                                                &raquo;</a>
                                        @else
                                            <span class="disabled">Last</span>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
     @endif 

     @if($completed_events->count())
        <section>
            <div class="block gray">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 column">
                            <div class="title">

                                <h2>COMPLETED <span>EVENTS</span></h2>

                            </div><!-- Title -->

                            <div class="remove-ext">
                                <div class="row">
                                    @foreach ($completed_events as $event)
                                        @php
                                            $minpackage = App\Models\Package::where('event_id', $event->id)->min(
                                                'cost',
                                            );
                                            $packagecount = App\Models\Package::where('event_id', $event->id)->count();
                                        @endphp
                                        @if ($packagecount >= 1)
                                            <div class="col-md-6">
                                                <div class="package">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="package-img"><img
                                                                    src="{{ 'storage/' .$event->primary_photo }}"
                                                                    alt="" /></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i>RM </i>{{ $minpackage }}</strong>
                                                            @if ($packagecount > 1)
                                                                <span class="shortline">Package cost start from</span>
                                                            @else
                                                                <span class="shortline">Event Cost</span>
                                                            @endif
                                                            <div class="package-info">
                                                                <h3><a href=""
                                                                        title="">{{ $event->title }}</a></h3>
                                                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i>
                                                                    {{ $event->date->format('D d M, Y') }} <i
                                                                        class="fa fa-clock-o" aria-hidden="true"></i>
                                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                                    awards</span>
                                                                <span><i class="fa fa-map-marker" aria-hidden="true"></i>
                                                                    {{ $event->vennue }}</span>
                                                            </div>
                                                            <a href=""
                                                                title="">Completed</a>
                                                        </div>
                                                    </div>
                                                </div><!-- Package -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @stop

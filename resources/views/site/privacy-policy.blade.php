@extends('site.template')

@section('content')
    <section>
        <div class="block gray half-parallax blackish remove-bottom">
            <div style="background:url({{ asset('site/images/parallax8.jpg') }});" class="parallax"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="page-title">
                            <h1><span>Privacy Policy</span></h1>
                            <p>Please read our privacy policy carefully before using our services.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="block gray">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 column">
                        <div class="terms-content">
                            {{-- @foreach ($terms as $term) --}}
                                <div class="term-item">
                                    <h2>{{ $privacy->title }}</h2>
                                    <p>{!! nl2br($privacy->description) !!}</p> <!-- Converts newlines to <br> for better readability -->
                                </div>
                            {{-- @endforeach --}}
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('getHome') }}" class="btn btn-danger">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 9rem">
        <p class="fs-3"><a href="{{ url('/') }}"><i class="fas fa-arrow-left fs-4 me-2"
                    style="color: #38527E"></i></a>{{ $article->title }}</p>
        <div class="col-md-4">
            <img src="{{ asset('storage/' . $article->cover) }}" class="img-fluid" alt="">
        </div>
        <div class="col-md-12 mt-4">
            {!! $article->description !!}
        </div>
    </div>
@endsection

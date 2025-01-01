@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 9rem">
        <p class="fs-3">
            {{ $article->title }}</p>
        <div class="col-md-4">
            <img src="{{ asset('storage/' . $article->cover) }}" class="img-fluid" alt="">
        </div>
        <div class="col-md-12 mt-4">
            {!! $article->description !!}
        </div>
    </div>
@endsection

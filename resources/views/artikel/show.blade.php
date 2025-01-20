@extends('layouts.app')
@section('content')
<section style="margin-top: 6rem">
    <div class="container">
        <p class="fs-3 mb-4 text-navy">
            {{ $artikel->title }}</p>
        <div class="col-md-4">
            <img src="{{ asset('storage/' . $artikel->cover) }}" class="img-fluid" alt="">
        </div>
        <div class="col-md-12 mt-4">
            {!! $artikel->description !!}
        </div>
    </div>
</section>
@endsection

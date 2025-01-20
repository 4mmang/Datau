@extends('layouts.app')
@section('content')
    <section style="margin-top: 6rem">
        <div class="container">
            <div class="row mt-3 justify-content-center">
                <p class="fs-2 text-navy text-center">Daftar Artikel</p>
                <p class="text-center mb-5">Ayo perluas wawasan dengan banyak membaca!</p>
                @forelse ($artikel as $art)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow">
                            <a href="{{ route('artikel.show' , $art->id) }}" target="_blank"
                                style="text-decoration: none; color: #333;">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('storage/' . $art->cover) }}" alt="" class="img-fluid">
                                </div>
                                <div class="card-body">
                                    <h5 class="mt-3">{{ Str::limit($art->title, 30, '...') }}</h5>
                                    <p class="mt-3">by: Admin | 10 January 2024</p>
                                    {{-- <p class="small" style="overflow: hidden; text-overflow: ellipsis; white-space: normal;">
                                    {!! limitHtml($art->description, 100) !!}
                                </p> --}}
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                <p class="text-center">Belum ada artikel yang tersedia...</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection

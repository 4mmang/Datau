@extends('layouts.app')
@section('content')
    
<main>
    <div class="container login-container p-3" style="margin-top: 10rem; margin-bottom: 3rem">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card p-4">
                    Dataset yang Anda upload sedang diproses, Anda dapat mengupload dataset baru setelah dataset
                    yang Anda upload
                    sebelumnya
                    telah disetujui.
                    <p class="mt-2"><span class="badge bg-info">Status : pending</span></p>
                    <a href="{{ route('admin.dataset.index') }}" class="btn text-white mt-1"
                        style="background-color: #38527E; max-width: 12rem;">Lihat Dataset Saya</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
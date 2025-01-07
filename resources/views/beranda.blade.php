@extends('layouts.app')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container p-3" sty>
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>Selamat Datang</h1>
                    <h2 class="fs-5">Platform pengumpulan data Pusat Studi Artificial Intelligence Universitas Sulawesi
                        Barat, di mana setiap
                        kontribusi Anda berdampak positif
                        terhadap kemajuan
                        penelitian dan
                        pengembangan teknologi.</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="{{ route('dataset.index') }}" class="btn-get-started scrollto"><i class="fal fa-search"></i>
                            Temukan
                            Dataset</a>
                        <a href="{{ route('dataset.create') }}" class="btn-get-started scrollto ms-3"><i
                                class="fal fa-upload"></i> Upload Dataset</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img mt-4 mb-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="row justify-content-center">
                        <div class="col-md-5 text-center">
                            <div class="card p-4 animated">
                                <center>
                                    <img id="img-welcome" src="{{ asset('assets/img/Universitas_Sulawesi_Barat.jpg') }}"
                                        class="img-fluid" alt="">
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero -->

    <!-- start main -->
    <main id="main">
        <!-- start chart -->
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 mb-5">
                    <div class="card rounded-4">
                        <div class="card-title">
                            {{-- <h2 class="text-center mt-2" style="color: #38527E">Statistik Jumlah Dataset</h2> --}}
                        </div>
                        <div class="card-body">
                            <canvas id="myBarChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
                @if (optional($popularDataset)->count() > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card p-4 rounded-top-4 shadow-sm">
                            <p class="fw-bold fs-3 text-center" style="color: #38527E"><i class="fad fa-database"></i>
                                Dataset Populer</p>
                            <hr style="margin-top: -0px">
                            <div class="row align-items-center">
                                <div class="col-md-2" id="img-dataset">
                                    <i class="fad fa-database fa-4x" style="color: #38527E"></i>
                                </div>
                                <div class="col-md-10 mb-2">
                                    <a href="{{ url('dataset/' . optional($popularDataset)->id) }}">
                                        <h5 class="text-capitalize" style="color: #38527E">
                                            {{ optional($popularDataset)->name }}
                                        </h5>
                                    </a>
                                    <p>{{ Str::limit(optional($popularDataset)->abstract, 40, '...') }}
                                    </p>
                                    <div class="input-group gap-5">
                                        <a href="" class="nav-link"><i class="fal fa-download me-2"></i>
                                            {{ $count }}
                                        </a>
                                        <a href="#" class="nav-link"><i class="far fa-laptop-code me-2"></i>Bidang Studi : {{
                                            $popularDataset->subjectArea->name_subject_area }}
                                        </a>
                                        {{-- <a href="#" class="nav-link"><i
                                                class="bi bi-building me-2"></i>{{ optional($popularDataset)->instances }}
                                            Jumlah Baris</a>
                                        <a href="#" class="nav-link"><i
                                                class="bi bi-table me-2"></i>{{ optional($popularDataset)->features }}
                                            Jumlah Fitur</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ($newArticles->count() > 0)
                <div class="row mt-3 justify-content-center">
                    <p class="fs-3 text-center mb-5">Mungkin Anda tertarik membaca artikel berikut.</p>
                    @foreach ($newArticles as $article)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow">
                                <a href="{{ route('artikel.show' , $article->id) }}" target="_blank"
                                    style="text-decoration: none; color: #333;">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('storage/' . $article->cover) }}" alt=""
                                            class="img-fluid">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="mt-3">{{ Str::limit($article->title, 30, '...') }}</h5>
                                        {{-- <p class="small"
                                            style="overflow: hidden; text-overflow: ellipsis; white-space: normal;">
                                            {!! limitHtml($article->description, 100) !!}
                                        </p> --}}
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <!-- end chart -->
    </main>
    <!-- End main -->
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        let subjectAreas = @json($subjectAreas).map(item => item.name_subject_area);
        let data = @json($data);

        var ctx = document.getElementById('myBarChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: subjectAreas,
                datasets: [{
                    label: 'Total Data',
                    data: data,
                    backgroundColor: [
                        '#ff0000',
                        '#ffa500',
                        '#ffff00',
                        '#00ff00',
                        '#0000ff',
                        '#800080',
                        '#ff00ff',
                        '#800000',
                        '#808000',
                        '#00ffff',
                        '#000080'
                    ],
                    borderColor: [
                        '#ff0000',
                        '#ffa500',
                        '#ffff00',
                        '#00ff00',
                        '#0000ff',
                        '#800080',
                        '#ff00ff',
                        '#800000',
                        '#808000',
                        '#00ffff',
                        '#000080'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Statistik Total Dataset Berdasarkan Bidang Studi'
                    },
                },
                scales: {
                    y: {
                        responsive: true,
                        maintainAspectRatio: false,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1, // Kenaikan 1 per langkah
                            callback: function(value) {
                                return Number.isInteger(value) ? value : null; // Hanya tampilkan bilangan bulat
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection

@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p class="fs-2 mb-0" style="color: #38527E">Dashboard</p>
        </div>
        <!-- Content Row -->
        <div class="row">
            @if (Auth::user()->role === 'admin')
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2 ms-3">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Dataset</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countDataset }}</div>
                            </div>
                            <div class="col-auto mr-3">
                                <i class="fas fa-database fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2 ms-3">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countUser }}</div>
                            </div>
                            <div class="col-auto mr-3">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2 ms-3">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Articles</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countArticle }}</div>
                            </div>
                            <div class="col-auto mr-3">
                                <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="card rounded-4 shadow-lg">
                    <div class="card-body">
                        <canvas id="myBarChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
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
                    label: 'Jumlah Data',
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
                        text: 'Statistik Jumlah Dataset'
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

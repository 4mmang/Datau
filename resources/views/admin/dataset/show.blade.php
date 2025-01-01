@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p class="fs-2 mb-0" style="color: #38527E"><a href="{{ route('admin.dataset.index') }}" style="color: #38527E"><i
                        class="fas fa-arrow-left mr-2 fa-sm"></i></a>Detail Dataset</p>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="row align-items-center">
                        <div class="col-md-1" id="img-dataset">
                            <i class="fas fa-database fa-4x" style="color: #38527E"></i>
                            {{-- <img class="img-fluid" src="{{ asset('assets/img/hero-img.png') }}" alt=""> --}}
                        </div>
                        <div class="col-md-11 mb-2">
                            <a href="#" class="nav-link">
                                <h2 class="mt-3 text-capitalize" style="color: #38527E">{{ $dataset->name }}
                                </h2>
                            </a>
                            <p class="text-capitalize]" style="margin-bottom: 0px">Dibuat oleh :
                                {{ $dataset->user->full_name }}
                            </p>
                            <span id="status" class="badge bg-info p-1 me-2">{{ $dataset->status }}</span><span
                                class="text-danger">{{ $dataset->note }}</span>
                        </div>
                        <div class="col-md-12 p-3">
                            <p>{{ $dataset->abstract }}</p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4>Dataset Characteristics</h4>
                            <p>
                                @if ($dataset->characteristics->count() > 0)
                                    @foreach ($dataset->characteristics as $characteristic)
                                        {{ $characteristic->characteristic->name_characteristic }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4>Subject Area</h4>
                            <p>{{ $dataset->subjectArea->name_subject_area ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4>Associated Tasks</h4>
                            <p>
                                @if ($dataset->associatedTask->count() > 0)
                                    @foreach ($dataset->associatedTask as $associated)
                                        {{ $associated->associated->name_associated_task }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4>Feature Type</h4>
                            <p>
                                @if ($dataset->featuresType->count() > 0)
                                    @foreach ($dataset->featuresType as $featureType)
                                        {{ $featureType->feature->name_feature_type }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4># Instances</h4>
                            <p>{{ $dataset->instances ?? '-' }}</p>
                        </div>
                        <div class="col-md-3 ms-3">
                            <h4># Features</h4>
                            <p>{{ $dataset->features ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="card-header">
                        <p class="fs-2 mt-2" style="color: #38527E">Dataset Information</p>
                    </div>
                    <div class="card-body">
                        {!! $dataset->information !!}
                    </div>
                    <div class="card-header">
                        <p class="fs-2 mt-2" style="color: #38527E">Dataset Files</p>
                    </div>
                    <div class="card-body">
                        <p><a href="{{ url('download/' . $id) }}"
                                style="color: #38527E; text-decoration: none">Download</a>
                            to review</p>
                        @foreach ($files as $file)
                            <li>
                                {{ basename($file) }}
                            </li>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="card p-4">
                    <div class="card-header mt-2">
                        <p class="fs-2" style="color: #38527E">Related Papers</p>
                    </div>
                    <div class="card-body">
                        @foreach ($papers as $paper)
                            <p class="fs-5"><a class="nav-link" target="_blank" href="{{ url('' . $paper->url) }}"
                                    style="color: #38527E">{{ $paper->title }}</a>
                            </p>
                            <p style="margin-top: -17px">{{ $paper->description }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($dataset->status == 'pending' && Auth::user()->role === 'admin')
                <div class="col-md-12 text-end mt-3 " id="btnValidate">
                    <a href="#" onclick="valid({{ $id }})" class="btn btn-success mt-2 px-3"><i
                            class="fas fa-check mr-1"></i>Setujui</a>
                    <button data-toggle="modal" data-target="#modalInvalid" class="btn px-3 btn-danger mt-2"><i
                            class="fas fa-times mr-1"></i>Tolak</button>
                </div>
            @endif
        </div>
        <!-- Content Row -->
        <div class="row">
        </div>
    </div>
    <!-- /.container-fluid -->

    <!-- Modal -->
    <div class="modal fade" id="modalInvalid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Catatan!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Masukkan catatan" class="form-control" id="note">
                    <div style="display: none" id="noteRequired" class="invalid-feedback">
                        Harap masukkan catatan penolakan
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="invalid" onclick="invalid({{ $id }})" class="btn btn-danger text-white"
                        >Ya, Tolak</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function valid(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda tidak dapat mengembalikannya!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, setujui!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    fetch('/admin/validate/dataset/' + id, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: {
                                id: id
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById('btnValidate').style.display = "none"
                            document.getElementById('status').innerHTML = "valid"
                            Swal.fire({
                                title: "Bagus",
                                text: "Dataset berhasil disetujui",
                                icon: "success"
                            });
                        })
                        .catch(error => {
                            console.error('Ada kesalahan:', error.message);
                        });
                }
            });
        }

        function invalid(id) {
            let formData = new FormData()
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let note = document.getElementById('note').value
            document.getElementById('invalid').disabled = true
            formData.append('note', note)
            fetch('/admin/invalid/dataset/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status == 422) {
                        document.getElementById('note').classList.add('is-invalid')
                        document.getElementById('noteRequired').style.display = "block"
                        document.getElementById('invalid').disabled = false
                    } else {
                        location.reload();
                        document.getElementById('modalInvalid').style.display = "none"
                    }
                    console.log(data);
                })
                .catch(error => {
                    console.error('Ada kesalahan:', error.message);
                });
        }
    </script>
@endsection

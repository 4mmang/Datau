@extends('layouts.app')
@section('content')
        <main>
            <div class="container p-3" style="margin-top: 10rem; margin-bottom: 3rem">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="text-center">
                            <h1 class="fw-bold mb-4" style="color: #38527E"><i class="fad fa-database"></i> Form Upload Dataset
                            </h1>
                            <h5 style="color: gray">Kami menawarkan kepada pengguna opsi untuk mengupload dataset mereka ke
                                repositori kami.</h5>
                        </div>
                    </div>
                </div>
                <form id="form-upload-dataset" action="{{ route('dataset.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row justify-content-center mt-5">
                                <div class="col-md-12">
                                    <p class="card-title fs-5 mb-2 text-start" style="color: #38527E; ">Info Dasar</p>
                                    <div class="card p-4 rounded-3">
                                        <div class="card-body">
                                            <div class="mb-3 position-relative">
                                                <label for="" class="form-label">Nama Dataset <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" value="{{ old('name') }}" class="form-control @error('name')
                                                    is-invalid
                                                @enderror" id="name" name="name">
                                                @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <hr class="border-bottom">
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label for="" class="form-label">File Dataset <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="file" value="{{ old('file') }}" multiple class="form-control @error('file')
                                                    is-invalid
                                                @enderror" id="file[]" name="file[]">
                                                @error('file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <hr class="border-bottom">
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label for="" class="form-label"><span
                                                        class="">Abstract</span>
                                                    (Garis besar mengenai dataset) <sup class="text-danger"> </sup></label>
                                                <textarea name="" id="abstract" cols="30" class="form-control" rows="5"></textarea>
                                                <div class="invalid-feedback">
                                                    Harap masukkan abstract dataset!
                                                </div>
                                                <hr class="border-bottom">
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label for="" class="form-label">Jumlah Baris dalam
                                                    Dataset <sup class="text-danger"></sup></label>
                                                <input type="number" class="form-control" name="instances" id="instances">
                                                <div class="invalid-feedback">
                                                </div>
                                                <hr class="border-bottom">
                                            </div>
                                            <div class="mb-3 position-relative">
                                                <label for="" class="form-label">Jumlah Fitur dalam Dataset <sup
                                                        class="text-danger"></sup></label>
                                                <input type="number" class="form-control" id="features">
                                                <hr class="border-bottom">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <p class="card-title fs-5 mt-4 mb-1 text-start" style="color: #38527E;">
                                        Deskripsi Dataset</p>
                                    <textarea name="information" id="information" cols="30" class="form-control" rows="10"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row justify-content-center mt-5">
                                <div class="col-md-12">
                                    <p class="card-title fs-5 text-start mb-2" style="color: #38527E;">Karakteristik
                                        Dataset</p>
                                    <div class="card p-1 rounded-3">
                                        <div class="card-body" id="characteristics">
                                            @foreach ($characteristics as $characteristic)
                                                <div class="form-check d-flex align-items-center">
                                                    <label class="form-check-label"
                                                        for="flexCheckDefault">{{ $characteristic->name_characteristic }}</label>
                                                    <input class="form-check-input ms-auto characteristic" type="checkbox"
                                                        value="{{ $characteristic->id }}" style="border-color: #38527E;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-12">
                                    <p class="card-title fs-5 text-start mb-2" style="color: #38527E;">Bidang Studi</p>
                                    <div class="card p-1 rounded-3">
                                        <div class="card-body" id="subjectArea">
                                            @foreach ($subjectAreas as $subjectArea)
                                                <div class="form-check d-flex align-items-center">
                                                    <label class="form-check-label"
                                                        for="tabular">{{ $subjectArea->name_subject_area }}</label>
                                                    <input class="form-check-input ms-auto subjectArea" type="radio"
                                                        name="subjectArea" id="subjectArea"
                                                        value="{{ $subjectArea->id }}" style="border-color: #38527E;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-12">
                                    <p class="card-title fs-5 text-start mb-2" style="color: #38527E;">Tugas Terkait
                                    </p>
                                    <div class="card p-1 rounded-3">
                                        <div class="card-body" id="associatedTasks">
                                            @foreach ($associatedTasks as $associatedTask)
                                                <div class="form-check d-flex align-items-center">
                                                    <label class="form-check-label"
                                                        for="flexCheckDefault">{{ $associatedTask->name_associated_task }}</label>
                                                    <input class="form-check-input ms-auto associatedTasks"
                                                        type="checkbox" value="{{ $associatedTask->id }}"
                                                        style="border-color: #38527E;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-12">
                                    <p class="card-title fs-5 text-start mb-2" style="color: #38527E;">Jenis Fitur</p>
                                    <div class="card p-1 rounded-3">
                                        <div class="card-body" id="featureTypes">
                                            @foreach ($featureTypes as $featureType)
                                                <div class="form-check d-flex align-items-center">
                                                    <label class="form-check-label"
                                                        for="flexCheckDefault">{{ $featureType->name_feature_type }}</label>
                                                    <input class="form-check-input ms-auto featureTypes" type="checkbox"
                                                        value="{{ $featureType->id }}" style="border-color: #38527E;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="simpan" type="submit" id="btnNext" class="btn fs-6 text-light mt-4 px-4 flex float-end"
                                        style="background-color: #38527E;"><i class="fas fa-save"></i>
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </main>
@endsection
@section('scripts')
    <script>
        let form = document.getElementById('form-upload-dataset')
            form.addEventListener('submit', function() {
                let btnSave = document.getElementById('simpan')
                btnSave.disabled = true
                btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            })
    </script>
@endsection
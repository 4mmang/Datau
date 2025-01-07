@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between">
            <p class="fs-2 mb-0" style="color: #38527E">Upload Dataset</p>
        </div>

        <form action="{{ route('admin.dataset.store') }}" method="post" id="form-upload" enctype="multipart/form-data">
            @csrf
            <!-- Content Row -->
            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Info Dasar</p>
                    <div class="card p-3">
                        <label for="name">Nama Dataset <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name')
                                                                        is-invalid
                                                                    @enderror">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <label for="" class="form-label mt-3">File Dataset <sup class="text-danger">*</sup></label>
                        <input type="file" value="{{ old('file') }}" multiple
                            class="@error('file')
                                                                        is-invalid
                                                                    @enderror"
                            id="file[]" name="file[]">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <label for="name" class="mt-3">Abstract (Garis besar mengenai dataset)</label>
                        <textarea name="abstract" id="abstract" class="form-control" cols="30" rows="5">{{ old('abstract') }}</textarea>
                        <label for="instances" class="mt-3">Jumlah Baris dalam Dataset</label>
                        <input type="number" name="instances" id="instances" value="{{ old('instances') }}"
                            class="form-control">
                        <label for="features" class="mt-3">Jumlah Fitur dalam Dataset</label>
                        <input type="number" name="features" id="features" value="{{ old('features') }}"
                            class="form-control">
                    </div>
                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Dataset Information</p>
                    <textarea class="form-control" id="information" name="information" cols="30" rows="5">{!! old('information') !!}</textarea>
                </div>
                <div class="col-md-6">
                    <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Dataset Characteristics</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="characteristics">
                            @foreach ($characteristics as $characteristic)
                                <div class="form-check d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $characteristic->name_characteristic }}</label>
                                    <input name="characteristics[]" class="form-check-input ms-auto characteristic"
                                        type="checkbox" value="{{ $characteristic->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Subject Area</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="subjectArea">
                            @foreach ($subjectAreas as $subjectArea)
                                <div class="form-check d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="tabular">{{ $subjectArea->name_subject_area }}</label>
                                    <input class="form-check-input ms-auto subjectArea" type="radio" name="subjectArea"
                                        id="subjectArea" value="{{ $subjectArea->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Associated Task</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="associatedTasks">
                            @foreach ($associatedTasks as $associatedTask)
                                <div class="form-check d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $associatedTask->name_associated_task }}</label>
                                    <input name="associatedTasks[]" class="form-check-input ms-auto associatedTasks"
                                        type="checkbox" value="{{ $associatedTask->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Feature Types</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="featureTypes">
                            @foreach ($featureTypes as $featureType)
                                <div class="form-check d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $featureType->name_feature_type }}</label>
                                    <input name="featureTypes[]" class="form-check-input ms-auto featureTypes"
                                        type="checkbox" value="{{ $featureType->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.dataset.index') }}" class="btn btn-danger float-end mt-4"><i
                    class="fas fa-arrow-left"></i> Kembali</a>
            <button type="submit" id="simpan" class="btn text-white mt-4 float-end mr-2"
                style="background-color: #38527E"><i class="fas fa-save mr-1"></i>Simpan</button>
        </form>
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts')
    <script>
        $('#information').summernote({
            placeholder: 'Masukkan informasi dataset',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
            ]
        });
    </script>
    <script>
        let form = document.getElementById('form-upload')
        form.addEventListener('submit', function() {
            let btnUpdate = document.getElementById('simpan')
            btnUpdate.disabled = true
            btnUpdate.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Processing...`
        })
    </script>
@endsection

@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between">
            <p class="fs-2 mb-0" style="color: #38527E">Edit Dataset</p>
        </div>

        <form action="{{ route('admin.dataset.update', $id) }}" method="post" id="form-update">
            @csrf
            @method('put')
            <!-- Content Row -->
            <div class="row mt-3">
                @if (Auth::user()->role != 'user')
                    <p>Dibuat oleh : <span class="fw-bold">{{ $dataset->user->full_name }}</span></p>
                @endif
                <div class="col-md-6 mb-3">
                    <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Info Dasar</p>
                    <div class="card p-3">
                        <label for="name">Nama Dataset <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" id="name" value="{{ $dataset->name }}"
                            class="form-control">
                        <label for="name" class="mt-3">Abstract (Garis besar mengenai dataset)</label>
                        <textarea name="abstract" id="abstract" class="form-control" cols="30" rows="5">{{ $dataset->abstract }}</textarea>
                        <label for="instances" class="mt-3">Jumlah Baris dalam Dataset</label>
                        <input type="number" name="instances" id="instances" value="{{ $dataset->instances }}"
                            class="form-control">
                        <label for="features" class="mt-3">Jumlah Fitur dalam Dataset</label>
                        <input type="number" name="features" id="features" value="{{ $dataset->features }}"
                            class="form-control">
                    </div>
                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Dataset Information</p>
                    <textarea class="form-control" id="information" name="information" cols="30" rows="5">{!! $dataset->information !!}</textarea>
                </div>
                <div class="col-md-6">
                    <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Dataset Characteristics</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="characteristics">
                            @foreach ($characteristics as $characteristic)
                                <div class="d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $characteristic->name_characteristic }}</label>
                                    <input class="form-check-input ms-auto characteristic" type="checkbox"
                                        name="characteristics[]" @if (in_array($characteristic->id, $datasetCharacteristics->pluck('id')->toArray())) checked @endif
                                        value="{{ $characteristic->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Subject Area</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="subjectArea">
                            @foreach ($subjectAreas as $subjectArea)
                                <div class="d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="tabular">{{ $subjectArea->name_subject_area }}</label>
                                    <input class="form-check-input ms-auto subjectArea" type="radio" name="subjectArea"
                                        @if (in_array($subjectArea->id, $dataset->pluck('id_subject_area')->toArray())) checked @endif name="subjectArea" id="subjectArea"
                                        value="{{ $subjectArea->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Associated Task</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="associatedTasks">
                            @foreach ($associatedTasks as $associatedTask)
                                <div class="d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $associatedTask->name_associated_task }}</label>
                                    <input class="form-check-input ms-auto associatedTasks" type="checkbox"
                                        name="associatedTasks[]" @if (in_array($associatedTask->id, $datasetAssociatedTasks->pluck('id')->toArray())) checked @endif
                                        value="{{ $associatedTask->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="card-title fs-6 text-start mb-2 mt-3" style="color: #38527E;">Feature Types</p>
                    <div class="card p-1 rounded-3">
                        <div class="card-body" id="featureTypes">
                            @foreach ($featureTypes as $featureType)
                                <div class="d-flex align-items-center">
                                    <label class="form-check-label ml-4"
                                        for="flexCheckDefault">{{ $featureType->name_feature_type }}</label>
                                    <input class="form-check-input ms-auto featureTypes" type="checkbox"
                                        name="featureTypes[]" @if (in_array($featureType->id, $datasetFeatureTypes->pluck('id')->toArray())) checked @endif
                                        value="{{ $featureType->id }}" style="border-color: #38527E;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.dataset.index') }}" class="btn btn-danger float-end mt-4"><i
                    class="fas fa-arrow-left"></i> Kembali</a>
            <button type="submit" id="update" class="btn text-white mt-4 float-end mr-2"
                style="background-color: #38527E"><i class="fas fa-save mr-1"></i>Update</button>
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
        let form = document.getElementById('form-update')
        form.addEventListener('submit', function() {
            let btnUpdate = document.getElementById('update')
            btnUpdate.disabled = true
            btnUpdate.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Processing...`
        })
    </script>
@endsection

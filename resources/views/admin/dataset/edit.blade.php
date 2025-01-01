@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between">
            <p class="fs-2 mb-0" style="color: #38527E"> <a href="{{ route('admin.dataset.index') }}"><i
                        class="fas fa-arrow-left fa-sm mr-1" style="color: #38527E"></i></a>Edit Dataset <span
                    class="fw-bold">"{{ $dataset->name }}"</span></p>
        </div>

        <form action="{{ route('admin.dataset.update', $id) }}" method="post" id="form-update">
            @csrf
            @method('put')
            <!-- Content Row -->
            <div class="row">
                <p>Dibuat oleh : <span class="fw-bold">{{ $dataset->user->full_name }}</span></p>

                <div class="col-md-12">
                    <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Abstract</p>
                    <textarea name="abstract" class="form-control" id="abstract" cols="30" rows="5">{{ $dataset->abstract }}</textarea>
                </div>
                <div class="col-md-12 mt-3">
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
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Subject Area</p>
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
            </div>

            <div class="col-md-12 mt-3">
                <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Associated Task</p>
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
            </div>

            <div class="col-md-12 mt-3">
                <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Feature Types</p>
                <div class="card p-1 rounded-3">
                    <div class="card-body" id="featureTypes">
                        @foreach ($featureTypes as $featureType)
                            <div class="d-flex align-items-center">
                                <label class="form-check-label ml-4"
                                    for="flexCheckDefault">{{ $featureType->name_feature_type }}</label>
                                <input class="form-check-input ms-auto featureTypes" type="checkbox" name="featureTypes[]"
                                    @if (in_array($featureType->id, $datasetFeatureTypes->pluck('id')->toArray())) checked @endif value="{{ $featureType->id }}"
                                    style="border-color: #38527E;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <p class="card-title fs-6 text-start mb-2" style="color: #38527E;">Dataset Information</p>
                <textarea class="form-control" id="information" name="information" cols="30" rows="5">{!! $dataset->information !!}</textarea>
            </div>
            <button type="submit" id="update" class="btn text-white mt-4 float-end mr-3"
                style="background-color: #38527E"><i class="fas fa-save mr-1"></i>Update</button>
        </form>
    </div>
    <!-- /.container-fluid -->
    @if (session('message'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: `{{ session('message') }}`,
                icon: "success"
            });
        </script>
    @endif
@endsection
@section('scripts')
    <script>
        let form = document.getElementById('form-update')
        form.addEventListener('submit', function() {
            let btnUpdate = document.getElementById('update')
            btnUpdate.disabled = true
            btnUpdate.innerHTML = `<i class="fas fa-spinner fa-spin mr-1"></i>Processing...`
        })
    </script>
@endsection

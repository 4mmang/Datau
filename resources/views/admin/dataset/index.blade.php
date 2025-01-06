@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p class="fs-2 mb-0" style="color: #38527E">Kelola Dataset</p>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="table-responsive">
                        <table id="datasets" class="table table-sm text-center table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Dataset</th>
                                    <th class="text-center">Creator</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Catatan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datasets as $dataset)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-capitalize">{{ $dataset->name }}</td>
                                        <td class="align-middle">{{ $dataset->user->full_name }}</td>
                                        <td class="align-middle"><span
                                                class="badge bg-info text-white p-1">{{ $dataset->status }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @if ($dataset->note == null || $dataset->note == '')
                                                -
                                            @else
                                                {{ Str::limit($dataset->note, 20, '...') }}
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.dataset.show', $dataset->id) }}"
                                                class="ml-1 btn btn-primary btn-sm mb-1 text-center" style="width: 1cm"><i
                                                class="fas fa-eye"></i></a>
                                                @if (Auth::user()->role === 'admin' || $dataset->status != 'pending' && Auth::user()->status ==='on')
                                                <a href="{{ route('admin.dataset.edit', $dataset->id) }}"
                                                    class="ml-1 btn btn-warning btn-sm mb-1 text-center" style="width: 1cm"><i
                                                        class="fas fa-pen"></i></a>
                                            <a href="#" onclick="deleteDataset({{ $dataset->id }})"
                                                class="ml-1 btn btn-sm btn-danger mb-1 text-center" style="width: 1cm"><i
                                                    class="fas fa-trash"></i></a>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@section('scripts') 
    <script>
        $(document).ready(function() {
            $('#datasets').DataTable();
        });
    </script>
    <script>
        function deleteDataset(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda tidak dapat mengembalikannya!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('/admin/dataset/' + id, {
                            method: 'DELETE',
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
                            const table = $('#datasets').DataTable();
                            // Clear existing rows using DataTables API
                            table.rows().remove();
                            let no = 0;
                            data.datasets.forEach(dataset => {
                                no++
                                const status =
                                    `<span class="badge bg-info p-1">${dataset.status}</span>`
                                const btn = `<a href="{{ url('admin/edit/dataset/') }}/${dataset.id}" class="ml-1 btn btn-warning btn-sm mb-1 text-center"
                                    style="width: 1cm"><i class="fas fa-pen"></i></a>
                                <a href="{{ url('admin/detail/dataset/') }}/${dataset.id}" class="btn btn-sm btn-primary" style="width: 1cm"><i
                                    class="fas fa-eye text-white fw-bold"></i></a>
                            <a href="#" onclick="deleteDataset(${dataset.id})" class="btn btn-sm btn-danger" style="width: 1cm"><i
                                    class="fas fa-trash text-white fw-bold"></i></a>`;
                                table.row.add([no, dataset.name, dataset.user.full_name, dataset.status,
                                    dataset.note, btn
                                ]);
                            });
                            table.draw();
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            });
                        })
                        .catch(error => {
                            console.error('Ada kesalahan:', error.message);
                        });
                }
            });
        }

        // function confirmReject() {
        //     Swal.fire({
        //         title: "Apakah Anda yakin?",
        //         text: "Anda tidak dapat mengembalikannya!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Ya, tolak!"
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             Swal.fire({
        //                 title: "Dotolak!",
        //                 text: "Your file has been rejected.",
        //                 icon: "success"
        //             });
        //         }
        //     });
        // }
    </script>
@endsection

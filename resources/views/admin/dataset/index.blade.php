@extends('layouts.admin.master')
@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->email }}</span>
                            <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profileAdmin') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

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
                                            <th class="text-center">Name Dataset</th>
                                            <th class="text-center">Creator</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Note</th>
                                            <th class="text-center">Action</th>
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
                                                    <a href="{{ route('admin.dataset.edit', $dataset->id) }}"
                                                        class="ml-1 btn btn-warning btn-sm mb-1 text-center"
                                                        style="width: 1cm"><i class="fas fa-pen"></i></a>
                                                    <a href="{{ route('admin.dataset.show', $dataset->id) }}"
                                                        class="ml-1 btn btn-primary btn-sm mb-1 text-center"
                                                        style="width: 1cm"><i class="fas fa-eye"></i></a>
                                                    <a href="#" onclick="deleteDataset({{ $dataset->id }})"
                                                        class="ml-1 btn btn-sm btn-danger mb-1 text-center"
                                                        style="width: 1cm"><i class="fas fa-trash"></i></a>
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

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Arman 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datasets').DataTable();
        });
    </script>
    <script>
        function deleteDataset(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
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

        function confirmReject() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, reject it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Rejected!",
                        text: "Your file has been rejected.",
                        icon: "success"
                    });
                }
            });
        }
    </script>
@endsection

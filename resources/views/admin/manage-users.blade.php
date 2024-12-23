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
                    <p class="fs-2 mb-0" style="color: #38527E">Manage Users</p>
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
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $user->full_name }}</td>
                                                <td class="align-middle"><span
                                                        class="{{ $user->status === 'on' ? 'bg-info' : 'bg-secondary' }} text-white rounded-5 py-1 px-3 text-capitalize">{{ $user->status }}</span>
                                                </td>
                                                <td class="align-middle">{{ $user->email }}</td>
                                                <td class="align-middle items-center">
                                                    <form action="{{ url('admin/manage/user/' . $user->id) }}" id="status-update-{{ $user->id }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="status"
                                                            value="{{ $user->status === 'on' ? 'off' : 'on' }}">
                                                        <button type="submit" onclick="disableButtonStatus({{ $user->id }})" 
                                                            class="ml-1 btn btn-sm btn-success mb-1 text-center"
                                                            style="width: 1cm">{{ $user->status === 'on' ? 'off' : 'on' }}</button>
                                                    </form>
                                                    <a href="#" onclick="deleteUser({{ $user->id }})"
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
                    <span>Copyright &copy; Your Website 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper --> 
    @if (session('message'))
        <script>
            Swal.fire({
                title: "Berhasil",
                text: "{{ session('message') }}",
                icon: "success"
            });
        </script>
    @endif
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datasets').DataTable();
        });

        function deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "The dataset uploaded by this user will also be deleted",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('/admin/delete/user/' + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                title: "Deleted!",
                                text: "The user has been successfully deleted",
                                icon: "success",
                                confirmButtonText: "OK",
                                allowOutsideClick: false, // Tidak bisa ditutup dengan klik di luar
                                allowEscapeKey: false // Tidak bisa ditutup dengan tombol Escape
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Reload halaman setelah tombol "OK" ditekan
                                    window.location.reload();
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Ada kesalahan:', error.message);
                        });
                }
            });
        }
    </script>

    <script>
        function disableButtonStatus(id) {
                const button = document.querySelector(`#status-update-${id} button`);
                button.disabled = true;
                button.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
                document.querySelector(`#status-update-${id}`).submit();
            }
    </script>
@endsection

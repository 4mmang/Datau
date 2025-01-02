@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p class="fs-2 mb-0" style="color: #38527E">Kelola User</p>
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
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $user->full_name }}</td>
                                        <td class="align-middle"><span
                                                class="{{ $user->status === 'on' ? 'bg-info' : 'bg-secondary' }} text-white rounded-5 py-1 px-3 text-capitalize">{{ $user->status === 'on' ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="align-middle items-center">
                                            <form action="{{ route('admin.user.update', $user->id) }}"
                                                id="status-update-{{ $user->id }}" method="post">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="status"
                                                    value="{{ $user->status === 'on' ? 'off' : 'on' }}">
                                                <button type="submit" onclick="disableButtonStatus({{ $user->id }})"
                                                    class="ml-1 btn btn-sm {{ $user->status === 'on' ? 'btn-danger' : 'btn-success' }}  mb-1 text-center"
                                                    style="width: 1cm">
                                                    <i class="fas fa-power-off"></i>
                                                    {{-- {{ $user->status === 'on' ? 'off' : 'on' }} --}}
                                                    </button>
                                            </form>
                                            <a href="#" onclick="deleteUser({{ $user->id }})"
                                                class="ml-1 btn btn-sm btn-danger mb-1 text-center" style="width: 1cm"><i
                                                    class="fas fa-trash"></i></a>
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

                    fetch('/admin/user/' + id, {
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

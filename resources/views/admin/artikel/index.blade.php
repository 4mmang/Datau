@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p class="fs-2 mb-0" style="color: #38527E">Kelola Artikel</p>
            <a href="{{ route('admin.artikel.create') }}" style="background-color: #38527E" class="btn mt-3 text-white"><i
                    class="fas fa-plus"></i> Buat Artikel</a>
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
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $article)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $article->title }}</td>
                                        <td class="align-middle">
                                            <form id="delete-article-{{ $article->id }}"
                                                action="{{ route('admin.artikel.destroy', $article->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('admin.artikel.edit', $article->id) }}"
                                                    class="btn btn-warning btn-sm mb-1"><i class="fas fa-pen"></i></a>
                                                <button type="submit" onclick="disableDeleteButton({{ $article->id }})"
                                                    class="ml-1 btn btn-sm btn-danger mb-1 text-center"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
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
                title: "Good job!",
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
    </script>
    <script>
        function disableDeleteButton(id) {
            const button = document.querySelector(`#delete-article-${id} button`);
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
            document.querySelector(`#delete-article-${id}`).submit();
        }
    </script>
@endsection

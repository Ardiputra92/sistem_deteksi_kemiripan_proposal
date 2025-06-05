@extends('layout.main')
@section('content')
  <div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Deteksi Kemiripan Proposal</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
              <li class="breadcrumb-item active">Deteksi Proposal</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

        <!-- DEBUG: Menampilkan nilai threshold -->
        <!-- <p><strong>Threshold saat ini:</strong> {{ $threshold }}%</p> -->

            <p>Menampilkan Data Similarity</p>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="mr-auto">Data Proposal</span>
                            <a href="{{ route('plagiarism.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Data</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="data-proposal" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status</th>
                                        <th>Judul</th>
                                        <th>File</th>
                                        <th>Kemiripan(%)</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th> <!-- Kolom untuk aksi -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($similarities as $index => $similarity)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <!-- Kolom untuk status -->
                                            @if ($similarity->persentase_kemiripan >= $threshold)
                                                <span class="badge badge-danger">Tidak Lolos Plagiasi</span>
                                            @else
                                                <span class="badge badge-primary">Lolos Plagiasi</span>
                                            @endif
                                        </td>

                                        <td>{{ $similarity->judul }}</td>
                                        <!-- <td>
                                            <a href="{{ asset('uploads/' . $similarity->nama_file) }}" target="_blank">
                                                {{ $similarity->nama_file }}
                                            </a>
                                        </td> -->

                                        <td class="text-center">
                                            <a href="{{ asset('uploads/' . $similarity->nama_file) }}" download title="Download Dokumen">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>


                                        <td>{{ $similarity->persentase_kemiripan }}%</td>
                                        <td>
                                            @if ($similarity->persentase_kemiripan >= $threshold)
                                                Judul Skripsi Anda Tidak Lolos Plagiasi karena mempunyai tingkat kemiripan di atas {{ $threshold }}%.
                                            @else
                                                Judul Skripsi Anda Lolos Plagiasi karena mempunyai tingkat kemiripan di bawah {{ $threshold }}%.
                                            @endif
                                        </td>
                                        <td>
                                            <nobr>
                                                <a href="{{ route('plagiarism.edit', $similarity->id) }}" class="btn btn-warning btn-xs mx-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </nobr>
                                            <nobr>
                                                <button type="button" class="btn btn-danger btn-xs mx-1" title="Hapus" onclick="confirmDelete({{ $similarity->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </nobr>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Modal untuk konfirmasi hapus -->
                            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data proposal ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <form id="deleteForm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </div>

                        </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


  </div>
@endsection

<!-- Script JavaScript -->
@section('scripts')
<script>
    $(document).ready(function () {
        $("#data-proposal").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "language": {
                "emptyTable": "No data available in table"
            }
        });
    });

    function confirmDelete(id) {
        const url = "{{ route('plagiarism.destroy', ':id') }}".replace(':id', id);
        document.getElementById('deleteForm').setAttribute('action', url);
        $('#confirmDeleteModal').modal('show');
    }
</script>

@endsection

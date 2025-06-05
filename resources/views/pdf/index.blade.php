@extends('layout.main')
@section('content')
  <div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Data Proposal</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
              <li class="breadcrumb-item active">Tambah Proposal</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <p>Menambah data proposal</p>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="mr-auto">Data Proposal</span>
                      <a href="{{ route('pdf.create') }}" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Tambah Data </a>
                  </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul</th>
                                <th>File</th>
                                <th>Aksi</th> <!-- Kolom Aksi -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fingerprints as $index => $fingerprint)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fingerprint->judul }}</td>
                                <!-- <td>
                                    <a href="{{ asset('uploads/' . $fingerprint->file_dokumen) }}" target="_blank">{{ $fingerprint->file_dokumen }}</a>

                                </td> -->
                                <td class="text-center">
                                    <a href="{{ asset('uploads/' . $fingerprint->file_dokumen) }}" download title="Download Dokumen">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>

                                <td> <!-- Tombol Aksi -->
                                    {{-- <nobr>
                                        <a href="{{ route('pdf.show', $fingerprint->id) }}" class="btn btn-info btn-xs mx-1" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </nobr> --}}
                                    <nobr>
                                        <a href="{{ route('pdf.edit', $fingerprint->id) }}" class="btn btn-warning btn-xs mx-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </nobr>

                                    <nobr>
                                        <!-- <form action="{{ route('pdf.destroy', $fingerprint->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs mx-1" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus skripsi ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form> -->
                                        <button type="button" class="btn btn-danger btn-xs mx-1" title="Hapus"
                                            onclick="confirmDelete({{ $fingerprint->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </nobr>
                                </td>
                            </tr>
                            <!-- Modal untuk konfirmasi hapus -->
                            <!-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Konfirmasi Hapus</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          Apakah Anda yakin ingin menghapus proposal skripsi ini?
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
                            </div> -->

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
                                  Apakah Anda yakin ingin menghapus proposal skripsi ini?
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
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>
@endsection

<!-- Script JavaScript -->
@section('scripts')
<!-- <script>
    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "language": {
                "emptyTable": "No data available in table"
            }
        });
    });
</script> -->

<script>
    function confirmDelete(id) {
        const url = "{{ route('pdf.destroy', ':id') }}".replace(':id', id);
        document.getElementById('deleteForm').setAttribute('action', url);
        $('#confirmDeleteModal').modal('show');
    }

    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "emptyTable": "No data available in table"
            }
        });
    });
</script>

@endsection

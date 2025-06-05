@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2" style="min-height: 485px;">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1 class="m-0">Deteksi Kemiripan Proposal</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Hasil Kemiripan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <p>Menampilkan Data Similarity</p>

      <div class="row">
        <div class="col-12 mb-3">
            <form action="{{ route('result.update-threshold') }}" method="POST" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <label for="threshold" class="mr-2">Nilai Ambang Batas Persentase Kemiripan:</label>
                    <input type="number" class="form-control" id="threshold" name="threshold" value="{{ $threshold }}" style="width: 80px;">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="mr-auto">Data Proposal</span>
              {{-- Tombol tambah data dihapus --}}
            </div>

            <div class="card-body">
              <table id="data-proposal" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    
                    <th>Judul</th>
                    <th>File</th>
                    <th>Kemiripan(%)</th>
                    <th>Keterangan</th>
                    {{-- Kolom aksi dihapus --}}
                  </tr>
                </thead>
                <tbody>
                  @foreach($similarities as $index => $similarity)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                      @if ($similarity->persentase_kemiripan >= $threshold)
                        <span class="badge badge-danger">Tidak Lolos Plagiasi</span>
                      @else
                        <span class="badge badge-primary">Lolos Plagiasi</span>
                      @endif
                    </td>
                    <td>{{ $similarity->user->nim ?? '-' }}</td>
                    <td>{{ $similarity->user->name ?? '-' }}</td>
                    
                    <td>{{ $similarity->judul }}</td>
                    <td>{{ $similarity->nama_file }}</td>
                    <td>{{ $similarity->persentase_kemiripan }}%</td>
                    <td>
                      @if ($similarity->persentase_kemiripan >= $threshold)
                        Judul Skripsi Tidak Lolos Plagiasi karena tingkat kemiripan di atas {{ $threshold }}%.
                      @else
                        Judul Skripsi Lolos Plagiasi karena tingkat kemiripan di bawah {{ $threshold }}%.
                      @endif
                    </td>
                    {{-- Kolom tombol edit/hapus dihapus --}}
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div> <!-- card-body -->
          </div> <!-- card -->
        </div> <!-- col-12 -->
      </div> <!-- row -->
    </div> <!-- container-fluid -->
  </section>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function () {
    $("#data-proposal").DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: false,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      language: {
        emptyTable: "Tidak ada data tersedia"
      }
    });
  });
</script>
@endsection

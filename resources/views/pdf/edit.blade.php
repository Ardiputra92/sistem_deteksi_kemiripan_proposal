@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Proposal</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Proposal</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>Mengedit data proposal</p>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Form Edit Proposal</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('pdf.update', $fingerprint->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="judul">Judul:</label>
                                    <input type="text" class="form-control" id="judul" name="judul" value="{{ $fingerprint->judul }}" placeholder="Masukkan judul proposal" required>
                                </div>

                                <div class="form-group">
                                    <label for="file">File Proposal:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-file-pdf fa-sm"></i>
                                            </div>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" id="file" name="pdf" class="custom-file-input" onchange="displayFileName(this)">
                                            <label class="custom-file-label text-truncate" for="file" data-browse="Upload" data-default="{{ $fingerprint->file_dokumen }}">{{ $fingerprint->file_dokumen }}</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('pdf.index') }}" class="btn btn-secondary my-2" style="height: 38px;">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success" style="height: 38px;">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
<script>
    function displayFileName(input) {
        const fileName = input.files[0].name;
        const label = input.nextElementSibling;
        label.innerText = fileName;
    }
</script>
@endsection

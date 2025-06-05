@extends('layout.main')
@section('content')
  <div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Dokumen</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('pdf.index') }}">Proposal</a></li>
              <li class="breadcrumb-item active">{{ $fingerprint->judul }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Judul: {{ $fingerprint->judul }}</h5>
                <p class="card-text">File: <a href="{{ asset('uploads/' . $fingerprint->file_dokumen) }}" target="_blank">{{ $fingerprint->file_dokumen }}</a></p>
                <div class="col s6">
                    <h3>N-GRAM Teks</h3>
                    <div class="mc-text-content">
                        <textarea class="materialize-textarea" cols="50" rows="10">{{ $fingerprint->n_gram }}</textarea>
                    </div>
                </div>

                <div class="col s6">
                    <h3>Hashing Teks</h3>
                    <div class="mc-text-content">
                        <textarea class="materialize-textarea" cols="50" rows="10">{{ $fingerprint->hashing }}</textarea>
                    </div>
                </div>

                <div class="col s6">
                    <h3>Winnowing Teks</h3>
                    <div class="mc-text-content">
                        <textarea class="materialize-textarea" cols="50" rows="10">{{ $fingerprint->winnowing }}</textarea>
                    </div>
                </div>

                <div class="col s6">
                    <h3>Fingerprint Teks</h3>
                    <div class="mc-text-content">
                        <textarea class="materialize-textarea" cols="50" rows="10">{{ $fingerprint->fingerprint }}</textarea>
                    </div>
                </div>

                <p class="card-text">Total Fingerprint: {{ $fingerprint->total_fingerprint }}</p>
                <p class="card-text">Total Ngram: {{ $fingerprint->total_ngram }}</p>
                <p class="card-text">Total Hash: {{ $fingerprint->total_hash }}</p>
                <p class="card-text">Total Window: {{ $fingerprint->total_window }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

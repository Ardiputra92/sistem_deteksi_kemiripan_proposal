@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Proposal</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Proposal</a></li>
              <li class="breadcrumb-item active">Tambah</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>Menambahkan data proposal</p>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Form Proposal</div>
                        <div class="card-body">
                            <div class="result col s12">
                                <div class="row">
                                    <div class="col s6">
                                        <h3>N-GRAM Teks</h3>
                                        <div class="mc-text-content">
                                            <textarea class="materialize-textarea" cols="50" rows="10">{{ $result['nGramFirst'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col s6">
                                        <h3>Rolling Hash Teks</h3>
                                        <div class="mc-text-content">
                                            <textarea class="materialize-textarea" cols="50" rows="10">{{ $result['rollingHashFirst'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col s6">
                                        <h3>Window Teks</h3>
                                        <div class="mc-text-content">
                                            <textarea class="materialize-textarea" cols="50" rows="10">{{ $result['windowFirst'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col s6">
                                        <h3>Fingerprints Teks</h3>
                                        <div class="mc-text-content">
                                            <textarea class="materialize-textarea" cols="50" rows="10">{{ $result['FingerprintsFirst'] }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col s6">
                                        <ul>
                                            <li><label for="">Jumlah N-gram Teks</label>: {{ $result['countNgram1'] }}</li>
                                            <li><label for="">Jumlah Hash Teks</label>: {{ $result['countHash1'] }}</li>
                                            <li><label for="">Jumlah Window Teks</label>: {{ $result['countWindow1'] }}</li>
                                            <li><label for="">Jumlah Fingerprints Teks</label>: {{ $result['countFinger1'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                             </div>
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




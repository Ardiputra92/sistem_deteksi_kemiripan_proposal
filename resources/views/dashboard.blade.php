@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
      <section class="content">
          <div class="container-fluid">
              <p>Halo <b>{{ $user->name }}</b>, selamat datang di Sistem Deteksi Kemiripan Proposal Skripsi</p>
          </div>
      </section>
    <!-- /.content -->
</div>
@endsection

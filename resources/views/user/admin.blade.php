@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2" style="min-height: 485px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Profil</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profil</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <p>Informasi profil admin.</p>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">Informasi Profil</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama:</label>
                                <div class="form-control" readonly>{{ Auth::user()->name }}</div>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <div class="form-control" readonly>{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

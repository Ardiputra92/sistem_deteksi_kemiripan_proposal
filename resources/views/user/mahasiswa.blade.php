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
            <p>Informasi profil akun Anda.</p>
            <div class="row">
                <!-- Informasi Profil -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">Informasi Profil</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama:</label>
                                <div class="form-control" readonly>{{ Auth::user()->name }}</div>
                            </div>
                            <div class="form-group">
                                <label>NIM:</label>
                                <div class="form-control" readonly>{{ Auth::user()->nim }}</div>
                            </div>
                            <div class="form-group">
                                <label>Program Studi:</label>
                                <div class="form-control" readonly>{{ Auth::user()->program_studi }}</div>
                            </div>
                            <div class="form-group">
                                <label>Kelas:</label>
                                <div class="form-control" readonly>{{ Auth::user()->kelas }}</div>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <div class="form-control" readonly>{{ Auth::user()->email }}</div>
                            </div>
                            <div class="form-group">
                                <label>No HP:</label>
                                <div class="form-control" readonly>{{ Auth::user()->no_hp }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @elseif (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endsection

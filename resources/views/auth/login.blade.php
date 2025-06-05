<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Sistem Deteksi</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Sistem</b>Deteksi</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Silakan login untuk melanjutkan</p>

      @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="social-auth-links text-center mt-2 mb-3">
            <button type="submit" class="btn btn-block btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </div>

      </form>

      <!-- Tambahkan link register di sini -->
        <p class="mb-1 mt-3 text-center">
        <a href="{{ route('register') }}">Belum punya akun? Daftar di sini</a>
        </p>

      {{-- Optional links --}}
      {{-- <p class="mb-1 mt-3">
        <a href="#">Lupa Password?</a>
      </p> --}}
    </div>
  </div>
</div>

<!-- JS -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
</body>
</html>

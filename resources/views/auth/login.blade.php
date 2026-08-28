<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Masuk — ClassEditor</title>
  <!-- 8KB custom CSS, zero CDN for speed -->
  <link rel="stylesheet" href="{{ asset('css/siswa.css') }}">
</head>
<body>
  <div class="login-page">
    <!-- Logo -->
    <div class="login-logo">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
      </svg>
    </div>
    <h1 class="login-title">ClassEditor</h1>
    <p class="login-subtitle">Platform Belajar Coding — SMKW9</p>

    <!-- Card -->
    <div class="login-card">
      <h2 class="login-card-title">Masuk ke Akun</h2>

      @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
          <label class="form-label">Username / NIS</label>
          <input type="text" name="username" value="{{ old('username') }}"
                 class="form-input" placeholder="Ketik username atau NIS"
                 autocomplete="username" autofocus required>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password"
                 class="form-input" placeholder="Ketik password"
                 autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px;">
          Masuk →
        </button>
      </form>
    </div>

    <p class="login-footer">SMKW9 · Pemrograman Dasar · HTML & CSS</p>
  </div>
</body>
</html>
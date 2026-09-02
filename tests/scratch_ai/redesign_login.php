<?php
$file = 'c:\laragon\www\liveeditor\resources\views\auth\login.blade.php';

$newLogin = <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - ClassEditor</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background-color: #f8fafc; color: #0f172a; min-height: 100vh; display: flex; }
    
    .split-layout { display: flex; width: 100%; min-height: 100vh; }
    
    /* LEFT SIDE - BRANDING */
    .branding-side {
        flex: 1.2;
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: white;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .branding-side::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
        pointer-events: none;
    }
    
    .logo-container { display: flex; align-items: center; gap: 12px; z-index: 1; }
    .logo-icon { width: 48px; height: 48px; background: white; color: #2563eb; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
    .logo-icon svg { width: 28px; height: 28px; }
    .logo-text { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; }
    
    .branding-hero { z-index: 1; margin: auto 0; }
    .branding-hero h1 { font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -1px; }
    .branding-hero p { font-size: 1.25rem; color: #bfdbfe; max-width: 480px; line-height: 1.6; font-weight: 500; }
    
    .branding-footer { z-index: 1; font-size: 0.875rem; color: #93c5fd; font-weight: 500; }
    
    /* RIGHT SIDE - LOGIN FORM */
    .login-side {
        flex: 1;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        box-shadow: -10px 0 30px rgba(0,0,0,0.03);
    }
    .login-box { width: 100%; max-width: 400px; }
    .login-box h2 { font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.5px; }
    .login-box p { color: #64748b; font-size: 1rem; margin-bottom: 32px; font-weight: 500; }
    
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 8px; }
    .form-input { 
        width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; 
        font-size: 1rem; color: #0f172a; transition: all 0.2s; outline: none; background: #f8fafc; font-weight: 500;
    }
    .form-input:focus { border-color: #3b82f6; background: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1); }
    
    .btn-login {
        width: 100%; padding: 16px; background: #2563eb; color: white; border: none; border-radius: 12px;
        font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px;
    }
    .btn-login:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37,99,235,0.2); }
    .btn-login:active { transform: translateY(0); }
    
    .alert { padding: 16px; border-radius: 12px; font-size: 0.875rem; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .alert-error { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
    
    /* MOBILE RESPONSIVE */
    @media(max-width: 900px) {
        .split-layout { flex-direction: column; }
        .branding-side { flex: none; padding: 40px 24px; text-align: center; align-items: center; border-bottom-left-radius: 32px; border-bottom-right-radius: 32px; }
        .branding-hero { margin: 40px 0; }
        .branding-hero h1 { font-size: 2.5rem; }
        .branding-hero p { font-size: 1rem; margin: 0 auto; }
        .branding-footer { display: none; }
        .login-side { padding: 40px 24px; background: transparent; box-shadow: none; align-items: flex-start; }
        body { background: #f8fafc; }
    }
  </style>
</head>
<body>
  <div class="split-layout">
      <!-- Kiri: Branding -->
      <div class="branding-side">
          <div class="logo-container">
              <div class="logo-icon">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
              </div>
              <div class="logo-text">ClassEditor</div>
          </div>
          
          <div class="branding-hero">
              <h1>Platform Live Code Editor HTML & CSS</h1>
              <p>Mulai perjalanan belajarmu di SMKW9. Tulis kode, lihat hasil seketika, dan dapatkan nilai otomatis tanpa ribet.</p>
          </div>
          
          <div class="branding-footer">
              &copy; {{ date('Y') }} SMKW9 - Pemrograman Dasar
          </div>
      </div>
      
      <!-- Kanan: Form -->
      <div class="login-side">
          <div class="login-box">
              <h2>Masuk ke Akun</h2>
              <p>Silakan masuk menggunakan kredensial Anda.</p>
              
              @if($errors->any())
                <div class="alert alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
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
                
                <button type="submit" class="btn-login">
                  Mulai Belajar
                  <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
              </form>
          </div>
      </div>
  </div>
</body>
</html>
HTML;

file_put_contents($file, $newLogin);
echo "Login page redesigned.\n";
?>
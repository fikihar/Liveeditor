<?php
$file = 'c:\laragon\www\liveeditor\resources\views\auth\login.blade.php';
$content = file_get_contents($file);

$search = <<<HTML
                <div class="form-group">
                  <label class="form-label">Password</label>
                  <input type="password" name="password"
                         class="form-input" placeholder="Ketik password"
                         autocomplete="current-password" required>
                </div>
HTML;

$replace = <<<HTML
                <div class="form-group">
                  <label class="form-label">Password</label>
                  <div style="position:relative;">
                      <input type="password" name="password" id="password"
                             class="form-input" placeholder="Ketik password"
                             autocomplete="current-password" required style="padding-right: 48px;">
                      <button type="button" onclick="togglePassword()" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:4px;" title="Tampilkan Password">
                          <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                      </button>
                  </div>
                </div>
HTML;

if (strpos($content, 'togglePassword()') === false) {
    $content = str_replace($search, $replace, $content);
    
    // Add the javascript at the end
    $js = <<<JS
  <script>
    function togglePassword() {
        const pass = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
        } else {
            pass.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
  </script>
</body>
JS;
    
    $content = str_replace('</body>', $js, $content);
    file_put_contents($file, $content);
    echo "Eye icon added to login.\n";
} else {
    echo "Eye icon already exists.\n";
}
?>
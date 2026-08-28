<?php
$file = 'c:\laragon\www\liveeditor\resources\views\layouts\guru.blade.php';
$html = file_get_contents($file);

$sweetAlert = <<<HTML
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const deleteForms = document.querySelectorAll('form.form-delete');
      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const message = form.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini secara permanen?';
          Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            backdrop: `rgba(15,23,42,0.6)`
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    });
  </script>
</body>
HTML;

$html = str_replace('</body>', $sweetAlert, $html);
file_put_contents($file, $html);
echo "SweetAlert added to Guru layout.\n";
?>
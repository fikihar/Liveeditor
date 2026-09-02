<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$content = file_get_contents($file);

// Replace the form's native onsubmit with an id and type="button" for sweetalert trigger
$oldForm = <<<HTML
<form action="{{ route('guru.tugas.force_submit', \$assignment) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tarik paksa semua tugas siswa yang belum dikumpulkan? (Status Draft akan diubah menjadi Dikumpulkan dan dinilai otomatis jika ada kriteria)')">
          @csrf
          <button type="submit" class="btn btn-primary btn-sm" style="background:#ef4444;border-color:#ef4444;">Tarik Paksa (Force Submit)</button>
        </form>
HTML;

$newForm = <<<HTML
<form id="forceSubmitForm" action="{{ route('guru.tugas.force_submit', \$assignment) }}" method="POST" style="display:inline;">
          @csrf
          <button type="button" onclick="confirmForceSubmit()" class="btn btn-primary btn-sm" style="background:#ef4444;border-color:#ef4444;">Tarik Paksa (Force Submit)</button>
        </form>
HTML;

$content = str_replace($oldForm, $newForm, $content);

// Add the sweetalert javascript at the end of the file
$js = <<<JS
  function confirmForceSubmit() {
      Swal.fire({
          title: 'Tarik Paksa Tugas?',
          text: "Semua tugas siswa yang masih Draft akan diubah menjadi Dikumpulkan dan dinilai otomatis (Auto-Grading). Tindakan ini tidak bisa dibatalkan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Ya, Tarik Paksa!',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              document.getElementById('forceSubmitForm').submit();
          }
      });
  }
</script>
@endsection
JS;

$content = preg_replace('/<\/script>\s*@endsection/is', "</script>\n" . $js, $content);

file_put_contents($file, $content);
echo "SweetAlert added for Force Submit.\n";
?>
# ClassEditor — Project Rules

## Tentang Project Ini
Ini adalah project **ClassEditor**, platform live code editor untuk pembelajaran HTML & CSS di SMKW9.
Selalu aktifkan skill classeditor saat bekerja di project ini.

## Stack & Konvensi
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8, nama DB: classeditor
- **CSS**: Tailwind CSS (utility-first, jangan pakai custom CSS kecuali terpaksa)
- **JS**: Alpine.js untuk interaktivitas ringan, CodeMirror 6 untuk editor
- **Real-time**: Laravel WebSockets (beyondcode/laravel-websockets)
- **Bahasa**: Komentar kode boleh bahasa Indonesia, nama variabel/fungsi bahasa Inggris

## Aturan Coding
- Selalu gunakan **Form Request** untuk validasi input
- Selalu gunakan **Policy** untuk otorisasi (jangan hardcode role check di controller)
- Gunakan **Resource Controller** untuk semua CRUD
- Semua route dikelompokkan dalam prefix dan middleware yang sesuai:
  - /guru/* → middleware uth + ole:guru
  - /siswa/* → middleware uth + ole:siswa
- Semua response AJAX menggunakan format JSON: {success: bool, message: str, data: any}
- Mobile-first: semua view harus responsif, terutama halaman editor siswa

## Database
- Nama database: **classeditor**
- Gunakan **soft deletes** untuk tabel users, classes, assignments
- Semua migration ada di database/migrations/
- Seeder di database/seeders/

## Keamanan
- Siswa hanya bisa akses assignment milik kelasnya sendiri
- Siswa tidak bisa melihat data siswa lain
- Guru hanya bisa kelola kelas miliknya sendiri
- Semua input di-sanitize sebelum dirender di iframe preview

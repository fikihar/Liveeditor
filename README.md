<h1 align="center">
  <br>
  📝 ClassEditor — Live Code Editor untuk Kelas SMK
  <br>
</h1>

<p align="center">
  Platform live coding berbasis web untuk pembelajaran HTML &amp; CSS di SMK,<br>
  dilengkapi monitoring siswa real-time, manajemen tugas, dan anti-distraksi.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL 8">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=flat&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat&logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/CodeMirror-6-D30707?style=flat" alt="CodeMirror 6">
</p>

---

## 📖 Tentang ClassEditor

**ClassEditor** adalah platform web yang dirancang khusus untuk guru SMK yang mengajar mata pelajaran Pemrograman Dasar (HTML & CSS). Platform ini menggantikan penggunaan W3Schools di HP siswa yang rawan distraksi (WhatsApp, game, dll).

Dengan ClassEditor, guru dapat:
- Membuat **latihan** dan **tugas** coding dengan starter code
- Memantau aktivitas siswa secara **real-time** (Live CCTV)
- Mendeteksi perilaku tidak jujur (**anti-cheat** via tab-switch tracking)
- Mengelola kelas dan siswa (manual atau import Excel)

Siswa mendapatkan pengalaman coding yang terfokus dengan:
- Editor HTML & CSS berbasis **CodeMirror 6** dengan preview langsung
- Mode **fullscreen** untuk meminimalkan gangguan
- Auto-save draft secara otomatis

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|---|---|
| 🖥️ Live Editor | CodeMirror 6 dengan tab HTML & CSS, preview real-time di iframe |
| 👁️ Monitoring Real-time | Dashboard guru menampilkan status siswa secara live (hadir, mengetik, dll) |
| 🔒 Anti-Cheat | Page Visibility API — setiap tab-switch siswa tercatat dan dilaporkan |
| 📋 Manajemen Tugas | Buat Latihan (bebas) & Tugas (deadline, dinilai) dengan starter code |
| 👥 Manajemen Kelas & Siswa | CRUD kelas & siswa, import massal via file Excel (.xlsx) |
| 📱 Mobile-First | UI responsif, nyaman dipakai di HP siswa |
| 🔐 Role-Based Auth | Dua peran: **Guru** (admin kelas) & **Siswa** (akses terbatas) |

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8 (nama DB: `classeditor`)
- **Frontend**: Tailwind CSS, Alpine.js
- **Code Editor**: CodeMirror 6
- **Real-time**: Laravel Reverb (WebSockets)
- **Import Excel**: Maatwebsite/Laravel-Excel

---

## 🚀 Cara Install (Development)

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8
- (Opsional) Laragon untuk Windows

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/fikihar/Liveeditor.git
cd Liveeditor

# 2. Install dependencies PHP
composer install

# 3. Install dependencies JS
npm install

# 4. Salin file konfigurasi
cp .env.example .env

# 5. Generate app key
php artisan key:generate
```

**Edit file `.env`**, sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=classeditor
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Build assets frontend
npm run dev

# 8. Jalankan server
php artisan serve
```

Buka browser di **http://localhost:8000**

---

## 👤 Akun Default (Seeder)

| Role | Username | Password |
|---|---|---|
| Guru | `guru` | `guru1234` |
| Siswa (contoh) | `NIS siswa` | `smk1234` |

> Password siswa bisa diganti sendiri setelah login.

---

## 🗂️ Struktur Database

```
users            → id, name, username(NIS), password, role(guru|siswa), class_id
classes          → id, name, guru_id
assignments      → id, class_id, title, description, type(latihan|tugas),
                   deadline, starter_html, starter_css, max_score, is_graded, status(draft|published)
grading_criteria → id, assignment_id, type(has_tag|has_css|has_attribute|has_text),
                   target, description, points
submissions      → id, assignment_id, student_id, html_code, css_code,
                   status(draft|submitted), score, submitted_at
activity_logs    → id, student_id, assignment_id, event(opened|tab_switch|submit|focus_lost), created_at
```

---

## 🗺️ Status Pengembangan

- [x] **Fase 1 — Foundation**: Auth role-based, CRUD Kelas & Siswa, import Excel, seeder
- [x] **Fase 2 — Editor & Tugas**: Live Editor CodeMirror 6, preview iframe, submit, auto-save draft
- [x] **Fase 3 — Monitoring Real-time**: Laravel Reverb WebSockets, Live CCTV guru, anti-cheat tab-switch
- [ ] **Fase 4 — Auto-Grading**: Form kriteria penilaian, engine grading otomatis via DOM checker
- [ ] **Fase 5 — Polish & Deploy**: Optimasi UI mobile, testing, panduan deploy VPS

---

## 📁 Struktur Route

```
/login               → Halaman login (semua role)
/guru/dashboard      → Dashboard guru + Live CCTV
/guru/kelas/*        → CRUD kelas
/guru/siswa/*        → CRUD siswa (termasuk import Excel)
/guru/tugas/*        → CRUD latihan & tugas + koreksi nilai
/siswa/dashboard     → Daftar tugas/latihan siswa
/siswa/editor/{id}   → Halaman live code editor
```

---

## 🔒 Keamanan & Kebijakan

- Siswa hanya bisa mengakses assignment milik kelasnya sendiri
- Guru hanya bisa mengelola kelas yang ia miliki
- Semua input di-sanitize sebelum dirender di iframe preview (sandbox)
- Setiap perpindahan tab/window oleh siswa dicatat ke `activity_logs`

---

## 📚 Referensi

- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [CodeMirror 6](https://codemirror.net/)
- [Laravel Reverb](https://laravel.com/docs/11.x/reverb)
- [Maatwebsite Excel](https://docs.laravel-excel.com/)
- [Tailwind CSS](https://tailwindcss.com/)

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran di **SMKW9** dan bersifat open-source.  
Silakan digunakan dan dikembangkan sesuai kebutuhan.

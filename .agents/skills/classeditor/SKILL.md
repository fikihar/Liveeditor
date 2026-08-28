---
name: classeditor
description: >-
  Skill khusus untuk project ClassEditor - platform live code editor (HTML/CSS)
  untuk pembelajaran di SMK, dengan fitur monitoring siswa real-time, manajemen
  tugas/latihan, dan auto-grading. Aktifkan skill ini ketika user meminta untuk
  melanjutkan, mengerjakan, atau mendiskusikan project ClassEditor.
---

# ClassEditor - Live Code Editor Platform untuk Kelas SMK

## Konteks Project

**ClassEditor** adalah platform web untuk guru SMK yang mengajar Pemrograman Dasar (HTML & CSS).
Platform ini menggantikan penggunaan W3Schools di HP siswa yang rawan distraksi (WA, game, dll).

### Informasi Guru (Owner)
- Mengajar di **SMKW9**
- Mapel: **Pemrograman Dasar (Pemdas)** - HTML & CSS
- Kelas: **X TJKT A** dan **X TJKT B** (masing-masing 36 siswa = 72 siswa total)
- Deploy target: **VPS sekolah**
- Project path: **c:\laragon\www\liveeditor**

---

## Keputusan Desain yang Sudah Disepakati

| Aspek | Keputusan |
|---|---|
| Tech Stack | **Laravel 11 + MySQL + Tailwind CSS + Alpine.js** |
| Code Editor | **CodeMirror 6** (ringan, support mobile) |
| Real-time | **Laravel WebSockets** (self-hosted) |
| Login siswa | Guru buat akun, **username = NIS siswa** |
| Password awal | smk1234 (bisa diubah sendiri) |
| Import siswa | Upload **Excel** (.xlsx) via Maatwebsite/Excel |
| Jenis konten | **Latihan** (bebas, tidak dinilai) & **Tugas** (deadline, dinilai) |
| Auto-grading | Guru definisikan kriteria - sistem cek DOM via iframe sandbox |
| Anti-distraksi | Fullscreen mode + **Page Visibility API** (tab switch tercatat) |
| Tema UI | Biru + Putih, mobile-first |
| Nama platform | ClassEditor |

---

## Database Schema

`
users            -> id, name, username(NIS), password, role(guru|siswa), class_id
classes          -> id, name, guru_id
assignments      -> id, class_id, title, description, type(latihan|tugas),
                   deadline, starter_html, starter_css, max_score, is_graded, status(draft|published)
grading_criteria -> id, assignment_id, type(has_tag|has_css|has_attribute|has_text),
                   target, description, points
submissions      -> id, assignment_id, student_id, html_code, css_code,
                   status(draft|submitted), score, submitted_at
activity_logs    -> id, student_id, assignment_id, event(opened|tab_switch|submit|focus_lost), created_at
`

---

## Fase Development & Status

### Fase 1 - Foundation (SELESAI âœ…)
- [x] Setup Laravel 11 project
- [x] Install: Tailwind CDN, Alpine CDN, Laravel Reverb, Maatwebsite/Excel
- [x] Konfigurasi .env MySQL (database: classeditor)
- [x] Buat semua migration (9 tabel)
- [x] Role-based auth (Guru & Siswa) + RoleMiddleware
- [x] Seeder: guru (user:guru pw:guru1234) + X TJKT A & B + 10 siswa contoh
- [x] CRUD Kelas
- [x] CRUD Siswa (manual + import Excel via StudentsImport)
- [x] Dashboard guru sederhana

### Fase 2 - Editor & Tugas (SELESAI ✅)
- [x] CRUD Latihan & Tugas + starter code
- [x] Halaman daftar tugas/latihan siswa (mobile-friendly)
- [x] Live Editor: CodeMirror 6 (tab HTML + CSS)
- [x] Preview real-time di iframe sandbox
- [x] Fullscreen mode + Page Visibility API
- [x] Tombol Submit + auto-save draft

### Fase 3 - Monitoring Real-time (SELESAI ✅)
- [x] Setup Laravel WebSockets (Menggunakan Laravel Reverb)
- [x] Broadcast events: siswa_aktif (hadir), tab_switch (anti-cheat), mengetik
- [x] Dashboard guru real-time dengan status siswa (Live CCTV)

### Fase 4 - Auto-Grading (BELUM MULAI)
- [ ] Form kriteria penilaian saat buat tugas
- [ ] Auto-grading engine (iframe + JS DOM checker)
- [ ] Hitung skor otomatis saat submit

### Fase 5 - Polish & Deploy (BELUM MULAI)
- [ ] Optimasi mobile UI
- [ ] Testing end-to-end
- [ ] Panduan deploy VPS (Nginx + PHP 8.2 + MySQL)

---

## Cara Melanjutkan Project

Setiap kali melanjutkan project ini:
1. Baca file SKILL.md ini untuk refresh konteks
2. Jalankan: dir c:\laragon\www\liveeditor untuk cek progress
3. Cek status fase di atas dan lanjutkan dari task yang belum selesai
4. Update checklist setelah selesai setiap task

---

## Referensi Dokumentasi
- Laravel 11: https://laravel.com/docs/11.x
- CodeMirror 6: https://codemirror.net/
- Laravel WebSockets: https://beyondco.de/docs/laravel-websockets
- Maatwebsite Excel: https://docs.laravel-excel.com/

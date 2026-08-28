---
name: classeditor
description: Skill khusus untuk project ClassEditor - platform live code editor (HTML/CSS) untuk pembelajaran di SMK.
---

# ClassEditor — Panduan & Konteks

## Konvensi Penting
- Gunakan Laravel 11, Tailwind CSS, Alpine.js, CodeMirror 6, Laravel Reverb.
- Semua form wajib pakai Form Request & Policy.
- Prefix route dan middleware: guru (Auth+Role:guru) dan siswa (Auth+Role:siswa).

## Fase Development & Status

### Fase 1 - Foundation (SELESAI ✅)
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
- [x] Tombol Submit 1x + Tombol Reset
- [x] Auto-save draft

### Fase 3 - Monitoring Real-time (SELESAI ✅)
- [x] Setup Laravel WebSockets (Menggunakan Laravel Reverb)
- [x] Broadcast events: siswa_aktif (hadir), tab_switch (anti-cheat), mengetik
- [x] Dashboard guru real-time dengan Live CCTV
- [x] Rekam jejak Nyontek Permanen (cheat_count)

### Fase 4 - Auto-Grading (BELUM MULAI)
- [ ] Form kriteria penilaian saat buat tugas
- [ ] Auto-grading engine (iframe + JS DOM checker)
- [ ] Hitung skor otomatis saat submit

### Fase 5 - Polish & Deploy (BELUM MULAI)
- [ ] Optimasi mobile UI
- [ ] Testing end-to-end
- [ ] Panduan deploy VPS (Nginx + PHP 8.2 + MySQL)

---
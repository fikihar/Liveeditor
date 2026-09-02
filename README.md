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
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
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

## 🛠️ Tech Stack & Packages

### Backend (PHP / Composer)

| Package | Versi | Keterangan | Status |
|---|---|---|---|
| Laravel Framework | ^12.0 | Core framework | ✅ Otomatis via `composer install` |
| Laravel Reverb | ^1.11 | WebSocket server (real-time monitoring) | ✅ Otomatis via `composer install` |
| Maatwebsite/Excel | 3.1.70 | Import data siswa dari file .xlsx | ✅ Otomatis via `composer install` |
| Laravel Tinker | ^2.10 | REPL untuk debugging | ✅ Otomatis via `composer install` |

### Frontend (JavaScript / NPM)

| Package | Versi | Keterangan | Status |
|---|---|---|---|
| Tailwind CSS | ^4.3 | Utility-first CSS framework | ✅ Otomatis via `npm install` |
| Alpine.js | ^3.16 | Reaktivitas ringan di frontend | ✅ Otomatis via `npm install` |
| Vite | ^5.4 | Build tool & hot-reload | ✅ Otomatis via `npm install` |
| Axios | ^1.11 | HTTP client untuk request AJAX | ✅ Otomatis via `npm install` |
| Concurrently | ^9.0 | Jalankan banyak proses sekaligus | ✅ Otomatis via `npm install` |

> **Semua package sudah terdaftar di `composer.json` dan `package.json`.**
> Tidak perlu install manual satu per satu — cukup jalankan `composer install` dan `npm install`.

---

## 🚀 Panduan Instalasi

### Prasyarat

Pastikan sudah terinstall di sistem:
- **PHP 8.2+** — cek: `php -v`
- **Composer** — cek: `composer -V`
- **Node.js 18+ & NPM** — cek: `node -v` dan `npm -v`
- **MySQL 8** — cek: `mysql --version`

> 💡 Di Windows, disarankan menggunakan **[Laragon](https://laragon.org/)** — sudah menyertakan PHP, MySQL, dan Composer dalam satu paket.

---

### Langkah 1 — Clone & Install Dependencies

```bash
# Clone repository
git clone https://github.com/fikihar/Liveeditor.git
cd Liveeditor

# Install semua package PHP (termasuk Reverb, Maatwebsite/Excel, dll)
composer install

# Install semua package JS (termasuk Tailwind CSS, Alpine.js, Vite, dll)
npm install
```

---

### Langkah 2 — Konfigurasi Environment

```bash
# Salin file konfigurasi
cp .env.example .env

# Generate application key
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME=ClassEditor
APP_URL=http://localhost:8000

# -- Database (wajib diisi) --
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=classeditor
DB_USERNAME=root
DB_PASSWORD=

# -- Broadcasting (aktifkan Reverb) --
BROADCAST_CONNECTION=reverb

# -- Queue (wajib untuk broadcast event) --
QUEUE_CONNECTION=database

# -- Laravel Reverb --
REVERB_APP_ID=classeditor-app
REVERB_APP_KEY=classeditor-key
REVERB_APP_SECRET=classeditor-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

### Langkah 3 — Setup Database

Buat database `classeditor` di MySQL terlebih dahulu:

```sql
CREATE DATABASE classeditor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Lalu jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Seeder akan otomatis membuat:
- Akun guru: `username: guru` / `password: guru1234`
- 2 kelas: **X TJKT A** dan **X TJKT B**
- 10 akun siswa contoh per kelas (password: `smk1234`)

---

### Langkah 4 — Build Frontend

```bash
# Mode development (dengan hot-reload)
npm run dev

# Atau mode production (untuk deploy)
npm run build
```

---

### Langkah 5 — Jalankan Semua Server

Aplikasi ini membutuhkan **3 proses** berjalan bersamaan:

| Proses | Perintah | Keterangan |
|---|---|---|
| Web Server | `php artisan serve` | Server utama Laravel |
| WebSocket Server | `php artisan reverb:start` | Untuk fitur real-time monitoring |
| Queue Worker | `php artisan queue:listen` | Untuk memproses broadcast event |

**Cara 1 — Jalankan satu per satu** (buka 3 terminal terpisah):

```bash
# Terminal 1
php artisan serve

# Terminal 2
php artisan reverb:start

# Terminal 3
php artisan queue:listen
```

**Cara 2 — Jalankan sekaligus** (1 terminal, pakai Concurrently yang sudah include di package.json):

```bash
composer run dev
```

Buka browser di **http://localhost:8000**

---

---

## 🌍 Panduan Deployment VPS (Production)

Untuk meng-online-kan aplikasi ini ke VPS (contoh: Ubuntu 22.04 / 24.04), ikuti langkah-langkah berikut:

### 1. Persiapan Server
Pastikan VPS Anda sudah terinstall:
- **Nginx** (Web Server)
- **PHP 8.2+** (beserta ekstensi: `php-fpm, php-mysql, php-xml, php-zip, php-curl, php-mbstring`)
- **MySQL 8** atau MariaDB
- **Composer** & **Node.js/NPM**

### 2. Setup Proyek
```bash
cd /var/www
git clone https://github.com/fikihar/Liveeditor.git classeditor
cd classeditor

# Install dependensi
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Atur permission
chown -R www-data:www-data /var/www/classeditor
chmod -R 775 storage bootstrap/cache
```

### 3. Konfigurasi `.env` Production
Ubah pengaturan di `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

# Reverb harus diatur ke domain asli untuk production
REVERB_HOST="0.0.0.0"
REVERB_PORT=8080
REVERB_SCHEME=https

VITE_REVERB_HOST="domain-anda.com"
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```
Lalu jalankan: `php artisan migrate --force` (dan `--seed` jika database masih kosong).

### 4. Konfigurasi Nginx (Reverse Proxy Reverb & Web)
Buat file konfigurasi di `/etc/nginx/sites-available/classeditor`:
```nginx
server {
    listen 80;
    server_name domain-anda.com;
    root /var/www/classeditor/public;
    index index.php;

    # Konfigurasi standar Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Proxy untuk Laravel Reverb (WebSocket)
    location /app {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
```
Aktifkan dan restart Nginx: `sudo ln -s /etc/nginx/sites-available/classeditor /etc/nginx/sites-enabled/ && sudo systemctl restart nginx`

### 5. Setup Supervisor (Menjaga Reverb & Queue 24/7)
Install Supervisor: `sudo apt install supervisor`

Buat file konfigurasi penjaga Reverb di `/etc/supervisor/conf.d/classeditor-reverb.conf`:
```ini
[program:classeditor-reverb]
process_name=%(program_name)s
command=php /var/www/classeditor/artisan reverb:start --host="0.0.0.0" --port=8080
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/classeditor/storage/logs/reverb.log
```

Buat file konfigurasi penjaga Queue di `/etc/supervisor/conf.d/classeditor-queue.conf`:
```ini
[program:classeditor-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/classeditor/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/classeditor/storage/logs/queue.log
```

Jalankan Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

> **Catatan:** Jangan lupa pasang SSL (HTTPS) menggunakan **Certbot / Let's Encrypt** agar WebSocket `wss://` bisa berjalan di production dengan aman.

## 👤 Akun Default (Seeder)

| Role | Username | Password |
|---|---|---|
| Guru | `guru` | `guru1234` |
| Siswa (contoh) | NIS siswa | `smk1234` |

> Password siswa dapat diganti sendiri setelah login pertama kali.

---

## 🗂️ Struktur Database

```
users            -> id, name, username(NIS), password, role(guru|siswa), class_id
classes          -> id, name, guru_id
assignments      -> id, class_id, title, description, type(latihan|tugas),
                   deadline, starter_html, starter_css, max_score, is_graded, status(draft|published)
grading_criteria -> id, assignment_id, type(has_tag|has_css|has_attribute|has_text),
                   target, description, points
submissions      -> id, assignment_id, student_id, html_code, css_code,
                   status(draft|submitted), score, submitted_at
activity_logs    -> id, student_id, assignment_id, event(opened|tab_switch|submit|focus_lost), created_at
```

---

## 🗺️ Status Pengembangan

- [x] **Fase 1 — Foundation**: Auth role-based, CRUD Kelas & Siswa, import Excel, seeder
- [x] **Fase 2 — Editor & Tugas**: Live Editor CodeMirror 6, preview iframe, submit, auto-save draft
- [x] **Fase 3 — Monitoring Real-time**: Laravel Reverb WebSockets, Live CCTV guru, anti-cheat tab-switch
- [ ] **Fase 4 — Auto-Grading**: Form kriteria penilaian, engine grading otomatis via DOM checker
- [x] **Fase 5 — Polish & Deploy**: Optimasi UI mobile, testing, panduan deploy VPS

---

## 📁 Struktur Route

```
/login               -> Halaman login (semua role)
/guru/dashboard      -> Dashboard guru + Live CCTV
/guru/kelas/*        -> CRUD kelas
/guru/siswa/*        -> CRUD siswa (termasuk import Excel)
/guru/tugas/*        -> CRUD latihan & tugas + koreksi nilai
/siswa/dashboard     -> Daftar tugas/latihan siswa
/siswa/editor/{id}   -> Halaman live code editor
```

---

## 🔒 Keamanan & Kebijakan

- Siswa hanya bisa mengakses assignment milik kelasnya sendiri
- Guru hanya bisa mengelola kelas yang ia miliki
- Semua input di-sanitize sebelum dirender di iframe preview (sandbox)
- Setiap perpindahan tab/window oleh siswa dicatat ke `activity_logs`

---

## Troubleshooting

**Fitur real-time / monitoring siswa tidak jalan:**
- Pastikan `php artisan reverb:start` sudah berjalan
- Pastikan `php artisan queue:listen` sudah berjalan
- Pastikan `BROADCAST_CONNECTION=reverb` di `.env`
- Pastikan `QUEUE_CONNECTION=database` di `.env`

**Error saat migrate:**
- Pastikan database `classeditor` sudah dibuat di MySQL
- Pastikan `DB_CONNECTION=mysql` (bukan `sqlite`) di `.env`

**Import Excel gagal:**
- Pastikan ekstensi PHP `php_zip` dan `php_xml` aktif di `php.ini`
- Di Laragon: klik kanan tray icon -> PHP Extensions -> centang `zip` dan `xml`

---

## 📚 Referensi

- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Laravel Reverb Docs](https://laravel.com/docs/11.x/reverb)
- [CodeMirror 6](https://codemirror.net/)
- [Maatwebsite Excel](https://docs.laravel-excel.com/)
- [Tailwind CSS v4](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran di **SMKW9** dan bersifat open-source.
Silakan digunakan dan dikembangkan sesuai kebutuhan.

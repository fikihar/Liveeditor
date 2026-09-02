<?php
$file = 'c:\laragon\www\liveeditor\README.md';
$content = file_get_contents($file);

$vps_section = <<<MARKDOWN
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
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # Proxy untuk Laravel Reverb (WebSocket)
    location /app {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host \$host;
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

MARKDOWN;

$content = str_replace('## 👤 Akun Default (Seeder)', $vps_section . "\n## 👤 Akun Default (Seeder)", $content);
// Check off phase 5
$content = str_replace('- [ ] **Fase 5 — Polish & Deploy**: Optimasi UI mobile, testing, panduan deploy VPS', '- [x] **Fase 5 — Polish & Deploy**: Optimasi UI mobile, testing, panduan deploy VPS', $content);

file_put_contents($file, $content);
echo "README updated with VPS Deployment guide.\n";
?>
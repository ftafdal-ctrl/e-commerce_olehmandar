# ============================================================
# SETUP olehMandar — Cara Menjalankan Aplikasi
# ============================================================

## OPSI 1: Menggunakan `php artisan serve` (PALING MUDAH)
# -------------------------------------------------------
# Tidak perlu konfigurasi tambahan apapun.

# 1. Jalankan server:
#    php artisan serve
#
# 2. Buka browser: http://127.0.0.1:8000
#
# 3. Untuk CSS live-reload (development):
#    npm run dev
#    (jalankan di terminal terpisah)
#
# 4. Untuk production (CSS sudah ter-build):
#    npm run build  (sudah dijalankan, tidak perlu lagi)


## OPSI 2: Menggunakan Laragon dengan domain `olehmandar.test`
# -------------------------------------------------------
# Lakukan langkah berikut SATU KALI saja:

# Langkah 1 — Buat file Nginx vhost di Laragon:
#   Buka: F:\KULIAH\LARAGON\laragon\etc\nginx\sites-enabled\
#   Buat file baru: auto.olehmandar.test.conf
#   Isi dengan konten di bawah ini:
#
# ---COPY MULAI DARI SINI---
# server {
#     listen 80;
#     server_name olehmandar.test *.olehmandar.test;
#     root "F:/KULIAH/IBM BOB/e-commerce/public";
#     index index.html index.htm index.php;
#     access_log "F:/KULIAH/LARAGON/laragon/logs/nginx/olehmandar.test-access.log";
#     error_log  "F:/KULIAH/LARAGON/laragon/logs/nginx/olehmandar.test-error.log";
#     location / {
#         try_files $uri $uri/ /index.php$is_args$args;
#     }
#     location ~ \.php$ {
#         include snippets/fastcgi-php.conf;
#         fastcgi_pass   php_upstream;
#         fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
#     }
#     charset utf-8;
#     client_max_body_size 100M;
#     location ~ /\.ht { deny all; }
# }
# ---COPY SAMPAI SINI---

# Langkah 2 — Update .env:
#   APP_URL=http://olehmandar.test

# Langkah 3 — Restart Laragon (klik kanan icon Laragon → Reload/Restart)

# Langkah 4 — Buka browser: http://olehmandar.test
# (Laragon otomatis menambahkan ke hosts file Windows)


## OPSI 3: Deploy publik ke Render
# -------------------------------------------------------
# Panduan Docker, PostgreSQL, disk persisten, dan akun admin:
# lihat RENDER.md


## CATATAN PENTING
# -------------------------------------------------------
# - File CSS sudah di-build ke: public/build/assets/
# - TIDAK perlu `npm run dev` untuk production
# - Jika mengubah CSS/JS, jalankan: npm run build
# - Untuk development dengan hot-reload: npm run dev

# Deploy olehMandar ke Render

Repository ini sudah memiliki Dockerfile dan Render Blueprint. Deploy membuat Laravel web service di Singapore dengan disk persisten 1 GB untuk foto produk, kategori, dan bukti pembayaran. PostgreSQL dibuat terpisah lalu dihubungkan saat Blueprint disiapkan.

> **Biaya:** Blueprint memakai web service `starter` karena Render memerlukan service berbayar untuk persistent disk. PostgreSQL dikelola terpisah dan biayanya mengikuti paket yang dipilih. Periksa harga terkini di dashboard Render sebelum membuat resource.

## 1. Siapkan GitHub

1. Pastikan `.env` tidak di-commit. `.env` berisi konfigurasi lokal dan rahasia.
2. Push seluruh perubahan proyek ke repository GitHub.
3. Jangan pernah mengunggah `APP_KEY`, password database, atau password admin ke GitHub.

## 2. Buat PostgreSQL dan web service

1. Buat database PostgreSQL di Render, pilih region Singapore, lalu salin **Internal Database URL**. Untuk penggunaan production, pilih paket berbayar yang sesuai. Database Free memiliki batasan dan akan kedaluwarsa setelah 30 hari.
2. Di Render, pilih **New > Blueprint** dan hubungkan repository GitHub.
3. Saat diminta mengisi `DATABASE_URL`, tempel Internal Database URL. Jangan gunakan External Database URL untuk service yang sama di Render.
4. Tinjau Blueprint: ini membuat web service `starter` dan persistent disk 1 GB, keduanya berbayar. Pastikan biaya yang ditampilkan sesuai sebelum menyetujui.
5. Pastikan `APP_KEY` dibuat otomatis oleh Blueprint. Jangan menggantinya saat service sudah menyimpan data, karena session dan data terenkripsi lama dapat tidak lagi terbaca.
6. Tunggu deploy selesai. Entrypoint menjalankan `php artisan migrate --force` sebelum Apache dimulai.

Render menyediakan `RENDER_EXTERNAL_URL`; aplikasi menggunakannya sebagai `APP_URL` jika Anda tidak mengisi `APP_URL` sendiri.

## 3. Isi katalog dan buat admin pertama

Setelah database tersambung dan service hidup:

1. Buka **Shell** pada web service Render.
2. Jalankan `php artisan db:seed --force` **satu kali** untuk mengisi kategori dan produk contoh. Seeder tidak membuat akun demo di production.
3. Jalankan `php artisan tinker`, lalu buat akun admin dengan email Anda dan password yang kuat:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'email-anda@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make('GANTI_DENGAN_PASSWORD_KUAT'),
    'role' => 'admin',
]);
```

Ganti kedua nilai contoh sebelum menjalankan perintah. Jangan membagikan password admin atau menyimpannya di Git.

## Catatan operasional

- Disk persisten menyimpan file di `/var/data/public`, dipetakan Laravel ke URL `/storage/...`. Disk Render membuat deployment service berjalan dengan satu instance; jangan menambah instance tanpa memindahkan unggahan ke object storage.
- QRIS toko (`public/images/qris.png`) dan aset statis berada di image deployment dan ikut diperbarui setiap deploy.
- Database dan disk adalah resource berbeda. Buat backup database dan simpan salinan file penting di luar service.
- Setiap start/deploy menjalankan migrasi. Buat perubahan skema secara kompatibel dengan versi aplikasi yang sedang berjalan.
- Deploy berikutnya otomatis berjalan saat Anda push perubahan ke branch yang terhubung di Render.

## Troubleshooting

- Jika deploy gagal sebelum Apache hidup, periksa log service dan pastikan `DATABASE_URL` berisi Internal Database URL PostgreSQL yang benar.
- Jika unggahan hilang setelah deploy, pastikan disk `olemandar-storage` terpasang di `/var/data`.
- Jika URL aset menggunakan domain lokal, pastikan `RENDER_EXTERNAL_URL` tersedia atau isi `APP_URL` dengan URL HTTPS service Anda.

# olehMandar — E-Commerce Oleh-Oleh Khas Mandar — Plan

## Top-Level Overview

Membangun aplikasi e-commerce **olehMandar** untuk penjualan produk oleh-oleh khas Mandar (Baje, Golla Kambu, Kasippi, dll.) menggunakan **Laravel + MySQL + Blade + Tailwind CSS**.

Fitur utama:
- Katalog produk dengan kategori
- Keranjang belanja
- Checkout dengan pembayaran manual via QRIS
- Konfirmasi pembayaran oleh admin
- Tracking status pengiriman pesanan
- Autentikasi pengguna (login/register)
- Admin Panel: manajemen produk, stok, pesanan, pembayaran, dan pengiriman

Pendekatan: Laravel monolith dengan Blade views, middleware role-based (user vs admin), dan session-based cart.

---

## Sub-Tasks

---

### Sub-Task 1 — Setup Project Laravel

**Intent:**
Inisialisasi project Laravel baru dengan konfigurasi database, Tailwind CSS, dan struktur folder dasar.

**Expected Outcomes:**
- Project Laravel berjalan di localhost
- Database MySQL terhubung via `.env`
- Tailwind CSS terintegrasi via Vite
- Route dasar (`/`) menampilkan halaman welcome toko

**Todo List:**
- [ ] Buat project Laravel baru: `composer create-project laravel/laravel .`
- [ ] Konfigurasi `.env`: nama database `ecommerce_mandar`, charset utf8mb4
- [ ] Install Tailwind CSS via npm + konfigurasi `vite.config.js` dan `tailwind.config.js`
- [ ] Buat layout utama `resources/views/layouts/app.blade.php` dengan navbar dan footer
- [ ] Buat layout admin `resources/views/layouts/admin.blade.php`
- [ ] Set route `/` mengarah ke halaman katalog produk

**Relevant Context:**
- File `.env` untuk konfigurasi DB
- `resources/views/layouts/` untuk master layout
- `routes/web.php` untuk semua routing

**Status:** `[ ] pending`

---

### Sub-Task 2 — Database Schema & Migrations

**Intent:**
Membuat semua tabel database yang dibutuhkan sesuai domain e-commerce ini.

**Expected Outcomes:**
- Semua migration berhasil dijalankan (`php artisan migrate`)
- Tabel: `users`, `categories`, `products`, `carts`, `cart_items`, `orders`, `order_items`, `payments`, `shipping_statuses` terbuat

**Todo List:**
- [ ] Migration `users` — tambahkan kolom `role` (enum: `user`, `admin`, default `user`)
- [ ] Migration `categories` — `id`, `name`, `slug`, `image`, `timestamps`
- [ ] Migration `products` — `id`, `category_id` (FK), `name`, `slug`, `description`, `price`, `stock`, `image`, `is_active`, `timestamps`
- [ ] Migration `carts` — `id`, `user_id` (FK nullable), `session_id`, `timestamps`
- [ ] Migration `cart_items` — `id`, `cart_id` (FK), `product_id` (FK), `quantity`, `timestamps`
- [ ] Migration `orders` — `id`, `user_id` (FK), `order_number`, `status` (enum: `pending`, `confirmed`, `processing`, `shipped`, `delivered`, `cancelled`), `total_amount`, `shipping_address`, `shipping_name`, `shipping_phone`, `notes`, `timestamps`
- [ ] Migration `order_items` — `id`, `order_id` (FK), `product_id` (FK), `product_name`, `product_price`, `quantity`, `subtotal`, `timestamps`
- [ ] Migration `payments` — `id`, `order_id` (FK), `bank_name`, `account_number`, `account_name`, `amount`, `proof_image`, `status` (enum: `pending`, `confirmed`, `rejected`), `confirmed_at`, `timestamps`
- [ ] Migration `shipping_statuses` — `id`, `order_id` (FK), `status`, `description`, `updated_by` (FK users), `timestamps`
- [ ] Buat Eloquent Models untuk semua tabel dengan relasi yang tepat
- [ ] Buat Seeder untuk data awal: 1 admin user, 3 kategori, 5-10 produk oleh-oleh Mandar

**Relevant Context:**
- `database/migrations/`
- `app/Models/`
- `database/seeders/`

**Status:** `[ ] pending`

---

### Sub-Task 3 — Autentikasi User (Login, Register, Middleware Role)

**Intent:**
Menyediakan sistem autentikasi untuk pembeli dan admin dengan pemisahan akses berbasis role.

**Expected Outcomes:**
- User bisa register, login, logout
- Middleware `AdminMiddleware` memblokir non-admin dari rute `/admin/*`
- Middleware `AuthMiddleware` memblokir guest dari checkout dan riwayat pesanan

**Todo List:**
- [ ] Install Laravel Breeze (auth scaffold minimal) atau buat manual controller auth
- [ ] Buat `RegisterController` — validasi, hash password, simpan user dengan role `user`
- [ ] Buat `LoginController` — validasi kredensial, session login
- [ ] Buat `app/Http/Middleware/AdminMiddleware.php` — cek `auth()->user()->role === 'admin'`
- [ ] Daftarkan middleware di `bootstrap/app.php` atau `app/Http/Kernel.php`
- [ ] Buat views: `login.blade.php`, `register.blade.php`
- [ ] Tampilkan nama user di navbar, tombol logout

**Relevant Context:**
- `app/Http/Controllers/Auth/`
- `app/Http/Middleware/`
- `routes/web.php` — grup route `auth` dan `admin`

**Status:** `[ ] pending`

---

### Sub-Task 4 — Katalog Produk (Halaman Utama & Detail Produk)

**Intent:**
Menampilkan semua produk oleh-oleh Mandar kepada pengunjung dengan filter kategori dan halaman detail produk.

**Expected Outcomes:**
- Halaman `/` dan `/products` menampilkan grid produk aktif
- Filter kategori berfungsi (Baje, Golla Kambu, Kasippi, dll.)
- Halaman `/products/{slug}` menampilkan detail produk lengkap
- Produk yang stok habis ditandai "Habis"

**Todo List:**
- [ ] Buat `ProductController@index` — query produk aktif + filter kategori, pagination
- [ ] Buat `ProductController@show` — query produk by slug
- [ ] Buat view `products/index.blade.php` — grid card produk + filter kategori sidebar/tab
- [ ] Buat view `products/show.blade.php` — detail: gambar, nama, harga, deskripsi, stok, tombol "Tambah ke Keranjang"
- [ ] Buat view komponen `_product-card.blade.php`
- [ ] Daftarkan route `GET /products` dan `GET /products/{slug}`

**Relevant Context:**
- `app/Http/Controllers/ProductController.php`
- `resources/views/products/`
- Model `Product` dengan relasi `belongsTo(Category)`

**Status:** `[ ] pending`

---

### Sub-Task 5 — Keranjang Belanja (Cart)

**Intent:**
Memungkinkan pengunjung (guest dan user login) menambah, mengubah, dan menghapus produk dari keranjang.

**Expected Outcomes:**
- Tombol "Tambah ke Keranjang" di halaman produk berfungsi
- Halaman `/cart` menampilkan daftar item + subtotal + total
- Quantity bisa diubah, item bisa dihapus
- Jumlah item keranjang tampil di navbar

**Todo List:**
- [ ] Buat `CartController` dengan method: `index`, `store`, `update`, `destroy`
- [ ] Untuk guest: simpan cart di session. Untuk user login: simpan ke tabel `carts` + `cart_items`
- [ ] Buat service `CartService` untuk logika unifikasi cart (session vs DB)
- [ ] Buat view `cart/index.blade.php` — tabel item, input quantity, tombol hapus, ringkasan total
- [ ] Tambahkan badge item count di navbar (via session/cart helper)
- [ ] Merge cart session ke DB saat user login
- [ ] Daftarkan route: `GET /cart`, `POST /cart`, `PATCH /cart/{id}`, `DELETE /cart/{id}`

**Relevant Context:**
- `app/Http/Controllers/CartController.php`
- `app/Services/CartService.php`
- `resources/views/cart/`
- Model `Cart`, `CartItem`

**Status:** `[ ] pending`

---

### Sub-Task 6 — Checkout & Pembuatan Order

**Intent:**
Mengolah proses checkout: input alamat pengiriman, review pesanan, dan simpan order ke database.

**Expected Outcomes:**
- Halaman `/checkout` hanya bisa diakses user yang sudah login
- Form checkout: nama, nomor HP, alamat lengkap, catatan
- Setelah submit, order tersimpan di DB dengan status `pending`
- User diarahkan ke halaman konfirmasi pesanan + instruksi pembayaran

**Todo List:**
- [ ] Buat `CheckoutController@index` — tampilkan review cart + form alamat
- [ ] Buat `CheckoutController@store` — validasi form, buat `Order`, buat `OrderItem` dari cart, kosongkan cart
- [ ] Generate `order_number` unik (contoh: `MDR-20241201-0001`)
- [ ] Buat view `checkout/index.blade.php` — form + ringkasan pesanan
- [ ] Buat view `checkout/success.blade.php` — nomor order + instruksi pembayaran QRIS
- [ ] Tampilkan gambar QRIS toko dari `public/images/qris.png` di halaman pembayaran
- [ ] Daftarkan route: `GET /checkout`, `POST /checkout`

**Relevant Context:**
- `app/Http/Controllers/CheckoutController.php`
- `resources/views/checkout/`
- Model `Order`, `OrderItem`

**Status:** `[ ] pending`

---

### Sub-Task 7 — Konfirmasi Pembayaran (User)

**Intent:**
Memungkinkan user mengunggah bukti pembayaran QRIS sebagai konfirmasi pembayaran manual.

**Expected Outcomes:**
- Halaman riwayat pesanan user menampilkan semua order
- Untuk order dengan status `pending`, ada tombol "Upload Bukti Pembayaran QRIS"
- User dapat menghapus riwayat pesanan berstatus `delivered` miliknya
- Setelah upload, record `payments` tersimpan dengan status `pending`
- User menerima notifikasi bahwa pembayaran sedang diverifikasi

**Todo List:**
- [ ] Buat `OrderController@index` (user) — list semua order milik user login
- [ ] Buat `OrderController@show` (user) — detail order + status pembayaran + tracking
- [ ] Buat `PaymentController@store` — validasi + simpan bukti pembayaran QRIS ke storage, buat record `payments`
- [ ] Buat view `orders/index.blade.php` — list pesanan user
- [ ] Buat view `orders/show.blade.php` — detail pesanan + form upload bukti pembayaran QRIS + history status
- [ ] Daftarkan route: `GET /orders`, `GET /orders/{orderNumber}`, `DELETE /orders/{orderNumber}`, `POST /orders/{orderNumber}/payment`

**Relevant Context:**
- `app/Http/Controllers/OrderController.php`
- `app/Http/Controllers/PaymentController.php`
- `resources/views/orders/`
- Model `Order`, `Payment`

**Status:** `[ ] pending`

---

### Sub-Task 8 — Admin Panel: Manajemen Produk & Kategori

**Intent:**
Memberikan admin kemampuan untuk mengelola katalog produk dan kategori dari panel admin.

**Expected Outcomes:**
- Admin bisa CRUD kategori
- Admin bisa CRUD produk (termasuk upload gambar, set harga, stok, status aktif)
- Semua route admin diproteksi middleware `AdminMiddleware`

**Todo List:**
- [ ] Buat `Admin\CategoryController` — index, create, store, edit, update, destroy
- [ ] Buat `Admin\ProductController` — index, create, store, edit, update, destroy
- [ ] Buat views admin: `admin/categories/` (index, form) dan `admin/products/` (index, form)
- [ ] Handle upload gambar produk/kategori ke `storage/app/public/`
- [ ] Tambahkan link storage: `php artisan storage:link`
- [ ] Daftarkan route grup `prefix('admin')->middleware('admin')`

**Relevant Context:**
- `app/Http/Controllers/Admin/`
- `resources/views/admin/`
- `routes/web.php` — route group admin

**Status:** `[ ] pending`

---

### Sub-Task 9 — Admin Panel: Manajemen Pesanan, Pembayaran & Pengiriman

**Intent:**
Memberikan admin kemampuan untuk melihat semua pesanan, mengkonfirmasi pembayaran, dan mengupdate status pengiriman.

**Expected Outcomes:**
- Admin bisa melihat semua pesanan dengan filter status
- Admin bisa melihat bukti pembayaran QRIS dan mengkonfirmasi/menolak pembayaran
- Admin bisa menambah update status pengiriman (misalnya: "Paket dikirim via JNE, resi: xxx")
- Status order otomatis terupdate sesuai alur

**Todo List:**
- [ ] Buat `Admin\OrderController` — index (+ filter status), show, update status
- [ ] Buat `Admin\PaymentController` — confirm, reject (update status payment + order)
- [ ] Buat `Admin\ShippingController` — store (tambah entry shipping status baru)
- [ ] Buat views: `admin/orders/` (index, show), form konfirmasi pembayaran, form update pengiriman
- [ ] Logika status: konfirmasi payment → order status jadi `processing`; tambah status kirim → `shipped`; tandai delivered → `delivered`
- [ ] Daftarkan route admin untuk orders, payments, shipping

**Relevant Context:**
- `app/Http/Controllers/Admin/`
- `resources/views/admin/orders/`
- Model `Order`, `Payment`, `ShippingStatus`

**Status:** `[ ] pending`

---

### Sub-Task 10 — Admin Dashboard & Polish UI

**Intent:**
Menyelesaikan Admin Dashboard dengan ringkasan statistik dan memastikan UI konsisten menggunakan Tailwind CSS.

**Expected Outcomes:**
- Dashboard admin menampilkan: total order, order pending, total revenue, produk terjual
- Navigasi admin sidebar berfungsi dengan baik
- UI toko (frontend) konsisten: color palette batik Mandar (merah tua, emas/kuning, hitam), font yang sesuai
- Semua halaman responsif (mobile-friendly)

**Todo List:**
- [ ] Buat `Admin\DashboardController` — hitung statistik dari DB
- [ ] Buat view `admin/dashboard.blade.php` — card statistik
- [ ] Definisikan color palette batik Mandar di `tailwind.config.js`: warna primer merah tua `#8B1A1A`, aksen emas `#C9A84C`, netral hitam `#1A1A1A`, background krem `#FAF3E0`
- [ ] Terapkan nama brand **olehMandar** di navbar, title, footer, dan halaman sukses checkout
- [ ] Polish navbar, footer, card produk, form checkout, halaman order
- [ ] Pastikan semua halaman responsif dengan breakpoint Tailwind

**Relevant Context:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/dashboard.blade.php`
- `tailwind.config.js`

**Status:** `[ ] pending`

---

## Urutan Implementasi

```
Sub-Task 1 (Setup)
    → Sub-Task 2 (Database)
    → Sub-Task 3 (Auth)
    → Sub-Task 4 (Katalog)
    → Sub-Task 5 (Cart)
    → Sub-Task 6 (Checkout)
    → Sub-Task 7 (Pembayaran User)
    → Sub-Task 8 (Admin Produk)
    → Sub-Task 9 (Admin Pesanan)
    → Sub-Task 10 (Dashboard + Polish)
```

---

## Catatan Teknis

- **PHP**: >= 8.2, **Laravel**: >= 11.x
- **Database**: MySQL 8.x, database name `ecommerce_mandar`
- **CSS**: Tailwind CSS v3 via Vite
- **Storage**: gambar produk disimpan di `storage/app/public/products/`
- **Pembayaran**: QRIS manual, unggah bukti dan konfirmasi dari panel admin
- **Tidak ada payment gateway** (Midtrans, dll.) di fase ini
- **Tracking**: berbasis status manual yang diupdate admin (bukan API ekspedisi)

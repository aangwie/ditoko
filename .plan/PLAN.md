# 📋 Rencana Pengembangan Proyek "DiToko" (Laravel 12)

Dokumen ini berisi panduan *Step-by-Step* yang sangat rinci untuk membangun aplikasi e-commerce produk digital **DiToko** menggunakan stack: **Laravel 12, Tailwind CSS, Alpine.js, dan MySQL**. 

Gunakan dokumen ini sebagai panduan instruksi (prompt) saat bekerja bersama AI. Kerjakan tahap demi tahap secara berurutan dan pastikan satu tahap selesai/berjalan dengan baik sebelum pindah ke tahap berikutnya.

---

## 🎨 Panduan Desain & Palet Warna (UI/UX)
* **Warna Dominan / Kebijakan Utama:** Biru Navy (`#003366`) — Digunakan untuk Navbar, Footer, Tombol Utama (CTA), dan Header.
* **Warna Aksen / Highlight:** Oranye (`#FF6600`) — Digunakan untuk Tombol "Beli Sekarang", Badge Status/Promo, Icon penting, dan Hover Effect.
* **Warna Latar / Kontras:** Putih (`#FFFFFF`) / Abu-abu Sangat Terang (`#F8FAFC`) — Untuk menjaga tampilan tetap bersih, modern, dan profesional mirip platform SaaS/Digital Marketplace premium.

---

## 🏗️ FASE 1: Inisialisasi, Konfigurasi & Autentikasi (Pondasi)

### Langkah 1.1: Konfigurasi Environment & Database
* [x] Buka file `.env`, sesuaikan pengaturan database:
    ```env
    APP_NAME=DiToko
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=onlinestore
    DB_USERNAME=root
    DB_PASSWORD=
    ```
* [x] Pastikan service MySQL menyala dan database `onlinestore` sudah dibuat.

### Langkah 1.2: Instalasi Starter Kit & Library Utama
* [x] Jalankan perintah install Laravel Breeze (pilih opsi **Blade with Alpine**):
    ```bash
    composer require laravel/breeze --dev
    php artisan breeze:install blade
    ```
* [x] Install Laravel Socialite untuk fitur login Google:
    ```bash
    composer require laravel/socialite
    ```

### Langkah 1.3: Kustomisasi Struktur User & Role
* [x] Modifikasi file migration user bawaan (`database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`):
    * Tambahkan kolom: `$table->string('role')->default('buyer'); // admin, buyer`
    * Tambahkan kolom: `$table->string('google_id')->nullable();`
    * Tambahkan kolom: `$table->string('avatar')->nullable();`
* [x] Modifikasi `app/Models/User.php` untuk menambahkan properti baru ke dalam array `$fillable`.
* [x] Jalankan migrasi: `php artisan migrate:fresh`

### Langkah 1.4: Konfigurasi Tailwind CSS Custom Colors
* [x] Buka file `tailwind.config.js`, daftarkan warna kustom DiToko:
    ```javascript
    theme: {
        extend: {
            colors: {
                'ditoko-navy': '#003366',
                'ditoko-orange': '#FF6600',

            },
        },
    },
    ```
* [x] Jalankan compilation asset: `npm run build`

### Langkah 1.5: Implementasi Middleware & Route Restriction
* [x] Buat middleware baru untuk mendeteksi Admin: `php artisan make:middleware AdminMiddleware`
* [x] Isi logika middleware untuk memvalidasi jika `auth()->user()->role !== 'admin'`, maka redirect ke halaman utama/dashboard dengan alert.
* [x] Daftarkan middleware tersebut di `bootstrap/app.php`.

### Langkah 1.6: Integrasi Google Sign-In (Socialite)
* [x] Tambahkan konfigurasi google client di `config/services.php`.
* [x] Buat `GoogleAuthController` untuk menangani redirect ke Google API dan handle callback-nya (jika email sudah ada, langsung login; jika belum, buat user baru dengan role `buyer`).
* [x] Daftarkan route `/auth/google` dan `/auth/google/callback`.
* [x] Tambahkan tombol "Masuk dengan Google" yang atraktif di halaman login Breeze menggunakan Tailwind.

---

## 📦 FASE 2: Manajemen Produk Digital (Katalog & Admin CRUD)

### Langkah 2.1: Desain Tabel & Model Produk
* [x] Buat model dan file migrasi: `php artisan make:model Product -m`
* [x] Struktur tabel `products`:
    * `id` (Primary Key)
    * `name` (String)
    * `slug` (String, Unique)
    * `description` (Text)
    * `price` (Decimal / BigInteger untuk IDR)
    * `cover_image` (String, Nullable)
    * `file_path` (String) -> Untuk menyimpan lokasi file e-book/software di secure storage.
    * `timestamps()`
* [x] Jalankan `php artisan migrate`.
* [x] Jalankan `php artisan storage:link`.

### Langkah 2.2: Dashboard Admin & CRUD Produk
* [x] Buat Controller khusus Admin: `php artisan make:controller Admin/ProductController`
* [x] Buat layout dashboard admin dengan nuansa Navy yang elegan.
* [x] Sediakan halaman:
    * **Index:** Tabel daftar produk (nama, harga, gambar).
    * **Create/Edit:** Form input produk yang dilengkapi link file terproteksi agar tidak bisa diunduh sembarangan sebelum membayar dan jika upload gambar file, batasi ukuran sebesar 400Kb dan compress gambar ke bentuk webp.
    * **Delete:** Fitur hapus data.

### Langkah 2.3: Frontend Katalog & Landing Page (Buyer)
* [x] Rombak halaman utama (`resources/views/welcome.blade.php`) menggunakan tema modern (Background putih bersih, hero section aksen Navy, tombol CTA warna Oranye).
* [x] Tampilkan grid produk digital yang menarik (Card produk berisi gambar sampul, judul, potongan deskripsi, harga terformat rupiah, dan tombol detail).
* [x] Buat Halaman Detail Produk (`/products/{slug}`) yang menampilkan deskripsi lengkap dan tombol "Beli Sekarang".

---

## 💳 FASE 3: Keranjang Belanja, Transaksi & Sistem Pembayaran

### Langkah 3.1: Sistem Keranjang Belanja (Sederhana via Session)
* [x] Karena ini produk digital, buat alur instan: Tombol "Beli Sekarang" bisa langsung mengarahkan ke halaman Checkout, atau membuat sistem *Cart* berbasis Session Laravel + Alpine.js.

### Langkah 3.2: Arsitektur Tabel Order & Order Item
* [x] Buat model transaksi: `php artisan make:model Order -m` dan `php artisan make:model OrderItem -m`
* [x] Struktur `orders`:
    * `id` (Primary Key)
    * `user_id` (Foreign Key ke users)
    * `order_number` (String, Unique - misal: DTK-YYYYMMDD-XXXX)
    * `total_price` (Decimal)
    * `payment_method` (String) -> 'midtrans' atau 'manual_transfer'
    * `payment_status` (String) -> 'pending', 'success', 'failed'
    * `proof_of_payment` (String, Nullable) -> Untuk transfer manual
* [x] Struktur `order_items`:
    * `id`, `order_id`, `product_id`, `price`
* [x] Jalankan `php artisan migrate`.

### Langkah 3.3: Integrasi Midtrans Payment Gateway
* [x] Install Midtrans SDK atau gunakan library wrapper (seperti `veritrans/midtrans-php` atau setup manual via Guzzle HTTP Client bawaan Laravel 12).
* [x] Tambahkan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` di `.env`.
* [x] Buat `CheckoutController` untuk meng-handle pembuatan order dan generate **Snap Token** dari Midtrans.
* [x] Di frontend checkout, pasang script Snap Midtrans menggunakan Alpine.js untuk memicu pop-up pembayaran otomatis.
* [x] Buat route khusus Webhook/Notification Handler (`/api/midtrans-callback`) yang bebas dari proteksi CSRF untuk mengubah status order menjadi `success` otomatis setelah dibayar.

### Langkah 3.4: Metode Transfer Bank Manual (Verifikasi Admin)
* [x] Jika pembeli memilih Transfer Manual, tampilkan nomor rekening admin dan total instruksi pembayaran yang presisi.
* [x] Buat form unggah bukti transfer bagi pembeli di halaman Riwayat Order.
* [x] Di sisi Admin, buat halaman ringkasan order masuk berstatus manual, tampilkan foto bukti transfer, serta tombol aksi **"Verifikasi & Setujui"** yang akan mengubah status order menjadi `success`.

### Langkah 3.5: Akses Amankan File Produk Digital
* [x] Buat `DownloadController` dengan proteksi middleware `auth`.
* [x] Pastikan logika controller memeriksa apakah user yang login benar-benar telah membeli produk tersebut (status order `success`).
* [x] Jika valid, kirimkan file secara aman menggunakan `Storage::download()`.

---

## 💬 FASE 4: Fitur Komunikasi Chat & Notifikasi WhatsApp

### Langkah 4.1: Fitur Chat Internal (Admin & Buyer)
* [ ] Buat model chat: `php artisan make:model Message -m`
* [ ] Struktur tabel `messages`:
    * `id`
    * `sender_id` (Foreign Key ke users)
    * `receiver_id` (Foreign Key ke users)
    * `message` (Text)
    * `is_read` (Boolean, default: false)
* [ ] Jalankan `php artisan migrate`.
* [ ] Buat halaman Chat di sisi Pembeli dan halaman Konsol Chat di sisi Admin.
* [ ] **Implementasi Tanpa Pusher (Polling Efisien):** Gunakan fungsi `x-init="setInterval(() => fetchMessages(), 4000)"` milik **Alpine.js** untuk melakukan polling berkala ke API endpoint internal Laravel guna memuat pesan baru tanpa reload halaman.

### Langkah 4.2: Integrasi WhatsApp Gateway API
* [ ] Buat berkas Service khusus: `app/Services/WhatsAppService.php`.
* [ ] Tulis fungsi `sendMessage($to, $message)` menggunakan HTTP Client Laravel (`Http::post()`) yang mengarah ke endpoint API Gateway WhatsApp pilihanmu.
* [ ] Pasang trigger pengiriman pesan otomatis pada event berikut:
    1.  **Order Created:** Mengirim tagihan dan instruksi pembayaran ke WA pembeli.
    2.  **Payment Success:** Mengirim ucapan terima kasih beserta tautan pintas menuju halaman download produk digital.

---

## 📝 FASE 5: Halaman Statis, Pengesahan, & Final Polishing

### Langkah 5.1: Halaman Syarat & Ketentuan
* [ ] Buat route statis `/syarat-ketentuan`.
* [ ] Desain view halaman tersebut dengan tipografi yang rapi menggunakan Tailwind (`prose` class jika menggunakan `@tailwindcss/typography`). Sampaikan aturan lisensi produk digital (larangan redistribusi, kebijakan refund, dsb).

### Langkah 5.2: Perapian Antarmuka (Polishing UI/UX)
* [ ] Satukan navigasi utama (Navbar) dengan logo **DiToko** yang elegan menggunakan kombinasi warna latar Navy dan aksen teks putih/oranye.
* [ ] Pastikan semua halaman sudah responsif (nyaman diakses via Mobile Smartphone maupun Desktop PC).
* [ ] Tambahkan halaman error kustom (404, 403) yang serasi dengan tema DiToko.

---

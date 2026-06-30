# DiToko - Aplikasi E-Commerce

Platform e-commerce berbasis Laravel dengan multi-role auth (admin/pembeli), manajemen produk, keranjang belanja, payment gateway Midtrans, notifikasi WhatsApp, dan chat real-time antara admin dan pembeli.

## Fitur

- **Multi-Role Auth**: Registrasi/login dengan email, Google OAuth
- **Dashboard Admin**: Kelola produk, pesanan, pengaturan
- **Dashboard Pembeli**: Lihat produk, kelola keranjang, checkout
- **Keranjang Belanja**: Tambah/hapus item, ubah jumlah
- **Payment Gateway**: Integrasi Midtrans (snap, transfer bank, dll.)
- **Notifikasi WhatsApp**: Update status pesanan via WhatsApp
- **Chat Admin-Pembeli**: Pesan real-time per pesanan
- **Manajemen Pesanan**: Lacak status (pending, paid, processing, shipped, completed, cancelled)
- **Manajemen Profil**: Update profil, ganti password

## Persyaratan Sistem

- PHP 8.2+
- Composer 2
- MySQL/MariaDB
- Node.js & NPM (untuk kompilasi aset Vite)
- Web server (Apache/Nginx) atau Laravel Artisan serve

## Instalasi

```bash
# 1. Clone repository
git clone https://github.com/aangwie/ditoko.git
cd ditoko

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JS
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi .env — edit database, mail, Midtrans, WhatsApp, Google OAuth
#    DB_DATABASE=ditoko
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Database
php artisan migrate --seed

# 7. Storage link (untuk gambar produk)
php artisan storage:link

# 8. Build aset
npm run build
# atau untuk development: npm run dev

# 9. Jalankan server
php artisan serve
```

## Akun Default (Seeder)

| Role    | Email              | Password |
|---------|--------------------|----------|
| Admin   | admin@ditoko.test  | password |
| Pembeli | buyer@ditoko.test  | password |

## Konfigurasi

### Variabel .env

| Key | Deskripsi |
|-----|-----------|
| `MIDTRANS_SERVER_KEY` | Server key Midtrans |
| `MIDTRANS_IS_PRODUCTION` | `true`/`false` |
| `WHATSAPP_API_URL` | Endpoint API WhatsApp |
| `WHATSAPP_API_TOKEN` | Token API WhatsApp |
| `GOOGLE_CLIENT_ID` | Client ID Google OAuth |
| `GOOGLE_CLIENT_SECRET` | Secret Google OAuth |
| `GOOGLE_REDIRECT_URI` | Contoh: `http://localhost/auth/google/callback` |

### Pengaturan Admin (via Web UI)

- Nama toko, logo, favicon, meta description
- Nomor WhatsApp
- Konfigurasi Midtrans
- Informasi tentang/kontak
- Kebijakan privasi, syarat & ketentuan

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Vite
- **Database**: MySQL
- **Payment**: Midtrans (Snap API)
- **Auth**: Laravel Breeze, Google OAuth (Socialite)
- **Notifikasi**: WhatsApp API (custom service)
- **Chat**: Database-driven messaging (polling)

## Lisensi

MIT
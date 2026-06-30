<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========== ADMIN USER ==========
        $admin = User::create([
            'name' => 'Admin DiToko',
            'email' => 'admin@ditoko.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        $this->command->info('✅ Admin created: admin@ditoko.id / admin123');

        // ========== BUYER USER ==========
        $buyer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('buyer123'),
            'role' => 'buyer',
        ]);
        $this->command->info('✅ Buyer created: budi@example.com / buyer123');

        // ========== DUMMY PRODUCTS ==========
        $products = [
            [
                'name' => 'E-Book "Mastering Laravel 12"',
                'description' => 'Panduan lengkap membangun aplikasi web modern menggunakan Laravel 12. Cocok untuk pemula hingga mahir. Materi mencakup Eloquent ORM, Authentication, API Development, Testing, dan Deployment.',
                'price' => 89000,
                'cover_image' => null,
                'file_path' => null,
            ],
            [
                'name' => 'Template Dashboard Admin (Tailwind + Alpine)',
                'description' => 'Template dashboard admin yang elegan dan responsif menggunakan Tailwind CSS dan Alpine.js. Dilengkapi dengan komponen siap pakai seperti tabel, form, chart, dan sidebar navigasi.',
                'price' => 125000,
                'cover_image' => null,
                'file_path' => null,
            ],
            [
                'name' => 'E-Book "UI/UX Design untuk Marketplace"',
                'description' => 'Pelajari prinsip-prinsip desain UI/UX untuk membangun marketplace digital yang user-friendly. Termasuk studi kasus desain halaman produk, keranjang, dan checkout.',
                'price' => 75000,
                'cover_image' => null,
                'file_path' => null,
            ],
            [
                'name' => 'Source Code Aplikasi Chat Real-time',
                'description' => 'Source code lengkap aplikasi chat real-time menggunakan Laravel Reverb + Alpine.js. Fitur: pesan real-time, notifikasi, online status, dan riwayat chat.',
                'price' => 150000,
                'cover_image' => null,
                'file_path' => null,
            ],
            [
                'name' => 'Paket Ikon Produk Digital (500+ Ikon)',
                'description' => 'Koleksi lebih dari 500 ikon vektor berkualitas tinggi untuk produk digital Anda. Format SVG, PNG, dan Figma. Cocok untuk tampilan website, aplikasi, dan presentasi.',
                'price' => 45000,
                'cover_image' => null,
                'file_path' => null,
            ],
            [
                'name' => 'E-Book "Strategi Marketing Produk Digital"',
                'description' => 'Panduan strategi pemasaran untuk produk digital. Mulai dari copywriting, SEO, social media marketing, email marketing, hingga optimasi conversion rate.',
                'price' => 65000,
                'cover_image' => null,
                'file_path' => null,
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'price' => $data['price'],
                'cover_image' => $data['cover_image'],
                'file_path' => $data['file_path'],
            ]);
            $this->command->info("✅ Product created: {$product->name} (Rp " . number_format($product->price, 0, ',', '.') . ')');
        }

        $this->command->info('');
        $this->command->info('🎉 Semua data dummy berhasil dibuat!');
        $this->command->info('🔑 Admin Login: admin@ditoko.id / admin123');
        $this->command->info('🔑 Buyer Login: budi@example.com / buyer123');
    }
}

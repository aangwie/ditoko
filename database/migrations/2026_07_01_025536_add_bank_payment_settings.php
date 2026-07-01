<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'bank_name', 'value' => 'BCA', 'type' => 'string', 'group' => 'bank', 'description' => 'Nama Bank'],
            ['key' => 'bank_account', 'value' => '1234567890', 'type' => 'string', 'group' => 'bank', 'description' => 'Nomor Rekening'],
            ['key' => 'bank_holder', 'value' => 'DiToko', 'type' => 'string', 'group' => 'bank', 'description' => 'Nama Pemilik Rekening'],
            ['key' => 'qris_image', 'value' => '', 'type' => 'string', 'group' => 'bank', 'description' => 'QRIS Image (base64)'],
            ['key' => 'payment_instructions', 'value' => "1. Transfer ke rekening bank yang tertera\n2. Konfirmasi pembayaran via WhatsApp\n3. Admin akan verifikasi pesanan", 'type' => 'text', 'group' => 'bank', 'description' => 'Instruksi Pembayaran'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }
    }

    public function down(): void
    {
        Setting::where('group', 'bank')->delete();
    }
};
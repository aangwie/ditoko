<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'site_name',
                'value' => 'DiToko',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama website/toko',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'text',
                'group' => 'general',
                'description' => 'Logo website (base64)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['site_name', 'site_logo'])->delete();
    }
};
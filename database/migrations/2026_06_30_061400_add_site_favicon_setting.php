<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            'key' => 'site_favicon',
            'value' => null,
            'type' => 'text',
            'group' => 'general',
            'description' => 'Favicon website (base64)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'site_favicon')->delete();
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'google_client_id',
                'value' => env('GOOGLE_CLIENT_ID', ''),
                'type' => 'string',
                'group' => 'google',
                'description' => 'Google OAuth Client ID',
            ],
            [
                'key' => 'google_client_secret',
                'value' => env('GOOGLE_CLIENT_SECRET', ''),
                'type' => 'string',
                'group' => 'google',
                'description' => 'Google OAuth Client Secret',
            ],
            [
                'key' => 'google_redirect_uri',
                'value' => env('GOOGLE_REDIRECT_URI', ''),
                'type' => 'string',
                'group' => 'google',
                'description' => 'Google OAuth Redirect URI',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'google_client_id',
            'google_client_secret',
            'google_redirect_uri',
        ])->delete();
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, text, boolean, json
            $table->string('group')->default('general'); // general, whatsapp, payment, etc
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default WhatsApp settings
        DB::table('settings')->insert([
            [
                'key' => 'whatsapp_api_url',
                'value' => 'https://api.whatsapp-gateway.com/send',
                'type' => 'string',
                'group' => 'whatsapp',
                'description' => 'URL endpoint WhatsApp Gateway API',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_api_key',
                'value' => '',
                'type' => 'string',
                'group' => 'whatsapp',
                'description' => 'API Key untuk WhatsApp Gateway',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'whatsapp',
                'description' => 'Enable/Disable WhatsApp notifications',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

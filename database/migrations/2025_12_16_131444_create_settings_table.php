<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->string('type')->default('text'); // text, textarea, password
            $table->string('group')->default('general'); // google, analytics, general
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'google_client_id',
                'value' => env('GOOGLE_CLIENT_ID', ''),
                'type' => 'text',
                'group' => 'google',
                'description' => 'Google OAuth Client ID for Sign-In functionality',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_client_secret',
                'value' => env('GOOGLE_CLIENT_SECRET', ''),
                'type' => 'password',
                'group' => 'google',
                'description' => 'Google OAuth Client Secret (keep this secure)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_redirect_uri',
                'value' => env('GOOGLE_REDIRECT_URI', ''),
                'type' => 'text',
                'group' => 'google',
                'description' => 'Google OAuth Redirect URI (e.g., https://marathibhasha.org/auth/google/callback)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'google_analytics_id',
                'value' => env('GOOGLE_ANALYTICS_ID', ''),
                'type' => 'text',
                'group' => 'analytics',
                'description' => 'Google Analytics Measurement ID (format: G-XXXXXXXXXX)',
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

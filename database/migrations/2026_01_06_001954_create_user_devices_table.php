<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('device_id');
            $table->string('platform', 20)->nullable();
            $table->string('fcm_token', 512);
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'device_id']);
            $table->unique('fcm_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('user_devices');
    }
};

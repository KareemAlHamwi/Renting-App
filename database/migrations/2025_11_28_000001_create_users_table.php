<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->date('verified_at')->nullable();
            $table->boolean('role')->default(0);
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
};

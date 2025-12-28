<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->nullable()->constrained()->nullOnDelete();
            $table->unique(['property_id', 'start_date', 'end_date']);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE reservations ADD CONSTRAINT chk_reservation_status CHECK (status IN (1,2,3,4))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('ALTER TABLE reservations DROP CONSTRAINT chk_reservation_status'); // Postgres

        Schema::dropIfExists('reservations');
    }
};

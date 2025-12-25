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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->decimal('stars', 2, 1)->default(0.0);
            $table->text('review')->nullable();
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE reviews
            ADD CONSTRAINT chk_review_stars
            CHECK (
                stars >= 0
                AND stars <= 5
                AND MOD(stars * 10, 5) = 0
            )"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};

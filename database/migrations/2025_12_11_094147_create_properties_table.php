<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('Title');
            $table->text('Description');
            $table->string('Address');
            $table->integer('Rent');
            $table->float('OverAllReviews')->nullable();
            $table->integer('ReviewersNumber')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('properties');
    }
};

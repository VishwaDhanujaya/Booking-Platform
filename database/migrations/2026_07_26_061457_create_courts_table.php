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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_category_id')->constrained('sport_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('outdoor'); // outdoor, indoor, covered
            $table->string('surface_type')->nullable(); // Acrylic Hard Court, Panoramic Glass & Turf, PVC Mat, Sprung Maple
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('peak_hourly_rate', 10, 2)->nullable();
            $table->integer('buffer_time_minutes')->default(0);
            $table->integer('max_capacity')->default(1);
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};

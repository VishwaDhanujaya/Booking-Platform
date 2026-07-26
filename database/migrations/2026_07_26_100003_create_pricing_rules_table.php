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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->nullable()->constrained('courts')->cascadeOnDelete();
            $table->foreignId('sport_category_id')->nullable()->constrained('sport_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('rule_type'); // peak, off_peak, seasonal, discount
            $table->string('discount_type')->default('none'); // student, senior, club, multi_booking, last_minute, none
            $table->string('adjustment_type')->default('percentage'); // percentage, fixed
            $table->decimal('adjustment_value', 10, 2);
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->json('days_of_week')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('min_slots')->default(1);
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};

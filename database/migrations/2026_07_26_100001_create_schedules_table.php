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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('type')->default('recurring'); // recurring, blocked, tournament, maintenance
            $table->integer('day_of_week')->nullable(); // 0 (Sun) to 6 (Sat) for recurring
            $table->string('start_time'); // "06:00"
            $table->string('end_time');   // "22:00"
            $table->date('specific_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_peak')->default(false);
            $table->decimal('override_rate', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

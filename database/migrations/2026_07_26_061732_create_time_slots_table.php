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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->date('date');
            $table->string('start_time'); // e.g. "06:00", "07:00"
            $table->string('end_time');   // e.g. "07:00", "08:00"
            $table->string('status')->default('available'); // available, booked, reserved, blocked, tournament
            $table->string('block_reason')->nullable();
            $table->boolean('is_peak')->default(false);
            $table->decimal('price', 10, 2);
            $table->string('booked_by_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};

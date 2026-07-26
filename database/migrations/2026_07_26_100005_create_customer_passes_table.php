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
        Schema::create('customer_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('pass_name');
            $table->integer('total_units');
            $table->integer('remaining_units');
            $table->decimal('price_paid', 10, 2);
            $table->date('expires_at')->nullable();
            $table->string('status')->default('active'); // active, exhausted, expired
            $table->timestamps();
        });

        Schema::create('pass_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_pass_id')->constrained('customer_passes')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->integer('units_in')->default(0);
            $table->integer('units_out')->default(0);
            $table->integer('units_after');
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pass_ledger_entries');
        Schema::dropIfExists('customer_passes');
    }
};

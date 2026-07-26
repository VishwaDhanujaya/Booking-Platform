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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained('courts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('booking_reference')->unique();
            $table->date('booking_date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('status')->default('confirmed'); // pending, payment_pending, confirmed, completed, cancelled, no_show
            $table->string('payment_status')->default('paid'); // unpaid, payment_pending, paid, refunded
            $table->string('payment_method')->default('pay_at_venue'); // manual_bank_transfer, pay_at_venue, credits, pass
            $table->decimal('base_amount', 10, 2)->default(0);
            $table->decimal('addons_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->json('price_breakdown')->nullable();
            $table->json('addons')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

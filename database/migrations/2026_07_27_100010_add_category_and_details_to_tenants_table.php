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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('category')->default('Sports Venues & Facilities')->after('name');
            $table->string('tagline')->nullable()->after('category');
            $table->text('description')->nullable()->after('tagline');
            $table->string('location')->nullable()->after('address');
            $table->boolean('is_public')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['category', 'tagline', 'description', 'location', 'is_public']);
        });
    }
};

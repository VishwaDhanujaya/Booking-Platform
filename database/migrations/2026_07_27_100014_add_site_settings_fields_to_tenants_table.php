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
            $table->string('favicon_url')->nullable()->after('logo_url');
            $table->text('opening_hours')->nullable()->after('location');
            $table->string('hero_headline')->nullable()->after('tagline');
            $table->text('hero_subheading')->nullable()->after('hero_headline');
            $table->string('hero_image_url')->nullable()->after('hero_subheading');
            $table->json('hero_highlights')->nullable()->after('hero_image_url');
            $table->json('notices')->nullable()->after('theme_settings');
            $table->json('nav_settings')->nullable()->after('notices');
            $table->string('custom_domain')->nullable()->after('domain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'favicon_url',
                'opening_hours',
                'hero_headline',
                'hero_subheading',
                'hero_image_url',
                'hero_highlights',
                'notices',
                'nav_settings',
                'custom_domain',
            ]);
        });
    }
};

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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_uuid', 36)->unique();
            $table->string('business_name', 128);
            $table->string('business_email', 320)->unique();
            $table->string('business_email_verification_code', 32)->nullable();
            $table->dateTime('business_email_verified_at')->nullable();
            $table->string('business_phone_code', 5);
            $table->string('business_phone_number', 18);
            $table->string('business_phone_verification_code', 5)->nullable();
            $table->dateTime('business_phone_verified_at')->nullable();
            $table->string('business_logo_url', 1024)->nullable();
            $table->string('business_post_code', 12);
            $table->string('business_country_iso', 2);
            $table->string('business_city', 256);
            $table->string('business_address_line_1', 128);
            $table->string('business_address_line_2', 128)->nullable();
            $table->string('business_website', 1024);
            $table->boolean('business_is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_uuid', 36)->unique();
            $table->foreignId('business_id')->nullable()->constrained('businesses');
            $table->string('user_title', 6);
            $table->string('user_display_name', 64)->nullable();
            $table->string('user_first_name', 64);
            $table->string('user_last_name', 64);
            $table->string('user_avatar_url', 1024)->nullable();
            $table->string('user_account_type', 64);
            $table->string('user_email', 320)->unique();
            $table->string('user_email_verification_code', 32)->nullable();
            $table->dateTime('user_email_verified_at')->nullable();
            $table->string('user_phone_code', 5);
            $table->string('user_phone_number', 18);
            $table->string('user_phone_verification_code', 5)->nullable();
            $table->dateTime('user_phone_verified_at')->nullable();
            $table->string('user_password');
            $table->boolean('user_is_active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_business_uuid');
        });

        Schema::dropIfExists('users');
    }
};

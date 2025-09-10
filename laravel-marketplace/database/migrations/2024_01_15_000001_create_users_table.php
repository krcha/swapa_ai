<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('jmbg_hash')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_sms_verified')->default(false);
            $table->boolean('is_email_verified')->default(false);
            $table->boolean('is_age_verified')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('is_verified');
            $table->index('is_sms_verified');
            $table->index('is_email_verified');
            $table->index('is_age_verified');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

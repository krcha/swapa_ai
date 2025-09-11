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
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20);
            $table->string('code', 10);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->datetime('expires_at');
            $table->boolean('verified')->default(false);
            $table->datetime('verified_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['phone_number', 'verified']);
            $table->index(['user_id', 'verified']);
            $table->index('expires_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
    }
};

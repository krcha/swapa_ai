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
        Schema::table('users', function (Blueprint $table) {
            // Add phone verification fields
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('phone_verification_code')->nullable();
            $table->timestamp('phone_verification_expires_at')->nullable();
            
            // Add subscription-related fields
            $table->string('stripe_customer_id')->nullable();
            $table->json('subscription_metadata')->nullable();
            
            // Add indexes
            $table->index('phone_verified_at');
            $table->index('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_verified_at',
                'phone_verification_code',
                'phone_verification_expires_at',
                'stripe_customer_id',
                'subscription_metadata'
            ]);
        });
    }
};

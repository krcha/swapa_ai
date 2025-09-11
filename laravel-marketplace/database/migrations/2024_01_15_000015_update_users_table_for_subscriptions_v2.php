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
            // Add subscription-related fields
            $table->foreignId('current_plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('phone_verification_code')->nullable();
            $table->timestamp('phone_verification_expires_at')->nullable();
            
            // Stripe integration fields
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            
            // Subscription metadata
            $table->json('subscription_metadata')->nullable();
            
            // User preferences
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(true);
            $table->string('timezone')->default('Europe/Belgrade');
            
            // Add indexes
            $table->index('current_plan_id');
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
            $table->dropForeign(['current_plan_id']);
            $table->dropColumn([
                'current_plan_id',
                'phone_verified_at',
                'phone_verification_code',
                'phone_verification_expires_at',
                'stripe_customer_id',
                'stripe_payment_method_id',
                'subscription_metadata',
                'email_notifications',
                'sms_notifications',
                'timezone'
            ]);
        });
    }
};

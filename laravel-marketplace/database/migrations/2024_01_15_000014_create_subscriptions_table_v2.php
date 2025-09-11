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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->datetime('trial_ends_at')->nullable();
            $table->enum('status', [
                'active',
                'trialing',
                'past_due',
                'canceled',
                'unpaid',
                'expired'
            ])->default('active');
            $table->string('payment_method')->nullable();
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->boolean('auto_renew')->default(true);
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['plan_id', 'status']);
            $table->index('ends_at');
            $table->index('trial_ends_at');
            $table->index('stripe_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

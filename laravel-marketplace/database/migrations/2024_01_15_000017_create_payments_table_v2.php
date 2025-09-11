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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', [
                'pending',
                'processing',
                'succeeded',
                'failed',
                'canceled',
                'refunded',
                'partially_refunded'
            ])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->text('failure_reason')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['subscription_id', 'status']);
            $table->index(['plan_id', 'status']);
            $table->index('payment_date');
            $table->index('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

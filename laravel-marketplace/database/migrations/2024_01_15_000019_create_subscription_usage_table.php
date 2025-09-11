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
        Schema::create('subscription_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->integer('listings_created')->default(0);
            $table->integer('listings_active')->default(0);
            $table->integer('listings_expired')->default(0);
            $table->integer('views_generated')->default(0);
            $table->integer('contacts_received')->default(0);
            $table->decimal('revenue_generated', 10, 2)->default(0);
            $table->date('tracking_date');
            $table->timestamps();

            // Unique constraint for daily tracking
            $table->unique(['user_id', 'subscription_id', 'tracking_date']);
            
            // Indexes for performance
            $table->index(['user_id', 'tracking_date']);
            $table->index(['subscription_id', 'tracking_date']);
            $table->index(['plan_id', 'tracking_date']);
            $table->index('tracking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_usage');
    }
};

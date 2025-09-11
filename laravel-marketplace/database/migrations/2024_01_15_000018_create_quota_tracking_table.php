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
        Schema::create('quota_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->integer('listings_used')->default(0);
            $table->integer('listings_limit');
            $table->boolean('is_unlimited')->default(false);
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicate tracking for same user/month
            $table->unique(['user_id', 'year', 'month']);
            
            // Indexes for performance
            $table->index(['user_id', 'year', 'month']);
            $table->index(['plan_id', 'year', 'month']);
            $table->index('reset_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_tracking');
    }
};

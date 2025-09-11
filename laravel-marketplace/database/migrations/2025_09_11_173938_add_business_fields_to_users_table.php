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
            // User type: personal or business
            $table->enum('user_type', ['personal', 'business'])->default('personal');
            
            // Business fields (only for business users)
            $table->string('business_name')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('business_tax_id')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_country')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_website')->nullable();
            
            // Subscription tier (Tier 2 or Tier 3 for business)
            $table->enum('subscription_tier', ['tier_1', 'tier_2', 'tier_3'])->default('tier_1');
            
            // Business verification status
            $table->boolean('is_business_verified')->default(false);
            $table->timestamp('business_verified_at')->nullable();
            
            // Business priority (business listings appear on top)
            $table->boolean('has_priority_listing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'business_name',
                'business_registration_number',
                'business_tax_id',
                'business_address',
                'business_city',
                'business_country',
                'business_phone',
                'business_email',
                'business_website',
                'subscription_tier',
                'is_business_verified',
                'business_verified_at',
                'has_priority_listing'
            ]);
        });
    }
};
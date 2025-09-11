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
        Schema::table('listings', function (Blueprint $table) {
            // Add subscription-related fields
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->timestamp('expires_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('contact_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            
            // Listing metadata
            $table->json('metadata')->nullable();
            $table->string('slug')->nullable();
            
            // Performance tracking
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            
            // Add indexes
            $table->index('plan_id');
            $table->index('expires_at');
            $table->index('is_featured');
            $table->index('featured_until');
            $table->index('slug');
            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn([
                'plan_id',
                'expires_at',
                'view_count',
                'contact_count',
                'is_featured',
                'featured_until',
                'metadata',
                'slug',
                'last_viewed_at',
                'last_contacted_at'
            ]);
        });
    }
};

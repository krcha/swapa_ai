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
            $table->integer('listing_limit')->default(10)->after('has_priority_listing');
            $table->boolean('is_banned')->default(false)->after('listing_limit');
            $table->text('ban_reason')->nullable()->after('is_banned');
            $table->timestamp('banned_at')->nullable()->after('ban_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['listing_limit', 'is_banned', 'ban_reason', 'banned_at']);
        });
    }
};
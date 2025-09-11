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
        Schema::table('approved_phone_models', function (Blueprint $table) {
            // Drop existing columns if they exist
            if (Schema::hasColumn('approved_phone_models', 'brand')) {
                $table->dropColumn('brand');
            }
            if (Schema::hasColumn('approved_phone_models', 'model_slug')) {
                $table->dropColumn('model_slug');
            }
            if (Schema::hasColumn('approved_phone_models', 'category')) {
                $table->dropColumn('category');
            }
            
            // Add new columns
            if (!Schema::hasColumn('approved_phone_models', 'brand_name')) {
                $table->string('brand_name')->after('id');
            }
            if (!Schema::hasColumn('approved_phone_models', 'model_code')) {
                $table->string('model_code')->unique()->after('model_name');
            }
            if (!Schema::hasColumn('approved_phone_models', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('model_code');
            }
            if (!Schema::hasColumn('approved_phone_models', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approved_phone_models', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn(['brand_name', 'model_code', 'is_active', 'sort_order']);
            $table->string('brand')->after('id');
            $table->string('model_slug')->after('model_name');
            $table->string('category')->after('model_slug');
        });
    }
};

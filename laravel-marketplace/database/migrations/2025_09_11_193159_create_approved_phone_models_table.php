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
        Schema::create('approved_phone_models', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('model_name');
            $table->string('model_code')->unique(); // URL-friendly version like 'iphone-15-pro'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['brand_name', 'is_active']);
            $table->index(['model_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_phone_models');
    }
};

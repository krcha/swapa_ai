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
            $table->string('brand');
            $table->string('model_name');
            $table->string('model_slug')->unique();
            $table->string('category')->default('smartphone'); // smartphone, tablet, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['brand', 'is_active']);
            $table->index('model_slug');
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
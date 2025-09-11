<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('listing_id')->nullable();
            $table->string('search_query')->nullable();
            $table->decimal('target_price', 10, 2);
            $table->enum('condition', ['below', 'above', 'equal'])->default('below');
            $table->boolean('is_active')->default(true);
            $table->timestamp('triggered_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('listing_id');
            $table->index('is_active');
            $table->index('target_price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_alerts');
    }
};
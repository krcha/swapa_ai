<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('listing_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id');
            $table->string('image_path');
            $table->string('image_url');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');

            // Indexes
            $table->index('listing_id');
            $table->index('is_primary');
            $table->index('sort_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_images');
    }
};

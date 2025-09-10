<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['like_new', 'excellent', 'good', 'fair']);
            $table->string('storage')->nullable();
            $table->string('color')->nullable();
            $table->integer('battery_health')->nullable();
            $table->string('screen_condition')->nullable();
            $table->string('body_condition')->nullable();
            $table->string('carrier')->nullable();
            $table->enum('contact_preference', ['phone', 'email', 'both']);
            $table->enum('status', ['pending', 'active', 'sold', 'expired', 'rejected'])->default('pending');
            $table->timestamp('expires_at');
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('status');
            $table->index('condition');
            $table->index('price');
            $table->index('expires_at');
            $table->index('created_at');
            $table->index(['status', 'expires_at']);
            $table->index(['category_id', 'status']);
            $table->index(['brand_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('listings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('listing_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('type', ['listing', 'user', 'message'])->default('listing');
            $table->enum('reason', [
                'spam', 'inappropriate', 'fraud', 'fake', 'harassment', 
                'copyright', 'other'
            ])->default('other');
            $table->text('description');
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('reporter_id');
            $table->index('listing_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blocked_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blocker_id');
            $table->unsignedBigInteger('blocked_id');
            $table->text('reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('blocker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('blocked_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('blocker_id');
            $table->index('blocked_id');
            $table->unique(['blocker_id', 'blocked_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blocked_users');
    }
};
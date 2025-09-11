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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('RSD');
            $table->enum('status', [
                'draft',
                'sent',
                'paid',
                'overdue',
                'canceled'
            ])->default('draft');
            $table->datetime('due_date');
            $table->datetime('paid_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'status']);
            $table->index('invoice_number');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

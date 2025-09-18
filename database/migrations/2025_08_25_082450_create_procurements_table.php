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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stationary_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('godown_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Quantity of item procured
            $table->decimal('unit_cost', 10, 2); // Unit cost
            $table->decimal('total_cost', 10, 2); // Total cost
            $table->string('company'); // Company name
            $table->string('salesperson'); // Salesperson name
            $table->enum('payment_type', ['cash', 'credit']); // Payment type
            $table->date('procurement_date'); // Date of procurement
            $table->string('invoice_number')->nullable(); // Invoice number
            $table->text('notes')->nullable(); // Additional notes
            $table->enum('status', ['pending', 'received', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};

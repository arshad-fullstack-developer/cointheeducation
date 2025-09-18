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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stationary_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_godown_id')->constrained('godowns')->onDelete('cascade');
            $table->foreignId('to_godown_id')->constrained('godowns')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->enum('movement_type', ['transfer', 'adjustment', 'return']);
            $table->string('moved_by'); // Person who moved the stock
            $table->date('movement_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

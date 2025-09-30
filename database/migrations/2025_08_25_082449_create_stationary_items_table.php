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
        Schema::create('stationary_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Item name like Pencil, Ball Pen, etc.
            $table->string('article')->nullable(); // Article number
            $table->string('quality')->nullable(); // Quality of item
            $table->string('color')->nullable(); // Color of item
            $table->string('company'); // Company name like Dux, ORO, Piano
            $table->string('size')->nullable(); // Size like full, half
            $table->string('volume')->nullable(); // Volume like 10g, 20g
            $table->string('shape')->nullable(); // Shape like round, square
            $table->string('cte_reck_number')->nullable(); // CTE reck number
            $table->decimal('unit_cost', 10, 2)->default(0); // Unit cost
            $table->decimal('selling_price', 10, 2)->default(0); // Selling price
            $table->decimal('profit_margin', 10, 2)->default(0); // Profit margin
            $table->integer('min_stock_level')->default(0); // Minimum stock level for alerts
            $table->date('expiry_date')->nullable(); // Expiry date
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stationary_items');
    }
};

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
        Schema::create('stationary_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('stationary_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('godown_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->enum('award_type', ['mobile_stationary', 'school_based', 'volunteer']);
            $table->string('awarded_by'); // Teacher/Volunteer name
            $table->date('award_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['awarded', 'returned', 'cancelled'])->default('awarded');
            $table->date('return_date')->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stationary_awards');
    }
};

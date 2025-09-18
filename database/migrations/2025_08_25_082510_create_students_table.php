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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('cte_id')->unique(); // CTE ID for each child
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('class'); // Class level
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('guardian_profession')->nullable();
            $table->string('address');
            $table->string('district');
            $table->string('tehsil');
            $table->string('uc'); // Union Council
            $table->enum('enrollment_type', ['school_based', 'individual'])->default('school_based');
            $table->date('enrollment_date')->default(now());
            $table->enum('status', ['active', 'inactive', 'dropped_out', 'graduated'])->default('active');
            $table->text('dropout_reason')->nullable();
            $table->date('dropout_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

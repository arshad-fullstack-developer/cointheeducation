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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_id')->unique(); // Unique school ID
            $table->string('address');
            $table->string('district');
            $table->string('tehsil');
            $table->string('uc'); // Union Council
            $table->string('principal_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->enum('school_type', ['government', 'private', 'madrassa']);
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->date('registration_date')->default(now());
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};

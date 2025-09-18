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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique(); // Unique customer ID
            $table->string('name');
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('hobby_1')->nullable(); // First hobby
            $table->string('hobby_2')->nullable(); // Second hobby
            $table->string('hobby_3')->nullable(); // Third hobby
            $table->string('favorite_food')->nullable();
            $table->string('profession')->nullable();
            $table->string('religion')->nullable();
            $table->string('recommended_by')->nullable(); // Source of CTE information
            $table->integer('number_of_children')->default(0);
            $table->string('residential_district');
            $table->boolean('consent_scholarship')->default(false); // Consent for scholarship update sharing
            $table->boolean('consent_whatsapp')->default(false); // WhatsApp sharing consent
            $table->date('membership_date')->default(now()); // Membership date
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

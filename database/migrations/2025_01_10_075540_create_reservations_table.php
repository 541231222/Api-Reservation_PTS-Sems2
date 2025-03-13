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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete("cascade");
            $table->foreignId('car_id')->constrained('cars')->onDelete("cascade");
            $table->date('start_date');
            $table->date('end_date');
            $table->string('proof_of_payment');
            $table->enum('payment_status', ['waiting', 'pending', 'success', 'failed']);
            $table->enum('status', ['pending', 'on_the_road', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

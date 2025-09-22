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
        Schema::create('fuel_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->dateTime('date');
            $table->decimal('fuel_price', 10, 2);
            $table->decimal('fuel_quantity', 10, 2);
            $table->enum('payment_mode', ['cash', 'upi']);
            $table->integer('kilometer_reading')->nullable();
            $table->text('note')->nullable();
            $table->string('image_vehicle_no')->nullable();
            $table->string('image_odometer')->nullable();
            $table->string('image_fuel_meter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_entries');
    }
};

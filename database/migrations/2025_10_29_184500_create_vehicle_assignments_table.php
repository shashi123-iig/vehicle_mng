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
       Schema::create('vehicle_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
        $table->string('driver_name');
        $table->string('driver_mobile');
        $table->boolean('active')->default(true);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_assignments');
    }
};

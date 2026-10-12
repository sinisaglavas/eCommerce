<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smartwatch_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('display_size_inches', 3, 1)->nullable();
            $table->string('display_type')->nullable();
            $table->unsignedTinyInteger('case_size_mm')->nullable();
            $table->unsignedSmallInteger('battery_hours')->nullable();
            $table->string('water_resistance_rating')->nullable();
            $table->boolean('gps_enabled')->default(false);
            $table->boolean('nfc_enabled')->default(false);
            $table->string('compatibility')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smartwatch_details');
    }
};

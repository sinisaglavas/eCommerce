<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('processor')->nullable();
            $table->string('graphics')->nullable();
            $table->unsignedSmallInteger('ram_gb')->nullable();
            $table->unsignedSmallInteger('storage_gb')->nullable();
            $table->string('storage_type')->nullable();
            $table->decimal('display_size_inches', 4, 1)->nullable();
            $table->string('display_resolution')->nullable();
            $table->unsignedSmallInteger('refresh_rate_hz')->nullable();
            $table->string('operating_system')->nullable();
            $table->unsignedSmallInteger('battery_wh')->nullable();
            $table->decimal('weight_kg', 4, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_details');
    }
};

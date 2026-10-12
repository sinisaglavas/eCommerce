<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tablet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('chipset')->nullable();
            $table->unsignedSmallInteger('ram_gb')->nullable();
            $table->unsignedSmallInteger('storage_gb')->nullable();
            $table->decimal('display_size_inches', 4, 1)->nullable();
            $table->string('display_resolution')->nullable();
            $table->unsignedSmallInteger('battery_mah')->nullable();
            $table->unsignedSmallInteger('main_camera_mp')->nullable();
            $table->string('operating_system')->nullable();
            $table->boolean('has_cellular')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tablet_details');
    }
};

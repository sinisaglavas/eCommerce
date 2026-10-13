<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('television_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('screen_size_inches', 4, 1)->nullable();
            $table->string('display_technology')->nullable();
            $table->string('resolution')->nullable();
            $table->unsignedSmallInteger('refresh_rate_hz')->nullable();
            $table->string('hdr_formats')->nullable();
            $table->string('smart_platform')->nullable();
            $table->unsignedTinyInteger('hdmi_ports')->nullable();
            $table->string('wall_mount_standard')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('television_details');
    }
};

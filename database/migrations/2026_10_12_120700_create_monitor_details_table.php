<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('screen_size_inches', 4, 1)->nullable();
            $table->string('panel_type')->nullable();
            $table->string('resolution')->nullable();
            $table->unsignedSmallInteger('refresh_rate_hz')->nullable();
            $table->unsignedSmallInteger('response_time_ms')->nullable();
            $table->string('aspect_ratio')->nullable();
            $table->boolean('has_curved_panel')->default(false);
            $table->string('connector_summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_details');
    }
};

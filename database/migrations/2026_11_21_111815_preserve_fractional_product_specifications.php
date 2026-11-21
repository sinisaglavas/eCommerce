<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('monitor_details', function (Blueprint $table): void {
            $table->decimal('response_time_ms', 6, 2)->nullable()->change();
        });
        Schema::table('smartwatch_details', function (Blueprint $table): void {
            $table->decimal('display_size_inches', 4, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasFractionalResponseTimes = DB::table('monitor_details')
            ->whereRaw('response_time_ms <> ROUND(response_time_ms, 0)')->exists();
        $hasPreciseDisplaySizes = DB::table('smartwatch_details')
            ->whereRaw('display_size_inches <> ROUND(display_size_inches, 1)')->exists();

        if ($hasFractionalResponseTimes || $hasPreciseDisplaySizes) {
            throw new RuntimeException('Reverting this migration would lose fractional product specifications. Use a forward migration instead.');
        }

        Schema::table('monitor_details', function (Blueprint $table): void {
            $table->unsignedSmallInteger('response_time_ms')->nullable()->change();
        });
        Schema::table('smartwatch_details', function (Blueprint $table): void {
            $table->decimal('display_size_inches', 3, 1)->nullable()->change();
        });
    }
};

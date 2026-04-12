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
        Schema::table('machine_schedules', function (Blueprint $table) {
            //
            $table->enum('shift_start', ['1', '2', '3'])->default('1')->after('assigned_by');
            $table->unsignedTinyInteger('shift_count')->default(1)->after('shift_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machine_schedules', function (Blueprint $table) {
            //
            $table->dropColumn(['shift_start', 'shift_count']);
        });
    }
};

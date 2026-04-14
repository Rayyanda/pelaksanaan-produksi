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
            $table->date('scheduled_date')->nullable()->after('assigned_by'); // tanggal kapan WIP dijadwalkan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machine_schedules', function (Blueprint $table) {
            //
            $table->dropColumn('scheduled_date');
        });
    }
};

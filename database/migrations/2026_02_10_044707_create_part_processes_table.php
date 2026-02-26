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
        Schema::create('part_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_internal_id')->constrained('part_internals')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->foreignId('part_operation_id')->nullable()->constrained('part_operations')->nullOnDelete();
            $table->integer('process_order')->nullable()->comment('urutan dalam workflow produksi');
            $table->integer('capacity')->nullable()->comment('kapasitas produksi per durasi kerja dalam menit');
            $table->integer('operator_count')->nullable()->commment('jumlah operator');
            $table->integer('duration')->nullable()->commment('durasi produksi per unit dalam menit');
            $table->boolean('is_active')->default(true);
            $table->string('equipment')->nullable()->comment('peralatan/mesin yang digunakan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_processes');
    }
};

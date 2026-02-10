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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('foreman_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->integer('capacity')->nullable()->comment('kapasitas produksi per durasi kerja dalam menit');
            $table->integer('operator_count')->nullable()->commment('jumlah operator');
            $table->integer('duration')->nullable()->commment('durasi produksi per unit dalam menit');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable()->comment('deskripsi detail area/proses');
            $table->integer('process_order')->nullable()->comment('urutan dalam workflow produksi');
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
        Schema::dropIfExists('areas');
    }
};

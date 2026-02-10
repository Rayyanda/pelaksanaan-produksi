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
        Schema::create('part_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_internal_id')->constrained('part_internals')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->text('operation_data')->nullable();
            $table->integer('route_order');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['part_internal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_operations');
    }
};

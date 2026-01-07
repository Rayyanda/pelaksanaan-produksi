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
        Schema::create('wip_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_internal_id')->constrained('part_internals')->cascadeOnDelete();
            $table->foreignId('part_operation_id')->constrained('part_operations')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->integer('wip_qty')->default(0);
            $table->enum('step',['quality_check','process','done','rework'])->default('process');
            $table->enum('status', ['in_progress', 'completed', 'waiting'])->default('waiting');
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            $table->text('operation_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wip_trackings');
    }
};

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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('po_production_id')->constrained('po_productions')->cascadeOnDelete();
            $table->foreignId('part_internal_id')->constrained('part_internals')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->date('target_completed')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('pending');
            $table->foreignId('approval_manager')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approval_manager_at')->nullable();
            $table->string('part_no_customer')->nullable();
            $table->string('part_no_customer_source')->nullable();
            $table->string('drawing_number')->nullable();
            $table->string('drawing_number_source')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

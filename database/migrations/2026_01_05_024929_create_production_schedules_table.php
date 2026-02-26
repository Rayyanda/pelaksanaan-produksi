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
        Schema::create('production_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->string('process_name');
            $table->integer('duration_weeks');

            // Plan
            $table->date('plan_start_date');
            $table->date('plan_end_date');
            $table->integer('plan_qty');

            // Actual
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->integer('actual_qty')->nullable();

            $table->enum('status', ['planned', 'in_progress', 'completed', 'delayed'])->default('planned');
            $table->text('notes')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};
